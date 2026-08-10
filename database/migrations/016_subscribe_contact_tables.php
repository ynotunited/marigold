<?php

// database/migrations/016_subscribe_contact_tables.php
//
// 1) The storefront newsletter forms (footer / home / popup) now write to the
//    real `newsletters` table, so it gains a `source` (where the signup came
//    from) and a GDPR `consent` flag.
// 2) A `contact_messages` inbox so contact-form submissions land in the admin
//    backend instead of a client-side toast.

$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo) {
    throw new \RuntimeException('016 migration requires the PDO connection ($pdo) to be in scope.');
}

$hasColumn = function (string $table, string $column) use ($pdo): bool {
    $stmt = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = " . $pdo->quote($table)
        . " AND COLUMN_NAME = " . $pdo->quote($column)
    );
    return (int) $stmt->fetchColumn() > 0;
};

$queries = [];

if (!$hasColumn('newsletters', 'source')) {
    $queries['newsletters_add_source'] =
        "ALTER TABLE newsletters ADD COLUMN `source` VARCHAR(50) NULL DEFAULT 'Footer' AFTER `email`;";
}

if (!$hasColumn('newsletters', 'consent')) {
    $queries['newsletters_add_consent'] =
        "ALTER TABLE newsletters ADD COLUMN `consent` TINYINT(1) NOT NULL DEFAULT 1 AFTER `source`;";
}

$queries['contact_messages'] = "
    CREATE TABLE IF NOT EXISTS contact_messages (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        company VARCHAR(150) NULL,
        email VARCHAR(254) NOT NULL,
        phone VARCHAR(30) NULL,
        subject VARCHAR(150) NULL,
        message TEXT NOT NULL,
        status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
        ip_address VARCHAR(45) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

return $queries;
