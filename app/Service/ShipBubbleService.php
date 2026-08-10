<?php

namespace App\Service;

use App\Core\Logger;
use App\Core\Model;

/**
 * ShipBubble logistics adapter.
 *
 * ShipBubble (https://docs.shipbubble.com) is a Nigerian logistics aggregator.
 * The checkout flow used by the storefront:
 *
 *   1. validateAddress()  — turn a free-text address into a verified address_code.
 *   2. fetchRates()       — get courier options (prices, ETAs) for a package.
 *   3. createLabel()      — route the shipment to the selected courier (wallet charge).
 *   4. webhooks           — status changes arrive signed (HMAC-SHA512) and update
 *                           orders.shipping_status + tracking.
 *
 * Like PaystackGateway, the adapter simulates every call in development until a
 * real API key is configured, so the storefront is testable end-to-end without
 * credentials.
 *
 * Rate quotes are never trusted from the client: fetchRates() persists the full
 * quote payload keyed by request_token, and the checkout server re-reads that
 * payload to price shipping authoritatively.
 */
class ShipBubbleService
{
    private string $apiKey;
    private string $webhookSecret;
    private bool $simulate;
    private bool $usedFallback = false;

    private const API_BASE = 'https://api.shipbubble.com/v1';

    public function __construct()
    {
        $this->apiKey = (string) ($_ENV['SHIPBUBBLE_API_KEY'] ?? '');
        $this->webhookSecret = (string) ($_ENV['SHIPBUBBLE_WEBHOOK_SECRET'] ?? '');
        $env = (string) ($_ENV['APP_ENV'] ?? 'development');

        // A "real" key is a sandbox or live key longer than its prefix.
        $hasRealKey = strlen($this->apiKey) > 4
            && (str_starts_with($this->apiKey, 'sb_sandbox_') || str_starts_with($this->apiKey, 'sb_prod_'));

        // Simulation only in development AND when no real API key is configured.
        $this->simulate = $env === 'development' && !$hasRealKey;
    }

    public function isSimulation(): bool
    {
        return $this->simulate || $this->usedFallback;
    }

    // ------------------------------------------------------------------ rates

    /**
     * Validate a delivery address and return the verified address code + a
     * clean, formatted address. Returns the ShipBubble `data` object.
     */
    public function validateAddress(array $data): array
    {
        if ($this->simulate) {
            return $this->simulateAddress($data);
        }

        try {
            return $this->unwrap($this->request('POST', '/shipping/address/validate', $data));
        } catch (\RuntimeException $t) {
            if ($this->canFallbackToSimulation($t)) {
                Logger::warning("ShipBubble address validation falling back to simulated result: {$t->getMessage()}", 'shipbubble');
                $this->usedFallback = true;
                return $this->simulateAddress($data);
            }
            throw $t;
        }
    }

    /**
     * Fetch courier rates for a package. Accepts the full ShipBubble request
     * body (sender_address_code, reciever_address_code, pickup_date,
     * category_id, package_items, package_dimension, ...). Persists the quote
     * so checkout can re-price authoritatively from the request_token.
     *
     * @return array{request_token: string, couriers: array}
     */
    public function fetchRates(array $data): array
    {
        if ($this->simulate) {
            return $this->simulatedQuote($data);
        }

        try {
            $quote = $this->unwrap($this->request('POST', '/shipping/fetch_rates', $data));
        } catch (\RuntimeException $t) {
            if ($this->canFallbackToSimulation($t)) {
                Logger::warning("ShipBubble rate fetch falling back to simulated rates: {$t->getMessage()}", 'shipbubble');
                $this->usedFallback = true;
                return $this->simulatedQuote($data);
            }
            throw $t;
        }
        $token = (string) ($quote['request_token'] ?? '');
        $couriers = $quote['couriers'] ?? [];
        if ($token !== '' && is_array($couriers)) {
            $this->persistQuote($token, $data, $couriers);
        }
        return [
            'request_token' => $token,
            'couriers' => $couriers,
            'cheapest_courier' => $quote['cheapest_courier'] ?? null,
            'fastest_courier' => $quote['fastest_courier'] ?? null,
        ];
    }

