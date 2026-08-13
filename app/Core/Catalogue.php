<?php

namespace App\Core;

use App\Core\Model;
use App\Core\FileCache;

class Catalogue
{
    private const CACHE_KEY = 'catalogue_v2';
    private const CACHE_TTL = 60;

    public static function all(): array
    {
        $cached = FileCache::get(self::CACHE_KEY);
        if ($cached !== null) {
            return $cached;
        }
        $data = self::load();
        FileCache::set(self::CACHE_KEY, $data, self::CACHE_TTL);
        return $data;
    }

    public static function forget(): void
    {
        FileCache::forget(self::CACHE_KEY);
    }

    private static function load(): array
    {
        $db = Model::getDB();

        $categories = [];
        $catRows = $db->query(
            "SELECT slug, name FROM categories WHERE status = 'active' ORDER BY sort_order ASC, name ASC"
        )->fetchAll();
        foreach ($catRows as $c) {
            $categories[] = ['id' => $c['slug'], 'label' => $c['name']];
        }

        $products = [];
        $rows = $db->query("
            SELECT
                p.id,
                p.slug,
                p.name,
                p.price,
                p.sale_price,
                p.badge,
                p.image,
                p.short_description,
                p.description,
                p.features,
                p.specs,
                p.is_featured,
                p.availability,
                p.stock_quantity,
                c.slug AS cat_slug,
                b.name AS brand_name,
                (SELECT pi.image FROM product_images pi
                  WHERE pi.product_id = p.id AND pi.is_featured = 1
                  ORDER BY pi.sort_order ASC, pi.id ASC LIMIT 1) AS featured_image
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN brands b ON b.id = p.brand_id
            WHERE p.deleted_at IS NULL AND p.status = 'published'
            ORDER BY p.created_at DESC, p.id DESC
        ")->fetchAll();

        foreach ($rows as $r) {
            $sale = $r['sale_price'];
            $effectivePrice = ($sale !== null && (float)$sale > 0) ? (float)$sale : (float)$r['price'];
            $products[] = [
                'id'       => $r['slug'],
                'name'     => $r['name'],
                'cat'      => $r['cat_slug'] ?: '',
                'price'    => $effectivePrice,
                'badge'    => $r['badge'] ?: '',
                'img'      => $r['featured_image'] ?: ($r['image'] ?: ''),
                'short'    => $r['short_description'] ?: '',
                'desc'     => $r['description'] ?: '',
                'features' => json_decode($r['features'] ?: '[]', true) ?: [],
                'specs'    => json_decode($r['specs'] ?: '{}', true) ?: [],
                'brand'    => $r['brand_name'] ?: '',
                'availability' => $r['availability'] ?: 'in_stock',
                'stock'    => (int)$r['stock_quantity'],
            ];
        }

        return [
            'categories' => $categories,
            'products'   => $products,
        ];
    }
}
