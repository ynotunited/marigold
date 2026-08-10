<?php

namespace App\Controller\Api;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Logger;
use App\Core\Model;
use App\Service\RateLimiter;
use App\Service\ShipBubbleService;

/**
 * ShipBubble logistics API.
 *
 * rates():
 *   Browser-initiated rate lookup. CSRF-protected and rate-limited. Validates
 *   the delivery address, fetches live courier rates, and persists the quote
 *   server-side (keyed by request_token) so the checkout can price shipping
 *   authoritatively at submit time. Simulated when no API key is configured.
 *
 * webhook():
 *   ShipBubble status-change ingress. NOT CSRF-protected — authenticity comes
 *   from the HMAC-SHA512 signature (x-ship-signature). Always returns a fast 2xx
 *   ack so providers stop retrying; duplicate events are acknowledged + dropped.
 */
class ShipbubbleApiController extends Controller
{
    public function rates()
    {
        $this->shield();

        $raw = file_get_contents('php://input');
        $data = json_decode((string) $raw, true);
        if (!is_array($data)) {
            $this->json(['error' => 'Invalid JSON body.'], 400);
        }

        $email = isset($data['email']) ? filter_var(trim((string) $data['email']), FILTER_VALIDATE_EMAIL) : null;
        $first = trim((string) ($data['first_name'] ?? ''));
        $last = trim((string) ($data['last_name'] ?? ''));
        $phone = preg_replace('/[^0-9+]/', '', trim((string) ($data['phone'] ?? '')));
        $address1 = trim((string) ($data['address_line1'] ?? ''));
        $address2 = trim((string) ($data['address_line2'] ?? ''));
        $city = trim((string) ($data['city'] ?? ''));
        $state = trim((string) ($data['state'] ?? ''));
        $postal = trim((string) ($data['postal_code'] ?? ''));

        if (!$email || $first === '' || $last === '' || $phone === '') {
            $this->json(['error' => 'Contact details (email, name, phone) are required to get delivery rates.'], 422);
        }
        if ($address1 === '' || $city === '' || $state === '') {
            $this->json(['error' => 'Street address, city and state are required to get delivery rates.'], 422);
        }

        $items = $data['items'] ?? [];
        if (!is_array($items) || count($items) === 0 || count($items) > 50) {
            $this->json(['error' => 'Add at least one item to get delivery rates.'], 422);
        }

        // ---- Resolve items + weights/prices from the DB (or the client for
        //      catalogue-only items that have no DB row) ----
        $productIds = [];
        $slugLookup = [];
        $quantities = [];
        foreach ($items as $item) {
            $qty = (int) ($item['quantity'] ?? 0);
            if ($qty < 1 || $qty > 1000000) {
                $this->json(['error' => 'Invalid item data.'], 422);
            }
            $productId = (int) ($item['product_id'] ?? 0);
            $slug = trim((string) ($item['slug'] ?? ''));
            if ($productId > 0) {
                $productIds[$productId] = $productId;
                $quantities[$productId] = ($quantities[$productId] ?? 0) + $qty;
            } elseif ($slug !== '') {
                $slugLookup[$slug] = $slug;
                $quantities[$slug] = ($quantities[$slug] ?? 0) + $qty;
            } else {
                $this->json(['error' => 'Invalid item data.'], 422);
            }
        }

        $products = $this->loadProducts(array_values($productIds));
        $slugRows = $slugLookup ? $this->loadProductsBySlug(array_values($slugLookup)) : [];

        $packageItems = [];
        $totalWeight = 0.0;
        foreach ($quantities as $key => $qty) {
            $row = $products[$key] ?? null;
            if ($row === null && isset($slugRows[$key])) {
                $row = $slugRows[$key];
            }
            if ($row !== null) {
                $weight = (float) ($row['weight'] ?? 0) > 0 ? (float) $row['weight'] : 0.5;
                $totalWeight += $weight * $qty;
                $packageItems[] = [
                    'name' => $row['name'],
                    'description' => $row['short_description'] ?? $row['name'],
                    'unit_weight' => number_format($weight, 3, '.', ''),
                    'unit_amount' => (string) self::effectiveUnit($row),
                    'quantity' => (string) $qty,
                ];
                continue;
            }

            // Catalogue-only item (no DB row): trust name/price sent by the client.
            $clientItem = null;
            foreach ($items as $it) {
                $id = (int) ($it['product_id'] ?? 0);
                $sl = trim((string) ($it['slug'] ?? ''));
                if (($id > 0 && $id === $key) || ($sl !== '' && $sl === $key)) {
                    $clientItem = $it;
                    break;
                }
            }
            $weight = 0.5;
            $totalWeight += $weight * $qty;
            $packageItems[] = [
                'name' => mb_substr(trim((string) ($clientItem['name'] ?? '')), 0, 255) ?: $key,
                'description' => mb_substr(trim((string) ($clientItem['name'] ?? '')), 0, 255) ?: $key,
                'unit_weight' => number_format($weight, 3, '.', ''),
                'unit_amount' => (string) max(0, round((float) ($clientItem['price'] ?? 0), 2)),
                'quantity' => (string) $qty,
            ];
        }

        // ---- Validate the delivery address with ShipBubble ----
        $addressString = trim($address1 . ($address2 !== '' ? ', ' . $address2 : '') . ', ' . $city . ', ' . $state . ', Nigeria');
        $shipbubble = new ShipBubbleService();

        try {
            $validated = $shipbubble->validateAddress([
                'name' => trim($first . ' ' . $last),
                'email' => $email,
                'phone' => $phone,
                'address' => $addressString,
            ]);
        } catch (\Throwable $t) {
            Logger::error("ShipBubble address validation failed: {$t->getMessage()}", 'shipbubble');
            $this->json(['error' => 'We could not verify that delivery address. Please check it and try again.'], 422);
        }

        $receiverCode = (int) ($validated['address_code'] ?? 0);
        if ($receiverCode <= 0) {
            $this->json(['error' => 'We could not verify that delivery address. Please check it and try again.'], 422);
        }

        // ---- Fetch courier rates ----
        $senderCode = $shipbubble->resolveSenderAddressCode();
        if ($senderCode <= 0) {
            $this->json(['error' => 'Delivery rates are unavailable right now. Please try again shortly.'], 502);
        }
        $categoryId = $shipbubble->resolveCategoryId();
        $pickupDate = date('Y-m-d', strtotime('+1 day', time()));

        try {
            $quote = $shipbubble->fetchRates([
                'sender_address_code' => $senderCode,
                'reciever_address_code' => $receiverCode,
                'pickup_date' => $pickupDate,
                'category_id' => $categoryId,
                'package_items' => $packageItems,
                'package_dimension' => [
                    'length' => (int) ($_ENV['SHIPBUBBLE_PACKAGE_LENGTH'] ?? 20),
                    'width' => (int) ($_ENV['SHIPBUBBLE_PACKAGE_WIDTH'] ?? 15),
                    'height' => (int) ($_ENV['SHIPBUBBLE_PACKAGE_HEIGHT'] ?? 10),
                ],
                'service_type' => 'pickup',
            ]);
        } catch (\Throwable $t) {
            Logger::error("ShipBubble rate fetch failed: {$t->getMessage()}", 'shipbubble');
            $this->json(['error' => 'Delivery rates are unavailable right now. Please try again shortly.'], 502);
        }

        $couriers = array_map(fn (array $c) => [
            'courier_id' => (string) ($c['courier_id'] ?? ''),
            'courier_name' => (string) ($c['courier_name'] ?? ''),
            'courier_image' => (string) ($c['courier_image'] ?? ''),
            'service_code' => (string) ($c['service_code'] ?? ''),
            'service_type' => (string) ($c['service_type'] ?? 'pickup'),
            'delivery_eta' => (string) ($c['delivery_eta'] ?? ''),
            'pickup_eta' => (string) ($c['pickup_eta'] ?? ''),
            'currency' => (string) ($c['currency'] ?? 'NGN'),
            'total' => round((float) ($c['total'] ?? 0), 2),
            'vat' => round((float) ($c['vat'] ?? 0), 2),
            'discount' => $c['discount'] ?? null,
            'waybill' => (bool) ($c['waybill'] ?? false),
            'tracking_level' => (int) ($c['tracking_level'] ?? 0),
            'rating' => (float) ($c['ratings'] ?? 0),
            'info' => $c['info'] ?? null,
        ], $quote['couriers'] ?? []);

        $this->json([
            'request_token' => (string) ($quote['request_token'] ?? ''),
            'receiver_address_code' => $receiverCode,
            'formatted_address' => $validated['formatted_address'] ?? $addressString,
            'package_weight' => round($totalWeight, 3),
            'pickup_date' => $pickupDate,
            'couriers' => $couriers,
            'simulated' => $shipbubble->isSimulation(),
        ], 200);
    }

