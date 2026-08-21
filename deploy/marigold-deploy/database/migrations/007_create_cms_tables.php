<?php
// database/migrations/007_create_cms_tables.php

return [
    'pages' => "
        CREATE TABLE IF NOT EXISTS pages (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            template VARCHAR(100) DEFAULT 'default',
            status ENUM('draft', 'published', 'hidden') DEFAULT 'draft',
            seo_json JSON NULL,
            published_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'page_sections' => "
        CREATE TABLE IF NOT EXISTS page_sections (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            page_id INT UNSIGNED NOT NULL,
            section_type VARCHAR(100) NOT NULL,
            sort_order INT DEFAULT 0,
            configuration_json JSON NULL,
            status ENUM('active', 'inactive') DEFAULT 'active',
            FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'post_categories' => "
        CREATE TABLE IF NOT EXISTS post_categories (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            description TEXT NULL,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'post_tags' => "
        CREATE TABLE IF NOT EXISTS post_tags (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) NOT NULL UNIQUE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'posts' => "
        CREATE TABLE IF NOT EXISTS posts (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            excerpt TEXT NULL,
            content LONGTEXT NULL,
            author_id BIGINT UNSIGNED NULL,
            featured_image VARCHAR(255) NULL,
            status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
            published_at TIMESTAMP NULL,
            meta_title VARCHAR(255) NULL,
            meta_description TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'post_post_categories' => "
        CREATE TABLE IF NOT EXISTS post_post_categories (
            post_id INT UNSIGNED NOT NULL,
            category_id INT UNSIGNED NOT NULL,
            PRIMARY KEY (post_id, category_id),
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES post_categories(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'post_post_tags' => "
        CREATE TABLE IF NOT EXISTS post_post_tags (
            post_id INT UNSIGNED NOT NULL,
            tag_id INT UNSIGNED NOT NULL,
            PRIMARY KEY (post_id, tag_id),
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (tag_id) REFERENCES post_tags(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    'media' => "
        CREATE TABLE IF NOT EXISTS media (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL,
            path VARCHAR(255) NOT NULL,
            type VARCHAR(100) NULL,
            size INT UNSIGNED NULL,
            alt_text VARCHAR(255) NULL,
            folder VARCHAR(100) DEFAULT 'general',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    "
];
