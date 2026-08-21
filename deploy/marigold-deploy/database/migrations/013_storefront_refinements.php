<?php

// database/migrations/013_storefront_refinements.php
//
// Two storefront gaps resolved in one idempotent migration:
//   1. `orders` has no address columns, so guest/billing shipping addresses are
//      snapshotted into a dedicated `order_addresses` table (a copy of the
//      address at purchase time, immune to later customer edits).
//   2. `quote_items` has no name/notes columns, so quote rows submitted by the
//      public quote form (which carry a free-text product name + per-item note,
//      not always a DB product_id) would otherwise lose that information.

$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo) {
    throw new \RuntimeException('013 migration requires the PDO connection ($pdo) to be in scope.');
}

$queries = [];

// 1. order_addresses
$hasTable = function (string $table) use ($pdo): bool {
    $stmt = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = " . $pdo->quote($table)
    );
    return (int) $stmt->fetchColumn() > 0;
};

if (!$hasTable('order_addresses')) {
    $queries['order_addresses'] = "
        CREATE TABLE order_addresses (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            order_id BIGINT UNSIGNED NOT NULL,
            type ENUM('shipping', 'billing') NOT NULL DEFAULT 'shipping',
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NULL,
            phone VARCHAR(30) NULL,
            company VARCHAR(150) NULL,
            address_line1 VARCHAR(255) NOT NULL,
            address_line2 VARCHAR(255) NULL,
            city VARCHAR(100) NOT NULL,
            state VARCHAR(100) NULL,
            postal_code VARCHAR(20) NULL,
            country VARCHAR(100) NOT NULL DEFAULT 'Nigeria',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
}

// 2. quote_items.name / quote_items.notes
$hasColumn = function (string $table, string $column) use ($pdo): bool {
    $stmt = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = " . $pdo->quote($table)
        . " AND COLUMN_NAME = " . $pdo->quote($column)
    );
    return (int) $stmt->fetchColumn() > 0;
};

if (!$hasColumn('quote_items', 'name')) {
    $queries['quote_items_add_name'] =
        "ALTER TABLE quote_items ADD COLUMN `name` VARCHAR(255) NULL AFTER `product_id`;";
}

if (!$hasColumn('quote_items', 'notes')) {
    $queries['quote_items_add_notes'] =
        "ALTER TABLE quote_items ADD COLUMN `notes` VARCHAR(1000) NULL AFTER `price`;";
}

return $queries;
