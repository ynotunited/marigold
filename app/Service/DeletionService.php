<?php

namespace App\Service;

use App\Core\Model;

/**
 * GDPR-aware user deletion service.
 *
 * Walks a cascade map of every table that touches a user, anonymizes
 * order/transaction PII (statutory retention), deletes everything else,
 * and logs a compliance receipt.
 */
class DeletionService
{
    /**
     * Retention window in days before automatic hard-delete.
     */
    public static function retentionDays(): int
    {
        return (int)(Settings::get('gdpr_retention_days', '30'));
    }

    /**
     * Whether to anonymize orders (true) or hard-delete them (false).
     * Anonymization is the safe default — orders contain financial records
     * required for tax/statutory retention (6-7 years).
     */
    public static function anonymizeOrders(): bool
    {
        return Settings::get('gdpr_anonymize_orders', '1') === '1';
    }

    /**
     * Soft-delete a user account. Sets deleted_at and status, logs out the
     * user on next request. Does NOT remove any data yet.
     *
     * @return array{blocked: bool, reason?: string}
     */
    public static function softDelete(int $userId): array
    {
        $db = Model::getDB();

        // Check for pending/unpaid orders that would block deletion
        $stmt = $db->prepare("
            SELECT COUNT(*) FROM orders o
            JOIN customers c ON c.id = o.customer_id
            WHERE c.user_id = :uid AND o.status IN ('pending','processing')
              AND o.payment_status IN ('pending','awaiting')
        ");
        $stmt->execute(['uid' => $userId]);
        $pending = (int)$stmt->fetchColumn();

        if ($pending > 0) {
            return [
                'blocked' => true,
                'reason' => "You have {$pending} pending order(s). Please wait for them to complete or contact support before deleting your account.",
            ];
        }

        $stmt = $db->prepare("
            UPDATE users SET deleted_at = NOW(), status = 'pending_deletion'
            WHERE id = :id AND deleted_at IS NULL
        ");
        $stmt->execute(['id' => $userId]);

        AuditService::act('user.deletion_requested', 'users', $userId, [], [
            'retention_days' => self::retentionDays(),
            'hard_delete_at' => date('Y-m-d H:i:s', strtotime('+' . self::retentionDays() . ' days')),
        ]);

        return ['blocked' => false];
    }

    /**
     * Cancel a pending soft-delete (user changed their mind within the
     * retention window).
     */
    public static function cancelDeletion(int $userId): void
    {
        $db = Model::getDB();
        $stmt = $db->prepare("
            UPDATE users SET deleted_at = NULL, status = 'active'
            WHERE id = :id AND deleted_at IS NOT NULL AND status = 'pending_deletion'
        ");
        $stmt->execute(['id' => $userId]);

        AuditService::act('user.deletion_cancelled', 'users', $userId);
    }

    /**
     * Check if a user is pending deletion.
     */
    public static function isPendingDeletion(int $userId): bool
    {
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT deleted_at FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch();
        return $row && $row['deleted_at'] !== null;
    }

    /**
     * Find all users past their retention window — ready for hard-delete.
     *
     * @return array<int, array{id: int, email: string, deleted_at: string}>
     */
    public static function findExpired(): array
    {
        $db = Model::getDB();
        $days = self::retentionDays();
        $stmt = $db->prepare("
            SELECT id, email, deleted_at FROM users
            WHERE deleted_at IS NOT NULL
              AND deleted_at <= DATE_SUB(NOW(), INTERVAL :days DAY)
            ORDER BY deleted_at ASC
            LIMIT 50
        ");
        $stmt->execute(['days' => $days]);
        return $stmt->fetchAll();
    }

    /**
     * Hard-delete a user and all cascaded data. Anonymizes order/transaction
     * PII if configured. Logs a compliance receipt.
     *
     * Returns the compliance receipt data.
     */
    public static function hardDelete(int $userId, string $initiatedBy = 'system', ?int $adminUserId = null): array
    {
        $db = Model::getDB();
        $db->beginTransaction();

        $tablesAffected = [];
        $rowCounts = [];
        $anonymizedTables = [];

        try {
            $userEmail = '';
            $stmt = $db->prepare("SELECT email FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $userId]);
            $row = $stmt->fetch();
            if (!$row) {
                $db->rollBack();
                return [];
            }
            $userEmail = $row['email'];

            // ── TIER 0: No-FK manual cleanup ──
            self::deleteRows($db, 'audit_log', 'user_id', $userId, $tablesAffected, $rowCounts);
            self::deleteRows($db, 'scheduled_notifications', 'user_id', $userId, $tablesAffected, $rowCounts);

            // ── TIER 1: Direct user children ──
            self::deleteRows($db, 'user_roles', 'user_id', $userId, $tablesAffected, $rowCounts);
            self::deleteRows($db, 'notifications', 'user_id', $userId, $tablesAffected, $rowCounts);
            self::deleteRows($db, 'quote_messages', 'sender_id', $userId, $tablesAffected, $rowCounts);

            // ── Find all customer IDs for this user ──
            $stmt = $db->prepare("SELECT id FROM customers WHERE user_id = :uid");
            $stmt->execute(['uid' => $userId]);
            $customerIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            $customerIds = array_map('intval', $customerIds);

            if ($customerIds) {
                $custPh = implode(',', array_fill(0, count($customerIds), '?'));

                // ── TIER 3: Customer-scoped leaves ──
                self::deleteByParents($db, 'customer_addresses', 'customer_id', $customerIds, $tablesAffected, $rowCounts);
                self::deleteByParents($db, 'wishlists', 'customer_id', $customerIds, $tablesAffected, $rowCounts);
                self::deleteByParents($db, 'reviews', 'customer_id', $customerIds, $tablesAffected, $rowCounts);

                // Carts → cart_items
                $stmt = $db->prepare("SELECT id FROM carts WHERE customer_id IN ($custPh)");
                $stmt->execute($customerIds);
                $cartIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                $cartIds = array_map('intval', $cartIds);
                if ($cartIds) {
                    self::deleteByParents($db, 'cart_items', 'cart_id', $cartIds, $tablesAffected, $rowCounts);
                }
                self::deleteByParents($db, 'carts', 'customer_id', $customerIds, $tablesAffected, $rowCounts);

                // ── Find all order IDs for these customers ──
                $stmt = $db->prepare("SELECT id FROM orders WHERE customer_id IN ($custPh)");
                $stmt->execute($customerIds);
                $orderIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                $orderIds = array_map('intval', $orderIds);

                // ── Find all quote IDs for these customers ──
                $stmt = $db->prepare("SELECT id FROM quotes WHERE customer_id IN ($custPh)");
                $stmt->execute($customerIds);
                $quoteIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                $quoteIds = array_map('intval', $quoteIds);

                // ── Find payment intent IDs ──
                $paymentIntentIds = [];
                if ($orderIds) {
                    $ordPh = implode(',', array_fill(0, count($orderIds), '?'));
                    $stmt = $db->prepare("SELECT id FROM payment_intents WHERE order_id IN ($ordPh)");
                    $stmt->execute($orderIds);
                    $paymentIntentIds = array_map('intval', $stmt->fetchAll(\PDO::FETCH_COLUMN));
                }

                // ── TIER 4: Order/quote tree leaves ──
                if ($orderIds) {
                    self::deleteByParents($db, 'order_items', 'order_id', $orderIds, $tablesAffected, $rowCounts);
                    self::deleteByParents($db, 'payments', 'order_id', $orderIds, $tablesAffected, $rowCounts);
                    self::deleteByParents($db, 'shipments', 'order_id', $orderIds, $tablesAffected, $rowCounts);
                    self::deleteByParents($db, 'order_status_history', 'order_id', $orderIds, $tablesAffected, $rowCounts);
                }
                if ($quoteIds) {
                    self::deleteByParents($db, 'quote_items', 'quote_id', $quoteIds, $tablesAffected, $rowCounts);
                    self::deleteByParents($db, 'quote_files', 'quote_id', $quoteIds, $tablesAffected, $rowCounts);
                }
                if ($paymentIntentIds) {
                    self::deleteByParents($db, 'payment_ledger', 'payment_intent_id', $paymentIntentIds, $tablesAffected, $rowCounts);
                    self::deleteByParents($db, 'webhook_events', 'payment_intent_id', $paymentIntentIds, $tablesAffected, $rowCounts);
                }

                // ── TIER 5: Anonymize or delete orders, quotes, payment_intents ──
                if (self::anonymizeOrders()) {
                    if ($orderIds) {
                        self::anonymizeRows($db, 'order_addresses', 'order_id', $orderIds, $tablesAffected, $rowCounts, $anonymizedTables);
                        self::anonymizeOrderRows($db, $orderIds, $tablesAffected, $rowCounts, $anonymizedTables);
                    }
                    if ($quoteIds) {
                        self::anonymizeQuotes($db, $quoteIds, $tablesAffected, $rowCounts, $anonymizedTables);
                    }
                    if ($paymentIntentIds) {
                        self::anonymizePaymentIntents($db, $paymentIntentIds, $tablesAffected, $rowCounts, $anonymizedTables);
                    }
                } else {
                    if ($orderIds) {
                        self::deleteByValues($db, 'order_addresses', 'order_id', $orderIds, $tablesAffected, $rowCounts);
                        self::deleteByValues($db, 'orders', 'id', $orderIds, $tablesAffected, $rowCounts);
                    }
                    if ($quoteIds) {
                        self::deleteByValues($db, 'quotes', 'id', $quoteIds, $tablesAffected, $rowCounts);
                    }
                    if ($paymentIntentIds) {
                        self::deleteByValues($db, 'payment_intents', 'id', $paymentIntentIds, $tablesAffected, $rowCounts);
                    }
                }

                // ── TIER 6: Customers ──
                self::deleteByParents($db, 'customers', 'user_id', [$userId], $tablesAffected, $rowCounts);
            }

            // Newsletter (by email)
            self::deleteByEmail($db, 'newsletters', $userEmail, $tablesAffected, $rowCounts);

            // ── TIER 7: User ──
            self::deleteRows($db, 'users', 'id', $userId, $tablesAffected, $rowCounts);

            $db->commit();
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            AuditService::log('user.deletion_failed', 'users', $userId, [], ['error' => $e->getMessage()]);
            throw $e;
        }

        // Log compliance receipt (outside transaction — if this fails, the data is still gone)
        $receipt = self::logReceipt($userId, $userEmail, 'delete', $tablesAffected, $rowCounts, $anonymizedTables, $initiatedBy, $adminUserId);

        return $receipt;
    }

    // ─── Cascade helpers ──────────────────────────────────────────────

    /**
     * Delete all rows where $column = $value.
     */
    private static function deleteRows(\PDO $db, string $table, string $column, int $value, array &$tables, array &$counts): void
    {
        $stmt = $db->prepare("DELETE FROM {$table} WHERE {$column} = :val");
        $stmt->execute(['val' => $value]);
        $affected = $stmt->rowCount();
        if ($affected > 0) {
            $tables[] = $table;
            $counts[$table] = ($counts[$table] ?? 0) + $affected;
        }
    }

    /**
     * Delete all rows where $column is in an array of IDs.
     */
    private static function deleteByParents(\PDO $db, string $table, string $column, array $ids, array &$tables, array &$counts): void
    {
        if (!$ids) return;
        $ph = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $db->prepare("DELETE FROM {$table} WHERE {$column} IN ($ph)");
        $stmt->execute($ids);
        $affected = $stmt->rowCount();
        if ($affected > 0) {
            $tables[] = $table;
            $counts[$table] = ($counts[$table] ?? 0) + $affected;
        }
    }

    /**
     * Delete all rows where $column is in an array of IDs (direct value match).
     */
    private static function deleteByValues(\PDO $db, string $table, string $column, array $ids, array &$tables, array &$counts): void
    {
        if (!$ids) return;
        $ph = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $db->prepare("DELETE FROM {$table} WHERE {$column} IN ($ph)");
        $stmt->execute($ids);
        $affected = $stmt->rowCount();
        if ($affected > 0) {
            $tables[] = $table;
            $counts[$table] = ($counts[$table] ?? 0) + $affected;
        }
    }

    /**
     * Delete rows by email (case-insensitive).
     */
    private static function deleteByEmail(\PDO $db, string $table, string $email, array &$tables, array &$counts): void
    {
        $stmt = $db->prepare("DELETE FROM {$table} WHERE LOWER(email) = LOWER(:email)");
        $stmt->execute(['email' => $email]);
        $affected = $stmt->rowCount();
        if ($affected > 0) {
            $tables[] = $table;
            $counts[$table] = ($counts[$table] ?? 0) + $affected;
        }
    }

    // ─── Anonymization helpers ────────────────────────────────────────

    private static function anonymizeOrderRows(\PDO $db, array $orderIds, array &$tables, array &$counts, array &$anon): void
    {
        if (!$orderIds) return;
        $ph = implode(',', array_fill(0, count($orderIds), '?'));
        $stmt = $db->prepare("
            UPDATE orders SET
                customer_id = NULL,
                whatsapp = NULL,
                notes = NULL,
                transaction_reference = NULL
            WHERE id IN ($ph)
        ");
        $stmt->execute($orderIds);
        $affected = $stmt->rowCount();
        if ($affected > 0) {
            $tables[] = 'orders';
            $counts['orders'] = ($counts['orders'] ?? 0) + $affected;
            $anon[] = 'orders';
        }
    }

    private static function anonymizeRows(\PDO $db, string $table, string $column, array $ids, array &$tables, array &$counts, array &$anon): void
    {
        if (!$ids) return;
        $ph = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $db->prepare("
            UPDATE {$table} SET
                first_name = 'Redacted',
                last_name = 'Redacted',
                email = NULL,
                phone = NULL,
                company = NULL,
                address_line1 = 'Redacted',
                address_line2 = NULL,
                postal_code = NULL
            WHERE {$column} IN ($ph)
        ");
        $stmt->execute($ids);
        $affected = $stmt->rowCount();
        if ($affected > 0) {
            $tables[] = $table;
            $counts[$table] = ($counts[$table] ?? 0) + $affected;
            if (!in_array($table, $anon, true)) $anon[] = $table;
        }
    }

    private static function anonymizeQuotes(\PDO $db, array $quoteIds, array &$tables, array &$counts, array &$anon): void
    {
        if (!$quoteIds) return;
        $ph = implode(',', array_fill(0, count($quoteIds), '?'));
        $stmt = $db->prepare("
            UPDATE quotes SET
                customer_id = NULL,
                sales_person = NULL,
                notes = NULL
            WHERE id IN ($ph)
        ");
        $stmt->execute($quoteIds);
        $affected = $stmt->rowCount();
        if ($affected > 0) {
            $tables[] = 'quotes';
            $counts['quotes'] = ($counts['quotes'] ?? 0) + $affected;
            $anon[] = 'quotes';
        }
    }

    private static function anonymizePaymentIntents(\PDO $db, array $intentIds, array &$tables, array &$counts, array &$anon): void
    {
        if (!$intentIds) return;
        $ph = implode(',', array_fill(0, count($intentIds), '?'));
        $stmt = $db->prepare("
            UPDATE payment_intents SET
                customer_id = NULL,
                customer_email = 'redacted@redacted.local',
                customer_name = 'Redacted',
                customer_phone = NULL
            WHERE id IN ($ph)
        ");
        $stmt->execute($intentIds);
        $affected = $stmt->rowCount();
        if ($affected > 0) {
            $tables[] = 'payment_intents';
            $counts['payment_intents'] = ($counts['payment_intents'] ?? 0) + $affected;
            $anon[] = 'payment_intents';
        }
    }

    // ─── Compliance receipt ───────────────────────────────────────────

    private static function logReceipt(
        int $userId,
        string $email,
        string $action,
        array $tablesAffected,
        array $rowCounts,
        array $anonymizedTables,
        string $initiatedBy,
        ?int $adminUserId
    ): array {
        $db = Model::getDB();
        $emailHash = hash('sha256', strtolower(trim($email)));

        $receipt = [
            'user_id' => $userId,
            'email_hash' => $emailHash,
            'action' => $action,
            'tables_affected' => array_values(array_unique($tablesAffected)),
            'row_counts' => $rowCounts,
            'anonymized_tables' => $anonymizedTables ?: null,
            'initiated_by' => $initiatedBy,
            'initiated_by_user_id' => $adminUserId,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $stmt = $db->prepare("
                INSERT INTO compliance_receipts
                    (user_id, email_hash, action, tables_affected, row_counts, anonymized_tables, initiated_by, initiated_by_user_id, created_at)
                VALUES
                    (:uid, :eh, :act, :ta, :rc, :at, :ib, :ibu, NOW())
            ");
            $stmt->execute([
                'uid' => $userId,
                'eh' => $emailHash,
                'act' => $action,
                'ta' => json_encode($receipt['tables_affected']),
                'rc' => json_encode($receipt['row_counts']),
                'at' => $anonymizedTables ? json_encode($anonymizedTables) : null,
                'ib' => $initiatedBy,
                'ibu' => $adminUserId,
            ]);
        } catch (\Throwable $e) {
            // Receipt logging should never crash the request
        }

        return $receipt;
    }

    /**
     * Query compliance receipts with optional filters.
     */
    public static function queryReceipts(array $filters = [], int $page = 1, int $perPage = 30): array
    {
        $db = Model::getDB();
        $where = [];
        $params = [];

        if (!empty($filters['action'])) {
            $where[] = 'cr.action = :action';
            $params['action'] = $filters['action'];
        }
        if (!empty($filters['initiated_by'])) {
            $where[] = 'cr.initiated_by = :ib';
            $params['ib'] = $filters['initiated_by'];
        }
        if (!empty($filters['search'])) {
            $where[] = '(cr.email_hash LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $countStmt = $db->prepare("SELECT COUNT(*) FROM compliance_receipts cr {$whereClause}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $perPage = min(max($perPage, 1), 100);
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $sql = "
            SELECT cr.*, u.first_name, u.last_name, u.email
            FROM compliance_receipts cr
            LEFT JOIN users u ON u.id = cr.initiated_by_user_id
            {$whereClause}
            ORDER BY cr.created_at DESC
            LIMIT {$perPage} OFFSET {$offset}
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return [
            'rows'    => $stmt->fetchAll(),
            'total'   => $total,
            'page'    => $page,
            'perPage' => $perPage,
            'pages'   => (int)ceil($total / $perPage),
        ];
    }
}
