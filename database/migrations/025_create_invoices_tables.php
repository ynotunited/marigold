<?php
return [
    'invoices' => "
        CREATE TABLE IF NOT EXISTS invoices (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            invoice_number VARCHAR(50) NOT NULL UNIQUE,
            token VARCHAR(64) NOT NULL UNIQUE,
            customer_name VARCHAR(255) NOT NULL DEFAULT '',
            customer_email VARCHAR(255) NOT NULL DEFAULT '',
            customer_phone VARCHAR(50) NULL,
            status ENUM('draft','sent','viewed','paid','cancelled','expired') NOT NULL DEFAULT 'draft',
            subtotal DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            tax_rate DECIMAL(5,2) NOT NULL DEFAULT 7.50,
            tax_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            discount_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            currency VARCHAR(3) NOT NULL DEFAULT 'NGN',
            notes TEXT NULL,
            due_date DATE NULL,
            payment_gateway VARCHAR(50) NULL,
            payment_reference VARCHAR(255) NULL,
            paid_at DATETIME NULL,
            created_by BIGINT UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_invoices_status (status),
            INDEX idx_invoices_token (token),
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'invoice_items' => "
        CREATE TABLE IF NOT EXISTS invoice_items (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            invoice_id BIGINT UNSIGNED NOT NULL,
            product_id BIGINT UNSIGNED NULL,
            name VARCHAR(255) NOT NULL,
            description TEXT NULL,
            quantity INT NOT NULL DEFAULT 1,
            unit_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            is_custom TINYINT(1) NOT NULL DEFAULT 0,
            FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'invoice_payments' => "
        CREATE TABLE IF NOT EXISTS invoice_payments (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            invoice_id BIGINT UNSIGNED NOT NULL,
            reference VARCHAR(255) NOT NULL UNIQUE,
            gateway VARCHAR(50) NOT NULL,
            amount DECIMAL(12,2) NOT NULL,
            currency VARCHAR(3) NOT NULL DEFAULT 'NGN',
            status ENUM('pending','success','failed') NOT NULL DEFAULT 'pending',
            response_json TEXT NULL,
            paid_at DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_invpay_invoice (invoice_id),
            INDEX idx_invpay_reference (reference),
            FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
];
