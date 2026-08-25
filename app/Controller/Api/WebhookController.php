<?php

namespace App\Controller\Api;

use App\Core\Controller;
use App\Core\Logger;
use App\Service\PaymentService;

/**
 * Webhook ingress.
 *
 * Security layers (in order):
 *   1. IP allowlisting  — rejects requests from IPs not in the whitelist.
 *   2. Signature check  — HMAC/secret-hash verified inside PaymentService before
 *                         any ledger mutation or order update.
 *   3. Event idempotency — duplicate (provider, event_id) pairs are detected at
 *                          INSERT and silently acknowledged.
 *
 * Always returns a fast 2xx ack so providers stop retrying.
 *
 * Configure IP allowlists via .env (comma-separated, supports optional CIDR):
 *   WEBHOOK_IP_WHITELIST=1.2.3.4,5.6.7.0/24
 *   WEBHOOK_IP_WHITELIST_PAYSTACK=1.2.3.4
 *   WEBHOOK_IP_WHITELIST_FLUTTERWAVE=5.6.7.0/24
 *
 * If no whitelist is configured for a provider the IP check is skipped (allow
 * all) — but you should always set one in production.
 */
class WebhookController extends Controller
{
    public function handle()
    {
        $this->ingest('paystack', $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '');
    }

    public function handleFlutterwave()
    {
        $this->ingest('flutterwave', $_SERVER['HTTP_VERIF_HASH'] ?? '');
    }

    private function ingest(string $provider, string $signature)
    {
        // ── 1. IP allowlisting ───────────────────────────────────────────
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
        if (!$this->ipAllowed($clientIp, $provider)) {
            Logger::warning(
                "Webhook {$provider} rejected — IP {$clientIp} not in allowlist",
                'security'
            );
            $this->json(['received' => false, 'error' => 'Forbidden'], 403);
        }

        // ── 2. Parse and validate payload ────────────────────────────────
        $raw = file_get_contents('php://input');
        $payload = json_decode((string) $raw, true);

        if (!is_array($payload)) {
            Logger::warning('Webhook received with invalid JSON payload.', 'payment');
            $this->json(['received' => false], 400);
        }

        try {
            $result = (new PaymentService($provider))->handleWebhook($payload, $signature, (string) $raw, $provider);
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

    /**
     * Check if the client IP is permitted for this provider.
     *
     * Resolution order:
     *   1. WEBHOOK_IP_WHITELIST_{PROVIDER}  (provider-specific)
     *   2. WEBHOOK_IP_WHITELIST              (shared across providers)
     *   3. Allow all if neither is set        (backward-compatible)
     *
     * Supports bare IPs (1.2.3.4) and CIDR (1.2.3.0/24).
     */
    private function ipAllowed(string $clientIp, string $provider): bool
    {
        $envKey = 'WEBHOOK_IP_WHITELIST_' . strtoupper($provider);
        $raw = $_ENV[$envKey] ?? '';

        if ($raw === '') {
            $raw = $_ENV['WEBHOOK_IP_WHITELIST'] ?? '';
        }

        // Empty / not configured → allow all (backward-compatible).
        if (trim($raw) === '') {
            return true;
        }

        $allowed = array_map('trim', explode(',', $raw));
        $clientIp = trim($clientIp);

        foreach ($allowed as $entry) {
            if ($entry === '') {
                continue;
            }

            // CIDR notation: check if client IP falls within the subnet.
            if (str_contains($entry, '/')) {
                if ($this->cidrMatch($clientIp, $entry)) {
                    return true;
                }
            } else {
                if ($clientIp === $entry) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if an IP address falls within a CIDR range.
     */
    private function cidrMatch(string $ip, string $cidr): bool
    {
        [$subnet, $prefix] = explode('/', $cidr);
        $prefix = (int) $prefix;

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);

        if ($ipLong === false || $subnetLong === false) {
            return false;
        }

        $mask = -1 << (32 - $prefix);
        return ($ipLong & $mask) === ($subnetLong & $mask);
    }
}
