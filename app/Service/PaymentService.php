<?php

namespace App\Service;

use App\Core\Idempotency;
use App\Core\Logger;
use App\Core\Model;
use App\Core\PaymentGateway;
use App\Core\PaymentGatewayFactory;
use App\Core\PaymentLedger;
use App\Core\RowSecurity;
use App\Service\Mailer;

/**
 * Payment orchestration.
 *
 * Every write path follows the same integrity contract:
 *
 *   1. CLAIM the client-generated idempotency key (saved FIRST, in its own row).
 *   2. WRITE the intent to the immutable ledger BEFORE touching the gateway.
 *   3. CALL the gateway.
 *   4. APPEND the resulting state change as a brand-new ledger row.
 *
 * Duplicate requests never re-run the gateway call — the cached result of the
 * original run is returned instead. Webhook ingestion is idempotent on the
 * provider event id and signature-verified before anything is appended.
 */
class PaymentService
{
    private string $gatewayName;

    public function __construct(string $gatewayName = 'paystack')
    {
        // Canonical gateway this service routes operations to by default. The
        // provider is stored on each intent so capture/refund/webhooks always
        // reconcile against the gateway that actually created the intent.
        $this->gatewayName = PaymentGatewayFactory::normalize($gatewayName);
    }

    /**
     * Create a payment intent. Writes the intent + an 'initiated' ledger event
     * BEFORE calling the gateway. Fully idempotent on $idempotencyKey.
     */
    public function createIntent(array $data, string $idempotencyKey, array $requestMeta = [], array $actor = []): array
    {
        $claim = Idempotency::begin($idempotencyKey, 'payment.intent.create', $data, $requestMeta);
        if ($claim['status'] === 'replay') {
            return ['replayed' => true, 'result' => $claim['response']];
        }
        if ($claim['status'] === 'in_flight') {
            return ['replayed' => false, 'result' => null, 'in_flight' => true];
        }

        $orderId = isset($data['order_id']) && $data['order_id'] !== null ? (int) $data['order_id'] : null;
        $amountKobo = (int) ($data['amount_kobo'] ?? 0);
        $currency = strtoupper((string) ($data['currency'] ?? 'NGN'));
        $email = isset($data['customer_email']) ? filter_var($data['customer_email'], FILTER_VALIDATE_EMAIL) : null;

        // Which provider issues this intent? Defaults to the service's gateway,
        // but an explicit gateway in the payload (e.g. 'flutterwave') wins.
        $gatewayName = PaymentGatewayFactory::normalize((string) ($data['gateway'] ?? $this->gatewayName));
        $gateway = PaymentGatewayFactory::make($gatewayName);

        if ($amountKobo <= 0) {
            throw new \InvalidArgumentException('amount_kobo must be a positive integer.', 422);
        }
        if (strlen($currency) !== 3 || !ctype_alpha($currency)) {
            throw new \InvalidArgumentException('currency must be a 3-letter ISO code.', 422);
        }
        if (!$gateway->isSimulation() && !$email) {
            throw new \InvalidArgumentException('customer_email is required.', 422);
        }

        $db = Model::getDB();

        if ($orderId !== null) {
            $this->assertOrderClaimable($orderId, $actor);
        }

        $db->beginTransaction();
        $intentId = null;
        try {
            // 1. Persist the immutable intent metadata, bound to its creator.
            $stmt = $db->prepare(
                "INSERT INTO payment_intents
                    (idempotency_key, order_id, customer_id, session_hash, gateway, amount_kobo, currency, customer_email, metadata)
                 VALUES (:k, :o, :c, :s, :g, :a, :cu, :e, :m)"
            );
            $stmt->execute([
                'k' => $idempotencyKey,
                'o' => $orderId,
                'c' => $actor['customer_id'] ?? null,
                's' => $actor['session_hash'] ?? null,
                'g' => $gatewayName,
                'a' => $amountKobo,
                'cu' => $currency,
                'e' => $email,
                'm' => json_encode(['intent_key' => $idempotencyKey]),
            ]);
            $intentId = (int) $db->lastInsertId();

            // 2. Write the intent to the ledger BEFORE calling the API.
            PaymentLedger::append($intentId, 'initiated', $amountKobo, 'api', self::eventId('init'));

            // 3. Call the gateway.
            $gatewayResult = $gateway->initialize([
                'email' => $email ?: '',
                'amount_kobo' => $amountKobo,
                'currency' => $currency,
                'reference' => 'PI_' . $intentId . '_' . strtoupper(bin2hex(random_bytes(6))),
                'redirect_url' => (string) ($data['redirect_url'] ?? ''),
                'customer_name' => (string) ($data['customer_name'] ?? ''),
                'customer_phone' => (string) ($data['customer_phone'] ?? ''),
            ]);

            // 4. Record the provider reference — a set-once metadata write,
            //    never a status overwrite.
            $stmt = $db->prepare(
                "UPDATE payment_intents SET gateway_ref = :r WHERE id = :i AND gateway_ref IS NULL"
            );
            $stmt->execute(['r' => $gatewayResult['reference'], 'i' => $intentId]);

            $db->commit();
        } catch (\Throwable $t) {
            $db->rollBack();
            Idempotency::fail($idempotencyKey, $t->getMessage());
            throw $t;
        }

        $response = [
            'intent_id' => $intentId,
            'reference' => $gatewayResult['reference'],
            'access_code' => $gatewayResult['access_code'],
            'authorization_url' => $gatewayResult['authorization_url'],
            'amount_kobo' => $amountKobo,
            'currency' => $currency,
            'status' => 'initiated',
        ];

        Idempotency::succeed($idempotencyKey, $response);

        Logger::info("Intent #{$intentId} initiated ({$amountKobo} {$currency})", 'payment');

        return ['replayed' => false, 'result' => $response];
    }

