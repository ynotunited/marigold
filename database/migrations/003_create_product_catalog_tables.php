<?php
// database/migrations/003_create_product_catalog_tables.php

return [
    'categories' => "
        CREATE TABLE IF NOT EXISTS categories (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            parent_id INT UNSIGNED NULL,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            description TEXT NULL,
            image VARCHAR(255) NULL,
            icon VARCHAR(100) NULL,
            status ENUM('active', 'inactive') DEFAULT 'active',
            sort_order INT DEFAULT 0,
            meta_title VARCHAR(255) NULL,
            meta_description TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'brands' => "
        CREATE TABLE IF NOT EXISTS brands (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            logo VARCHAR(255) NULL,
            website VARCHAR(255) NULL,
            description TEXT NULL,
            status ENUM('active', 'inactive') DEFAULT 'active',
            featured BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'collections' => "
        CREATE TABLE IF NOT EXISTS collections (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            banner VARCHAR(255) NULL,
            description TEXT NULL,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'products' => "
        CREATE TABLE IF NOT EXISTS products (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            uuid CHAR(36) NOT NULL UNIQUE,
            sku VARCHAR(100) NOT NULL UNIQUE,
            barcode VARCHAR(100) NULL,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            short_description TEXT NULL,
            description LONGTEXT NULL,
            brand_id INT UNSIGNED NULL,
            category_id INT UNSIGNED NULL,
            collection_id INT UNSIGNED NULL,
            status ENUM('draft', 'published', 'hidden', 'archived') DEFAULT 'draft',
            product_type ENUM('standard', 'variant', 'digital') DEFAULT 'standard',
            price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
            sale_price DECIMAL(10, 2) NULL,
            cost_price DECIMAL(10, 2) NULL,
            stock_quantity INT DEFAULT 0,
            minimum_order_quantity INT DEFAULT 1,
            maximum_order_quantity INT NULL,
            weight DECIMAL(8, 2) NULL,
            dimensions VARCHAR(255) NULL,
            is_featured BOOLEAN DEFAULT FALSE,
            is_new BOOLEAN DEFAULT FALSE,
            is_best_seller BOOLEAN DEFAULT FALSE,
            meta_title VARCHAR(255) NULL,
            meta_description TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            INDEX idx_products_slug (slug),
            INDEX idx_products_sku (sku),
            INDEX idx_products_status (status),
            INDEX idx_products_price (price),
            FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
            FOREIGN KEY (collection_id) REFERENCES collections(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'product_images' => "
        CREATE TABLE IF NOT EXISTS product_images (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            product_id BIGINT UNSIGNED NOT NULL,
            image VARCHAR(255) NOT NULL,
            alt_text VARCHAR(255) NULL,
            sort_order INT DEFAULT 0,
            is_featured BOOLEAN DEFAULT FALSE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'product_variants' => "
        CREATE TABLE IF NOT EXISTS product_variants (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            product_id BIGINT UNSIGNED NOT NULL,
            variant_name VARCHAR(255) NOT NULL,
            sku VARCHAR(100) NOT NULL UNIQUE,
            price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
            stock INT DEFAULT 0,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'product_attributes' => "
        CREATE TABLE IF NOT EXISTS product_attributes (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) NOT NULL UNIQUE,
            type ENUM('select', 'color', 'button', 'text') DEFAULT 'select'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'product_attribute_values' => "
        CREATE TABLE IF NOT EXISTS product_attribute_values (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            attribute_id INT UNSIGNED NOT NULL,
            value VARCHAR(255) NOT NULL,
            FOREIGN KEY (attribute_id) REFERENCES product_attributes(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'product_variant_attributes' => "
        CREATE TABLE IF NOT EXISTS product_variant_attributes (
            variant_id BIGINT UNSIGNED NOT NULL,
            attribute_value_id BIGINT UNSIGNED NOT NULL,
            PRIMARY KEY (variant_id, attribute_value_id),
            FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
            FOREIGN KEY (attribute_value_id) REFERENCES product_attribute_values(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    "
];
