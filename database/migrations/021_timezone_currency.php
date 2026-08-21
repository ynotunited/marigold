<?php
/**
 * Migration 021: Add timezone support and scheduled notifications.
 *
 * - users.timezone: stores each user's IANA timezone identifier
 * - orders.currency: stores the currency used for the order
 * - scheduled_notifications: timezone-aware job queue for automated comms
 * - settings.currency_rates: exchange rates JSON (upserted by CurrencyService)
 */
return [
    '021_add_timezone_and_currency_support' => "
        ALTER TABLE users
            ADD COLUMN IF NOT EXISTS timezone VARCHAR(64) DEFAULT NULL
            AFTER email_verified_at
    ",
    '021_add_orders_currency' => "
        ALTER TABLE orders
            ADD COLUMN IF NOT EXISTS currency CHAR(3) NOT NULL DEFAULT 'NGN'
            AFTER grand_total
    ",
    '021_create_scheduled_notifications' => "
        CREATE TABLE IF NOT EXISTS scheduled_notifications (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            type VARCHAR(32) NOT NULL DEFAULT 'email',
            template VARCHAR(128) NOT NULL,
            data_json JSON DEFAULT NULL,
            scheduled_at_utc DATETIME NOT NULL,
            status ENUM('pending','sent','failed','cancelled') NOT NULL DEFAULT 'pending',
            error_message TEXT DEFAULT NULL,
            sent_at DATETIME DEFAULT NULL,
            created_at DATETIME NOT NULL,
            INDEX idx_pending (status, scheduled_at_utc),
            INDEX idx_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    '021_seed_currency_rates' => "
        INSERT IGNORE INTO settings (`key`, `value`, `type`, `group`, `created_at`, `updated_at`)
        VALUES ('currency_rates', '{\"NGN_USD\":0.000649,\"NGN_GBP\":0.000512,\"NGN_EUR\":0.000598,\"NGN_GHS\":0.009980,\"NGN_ZAR\":0.011850,\"NGN_KES\":0.083333}', 'json', 'system', NOW(), NOW())
    ",
];
