<?php

namespace App\Core;

use App\Core\Logger;
use App\Core\Model;

/**
 * Idempotency store.
 *
 * Client-generated UUID keys are persisted BEFORE any gateway call. The unique
 * constraint on `key` is the safety net: if a duplicate request arrives, the
 * INSERT fails and we return the cached response instead of re-charging.
 *
 * Usage contract per operation:
 *   begin()  -> ['status' => 'new']  claim the key and continue processing
 *            -> ['status' => 'replay']  a completed result exists; return it
 *            -> ['status' => 'in_flight']  an identical request is mid-flight; 409
 *   succeed() -> mark completed + cache the response payload
 *   fail()    -> mark failed (a later retry with the SAME key may re-attempt,
 *                because nothing was committed to the gateway on failure)
 */
class Idempotency
{
    private const UUID_RE = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

    public static function isUuid(string $key): bool
    {
        return (bool) preg_match(self::UUID_RE, $key);
    }

    public static function begin(string $key, string $endpoint, array $payload, array $requestMeta = []): array
    {
        if (!self::isUuid($key)) {
            throw new \InvalidArgumentException('Idempotency key must be a valid UUID v4.', 422);
        }
        if (strlen($endpoint) > 120) {
            throw new \InvalidArgumentException('Idempotency endpoint is too long.', 422);
        }

        $payloadHash = hash('sha256', json_encode($payload));
        $db = Model::getDB();

        try {
            $stmt = $db->prepare(
                "INSERT INTO idempotency_keys (`key`, endpoint, scope, payload_hash, state, request_meta)
                 VALUES (:k, :e, 'payment', :h, 'in_progress', :m)"
            );
            $stmt->execute([
                'k' => $key,
                'e' => $endpoint,
                'h' => $payloadHash,
                'm' => json_encode($requestMeta),
            ]);
            return ['status' => 'new', 'key' => $key];
        } catch (\PDOException $e) {
            if (($e->getCode() !== '23000')) {
                throw $e;
            }
        }

        // Duplicate key. Recover the original record.
        $stmt = $db->prepare("SELECT * FROM idempotency_keys WHERE `key` = :k");
        $stmt->execute(['k' => $key]);
        $row = $stmt->fetch();

        if (!$row) {
            throw new \RuntimeException('Idempotency key conflict but no row found.', 500);
        }

        // Same key, different payload => misuse of the key. Never honour it.
        if (!hash_equals((string) $row['payload_hash'], $payloadHash)) {
            throw new \InvalidArgumentException('Idempotency key was already used with a different payload.', 422);
        }

        if ($row['state'] === 'completed' && $row['response_json'] !== null) {
            $response = json_decode((string) $row['response_json'], true);
            return ['status' => 'replay', 'response' => $response, 'key' => $key];
        }

        if ($row['state'] === 'failed') {
            // The first attempt never committed anything (gateway failure). A retry
            // with the same key is safe: clear the tombstone and start fresh.
            $db->beginTransaction();
            try {
                $db->prepare("DELETE FROM idempotency_keys WHERE `key` = :k")->execute(['k' => $key]);
                $stmt = $db->prepare(
                    "INSERT INTO idempotency_keys (`key`, endpoint, scope, payload_hash, state, request_meta)
                     VALUES (:k, :e, 'payment', :h, 'in_progress', :m)"
                );
                $stmt->execute([
                    'k' => $key,
                    'e' => $endpoint,
                    'h' => $payloadHash,
                    'm' => json_encode($requestMeta),
                ]);
                $db->commit();
                return ['status' => 'new', 'key' => $key];
            } catch (\Throwable $t) {
                $db->rollBack();
                throw $t;
            }
        }

        // in_progress: an identical request is being processed right now.
        return ['status' => 'in_flight', 'key' => $key];
    }

    public static function succeed(string $key, array $response): void
    {
        $db = Model::getDB();
        $stmt = $db->prepare(
            "UPDATE idempotency_keys
                SET state = 'completed', response_json = :r, completed_at = NOW()
              WHERE `key` = :k AND state = 'in_progress'"
        );
        $stmt->execute(['r' => json_encode($response), 'k' => $key]);
        if ($stmt->rowCount() !== 1) {
            Logger::warning("Idempotency::succeed could not complete key {$key}", 'payment');
        }
    }

    public static function fail(string $key, string $reason): void
    {
        $db = Model::getDB();
        $stmt = $db->prepare(
            "UPDATE idempotency_keys
                SET state = 'failed', response_json = :r, completed_at = NOW()
              WHERE `key` = :k"
        );
        $stmt->execute(['r' => json_encode(['error' => $reason]), 'k' => $key]);
    }
}