    /**
     * Re-read a previously fetched quote from the database by request_token.
     * Returns null if the quote does not exist or has expired.
     */
    public function loadQuote(string $requestToken): ?array
    {
        $stmt = Model::getDB()->prepare(
            "SELECT payload FROM shipbubble_quotes
              WHERE request_token = :t AND expires_at > NOW() LIMIT 1"
        );
        $stmt->execute(['t' => $requestToken]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        $payload = json_decode((string) $row['payload'], true);
        return is_array($payload) ? $payload : null;
    }

    /**
     * Find a courier inside a persisted quote. Returns the courier object or null.
     */
    public function findCourier(array $quote, string $serviceCode, string $courierId): ?array
    {
        foreach (($quote['couriers'] ?? []) as $courier) {
            if ((string) ($courier['service_code'] ?? '') === $serviceCode
                && (string) ($courier['courier_id'] ?? '') === $courierId) {
                return $courier;
            }
        }
        return null;
    }

    // --------------------------------------------------------------- labels

    /**
     * Route a shipment to the selected courier. Returns the ShipBubble `data`
     * object (order_id SB-…, courier, status, tracking_url, payment).
     */
    public function createLabel(array $data): array
    {
        if ($this->simulate) {
            return [
                'order_id' => 'SB-' . strtoupper(bin2hex(random_bytes(6))),
                'courier' => [
                    'name' => (string) ($data['courier_name'] ?? 'ShipBubble Courier'),
                    'email' => 'courier@shipbubble.com',
                    'phone' => '+2340000000000',
                ],
                'status' => 'pending',
                'payment' => [
                    'shipping_fee' => 0,
                    'type' => 'wallet',
                    'status' => 'completed',
                    'currency' => 'NGN',
                ],
                'tracking_url' => '',
                'date' => date('Y-m-d H:i:s'),
            ];
        }

        return $this->unwrap($this->request('POST', '/shipping/labels', $data));
    }

    /**
     * Cancel a future-scheduled shipment. Only works before the processing date.
     */
    public function cancelLabel(string $orderId): array
    {
        if ($this->simulate) {
            return ['status' => 'success', 'message' => 'Shipment successfully cancelled'];
        }

        return $this->request('POST', '/shipping/labels/cancel/' . rawurlencode($orderId));
    }

    // -------------------------------------------------------------- tracking

    /**
     * List shipments (paginated). Returns the ShipBubble `data` object.
     */
    public function getShipments(array $filters = []): array
    {
        if ($this->simulate) {
            return ['results' => [], 'pagination' => ['current' => 1, 'perPage' => 50, 'total' => 0]];
        }

        $query = http_build_query($filters);
        return $this->unwrap($this->request('GET', '/shipping/labels' . ($query !== '' ? '?' . $query : '')));
    }

    // --------------------------------------------------------------- extras

    public function getWalletBalance(): array
    {
        if ($this->simulate) {
            return ['balance' => 0, 'currency' => 'NGN'];
        }
        return $this->unwrap($this->request('GET', '/shipping/wallet/balance'));
    }

    public function getInsuranceRates(string $requestToken): array
    {
        if ($this->simulate) {
            return [];
        }
        return $this->unwrap($this->request(
            'GET',
            '/shipping/insurance_rates?request_token=' . rawurlencode($requestToken)
        ));
    }

    public function getCategories(): array
    {
        if ($this->simulate) {
            return $this->simulateCategories();
        }
        return $this->unwrap($this->request('GET', '/shipping/labels/categories'));
    }

    public function getBoxes(): array
    {
        if ($this->simulate) {
            return [
                ['box_size_id' => 27459899, 'name' => 'tiny box', 'height' => 2, 'width' => 5, 'length' => 5, 'max_weight' => 5],
                ['box_size_id' => 44174253, 'name' => 'medium box', 'height' => 10, 'width' => 20, 'length' => 20, 'max_weight' => 20],
                ['box_size_id' => 42172412, 'name' => 'big box', 'height' => 2, 'width' => 40, 'length' => 40, 'max_weight' => 40],
            ];
        }
        return $this->unwrap($this->request('GET', '/shipping/labels/boxes'));
    }

    public function getCouriers(): array
    {
        if ($this->simulate) {
            return [
                ['name' => 'Redstar', 'service_code' => 'red_star_courier', 'origin_country' => 'NG', 'domestic' => true, 'status' => 'operational'],
                ['name' => 'Kwik', 'service_code' => 'kwik', 'origin_country' => 'NG', 'domestic' => true, 'status' => 'operational'],
                ['name' => 'GIG logistics', 'service_code' => 'gigl', 'origin_country' => 'NG', 'domestic' => true, 'status' => 'operational'],
            ];
        }
        return $this->unwrap($this->request('GET', '/shipping/couriers'));
    }

    // ---------------------------------------------------------------- webhook

    /**
     * Verify a ShipBubble webhook signature. ShipBubble hashes the raw request
     * body with HMAC-SHA512 using the SECRET_KEY and sends it in the
     * `x-ship-signature` header. Verified with hash_equals (constant time).
     */
    public function verifyWebhookSignature(string $rawBody, string $signature): bool
    {
        if ($signature === '' || $this->webhookSecret === '') {
            return false;
        }
        $expected = hash_hmac('sha512', $rawBody, $this->webhookSecret);
        return hash_equals($expected, $signature);
    }

    /**
     * Map a ShipBubble shipment status onto the store's orders.shipping_status.
     */
    public static function mapShippingStatus(string $status): string
    {
        return match ($status) {
            'picked_up', 'in_transit' => 'shipped',
            'completed' => 'delivered',
            default => 'pending',
        };
    }

    // ------------------------------------------------------------------ utils

    /**
     * Resolve the sender address code used for rate quotes and label pickup.
     *
     * Preference order:
     *   1. SHIPBUBBLE_SENDER_ADDRESS_CODE when set to a positive integer.
     *   2. SHIPBUBBLE_SENDER_ADDRESS (validated + cached for 30 days) so the
     *      storefront works without manually looking up the dashboard code.
     *   3. The default Marigold Signature Opebi address (validated + cached).
     *
     * Returns 0 when we cannot resolve a code (e.g. real API unreachable).
     */
    public function resolveSenderAddressCode(): int
    {
        if ($this->simulate) {
            return 98794022;
        }

        $configured = (int) ($_ENV['SHIPBUBBLE_SENDER_ADDRESS_CODE'] ?? 0);
        if ($configured > 0) {
            return $configured;
        }

        $cacheKey = 'shipbubble_sender_code';
        $cached = \App\Core\Cache::get($cacheKey, 0);
        if ($cached > 0) {
            return (int) $cached;
        }

        $address = (string) ($_ENV['SHIPBUBBLE_SENDER_ADDRESS'] ?? '');
        if ($address === '') {
            $address = '6 Oluwole Omole Street, Opebi, Lagos, Nigeria';
        }

        try {
            $validated = $this->validateAddress([
                'name' => (string) ($_ENV['SHIPBUBBLE_SENDER_NAME'] ?? 'Marigold Signature'),
                'email' => (string) ($_ENV['SHIPBUBBLE_SENDER_EMAIL'] ?? 'store@marigoldsignature.com'),
                'phone' => (string) ($_ENV['SHIPBUBBLE_SENDER_PHONE'] ?? '+2347012345678'),
                'address' => $address,
            ]);
        } catch (\Throwable $t) {
            if ($this->canFallbackToSimulation($t)) {
                Logger::warning("ShipBubble sender address falling back to simulated code: {$t->getMessage()}", 'shipbubble');
                $this->usedFallback = true;
                return 98794022;
            }
            Logger::error("ShipBubble sender address validation failed: {$t->getMessage()}", 'shipbubble');
            return 0;
        }

        $code = (int) ($validated['address_code'] ?? 0);
        if ($code > 0) {
            \App\Core\Cache::set($cacheKey, $code, 30 * 86400);
        }
        return $code;
    }

    /**
     * Resolve a valid package category_id. ShipBubble category IDs differ per
     * account, so we pull the account's live list (cached 7 days), honour a
     * configured SHIPBUBBLE_DEFAULT_CATEGORY_ID only when it exists in that
     * list, and otherwise prefer a generic "Light weight items" category,
     * falling back to the first entry.
     */
    public function resolveCategoryId(): int
    {
        if ($this->simulate) {
            return 90097994;
        }

        $categories = $this->loadCategories();
        if (count($categories) === 0) {
            return (int) ($_ENV['SHIPBUBBLE_DEFAULT_CATEGORY_ID'] ?? 0);
        }

        $configured = (int) ($_ENV['SHIPBUBBLE_DEFAULT_CATEGORY_ID'] ?? 0);
        if ($configured > 0) {
            foreach ($categories as $category) {
                if ((int) ($category['category_id'] ?? 0) === $configured) {
                    return $configured;
                }
            }
        }

        foreach ($categories as $category) {
            if (stripos((string) ($category['category'] ?? ''), 'light weight') !== false) {
                return (int) ($category['category_id'] ?? 0);
            }
        }

        $first = $categories[0];
        return (int) (is_array($first) ? ($first['category_id'] ?? 0) : 0);
    }

    /**
     * Load the account's package categories, cached for 7 days.
     */
    private function loadCategories(): array
    {
        $cacheKey = 'shipbubble_categories';
        $cached = \App\Core\Cache::get($cacheKey, null);
        if (is_array($cached)) {
            return $cached;
        }

        try {
            $decoded = $this->request('GET', '/shipping/labels/categories');
            $categories = $decoded['data'] ?? [];
        } catch (\Throwable $t) {
            if ($this->canFallbackToSimulation($t)) {
                Logger::warning("ShipBubble category lookup falling back to simulated categories: {$t->getMessage()}", 'shipbubble');
                $this->usedFallback = true;
                return $this->simulateCategories();
            }
            Logger::error("ShipBubble category lookup failed: {$t->getMessage()}", 'shipbubble');
            return [];
        }

        if (is_array($categories)) {
            \App\Core\Cache::set($cacheKey, $categories, 7 * 86400);
        }
        return is_array($categories) ? $categories : [];
    }

    private function persistQuote(string $requestToken, array $request, array $couriers): void
    {
        $db = Model::getDB();
        $stmt = $db->prepare(
            "INSERT INTO shipbubble_quotes (request_token, request, payload, expires_at)
             VALUES (:t, :r, :p, DATE_ADD(NOW(), INTERVAL 7 DAY))
             ON DUPLICATE KEY UPDATE request = VALUES(request), payload = VALUES(payload)"
        );
        $stmt->execute([
            't' => $requestToken,
            'r' => json_encode($request),
            'p' => json_encode(['couriers' => $couriers]),
        ]);
    }

    private function simulateAddress(array $data): array
    {
        $address = (string) ($data['address'] ?? '');
        return [
            'name' => (string) ($data['name'] ?? ''),
            'email' => (string) ($data['email'] ?? ''),
            'phone' => (string) ($data['phone'] ?? ''),
            'formatted_address' => $address !== '' ? $address : '1 Test Street, Ikeja, Lagos, Nigeria',
            'country' => 'Nigeria',
            'country_code' => 'NG',
            'city' => (string) ($data['city'] ?? 'Lagos'),
            'city_code' => 'Lagos',
            'state' => (string) ($data['state'] ?? 'Lagos'),
            'state_code' => 'LA',
            'postal_code' => '100271',
            'latitude' => 6.6018,
            'longitude' => 3.3515,
            'address_code' => 98794022,
        ];
    }

    private function simulateCategories(): array
    {
        return [
            ['category_id' => 90097994, 'category' => 'Accessories'],
            ['category_id' => 3035980, 'category' => 'Electronics'],
            ['category_id' => 70830897, 'category' => 'Electronic gadgets'],
            ['category_id' => 66484941, 'category' => 'Jewelry'],
            ['category_id' => 69709726, 'category' => 'Food'],
            ['category_id' => 98246239, 'category' => 'Fashion wears'],
        ];
    }

    private function simulatedQuote(array $data): array
    {
        $token = 'sim_' . bin2hex(random_bytes(12));
        $couriers = $this->simulateCouriers();
        $this->persistQuote($token, $data, $couriers);
        return [
            'request_token' => $token,
            'couriers' => $couriers,
            'cheapest_courier' => $couriers[0],
            'fastest_courier' => $couriers[1],
        ];
    }

    private function canFallbackToSimulation(\Throwable $t): bool
    {
        if ((string) ($_ENV['APP_ENV'] ?? 'development') !== 'development') {
            return false;
        }

        $message = strtolower($t->getMessage());
        foreach ([
            'enable api access',
            'invalid sender address code',
            'invalid package category selected',
            'logistics provider unreachable',
            'ssl certificate problem',
        ] as $needle) {
            if (str_contains($message, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function unwrap(array $res): array
    {
        if (($res['status'] ?? '') !== 'success') {
            $message = (string) ($res['message'] ?? 'ShipBubble request failed.');
            Logger::error("ShipBubble API failure: {$message}", 'shipbubble');
            throw new \RuntimeException($message, 502);
        }
        return is_array($res['data'] ?? null) ? $res['data'] : [];
    }

    private function request(string $method, string $path, array $body = []): array
    {
        $ch = curl_init(self::API_BASE . $path);
        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
        ];

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if ($method === 'POST' || $method === 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
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
            Logger::error("ShipBubble curl error (#{$errno}): {$error}", 'shipbubble');
            throw new \RuntimeException('Logistics provider unreachable. Please try again.', 502);
        }

        $decoded = json_decode((string) $raw, true) ?: [];

        if ($httpCode < 200 || $httpCode >= 300) {
            $message = $decoded['message'] ?? ('ShipBubble HTTP ' . $httpCode);
            Logger::error("ShipBubble {$method} {$path} -> HTTP {$httpCode}: {$message}", 'shipbubble');
            throw new \RuntimeException($message, 502);
        }

        return $decoded;
    }

    private function simulateCouriers(): array
    {
        return [
            [
                'courier_id' => 'gigl',
                'courier_name' => 'GIG logistics',
                'courier_image' => '',
                'service_code' => 'gigl',
                'insurance' => ['code' => 'not available', 'fee' => 0],
                'discount' => ['percentage' => 0, 'symbol' => '%', 'discounted' => 0],
                'service_type' => 'pickup',
                'waybill' => true,
                'delivery_eta' => '1 - 2 working days',
                'currency' => 'NGN',
                'vat' => 0,
                'total' => 4500,
            ],
            [
                'courier_id' => 'kwik',
                'courier_name' => 'Kwik',
                'courier_image' => '',
                'service_code' => 'kwik',
                'insurance' => ['code' => 'not available', 'fee' => 0],
                'discount' => ['percentage' => 0, 'symbol' => '%', 'discounted' => 0],
                'service_type' => 'pickup',
                'waybill' => false,
                'delivery_eta' => 'Same day delivery',
                'currency' => 'NGN',
                'vat' => 0,
                'total' => 6500,
            ],
            [
                'courier_id' => 'red_star_courier',
                'courier_name' => 'Redstar',
                'courier_image' => '',
                'service_code' => 'red_star_courier',
                'insurance' => ['code' => 'not available', 'fee' => 0],
                'discount' => ['percentage' => 0, 'symbol' => '%', 'discounted' => 0],
                'service_type' => 'pickup',
                'waybill' => false,
                'delivery_eta' => '3 - 5 working days',
                'currency' => 'NGN',
                'vat' => 0,
                'total' => 3200,
            ],
        ];
    }
}
