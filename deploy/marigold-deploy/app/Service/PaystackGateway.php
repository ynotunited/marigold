<?php

namespace App\Service;

use App\Core\Logger;
use App\Core\PaymentGateway;

/**
 * Paystack adapter.
 *
 * Paystack does not implement a separate authorize/capture split — a successful
 * charge is captured by default. We model the ledger faithfully anyway:
 * 'authorized' is recorded when a payment is verified as successful, and
 * 'captured' is the same charge acknowledged in the ledger. Refunds create
 * 'refunded' rows.
 *
 * Webhook authenticity: Paystack signs the raw request body with the secret key
 * using HMAC-SHA512, delivered in the `x-paystack-signature` header. We verify
 * with hash_equals (constant time).
 */
class PaystackGateway implements PaymentGateway
{
    private string $secretKey;
    private string $publicKey;
    private bool $simulate;

    private const API_BASE = 'https://api.paystack.co';

    public function __construct()
    {
        $this->secretKey = (string) ($_ENV['PAYSTACK_SECRET_KEY'] ?? '');
        $this->publicKey = (string) ($_ENV['PAYSTACK_PUBLIC_KEY'] ?? '');
        $env = (string) ($_ENV['APP_ENV'] ?? 'development');

        // Simulation only in development AND when no real secret is configured.
        $this->simulate = $env === 'development'
            && ($this->secretKey === '' || $this->secretKey === 'sk_test_');
    }

    public function isSimulation(): bool
    {
        return $this->simulate;
    }

    public function initialize(array $params): array
    {
        if ($this->simulate) {
            $reference = $this->generateReference();
            return [
                'reference' => $reference,
                'access_code' => 'SIM_' . $this->generateReference(),
                'authorization_url' => '',
                'raw' => ['simulated' => true, 'reference' => $reference],
            ];
        }

        $body = [
            'email' => $params['email'] ?? '',
            'amount' => (int) $params['amount_kobo'],
            'currency' => $params['currency'] ?? 'NGN',
        ];
        if (!empty($params['reference'])) {
            $body['reference'] = $params['reference'];
        }
        $callbackUrl = (string) ($params['callback_url'] ?? $params['redirect_url'] ?? '');
        if ($callbackUrl !== '') {
            $body['callback_url'] = $callbackUrl;
        }
        if (!empty($params['metadata']) && is_array($params['metadata'])) {
            $body['metadata'] = $params['metadata'];
        }

        $res = $this->request('POST', '/transaction/initialize', $body);
        $data = $res['data'] ?? [];

        return [
            'reference' => (string) ($data['reference'] ?? ''),
            'access_code' => (string) ($data['access_code'] ?? ''),
            'authorization_url' => (string) ($data['authorization_url'] ?? ''),
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
                'raw' => ['simulated' => true, 'reference' => $reference],
            ];
        }

        $res = $this->request('GET', '/transaction/verify/' . rawurlencode($reference));
        $data = $res['data'] ?? [];

        return [
            'status' => (string) ($data['status'] ?? 'pending'),
            'amount_kobo' => (int) ($data['amount'] ?? 0),
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
                'raw' => ['simulated' => true, 'transaction' => $reference],
            ];
        }

        $body = ['transaction' => $reference, 'amount' => $amountKobo];
        if (!empty($meta)) {
            $body['metadata'] = $meta;
        }

        $res = $this->request('POST', '/refund', $body);
        $data = $res['data'] ?? [];

        return [
            'reference' => (string) ($data['reference'] ?? $this->generateReference()),
            'status' => (string) ($data['status'] ?? 'pending'),
            'raw' => $data,
        ];
    }

    public function verifySignature(string $rawBody, string $signature): bool
    {
        if ($signature === '' || $this->secretKey === '') {
            return false;
        }
        $expected = hash_hmac('sha512', $rawBody, $this->secretKey);
        return hash_equals($expected, $signature);
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
            Logger::error("Paystack curl error (#{$errno}): {$error}", 'payment');
            throw new \RuntimeException('Payment gateway unreachable. Please try again.', 502);
        }

        $decoded = json_decode((string) $raw, true) ?: [];

        if ($httpCode < 200 || $httpCode >= 300) {
            $message = $decoded['message'] ?? ('Paystack HTTP ' . $httpCode);
            Logger::error("Paystack {$method} {$path} -> HTTP {$httpCode}: {$message}", 'payment');
            throw new \RuntimeException($message, 502);
        }

        if (($decoded['status'] ?? false) !== true) {
            Logger::error("Paystack {$method} {$path} returned status=false: " . substr((string) $raw, 0, 500), 'payment');
            throw new \RuntimeException($decoded['message'] ?? 'Payment gateway rejected the request.', 502);
        }

        return $decoded;
    }

    private function generateReference(): string
    {
        return 'REF_' . strtoupper(bin2hex(random_bytes(8))) . time();
    }
}
