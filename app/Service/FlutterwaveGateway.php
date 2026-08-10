<?php

namespace App\Service;

use App\Core\Logger;
use App\Core\PaymentGateway;

/**
 * Flutterwave adapter.
 *
 * Flutterwave (https://developer.flutterwave.com) is a Pan-African payments
 * provider. This adapter uses the v3 Standard Checkout flow:
 *
 *   1. initialize()  — POST /v3/payments to create a hosted checkout link.
 *   2. verify()      — GET /v3/transactions/verify_by_reference?tx_ref=… to
 *                      reconcile a payment by our own transaction reference.
 *   3. refund()      — POST /v3/refunds against the Flutterwave transaction id.
 *   4. webhooks      — Flutterwave signs the request with a `verif-hash` header
 *                      equal to the secret hash configured in the dashboard.
 *
 * Amounts: the PaymentGateway contract speaks minor units (kobo). Flutterwave
 * charges in major units (naira), so this adapter converts at the boundary —
 * /100 outbound on initialize/refund, *100 inbound on verify.
 *
 * Like PaystackGateway, the adapter simulates every call in development until a
 * real secret key is configured, so the storefront is testable end-to-end
 * without credentials.
 */
class FlutterwaveGateway implements PaymentGateway
{
    private string $secretKey;
    private string $webhookHash;
    private bool $simulate;

    private const API_BASE = 'https://api.flutterwave.com/v3';

    public function __construct()
    {
        $this->secretKey = (string) ($_ENV['FLUTTERWAVE_SECRET_KEY'] ?? '');
        $this->webhookHash = (string) ($_ENV['FLUTTERWAVE_WEBHOOK_HASH'] ?? '');
        $env = (string) ($_ENV['APP_ENV'] ?? 'development');

        // Simulation only in development AND when no real secret is configured.
        $this->simulate = $env === 'development'
            && ($this->secretKey === '' || $this->secretKey === 'FLWSECK_TEST-');
    }

    public function isSimulation(): bool
    {
        return $this->simulate;
    }

    public function initialize(array $params): array
    {
        if ($this->simulate) {
            $reference = $params['reference'] ?? $this->generateReference();
            return [
                'reference' => $reference,
                'access_code' => 'SIM_' . $this->generateReference(),
                'authorization_url' => '',
                'raw' => ['simulated' => true, 'tx_ref' => $reference],
            ];
        }

        $body = [
            'tx_ref' => $params['reference'] ?? $this->generateReference(),
            'amount' => $this->majorAmount((int) $params['amount_kobo']),
            'currency' => $params['currency'] ?? 'NGN',
            'customer' => [
                'email' => $params['email'] ?? '',
                'name' => (string) ($params['customer_name'] ?? ($params['email'] ?? '')),
            ],
        ];
        if (!empty($params['customer_phone'])) {
            $body['customer']['phonenumber'] = $params['customer_phone'];
        }
        if (!empty($params['redirect_url'])) {
            $body['redirect_url'] = $params['redirect_url'];
        }
        $body['customizations'] = [
            'title' => (string) ($_ENV['APP_NAME'] ?? 'Marigold Signature'),
            'description' => 'Payment for your Marigold Signature order',
        ];

        $res = $this->request('POST', '/payments', $body);
        $data = $res['data'] ?? [];

        return [
            'reference' => (string) ($data['tx_ref'] ?? ($body['tx_ref'] ?? '')),
            'access_code' => (string) ($data['id'] ?? ''),
            'authorization_url' => (string) ($data['link'] ?? ''),
            'raw' => $data,
        ];
    }

    public function verify(string $reference): array
    {
        if ($this->simulate) {
            return [
                'status' => 'success',
                'amount_kobo' => 0, // authoritative amount comes from the ledger/intent
                'currency' => 'NGN',
                'raw' => ['simulated' => true, 'tx_ref' => $reference],
            ];
        }

        $res = $this->request('GET', '/transactions/verify_by_reference?tx_ref=' . rawurlencode($reference));
        $data = $res['data'] ?? [];

        return [
            // Flutterwave reports 'successful' for a completed payment.
            'status' => ($data['status'] ?? '') === 'successful' ? 'success' : (string) ($data['status'] ?? 'pending'),
            'amount_kobo' => (int) round(((float) ($data['amount'] ?? 0)) * 100),
            'currency' => (string) ($data['currency'] ?? 'NGN'),
            'raw' => $data,
        ];
    }

    public function refund(string $reference, int $amountKobo, array $meta = []): array
    {
        if ($this->simulate) {
            return [
                'reference' => $this->generateReference(),
                'status' => 'processed',
                'raw' => ['simulated' => true, 'tx_ref' => $reference],
            ];
        }

        // The refund endpoint needs the Flutterwave transaction id, not our
        // tx_ref — resolve it first, then refund.
        $verified = $this->verify($reference);
        $transactionId = (string) ($verified['raw']['id'] ?? '');
        if ($transactionId === '') {
            throw new \RuntimeException('Could not resolve Flutterwave transaction id for refund.', 502);
        }

        $body = ['id' => $transactionId, 'amount' => $this->majorAmount($amountKobo)];
        if (!empty($meta)) {
            $body['comments'] = 'Refund for Marigold Signature order';
        }

        $res = $this->request('POST', '/refunds', $body);
        $data = $res['data'] ?? [];

        return [
            'reference' => (string) ($data['flw_ref'] ?? ($data['id'] ?? $this->generateReference())),
            'status' => (string) ($data['status'] ?? 'pending') === 'completed' ? 'processed' : 'pending',
            'raw' => $data,
        ];
    }

    public function verifySignature(string $rawBody, string $signature): bool
    {
        // Flutterwave sends the configured secret hash verbatim in the
        // `verif-hash` header — a plaintext compare, not an HMAC.
        if ($signature === '' || $this->webhookHash === '') {
            return false;
        }
        return hash_equals($this->webhookHash, $signature);
    }

    private function majorAmount(int $amountKobo): float
    {
        return round($amountKobo / 100, 2);
    }

    private function request(string $method, string $path, array $body = []): array
    {
        $ch = curl_init(self::API_BASE . $path);
        $headers = [
            'Authorization: Bearer ' . $this->secretKey,
            'Content-Type: application/json',
        ];

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        } else {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }

        $raw = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($errno !== 0) {
            Logger::error("Flutterwave curl error (#{$errno}): {$error}", 'payment');
            throw new \RuntimeException('Payment gateway unreachable. Please try again.', 502);
        }

        $decoded = json_decode((string) $raw, true) ?: [];

        if ($httpCode < 200 || $httpCode >= 300) {
            $message = $decoded['message'] ?? ('Flutterwave HTTP ' . $httpCode);
            Logger::error("Flutterwave {$method} {$path} -> HTTP {$httpCode}: {$message}", 'payment');
            throw new \RuntimeException($message, 502);
        }

        if (($decoded['status'] ?? '') !== 'success') {
            $message = $decoded['message'] ?? 'Flutterwave rejected the request.';
            Logger::error("Flutterwave {$method} {$path} status not success: " . substr((string) $raw, 0, 500), 'payment');
            throw new \RuntimeException($message, 502);
        }

        return $decoded;
    }

    private function generateReference(): string
    {
        return 'FLW_' . strtoupper(bin2hex(random_bytes(8))) . time();
    }
}
