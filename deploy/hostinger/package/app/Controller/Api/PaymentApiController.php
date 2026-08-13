<?php

namespace App\Controller\Api;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Logger;
use App\Core\RowSecurity;
use App\Core\Session;
use App\Service\PaymentService;
use App\Service\RateLimiter;

/**
 * Payment API — idempotency-shielded, CSRF-protected endpoints.
 *
 * Browser-initiated writes MUST send:
 *   - `Idempotency-Key: <uuid-v4>` (header or `idempotency_key` body field)
 *   - `X-CSRF-Token: <session csrf token>`
 */
class PaymentApiController extends Controller
{
    private PaymentService $payments;

    public function __construct()
    {
        $this->payments = new PaymentService();
    }

    public function createIntent()
    {
        $this->shield('create');
        $data = $this->readJsonBody();
        $key = $this->idempotencyKey($data);

        try {
            $out = $this->payments->createIntent($data, $key, $this->requestMeta(), $this->actor());
        } catch (\InvalidArgumentException $e) {
            $this->json(['error' => $e->getMessage()], $e->getCode() ?: 422);
        } catch (\RuntimeException $e) {
            Logger::error("createIntent: {$e->getMessage()}", 'payment');
            $this->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }

        if ($out['replayed']) {
            $this->json(['replayed' => true, 'data' => $out['result']], 200);
        }
        if (!empty($out['in_flight'])) {
            $this->json(['error' => 'A request with this idempotency key is already in progress.'], 409);
        }
        $this->json(['replayed' => false, 'data' => $out['result']], 201);
    }

    public function capture(int $id)
    {
        $this->shield('capture');
        $this->assertPositiveId($id);
        $data = $this->readJsonBody();
        $key = $this->idempotencyKey($data);

        try {
            $out = $this->payments->capture($id, $data, $key, $this->requestMeta(), $this->actor());
        } catch (\InvalidArgumentException $e) {
            $this->json(['error' => $e->getMessage()], $e->getCode() ?: 422);
        } catch (\RuntimeException $e) {
            Logger::error("capture #{$id}: {$e->getMessage()}", 'payment');
            $this->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }

        if ($out['replayed']) {
            $this->json(['replayed' => true, 'data' => $out['result']], 200);
        }
        if (!empty($out['in_flight'])) {
            $this->json(['error' => 'A request with this idempotency key is already in progress.'], 409);
        }
        $this->json(['replayed' => false, 'data' => $out['result']], 200);
    }

    public function refund(int $id)
    {
        $this->shield('refund');
        $this->assertPositiveId($id);
        $data = $this->readJsonBody();
        $key = $this->idempotencyKey($data);

        try {
            $out = $this->payments->refund($id, $data, $key, $this->requestMeta(), $this->actor());
        } catch (\InvalidArgumentException $e) {
            $this->json(['error' => $e->getMessage()], $e->getCode() ?: 422);
        } catch (\RuntimeException $e) {
            Logger::error("refund #{$id}: {$e->getMessage()}", 'payment');
            $this->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }

        if ($out['replayed']) {
            $this->json(['replayed' => true, 'data' => $out['result']], 200);
        }
        if (!empty($out['in_flight'])) {
            $this->json(['error' => 'A request with this idempotency key is already in progress.'], 409);
        }
        $this->json(['replayed' => false, 'data' => $out['result']], 200);
    }

    /**
     * Admin audit endpoint — full immutable timeline for an intent.
     */
    public function events(int $id)
    {
        $this->assertPositiveId($id);

        try {
            $timeline = $this->payments->timeline($id);
        } catch (\InvalidArgumentException $e) {
            $this->json(['error' => $e->getMessage()], $e->getCode() ?: 422);
        }

        $this->json(['intent_id' => $id, 'timeline' => $timeline], 200);
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private function shield(string $action): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $limitKey = 'pay_' . $action . '_' . hash('sha256', $ip);
        if (RateLimiter::tooManyAttempts($limitKey, 20)) {
            Logger::warning("Payment {$action} rate-limit hit. IP: {$ip}", 'payment');
            $this->json(['error' => 'Too many requests. Please try again later.'], 429);
        }
        RateLimiter::hit($limitKey, 60);

        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!CSRF::verify($token)) {
            $this->json(['error' => 'Invalid CSRF token.'], 401);
        }
    }

    private function readJsonBody(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode((string) $raw, true);
        if (!is_array($data)) {
            $this->json(['error' => 'Invalid JSON body.'], 400);
        }
        return $data;
    }

    private function idempotencyKey(array $data): string
    {
        $key = trim((string) ($_SERVER['HTTP_IDEMPOTENCY_KEY'] ?? ($data['idempotency_key'] ?? '')));
        if ($key === '') {
            $this->json(['error' => 'Missing Idempotency-Key header (UUID v4 required).'], 400);
        }
        return $key;
    }

    private function assertPositiveId(int $id): void
    {
        if ($id <= 0) {
            $this->json(['error' => 'Invalid payment intent id.'], 400);
        }
    }

    private function requestMeta(): array
    {
        return [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
            'session' => Session::get('user_id') ? 'user_' . Session::get('user_id') : 'guest',
        ];
    }

    private function actor(): array
    {
        return RowSecurity::actor();
    }
}
