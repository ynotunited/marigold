<?php

namespace App\Controller\Api;

use App\Core\Controller;
use App\Core\Logger;
use App\Service\PaymentService;

/**
 * Webhook ingress.
 *
 * NOT CSRF-protected and NOT session-bound — authenticity is established by the
 * provider's HMAC signature (verified inside PaymentService::handleWebhook)
 * before any ledger mutation. Always returns a fast 2xx ack so providers stop
 * retrying; duplicate event ids are acknowledged and dropped.
 */
class WebhookController extends Controller
{
    public function handle()
    {
        $raw = file_get_contents('php://input');
        $payload = json_decode((string) $raw, true);
        $signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';

        if (!is_array($payload)) {
            Logger::warning('Webhook received with invalid JSON payload.', 'payment');
            $this->json(['received' => false], 400);
        }

        try {
            $result = (new PaymentService())->handleWebhook($payload, $signature, (string) $raw);
        } catch (\InvalidArgumentException $e) {
            $this->json(['received' => false, 'error' => $e->getMessage()], $e->getCode() ?: 400);
        } catch (\Throwable $t) {
            Logger::error('Webhook processing error: ' . $t->getMessage(), 'payment');
            // Ack anyway to avoid infinite provider retries; the raw event is
            // already persisted for manual reconciliation.
            $this->json(['received' => true], 200);
        }

        if (isset($result['signature_valid']) && !$result['signature_valid']) {
            $this->json($result, 401);
        }

        $this->json($result, 200);
    }
}
