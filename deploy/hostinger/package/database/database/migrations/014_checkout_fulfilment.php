<?php

// database/migrations/014_checkout_fulfilment.php
//
// Adds fulfilment details to `orders` for the new checkout flow:
//   1. `delivery_method` — how the customer wants the order fulfilled,
//      defaulting to office pickup ('pickup' / 'delivery').
//   2. `whatsapp` — the customer's WhatsApp number used for delivery
//      coordination (delivery fee is confirmed manually over WhatsApp).

$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo) {
    throw new \RuntimeException('014 migration requires the PDO connection ($pdo) to be in scope.');
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

if (!$hasColumn('orders', 'delivery_method')) {
    $queries['orders_add_delivery_method'] =
        "ALTER TABLE orders ADD COLUMN `delivery_method` ENUM('pickup','delivery') NOT NULL DEFAULT 'pickup' AFTER `shipping_status`;";
}

if (!$hasColumn('orders', 'whatsapp')) {
    $queries['orders_add_whatsapp'] =
        "ALTER TABLE orders ADD COLUMN `whatsapp` VARCHAR(30) NULL AFTER `delivery_method`;";
}

return $queries;
