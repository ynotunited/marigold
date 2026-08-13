<?php
// database/migrations/020_product_availability.php
//
// Adds the product availability column used by the admin form and the
// storefront product page. Mirrors the admin "Availability" selector:
//   - in_stock     -> "Available for order"
//   - store_pickup -> "In-store pickup only"
//   - preorder     -> "Pre-order only"
//
// Idempotent via information_schema (MySQL 8+ / MariaDB). Existing rows
// default to in_stock.

$pdo = $GLOBALS['pdo'] ?? null;

$queries = [];

if ($pdo) {
    $stmt = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'products' AND COLUMN_NAME = 'availability'"
    );
    if ((int)$stmt->fetchColumn() === 0) {
        $queries['products_add_availability'] = "
            ALTER TABLE products
                ADD COLUMN `availability` VARCHAR(20) NULL DEFAULT 'in_stock' AFTER `stock_quantity`;
        ";
    }
    $queries['products_default_availability'] = "
        UPDATE products SET availability = 'in_stock' WHERE availability IS NULL OR availability = '';
    ";
} else {
    $queries['products_add_availability'] = "
        ALTER TABLE products
            ADD COLUMN `availability` VARCHAR(20) NULL DEFAULT 'in_stock' AFTER `stock_quantity`;
    ";
    $queries['products_default_availability'] = "
        UPDATE products SET availability = 'in_stock' WHERE availability IS NULL OR availability = '';
    ";
}

return $queries;