    /**
     * Confirm a charge against the gateway and append a 'captured' ledger row.
     * Never appends twice; returns the existing state when already captured.
     */
    public function capture(int $intentId, array $data, string $idempotencyKey, array $requestMeta = [], array $actor = []): array
    {
        $claim = Idempotency::begin($idempotencyKey, 'payment.capture', ['intent_id' => $intentId] + $data, $requestMeta);
        if ($claim['status'] === 'replay') {
            return ['replayed' => true, 'result' => $claim['response']];
        }
        if ($claim['status'] === 'in_flight') {
            return ['replayed' => false, 'result' => null, 'in_flight' => true];
        }

        $intent = $this->loadIntent($intentId);
        if (!$intent) {
            throw new \InvalidArgumentException('Payment intent not found.', 404);
        }

        RowSecurity::authorize($intent, $actor, 'Payment intent');

        $current = PaymentLedger::currentStatus($intentId);
        if ($current === null) {
            throw new \InvalidArgumentException('Payment intent has no ledger history.', 409);
        }
        if ($current === 'captured') {
            $this->syncOrderPaymentStatus(
                (int) $intent['order_id'],
                'captured',
                (string) $intent['gateway_ref']
            );
            return $this->completeReplay($idempotencyKey, [
                'intent_id' => $intentId,
                'status' => 'captured',
                'already_captured' => true,
                'reference' => $intent['gateway_ref'],
            ]);
        }

        $gateway = PaymentGatewayFactory::make((string) ($intent['gateway'] ?? 'paystack'));
        $verified = $gateway->verify((string) $intent['gateway_ref']);

        if ($verified['status'] === 'success') {
            $appended = PaymentLedger::append(
                $intentId,
                'captured',
                (int) $intent['amount_kobo'],
                'api',
                self::eventId('cap'),
                $verified['raw'],
                $intent['gateway_ref'] ? (string) $intent['gateway_ref'] : null
            );

            if (!$appended) {
                // A concurrent path (webhook) already captured — no double charge.
                return $this->completeReplay($idempotencyKey, [
                    'intent_id' => $intentId,
                    'status' => 'captured',
                    'already_captured' => true,
                    'reference' => $intent['gateway_ref'],
                ]);
            }

            Logger::info("Intent #{$intentId} captured via API reconcile", 'payment');

            // Mirror the payment onto the attached order, exactly like the
            // webhook path does — an API reconcile must never leave the order
            // stuck in 'pending'.
            $this->syncOrderPaymentStatus(
                (int) $intent['order_id'],
                'captured',
                (string) $intent['gateway_ref']
            );

            return $this->completeReplay($idempotencyKey, [
                'intent_id' => $intentId,
                'status' => 'captured',
                'reference' => $intent['gateway_ref'],
            ]);
        }

        if ($verified['status'] === 'failed') {
            PaymentLedger::append(
                $intentId, 'failed', 0, 'api', self::eventId('fail'), $verified['raw']
            );
            Logger::warning("Intent #{$intentId} failed verification", 'payment');
        }

        return $this->completeReplay($idempotencyKey, [
            'intent_id' => $intentId,
            'status' => 'not_captured',
            'provider_status' => $verified['status'],
        ]);
    }

