<?php
// database/migrations/017_shipbubble_integration.php
//
// ShipBubble logistics integration:
//   - orders: stores the routed ShipBubble label + tracking metadata so the
//     webhook can update shipping_status without external lookups.
//   - shipbubble_quotes: rate quotes persisted server-side keyed by
//     request_token (expires with the 7-day ShipBubble token). The checkout
//     prices shipping authoritatively from this table — never from the client.

return [
    'orders_add_shipbubble_columns' => "
        ALTER TABLE orders
            ADD COLUMN shipbubble_order_id VARCHAR(50) NULL AFTER shipping_status,
            ADD COLUMN shipbubble_request_token VARCHAR(255) NULL AFTER shipbubble_order_id,
            ADD COLUMN shipbubble_service_code VARCHAR(100) NULL AFTER shipbubble_request_token,
            ADD COLUMN shipbubble_courier_id VARCHAR(100) NULL AFTER shipbubble_service_code,
            ADD COLUMN shipbubble_courier_name VARCHAR(255) NULL AFTER shipbubble_courier_id,
            ADD COLUMN shipbubble_tracking_code VARCHAR(255) NULL AFTER shipbubble_courier_name,
            ADD COLUMN shipbubble_tracking_url VARCHAR(500) NULL AFTER shipbubble_tracking_code,
            ADD COLUMN shipbubble_status VARCHAR(30) NULL AFTER shipbubble_tracking_url;
    ",

    'shipbubble_quotes' => "
        CREATE TABLE IF NOT EXISTS shipbubble_quotes (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            request_token VARCHAR(255) NOT NULL,
            request JSON NULL,
            payload JSON NULL,
            expires_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_sb_request_token (request_token),
            KEY idx_sb_quotes_expiry (expires_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
];
