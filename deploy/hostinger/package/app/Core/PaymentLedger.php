<?php

namespace App\Core;

use App\Core\Logger;
use App\Core\Model;

/**
 * Immutable payment ledger.
 *
 * Every state change creates a brand-new row. Rows are never updated or
 * deleted. The current state of an intent is derived by reading the latest
 * ledger entry — there is no in-place status column on the intent.
 *
 * Deduplication is enforced twice:
 *   - a unique index on (payment_intent_id, event_id) — one internal event
 *     can never be appended twice;
 *   - a unique index on provider_event_id — a webhook event can never be
 *     appended to the timeline twice, even across retries.
 */
class PaymentLedger
{
    public const EVENTS = ['initiated', 'authorized', 'captured', 'failed', 'refunded', 'reversed'];

    // Allowed transitions. initiated -> anything; captured may refund/reverse;
    // a failed attempt may be followed by a later success (retries).
    private const TRANSITIONS = [
        'initiated' => ['authorized', 'captured', 'failed', 'refunded', 'reversed'],
        'authorized' => ['captured', 'failed'],
        'captured' => ['refunded', 'reversed'],
        'failed' => ['captured', 'refunded'],
        // Partial refunds are legal: each subsequent refund appends another row.
        'refunded' => ['refunded'],
        'reversed' => [],
    ];

    /**
     * Append an immutable event to the ledger.
     *
     * @param int    $intentId
     * @param string $eventType   one of self::EVENTS
     * @param int    $amountKobo
     * @param string $source      api | webhook | manual
     * @param string $eventId     unique internal event id (e.g. UUID)
     * @param array  $rawPayload  provider payload
     * @param array  $meta        internal annotations
     *
     * @return bool true when appended, false when it was a duplicate (no-op)
     *
     * @throws \InvalidArgumentException on an invalid transition
     */
    public static function append(
        int $intentId,
        string $eventType,
        int $amountKobo,
        string $source,
        string $eventId,
        array $rawPayload = [],
        ?string $providerEventId = null,
        array $meta = []
    ): bool {
        if (!in_array($eventType, self::EVENTS, true)) {
            throw new \InvalidArgumentException("Unknown ledger event type: {$eventType}", 422);
        }
        if (!in_array($source, ['api', 'webhook', 'manual'], true)) {
            throw new \InvalidArgumentException("Unknown ledger source: {$source}", 422);
        }

        $db = Model::getDB();

        $previous = self::currentStatus($intentId);
        if ($previous !== null && !self::transitionAllowed($previous, $eventType)) {
            Logger::warning(
                "Ledger transition blocked: intent #{$intentId} {$previous} -> {$eventType}",
                'payment'
            );
            throw new \InvalidArgumentException(
                "Cannot move intent from '{$previous}' to '{$eventType}'.",
                409
            );
        }

        try {
            $stmt = $db->prepare(
                "INSERT INTO payment_ledger
                    (payment_intent_id, event_id, event_type, status, amount_kobo, source, provider_event_id, raw_payload, meta)
                 VALUES (:i, :eid, :et, :s, :a, :src, :peid, :raw, :m)"
            );
            $stmt->execute([
                'i' => $intentId,
                'eid' => $eventId,
                'et' => $eventType,
                's' => $eventType,
                'a' => $amountKobo,
                'src' => $source,
                'peid' => $providerEventId,
                'raw' => $rawPayload !== [] ? json_encode($rawPayload) : null,
                'm' => $meta !== [] ? json_encode($meta) : null,
            ]);
            return true;
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                Logger::info("Ledger duplicate ignored: intent #{$intentId} event {$eventId}", 'payment');
                return false;
            }
            throw $e;
        }
    }

    /**
     * Current state of an intent, derived from the latest ledger row.
     */
    public static function currentStatus(int $intentId): ?string
    {
        $db = Model::getDB();
        $stmt = $db->prepare(
            "SELECT status FROM payment_ledger WHERE payment_intent_id = :i ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute(['i' => $intentId]);
        $status = $stmt->fetchColumn();
        return $status === false ? null : (string) $status;
    }

    /**
     * Whether the intent has already been captured.
     */
    public static function isCaptured(int $intentId): bool
    {
        return self::currentStatus($intentId) === 'captured';
    }

    /**
     * Full, chronological audit timeline for an intent.
     */
    public static function timeline(int $intentId): array
    {
        $db = Model::getDB();
        $stmt = $db->prepare(
            "SELECT id, event_id, event_type, status, amount_kobo, source,
                    provider_event_id, raw_payload, meta, created_at
               FROM payment_ledger
              WHERE payment_intent_id = :i
              ORDER BY id ASC"
        );
        $stmt->execute(['i' => $intentId]);
        return $stmt->fetchAll();
    }

    /**
     * Sum of refunded amount for an intent (for partial refund accounting).
     */
    public static function refundedTotalKobo(int $intentId): int
    {
        $db = Model::getDB();
        $stmt = $db->prepare(
            "SELECT COALESCE(SUM(amount_kobo), 0) FROM payment_ledger
              WHERE payment_intent_id = :i AND event_type = 'refunded'"
        );
        $stmt->execute(['i' => $intentId]);
        return (int) $stmt->fetchColumn();
    }

    private static function transitionAllowed(string $from, string $to): bool
    {
        return isset(self::TRANSITIONS[$from]) && in_array($to, self::TRANSITIONS[$from], true);
    }
}
