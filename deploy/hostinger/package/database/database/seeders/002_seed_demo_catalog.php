<?php
// database/seeders/002_seed_demo_catalog.php
//
// Demo catalog so the storefront checkout, quote pre-select and shop pages have
// real product data to price against. Idempotent: INSERT IGNORE on the unique
// sku/uuid means re-running migrate.php never duplicates rows.
//
// These are the flagship corporate-merch items referenced across the demo UI.

return function (\PDO $db) {
    $products = [
        [
            'uuid'       => '10000000-0000-4000-8000-000000000001',
            'sku'        => 'MS-EXEC-001',
            'name'       => 'Executive Leather Notebook & Pen Set',
            'slug'       => 'executive-leather-notebook-pen-set',
            'price'      => 28500.00,
            'description'=> 'Premium bonded leather notebook with embossed branding, paired with a matching metal pen. Bulk corporate gifting staple.',
            'is_featured'=> 1,
            'is_new'     => 0,
            'is_best_seller' => 1,
        ],
        [
            'uuid'       => '10000000-0000-4000-8000-000000000002',
            'sku'        => 'MS-FLASK-001',
            'name'       => 'Branded Vacuum Flask 1L',
            'slug'       => 'branded-vacuum-flask-1l',
            'price'      => 5800.00,
            'description'=> 'Double-wall insulated stainless steel flask, laser-engraved with your logo. Ideal for events and staff kits.',
            'is_featured'=> 1,
            'is_new'     => 1,
            'is_best_seller' => 0,
        ],
        [
            'uuid'       => '10000000-0000-4000-8000-000000000003',
            'sku'        => 'MS-PEN-003',
            'name'       => 'Slim Metal Pen Set (3pcs)',
            'slug'       => 'slim-metal-pen-set-3pcs',
            'price'      => 4500.00,
            'description'=> 'Set of three slim metal pens in gift-ready packaging, engraved with your brand.',
            'is_featured'=> 0,
            'is_new'     => 0,
            'is_best_seller' => 0,
        ],
        [
            'uuid'       => '10000000-0000-4000-8000-000000000004',
            'sku'        => 'MS-TECH-004',
            'name'       => 'USB-C Hub & Organiser',
            'slug'       => 'usb-c-hub-organiser',
            'price'      => 12000.00,
            'description'=> 'Compact 6-in-1 USB-C hub with travel organiser case. High-perceived-value tech gift.',
            'is_featured'=> 1,
            'is_new'     => 1,
            'is_best_seller' => 0,
        ],
    ];

    $stmt = $db->prepare(
        "INSERT IGNORE INTO products
            (uuid, sku, name, slug, status, product_type, price, short_description, is_featured, is_new, is_best_seller)
         VALUES
            (:uuid, :sku, :name, :slug, 'published', 'standard', :price, :description, :is_featured, :is_new, :is_best_seller)"
    );

    foreach ($products as $p) {
        $stmt->execute([
            'uuid'          => $p['uuid'],
            'sku'           => $p['sku'],
            'name'          => $p['name'],
            'slug'          => $p['slug'],
            'price'         => $p['price'],
            'description'   => $p['description'],
            'is_featured'   => $p['is_featured'],
            'is_new'        => $p['is_new'],
            'is_best_seller'=> $p['is_best_seller'],
        ]);
    }
};