    /**
     * ShipBubble webhook ingress. Signature-verified; always acks fast.
     */
    public function webhook()
    {
        $raw = file_get_contents('php://input');
        $payload = json_decode((string) $raw, true);
        $signature = $_SERVER['HTTP_X_SHIP_SIGNATURE'] ?? '';

        if (!is_array($payload)) {
            Logger::warning('ShipBubble webhook received invalid JSON.', 'shipbubble');
            $this->json(['received' => false], 400);
        }

        $shipbubble = new ShipBubbleService();

        if (!$shipbubble->verifyWebhookSignature((string) $raw, $signature)) {
            Logger::warning('ShipBubble webhook signature verification failed.', 'shipbubble');
            $this->json(['received' => true, 'signature_valid' => false], 401);
        }

        $this->ingestWebhook($payload);

        $this->json(['received' => true, 'signature_valid' => true], 200);
    }

    // ------------------------------------------------------------------ utils

    private function ingestWebhook(array $payload): void
    {
        $db = Model::getDB();
        $eventType = (string) ($payload['event'] ?? 'shipment.status.changed');
        $orderId = (string) ($payload['order_id'] ?? '');
        $eventId = $eventType . '|' . ($orderId !== '' ? $orderId : hash('sha256', json_encode($payload)));

        // 1. Persist the raw event, deduplicated on (provider, event_id).
        try {
            $stmt = $db->prepare(
                "INSERT INTO webhook_events (provider, event_id, event_type, signature_valid, payload)
                 VALUES ('shipbubble', :e, :t, 1, :p)"
            );
            $stmt->execute(['e' => $eventId, 't' => $eventType, 'p' => json_encode($payload)]);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return; // Duplicate event — ack and drop.
            }
            Logger::error("ShipBubble webhook persist failed: {$e->getMessage()}", 'shipbubble');
            return;
        }

