<?php

/**
 * Migration 022: Create audit_log table for tracking sensitive actions.
 */
return [
    'up' => function ($db) {
        $db->exec("
            CREATE TABLE IF NOT EXISTS audit_log (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT UNSIGNED NULL,
                action VARCHAR(100) NOT NULL,
                entity_type VARCHAR(50) NOT NULL DEFAULT '',
                entity_id INT UNSIGNED NULL,
                old_values JSON NULL,
                new_values JSON NULL,
                ip_address VARCHAR(45) NULL,
                user_agent VARCHAR(500) NULL,
                request_uri VARCHAR(500) NULL,
                request_method VARCHAR(10) NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_audit_user (user_id),
                INDEX idx_audit_action (action),
                INDEX idx_audit_entity (entity_type, entity_id),
                INDEX idx_audit_created (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    },
    'down' => function ($db) {
        $db->exec("DROP TABLE IF EXISTS audit_log");
    },
];
