<?php

// database/migrations/012_enable_row_security.php
//
// Application-layer Row Level Security (MySQL has no native RLS; the working
// equivalent is owner binding + enforced ownership on every row access).
//
// payment_intents previously had NO owner columns, so any visitor with a CSRF
// token could target /api/payments/{id}/capture or /refund on an arbitrary
// intent id. This migration binds every intent to its creator:
//   - customer_id : customers.id when created by a logged-in customer
//   - session_hash: SHA-256 of the PHP session id for guest checkouts
//
// Idempotent (information_schema checks), safe on MySQL 8+ and MariaDB.

$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo) {
    throw new \RuntimeException('012 migration requires the PDO connection ($pdo) to be in scope.');
}

$queries = [];

$hasColumn = function (string $table, string $column) use ($pdo): bool {
    $stmt = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = " . $pdo->quote($table)
        . " AND COLUMN_NAME = " . $pdo->quote($column)
    );
    return (int) $stmt->fetchColumn() > 0;
};

if (!$hasColumn('payment_intents', 'customer_id')) {
    $queries['payment_intents_add_customer_id'] =
        "ALTER TABLE payment_intents ADD COLUMN `customer_id` BIGINT UNSIGNED NULL AFTER `order_id`;";
}

if (!$hasColumn('payment_intents', 'session_hash')) {
    $queries['payment_intents_add_session_hash'] =
        "ALTER TABLE payment_intents ADD COLUMN `session_hash` CHAR(64) NULL AFTER `customer_id`;";
}

// Owner lookup index (added independently of the columns above).
$hasIndex = function (string $table, string $index) use ($pdo): bool {
    $stmt = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.STATISTICS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = " . $pdo->quote($table)
        . " AND INDEX_NAME = " . $pdo->quote($index)
    );
    return (int) $stmt->fetchColumn() > 0;
};

if (!$hasIndex('payment_intents', 'idx_intent_owner')) {
    $queries['payment_intents_owner_index'] =
        "ALTER TABLE payment_intents ADD KEY `idx_intent_owner` (`customer_id`, `session_hash`);";
}

$hasFk = function (string $table, string $fk) use ($pdo): bool {
    $stmt = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
         WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = " . $pdo->quote($table)
        . " AND CONSTRAINT_NAME = " . $pdo->quote($fk)
    );
    return (int) $stmt->fetchColumn() > 0;
};

if (!$hasFk('payment_intents', 'fk_intent_customer')) {
    $queries['payment_intents_customer_fk'] =
        "ALTER TABLE payment_intents ADD CONSTRAINT `fk_intent_customer`
         FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL;";
}

return $queries;