        if ($orderId === '') {
            return;
        }

        // 2. Find the order by its ShipBubble label id.
        $stmt = $db->prepare("SELECT id FROM orders WHERE shipbubble_order_id = :o LIMIT 1");
        $stmt->execute(['o' => $orderId]);
        $order = $stmt->fetch();
        if (!$order) {
            Logger::warning("ShipBubble webhook for unknown label {$orderId}", 'shipbubble');
            return;
        }

        $courier = $payload['courier'] ?? [];
        $trackingCode = (string) ($courier['tracking_code'] ?? '');
        $trackingUrl = (string) ($payload['tracking_url'] ?? '');
        $status = (string) ($payload['status'] ?? '');
        $waybill = (string) ($payload['waybill_document'] ?? '');

        if ($eventType === 'shipment.label.created') {
            $courierName = (string) ($courier['name'] ?? '');
            $stmt = $db->prepare(
                "UPDATE orders
                    SET shipbubble_status = :s, shipbubble_courier_name = COALESCE(NULLIF(:cn, ''), shipbubble_courier_name),
                        shipbubble_tracking_code = COALESCE(NULLIF(:tc, ''), shipbubble_tracking_code),
                        shipbubble_tracking_url = COALESCE(NULLIF(:tu, ''), shipbubble_tracking_url)
                  WHERE id = :i"
            );
            $stmt->execute([
                's' => $status !== '' ? $status : 'pending',
                'cn' => $courierName,
                'tc' => $trackingCode,
                'tu' => $trackingUrl,
                'i' => (int) $order['id'],
            ]);
            return;
        }

        if ($eventType === 'shipment.cancelled') {
            $stmt = $db->prepare(
                "UPDATE orders SET shipbubble_status = 'cancelled' WHERE id = :i"
            );
            $stmt->execute(['i' => (int) $order['id']]);
            return;
        }

        // shipment.status.changed (and anything else) — reflect tracking + status.
        if ($status === '') {
            return;
        }

        $shippingStatus = ShipBubbleService::mapShippingStatus($status);
        $stmt = $db->prepare(
            "UPDATE orders
                SET shipbubble_status = :s, shipping_status = :ss,
                    shipbubble_tracking_code = COALESCE(NULLIF(:tc, ''), shipbubble_tracking_code),
                    shipbubble_tracking_url = COALESCE(NULLIF(:tu, ''), shipbubble_tracking_url)
              WHERE id = :i"
        );
        $stmt->execute([
            's' => $status,
            'ss' => $shippingStatus,
            'tc' => $trackingCode,
            'tu' => $trackingUrl,
            'i' => (int) $order['id'],
        ]);

        Logger::info("ShipBubble label {$orderId} -> order #{$order['id']} status {$status} (shipping {$shippingStatus})", 'shipbubble');
    }

    private function shield(): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $limitKey = 'sb_rates_' . hash('sha256', $ip);
        if (RateLimiter::tooManyAttempts($limitKey, 20)) {
            $this->json(['error' => 'Too many requests. Please try again later.'], 429);
        }
        RateLimiter::hit($limitKey, 60);

        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!CSRF::verify($token)) {
            $this->json(['error' => 'Invalid CSRF token.'], 401);
        }
    }

    private function loadProducts(array $ids): array
    {
        if (!$ids) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = Model::getDB()->prepare(
            "SELECT id, sku, name, short_description, price, sale_price, weight FROM products
             WHERE status = 'published' AND id IN ($placeholders)"
        );
        $stmt->execute($ids);
        $map = [];
        foreach ($stmt->fetchAll() as $row) {
            $map[(int) $row['id']] = $row;
        }
        return $map;
    }

    private function loadProductsBySlug(array $slugs): array
    {
        if (!$slugs) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($slugs), '?'));
        $stmt = Model::getDB()->prepare(
            "SELECT id, sku, name, slug, short_description, price, sale_price, weight FROM products
             WHERE status = 'published' AND slug IN ($placeholders)"
        );
        $stmt->execute($slugs);
        $map = [];
        foreach ($stmt->fetchAll() as $row) {
            $map[$row['slug']] = $row;
        }
        return $map;
    }

    private static function effectiveUnit(array $row): float
    {
        return $row['sale_price'] !== null && (float) $row['sale_price'] < (float) $row['price']
            ? (float) $row['sale_price']
            : (float) $row['price'];
    }
}
