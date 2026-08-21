<?php
// database/migrations/002_create_customer_tables.php

return [
    'customers' => "
        CREATE TABLE IF NOT EXISTS customers (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NULL,
            company_name VARCHAR(255) NULL,
            industry VARCHAR(100) NULL,
            tax_number VARCHAR(100) NULL,
            website VARCHAR(255) NULL,
            default_shipping_address BIGINT UNSIGNED NULL,
            default_billing_address BIGINT UNSIGNED NULL,
            notes TEXT NULL,
            status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'customer_addresses' => "
        CREATE TABLE IF NOT EXISTS customer_addresses (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            customer_id BIGINT UNSIGNED NOT NULL,
            type ENUM('shipping', 'billing', 'both') DEFAULT 'shipping',
            country VARCHAR(100) NOT NULL,
            state VARCHAR(100) NOT NULL,
            city VARCHAR(100) NOT NULL,
            address_line_1 VARCHAR(255) NOT NULL,
            address_line_2 VARCHAR(255) NULL,
            postal_code VARCHAR(50) NULL,
            phone VARCHAR(50) NULL,
            is_default BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    "
];
