<?php
// database/seeders/seed_catalogue.php
// Seeds categories + products (from catalogue_seed_data.php) and a few blog posts.
// Run: php database/seeders/seed_catalogue.php

require __DIR__ . '/../../app/Core/Config.php';
require __DIR__ . '/../../app/Core/Env.php';
require __DIR__ . '/../../app/Core/Model.php';
require __DIR__ . '/../../app/Core/FileCache.php';

use App\Core\Env;
use App\Core\Config;
use App\Core\Model;
use App\Core\FileCache;

define('BASE_PATH', dirname(__DIR__, 2));
Env::load(__DIR__ . '/../../.env');
Config::load([
    'db' => [
        'host'    => getenv('DB_HOST') ?: '127.0.0.1',
        'name'    => getenv('DB_NAME') ?: 'marigold_db',
        'user'    => getenv('DB_USER') ?: 'root',
        'pass'    => getenv('DB_PASS') ?: '',
        'port'    => getenv('DB_PORT') ?: '3306',
    ],
]);

$db = Model::getDB();
$seed = require __DIR__ . '/catalogue_seed_data.php';

$catId = [];
$catStmt = $db->prepare("INSERT INTO categories (name, slug, status, sort_order) VALUES (:name, :slug, 'active', :sort) ON DUPLICATE KEY UPDATE name = VALUES(name)");
$catSel = $db->prepare("SELECT id FROM categories WHERE slug = :slug LIMIT 1");

$i = 0;
foreach ($seed['categories'] as $c) {
    $i++;
    $catStmt->execute(['name' => $c['name'], 'slug' => $c['slug'], 'sort' => $i]);
    $catSel->execute(['slug' => $c['slug']]);
    $catId[$c['slug']] = (int)$catSel->fetch()['id'];
}
echo 'categories: ' . count($seed['categories']) . "\n";

$prodSel = $db->prepare("SELECT id FROM products WHERE slug = :slug AND deleted_at IS NULL LIMIT 1");
$prodImgSel = $db->prepare("SELECT id FROM product_images WHERE product_id = :pid AND image = :img LIMIT 1");
$prodInsert = $db->prepare("
    INSERT INTO products (uuid, sku, name, slug, short_description, description, category_id, status, price, badge, image, features, specs, is_featured)
    VALUES (:uuid, :sku, :name, :slug, :short, :desc, :cat_id, 'published', :price, :badge, :image, :features, :specs, :featured)
");
$imgInsert = $db->prepare("INSERT INTO product_images (product_id, image, is_featured, sort_order) VALUES (:pid, :img, :feat, :sort)");

$featuredSlugs = ['signature-gift-box', 'executive-watch', 'insulated-bottle', 'studio-headphones'];

foreach ($seed['products'] as $p) {
    $prodSel->execute(['slug' => $p['slug']]);
    if ($prodSel->fetch()) {
        echo 'skip (exists): ' . $p['slug'] . "\n";
        continue;
    }
    $sku = strtoupper('MS-' . str_replace(['-', ' '], '', $p['slug']));
    $uuid = sprintf('%s-%s-%s-%s-%s',
        bin2hex(random_bytes(4)),
        bin2hex(random_bytes(2)),
        bin2hex(random_bytes(2)),
        bin2hex(random_bytes(2)),
        bin2hex(random_bytes(6))
    );
    $prodInsert->execute([
        'uuid'     => $uuid,
        'sku'      => $sku,
        'name'     => $p['name'],
        'slug'     => $p['slug'],
        'short'    => $p['short'],
        'desc'     => $p['desc'],
        'cat_id'   => $catId[$p['cat']] ?? null,
        'price'    => $p['price'],
        'badge'    => $p['badge'] ?: null,
        'image'    => $p['img'] ?: null,
        'features' => json_encode($p['features']),
        'specs'    => json_encode($p['specs']),
        'featured' => in_array($p['slug'], $featuredSlugs, true) ? 1 : 0,
    ]);
    $pid = (int)$db->lastInsertId();
    $imgInsert->execute(['pid' => $pid, 'img' => $p['img'], 'feat' => 1, 'sort' => 0]);
    echo 'inserted: ' . $p['slug'] . "\n";
}

// Seed a few blog posts so the blog page isn't empty (idempotent).
$posts = [
    [
        'title' => 'Elevating Corporate Gifting: Trends for 2026',
        'slug' => 'elevating-corporate-gifting-trends-2026',
        'excerpt' => 'Discover how top companies are reinventing their corporate gifting strategies to foster deeper connections and brand loyalty in the modern business landscape.',
        'content' => '<p>Corporate gifting has evolved far beyond the ubiquitous branded pen or standard-issue mug. In 2026, the focus has shifted entirely towards intentionality, personalization, and premium quality.</p><h2>The Shift to Quality over Quantity</h2><p>Companies are realizing that a single, high-quality item leaves a much stronger and longer-lasting impression than a bag full of easily disposable trinkets.</p><p>As we navigate this new landscape, Marigold Signature remains committed to providing our clients with unparalleled options for premium, memorable corporate merchandise.</p>',
        'featured_image' => 'https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?q=80&w=1200&auto=format&fit=crop',
        'author_id' => 1,
        'published_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
    ],
    [
        'title' => 'The Psychology of Premium Merchandise',
        'slug' => 'psychology-premium-merchandise',
        'excerpt' => 'Why does a high-quality pen or a custom leather folio make such a lasting impact? We dive into the psychology behind premium corporate gifts.',
        'content' => '<p>Premium merchandise works because it taps into the psychology of perceived value, reciprocity, and brand association.</p><h2>Perceived Value</h2><p>A heavy, well-finished object signals quality. That perceived value transfers directly to your brand.</p><blockquote>"The right gift at the right time can solidify a partnership for years to come."</blockquote>',
        'featured_image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=1200&auto=format&fit=crop',
        'author_id' => 1,
        'published_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
    ],
    [
        'title' => 'Sustainable Gifting: A Necessity, Not a Trend',
        'slug' => 'sustainable-gifting-necessity',
        'excerpt' => 'Eco-friendly corporate merchandise is no longer just nice to have. Learn how to align your brand values with your corporate gifting program.',
        'content' => '<p>Eco-friendly corporate merchandise is no longer just nice to have — it is an expectation.</p><h2>Aligning Values</h2><p>When your gifting programme mirrors your sustainability commitments, every item becomes a statement about who you are.</p>',
        'featured_image' => 'https://images.unsplash.com/photo-1610555356070-d1fb336f1ae8?q=80&w=1200&auto=format&fit=crop',
        'author_id' => 1,
        'published_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
    ],
];

$postSel = $db->prepare("SELECT id FROM posts WHERE slug = :slug LIMIT 1");
$postInsert = $db->prepare("
    INSERT INTO posts (title, slug, excerpt, content, author_id, featured_image, status, published_at)
    VALUES (:title, :slug, :excerpt, :content, :author_id, :featured_image, 'published', :published_at)
");
foreach ($posts as $post) {
    $postSel->execute(['slug' => $post['slug']]);
    if ($postSel->fetch()) {
        echo 'skip post: ' . $post['slug'] . "\n";
        continue;
    }
    $postInsert->execute($post);
    echo 'inserted post: ' . $post['slug'] . "\n";
}

FileCache::forget('catalogue_v1');
echo "cache cleared\n";
echo "done\n";
