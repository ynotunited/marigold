<?php

// database/migrations/015_order_items_name.php
//
// The checkout modal cart holds catalogue items (from the static storefront
// catalogue) that may not exist in the products table. To preserve their names
// on the order, order_items gets a nullable `name` snapshot column (mirrors the
// quote_items.name approach from 013).

$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo) {
    throw new \RuntimeException('015 migration requires the PDO connection ($pdo) to be in scope.');
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

if (!$hasColumn('order_items', 'name')) {
    $queries['order_items_add_name'] =
        "ALTER TABLE order_items ADD COLUMN `name` VARCHAR(255) NULL AFTER `product_id`;";
}

return $queries;