    /**
     * Refund a captured payment (full or partial). Each refund is a new ledger row.
     */
    public function refund(int $intentId, array $data, string $idempotencyKey, array $requestMeta = [], array $actor = []): array
    {
        $claim = Idempotency::begin($idempotencyKey, 'payment.refund', ['intent_id' => $intentId] + $data, $requestMeta);
        if ($claim['status'] === 'replay') {
            return ['replayed' => true, 'result' => $claim['response']];
        }
        if ($claim['status'] === 'in_flight') {
            return ['replayed' => false, 'result' => null, 'in_flight' => true];
        }

        $intent = $this->loadIntent($intentId);
        if (!$intent) {
            throw new \InvalidArgumentException('Payment intent not found.', 404);
        }

        RowSecurity::authorize($intent, $actor, 'Payment intent');

        if (PaymentLedger::currentStatus($intentId) !== 'captured') {
            throw new \InvalidArgumentException('Only captured payments can be refunded.', 409);
        }

        $amountKobo = (int) ($data['amount_kobo'] ?? $intent['amount_kobo']);
        if ($amountKobo <= 0) {
            throw new \InvalidArgumentException('Refund amount must be positive.', 422);
        }
        $refundedSoFar = PaymentLedger::refundedTotalKobo($intentId);
        if ($refundedSoFar + $amountKobo > (int) $intent['amount_kobo']) {
            throw new \InvalidArgumentException('Refund amount exceeds the captured amount.', 422);
        }

        $gateway = PaymentGatewayFactory::make((string) ($intent['gateway'] ?? 'paystack'));
        $gatewayRefund = $gateway->refund(
            (string) $intent['gateway_ref'],
            $amountKobo,
            ['intent_id' => $intentId]
        );

        $appended = PaymentLedger::append(
            $intentId,
            'refunded',
            $amountKobo,
            'api',
            self::eventId('ref'),
            $gatewayRefund['raw'],
            isset($gatewayRefund['reference']) && $gatewayRefund['reference'] !== '' ? $gatewayRefund['reference'] : null
        );

        if (!$appended) {
            throw new \RuntimeException('Refund was already recorded. No duplicate refund issued.', 409);
        }

        Logger::info("Intent #{$intentId} refunded {$amountKobo} kobo", 'payment');

        return $this->completeReplay($idempotencyKey, [
            'intent_id' => $intentId,
            'status' => 'refunded',
            'refund_reference' => $gatewayRefund['reference'] ?? null,
            'amount_kobo' => $amountKobo,
        ]);
    }

    /**
     * Securely ingest an asynchronous gateway webhook.
     *
     * - Validates the HMAC signature (constant time) before trusting anything.
     * - Persists the raw event, deduplicated on (provider, event_id).
     * - Validates the event id is unknown before appending to the timeline.
     * - Appends a brand-new immutable ledger row (never updates an old one).
     */
    public function handleWebhook(array $payload, string $signature, string $rawBody, string $provider = 'paystack'): array
    {
        $eventType = (string) ($payload['event'] ?? '');
        $eventId = self::webhookEventId($payload);
        $provider = PaymentGatewayFactory::normalize($provider);

        if ($eventType === '') {
            throw new \InvalidArgumentException('Webhook payload missing event type.', 400);
        }

        $db = Model::getDB();

        // 1. Persist the raw event FIRST (idempotency on provider + event id).
        try {
            $stmt = $db->prepare(
                "INSERT INTO webhook_events (provider, event_id, event_type, signature_valid, payload)
                 VALUES (:p, :e, :t, 0, :pl)"
            );
            $stmt->execute([
                'p' => $provider,
                'e' => $eventId,
                't' => $eventType,
                'pl' => json_encode($payload),
            ]);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                // Already received this event id — acknowledge, do nothing more.
                return ['received' => true, 'duplicate' => true, 'event_id' => $eventId];
            }
            throw $e;
        }
        $webhookId = (int) $db->lastInsertId();

