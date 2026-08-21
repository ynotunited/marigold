<?php

/**
 * Migration 023: Create compliance_receipts table for GDPR audit trail.
 */
return [
    'up' => function ($db) {
        $db->exec("
            CREATE TABLE IF NOT EXISTS compliance_receipts (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT UNSIGNED NOT NULL,
                email_hash VARCHAR(64) NOT NULL,
                action ENUM('export','anonymize','delete') NOT NULL,
                tables_affected JSON NOT NULL,
                row_counts JSON NOT NULL,
                anonymized_tables JSON NULL,
                initiated_by ENUM('customer','admin','system') NOT NULL DEFAULT 'system',
                initiated_by_user_id INT UNSIGNED NULL,
                notes TEXT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_cr_user (user_id),
                INDEX idx_cr_email (email_hash),
                INDEX idx_cr_action (action),
                INDEX idx_cr_created (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    },
    'down' => function ($db) {
        $db->exec("DROP TABLE IF EXISTS compliance_receipts");
    },
];
