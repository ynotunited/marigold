<?php
// database/migrations/011_create_payment_integrity_tables.php
//
// Payment integrity layer:
//   - idempotency_keys  : client-generated UUID keys stored BEFORE any gateway call.
//                         Duplicate requests short-circuit to the cached response.
//   - payment_intents   : immutable intent metadata (written before calling the API).
//                         NEVER contains a status column; state lives in the ledger.
//   - payment_ledger    : append-only, immutable event timeline. Every state change
//                         (initiated, authorized, captured, failed, refunded, reversed)
//                         creates a brand-new row. Never updated in place.
//   - webhook_events    : raw, signature-validated webhook payloads, deduplicated on
//                         (provider, event_id) BEFORE being appended to the ledger.

return [
    'idempotency_keys' => "
        CREATE TABLE IF NOT EXISTS idempotency_keys (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `key` CHAR(36) NOT NULL,
            endpoint VARCHAR(120) NOT NULL,
            scope VARCHAR(120) NOT NULL DEFAULT 'payment',
            payload_hash CHAR(64) NOT NULL,
            state ENUM('in_progress', 'completed', 'failed') NOT NULL DEFAULT 'in_progress',
            response_json JSON NULL,
            request_meta JSON NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            completed_at TIMESTAMP NULL,
            UNIQUE KEY uniq_idem_key (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'payment_intents' => "
        CREATE TABLE IF NOT EXISTS payment_intents (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            idempotency_key CHAR(36) NOT NULL,
            order_id BIGINT UNSIGNED NULL,
            gateway VARCHAR(100) NOT NULL DEFAULT 'paystack',
            gateway_ref VARCHAR(255) NULL,
            amount_kobo BIGINT UNSIGNED NOT NULL,
            currency CHAR(3) NOT NULL DEFAULT 'NGN',
            customer_email VARCHAR(255) NULL,
            metadata JSON NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_intent_idem (`idempotency_key`),
            KEY idx_intent_ref (`gateway_ref`),
            FOREIGN KEY (idempotency_key) REFERENCES idempotency_keys(`key`) ON DELETE RESTRICT,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'payment_ledger' => "
        CREATE TABLE IF NOT EXISTS payment_ledger (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            payment_intent_id BIGINT UNSIGNED NOT NULL,
            event_id VARCHAR(100) NOT NULL,
            event_type ENUM('initiated', 'authorized', 'captured', 'failed', 'refunded', 'reversed') NOT NULL,
            status VARCHAR(30) NOT NULL,
            amount_kobo BIGINT UNSIGNED NOT NULL DEFAULT 0,
            source ENUM('api', 'webhook', 'manual') NOT NULL,
            provider_event_id VARCHAR(255) NULL,
            raw_payload JSON NULL,
            meta JSON NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_ledger_event (payment_intent_id, event_id),
            UNIQUE KEY uniq_ledger_provider (provider_event_id),
            FOREIGN KEY (payment_intent_id) REFERENCES payment_intents(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'webhook_events' => "
        CREATE TABLE IF NOT EXISTS webhook_events (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            provider VARCHAR(50) NOT NULL DEFAULT 'paystack',
            event_id VARCHAR(255) NOT NULL,
            event_type VARCHAR(100) NOT NULL,
            signature_valid TINYINT(1) NOT NULL DEFAULT 0,
            payload JSON NOT NULL,
            payment_intent_id BIGINT UNSIGNED NULL,
            processed TINYINT(1) NOT NULL DEFAULT 0,
            error_message TEXT NULL,
            received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            processed_at TIMESTAMP NULL,
            UNIQUE KEY uniq_webhook_event (provider, event_id),
            FOREIGN KEY (payment_intent_id) REFERENCES payment_intents(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
];