        // 2. Verify the signature BEFORE any trust or timeline mutation.
        $gateway = PaymentGatewayFactory::make($provider);
        $valid = $gateway->verifySignature($rawBody, $signature);
        if (!$valid) {
            $this->markWebhook($webhookId, true, 'Signature verification failed.');
            Logger::warning("Rejected webhook {$provider}:{$eventId} — invalid signature", 'payment');
            return ['received' => true, 'signature_valid' => false, 'event_id' => $eventId];
        }

        $this->markSignatureValid($webhookId);

        // 3. Resolve the payment intent by provider reference. Paystack uses
        //    data.reference, Flutterwave uses data.tx_ref.
        $reference = $payload['data']['tx_ref']
            ?? $payload['data']['reference']
            ?? $payload['data']['transaction']['reference']
            ?? null;
        $intent = null;
        if ($reference) {
            $stmt = $db->prepare("SELECT * FROM payment_intents WHERE gateway_ref = :r LIMIT 1");
            $stmt->execute(['r' => (string) $reference]);
            $intent = $stmt->fetch() ?: null;
        }

        if (!$intent) {
            $this->markWebhook($webhookId, true, "No intent matched reference '{$reference}'.");
            Logger::warning("Webhook {$provider}:{$eventId} for unknown reference '{$reference}'", 'payment');
            return ['received' => true, 'matched' => false, 'event_id' => $eventId];
        }

        // Link the raw event row to the resolved intent for full traceability.
        $stmt = $db->prepare("UPDATE webhook_events SET payment_intent_id = :pi WHERE id = :w");
        $stmt->execute(['pi' => (int) $intent['id'], 'w' => $webhookId]);

        // 4. Map to a ledger event. Unmappable events are acked, not appended.
        $ledgerType = self::mapEventType($eventType);
        // Flutterwave sends charge.completed for both outcomes — the transaction
        // status inside data disambiguates successful from failed payments.
        if ($provider === 'flutterwave' && $eventType === 'charge.completed') {
            $ledgerType = ($payload['data']['status'] ?? '') === 'successful' ? 'captured' : 'failed';
        }
        if ($ledgerType === null) {
            $this->markWebhook($webhookId, true, "Event type '{$eventType}' requires no ledger mutation.");
            return ['received' => true, 'signature_valid' => true, 'matched' => true, 'ledger_appended' => false, 'event_id' => $eventId];
        }

        // 5. Append to the immutable timeline. Event-id validation happens here:
        //    the unique (intent_id, event_id) + provider_event_id indexes make
        //    double-appending impossible.
        // Amounts: Paystack webhooks report kobo; Flutterwave reports major
        // units (naira). The intent's stored amount is the authoritative fallback.
        $rawAmount = $payload['data']['amount'] ?? null;
        $amountKobo = $rawAmount !== null
            ? (int) round(((float) $rawAmount) * ($provider === 'flutterwave' ? 100 : 1))
            : (int) $intent['amount_kobo'];
        $appended = true;
        try {
            $appended = PaymentLedger::append(
                (int) $intent['id'],
                $ledgerType,
                $amountKobo,
                'webhook',
                self::eventId('wh'),
                $payload['data'] ?? $payload,
                $provider . ':' . $eventId,
                ['event_type' => $eventType, 'webhook_id' => $webhookId]
            );
        } catch (\InvalidArgumentException $t) {
            // Transition blocked (e.g. duplicate charge.success after capture).
            // Acknowledge without touching the ledger.
            $appended = false;
            Logger::info("Webhook {$eventType} no-op for intent #{$intent['id']}: {$t->getMessage()}", 'payment');
        }

        $this->markWebhook($webhookId, true, $appended ? 'Ledger appended.' : 'No ledger mutation required.');

        if ($appended) {
            Logger::info("Webhook {$provider}:{$eventId} -> intent #{$intent['id']} {$ledgerType}", 'payment');
        }

        // 6. Mirror the payment state onto the attached order (idempotent) and
        //    notify the customer + staff. Notifications are best-effort and can
        //    never break the webhook acknowledgement.
        if ($appended && $intent['order_id']) {
            $this->syncOrderPaymentStatus(
                (int) $intent['order_id'],
                $ledgerType,
                (string) ($intent['gateway_ref'] ?: $reference)
            );
        }

