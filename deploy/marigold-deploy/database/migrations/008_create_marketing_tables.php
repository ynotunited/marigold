<?php
// database/migrations/008_create_marketing_tables.php

return [
    'coupons' => "
        CREATE TABLE IF NOT EXISTS coupons (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) NOT NULL UNIQUE,
            type ENUM('percentage', 'fixed') DEFAULT 'percentage',
            value DECIMAL(10, 2) NOT NULL,
            minimum_spend DECIMAL(10, 2) NULL,
            expiry DATE NULL,
            usage_limit INT NULL,
            customer_restriction BIGINT UNSIGNED NULL,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (customer_restriction) REFERENCES customers(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'newsletters' => "
        CREATE TABLE IF NOT EXISTS newsletters (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            status ENUM('subscribed', 'unsubscribed') DEFAULT 'subscribed',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'testimonials' => "
        CREATE TABLE IF NOT EXISTS testimonials (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            company VARCHAR(255) NULL,
            image VARCHAR(255) NULL,
            review TEXT NOT NULL,
            rating INT DEFAULT 5,
            featured BOOLEAN DEFAULT FALSE,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'faqs' => "
        CREATE TABLE IF NOT EXISTS faqs (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(100) NULL,
            question VARCHAR(255) NOT NULL,
            answer TEXT NOT NULL,
            sort_order INT DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'reviews' => "
        CREATE TABLE IF NOT EXISTS reviews (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            product_id BIGINT UNSIGNED NOT NULL,
            customer_id BIGINT UNSIGNED NOT NULL,
            rating INT NOT NULL DEFAULT 5,
            review TEXT NULL,
            status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'announcements' => "
        CREATE TABLE IF NOT EXISTS announcements (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            message TEXT NOT NULL,
            bg_color VARCHAR(50) DEFAULT '#C9A227',
            text_color VARCHAR(50) DEFAULT '#000000',
            button_text VARCHAR(100) NULL,
            button_url VARCHAR(255) NULL,
            schedule_start DATETIME NULL,
            schedule_end DATETIME NULL,
            priority INT DEFAULT 0,
            dismissible BOOLEAN DEFAULT TRUE,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    "
];
