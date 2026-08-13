<?php

namespace App\Core;

/**
 * Row-level security enforcement (the working MySQL equivalent of Postgres RLS).
 *
 * MySQL has no native `ENABLE ROW LEVEL SECURITY`, so ownership is enforced in
 * the application layer: every row that can be addressed by id is bound to an
 * actor at creation and re-checked before any mutation.
 *
 * Actor model:
 *   - customer_id : customers.id for a logged-in customer
 *   - session_hash: SHA-256 of the PHP session id (guests / non-customers)
 *   - is_admin    : admin roles may operate on any row
 */
class RowSecurity
{
    public static function sessionHash(): ?string
    {
        $sid = session_id();
        return ($sid === '' || $sid === false) ? null : hash('sha256', $sid);
    }

    public static function isAdmin(): bool
    {
        $roles = Session::get('user_roles') ?? [];
        return in_array('super-admin', $roles, true) || in_array('admin', $roles, true);
    }

    public static function customerId(): ?int
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return null;
        }
        $stmt = Model::getDB()->prepare("SELECT id FROM customers WHERE user_id = :u LIMIT 1");
        $stmt->execute(['u' => $userId]);
        $id = $stmt->fetchColumn();
        return $id ? (int) $id : null;
    }

    /**
     * Current actor derived from the session.
     */
    public static function actor(): array
    {
        return [
            'customer_id' => self::customerId(),
            'session_hash' => self::sessionHash(),
            'is_admin' => self::isAdmin(),
        ];
    }

    /**
     * True when the actor may access $row (admin, customer owner, or same session).
     */
    public static function isOwner(array $row, array $actor): bool
    {
        if (!empty($actor['is_admin'])) {
            return true;
        }

        $rowCustomer = (int) ($row['customer_id'] ?? 0);
        $actorCustomer = (int) ($actor['customer_id'] ?? 0);
        if ($rowCustomer > 0 && $rowCustomer === $actorCustomer) {
            return true;
        }

        if (!empty($row['session_hash']) && !empty($actor['session_hash'])) {
            return hash_equals((string) $row['session_hash'], (string) $actor['session_hash']);
        }

        return false;
    }

    /**
     * Enforce ownership on a row, failing with 404 so the row's existence is
     * never leaked to non-owners.
     */
    public static function authorize(array $row, array $actor, string $label = 'Record'): void
    {
        if (!self::isOwner($row, $actor)) {
            Logger::warning("Row-level access denied on {$label} #" . ($row['id'] ?? '?'), 'security');
            throw new \InvalidArgumentException(ucfirst($label) . ' not found.', 404);
        }
    }
}