        return [
            'received' => true,
            'signature_valid' => true,
            'matched' => true,
            'ledger_appended' => $appended,
            'event_id' => $eventId,
        ];
    }

    /**
     * Keep orders.payment_status consistent with the immutable ledger.
     * Purely stateful (the ledger is the source of truth) and idempotent.
     */
    private function syncOrderPaymentStatus(int $orderId, string $ledgerType, string $reference): void
    {
        $db = Model::getDB();

        if ($ledgerType === 'captured') {
            $stmt = $db->prepare(
                "UPDATE orders
                    SET payment_status = 'paid', transaction_reference = :r
                  WHERE id = :i AND payment_status != 'paid'"
            );
            $stmt->execute(['r' => $reference, 'i' => $orderId]);
            if ($stmt->rowCount() > 0) {
                $this->notifyOrderPaid($orderId, $reference);
            }
            $this->maybeRouteShipment($orderId);
            return;
        }

        if ($ledgerType === 'failed') {
            $stmt = $db->prepare(
                "UPDATE orders SET payment_status = 'failed' WHERE id = :i AND payment_status = 'pending'"
            );
            $stmt->execute(['i' => $orderId]);
        }
    }

    /**
     * Once an order is paid, route the ShipBubble shipment label if the order
     * was placed with a courier selection. Best-effort: a routing failure is
     * logged but must never break the payment acknowledgement.
     */
    private function maybeRouteShipment(int $orderId): void
    {
        try {
            $db = Model::getDB();
            $stmt = $db->prepare(
                "SELECT shipbubble_request_token, shipbubble_service_code, shipbubble_courier_id,
                        shipbubble_courier_name, shipbubble_order_id
                   FROM orders WHERE id = :i LIMIT 1"
            );
            $stmt->execute(['i' => $orderId]);
            $order = $stmt->fetch();

            $token = (string) ($order['shipbubble_request_token'] ?? '');
            $service = (string) ($order['shipbubble_service_code'] ?? '');
            $courier = (string) ($order['shipbubble_courier_id'] ?? '');
            $hasLabel = !empty($order['shipbubble_order_id']);

            if ($token === '' || $service === '' || $courier === '' || $hasLabel) {
                return;
            }

            $shipbubble = new ShipBubbleService();
            $label = $shipbubble->createLabel([
                'request_token' => $token,
                'service_code' => $service,
                'courier_id' => $courier,
                'courier_name' => (string) ($order['shipbubble_courier_name'] ?? ''),
            ]);

            $sbOrderId = (string) ($label['order_id'] ?? '');
            if ($sbOrderId === '') {
                Logger::warning("ShipBubble label create returned no order_id for order #{$orderId}", 'shipbubble');
                return;
            }

            $stmt = $db->prepare(
                "UPDATE orders
                    SET shipbubble_order_id = :o, shipbubble_status = :s,
                        shipbubble_tracking_url = COALESCE(NULLIF(:tu, ''), shipbubble_tracking_url)
                  WHERE id = :i AND shipbubble_order_id IS NULL"
            );
            $stmt->execute([
                'o' => $sbOrderId,
                's' => (string) ($label['status'] ?? 'pending'),
                'tu' => (string) ($label['tracking_url'] ?? ''),
                'i' => $orderId,
            ]);

            Logger::info("Order #{$orderId} routed to ShipBubble as {$sbOrderId}", 'shipbubble');
        } catch (\Throwable $t) {
            Logger::error("ShipBubble routing failed for order #{$orderId}: {$t->getMessage()}", 'shipbubble');
        }
    }

    private function notifyOrderPaid(int $orderId, string $reference): void
    {
        $db = Model::getDB();

        $stmt = $db->prepare(
            "SELECT o.*, oa.first_name, oa.last_name, oa.email
               FROM orders o
               LEFT JOIN order_addresses oa ON oa.order_id = o.id AND oa.type = 'shipping'
              WHERE o.id = :i LIMIT 1"
        );
        $stmt->execute(['i' => $orderId]);
        $order = $stmt->fetch();
        if (!$order) {
            return;
        }

        $customerName = trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? ''));
        $orderCurrency = (string) ($order['currency'] ?? 'NGN');
        $amount = \App\Core\Money::format((float) $order['grand_total'], $orderCurrency);
        $appUrl = rtrim((string) ($_ENV['APP_URL'] ?? ''), '/');

        if ($order['email']) {
            Mailer::sendTemplate($order['email'], 'Payment received for Order ' . $order['order_number'], 'payment_received', [
                'customer_name' => $customerName ?: 'there',
                'order_id' => $order['order_number'],
                'amount' => $amount,
                'date' => date('F j, Y'),
                'action_link' => $appUrl . '/order-confirmation?ref=' . rawurlencode($reference),
            ]);
        }

        Mailer::sendTemplate($_ENV['ADMIN_EMAIL'] ?? 'hello@marigoldsignatureng.com', 'New paid order: ' . $order['order_number'], 'admin_new_order', [
            'order_id' => $order['order_number'],
            'customer_name' => $customerName ?: ($order['email'] ?: 'guest'),
            'amount' => $amount,
        ]);
    }

    /**
     * Full auditable timeline for an intent (admin use).
     */
    public function timeline(int $intentId): array
    {
        if (!$this->loadIntent($intentId)) {
            throw new \InvalidArgumentException('Payment intent not found.', 404);
        }
        return PaymentLedger::timeline($intentId);
    }

    private function completeReplay(string $idempotencyKey, array $response): array
    {
        Idempotency::succeed($idempotencyKey, $response);
        return ['replayed' => false, 'result' => $response];
    }

    private function loadIntent(int $intentId): ?array
    {
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT * FROM payment_intents WHERE id = :i");
        $stmt->execute(['i' => $intentId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * A payment intent may only be attached to an order the caller may pay for:
     * admins may claim any order, a logged-in customer only their own, and a
     * guest only an order that has no owner yet. Failures read as 404 so the
     * order's existence is not leaked.
     */
    private function assertOrderClaimable(int $orderId, array $actor): void
    {
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT id, customer_id FROM orders WHERE id = :i");
        $stmt->execute(['i' => $orderId]);
        $order = $stmt->fetch();
        if (!$order) {
            throw new \InvalidArgumentException('Order not found.', 404);
        }
        if (!empty($actor['is_admin'])) {
            return;
        }

        $orderCustomer = (int) ($order['customer_id'] ?? 0);
        $actorCustomer = (int) ($actor['customer_id'] ?? 0);
        $allowed = ($orderCustomer > 0 && $orderCustomer === $actorCustomer)
            || ($orderCustomer === 0 && $actorCustomer === 0);

        if (!$allowed) {
            Logger::warning("Order #{$orderId} not claimable by current actor", 'security');
            throw new \InvalidArgumentException('Order not found.', 404);
        }
    }

    private function markSignatureValid(int $webhookId): void
    {
        $db = Model::getDB();
        $stmt = $db->prepare("UPDATE webhook_events SET signature_valid = 1 WHERE id = :i");
        $stmt->execute(['i' => $webhookId]);
    }

    private function markWebhook(int $webhookId, bool $processed, ?string $error): void
    {
        $db = Model::getDB();
        $stmt = $db->prepare(
            "UPDATE webhook_events
                SET processed = :p, error_message = :e, processed_at = NOW()
              WHERE id = :i"
        );
        $stmt->execute(['p' => $processed ? 1 : 0, 'e' => $error, 'i' => $webhookId]);
    }

    private static function eventId(string $prefix): string
    {
        return $prefix . '_' . bin2hex(random_bytes(12));
    }

    private static function webhookEventId(array $payload): string
    {
        $event = (string) ($payload['event'] ?? 'unknown');
        $dataId = $payload['data']['id'] ?? null;
        if ($dataId !== null && $dataId !== '') {
            return $event . '|' . $dataId;
        }
        return $event . '|' . hash('sha256', json_encode($payload));
    }

    private static function mapEventType(string $eventType): ?string
    {
        return match ($eventType) {
            'charge.success', 'charge.completed' => 'captured',
            'charge.failed' => 'failed',
            'charge.refunded', 'refund.processed', 'refund.completed' => 'refunded',
            'charge.reversed' => 'reversed',
            default => null,
        };
    }
}
