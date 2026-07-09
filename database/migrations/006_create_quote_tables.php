<?php
// database/migrations/006_create_quote_tables.php

return [
    'quotes' => "
        CREATE TABLE IF NOT EXISTS quotes (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            quote_number VARCHAR(100) NOT NULL UNIQUE,
            customer_id BIGINT UNSIGNED NULL,
            status ENUM('pending', 'reviewed', 'approved', 'rejected', 'expired', 'converted') DEFAULT 'pending',
            subtotal DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
            discount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
            tax DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
            grand_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
            expiry_date DATE NULL,
            notes TEXT NULL,
            sales_person BIGINT UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
            FOREIGN KEY (sales_person) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'quote_items' => "
        CREATE TABLE IF NOT EXISTS quote_items (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            quote_id BIGINT UNSIGNED NOT NULL,
            product_id BIGINT UNSIGNED NULL,
            quantity INT NOT NULL DEFAULT 1,
            price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
            FOREIGN KEY (quote_id) REFERENCES quotes(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'quote_messages' => "
        CREATE TABLE IF NOT EXISTS quote_messages (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            quote_id BIGINT UNSIGNED NOT NULL,
            sender_id BIGINT UNSIGNED NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (quote_id) REFERENCES quotes(id) ON DELETE CASCADE,
            FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'quote_files' => "
        CREATE TABLE IF NOT EXISTS quote_files (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            quote_id BIGINT UNSIGNED NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            file_type VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (quote_id) REFERENCES quotes(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    "
];
