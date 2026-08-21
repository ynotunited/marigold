<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Model;
use App\Core\Session;
use App\Core\View;
use App\Service\AuditService;

class ProductController extends Controller
{
    public function index()
    {
        $db = Model::getDB();

        $products = $db->query("
            SELECT
                p.id,
                p.name,
                p.sku,
                p.price,
                p.sale_price,
                p.image,
                p.stock_quantity AS stock,
                p.status,
                c.name AS category
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            WHERE p.deleted_at IS NULL
            ORDER BY p.created_at DESC
        ")->fetchAll();

        // Product images (first featured image per product)
        $rows = [];
        foreach ($products as $p) {
            $image = '';
            $imgStmt = $db->prepare("
                SELECT image FROM product_images
                WHERE product_id = :pid
                ORDER BY is_featured DESC, sort_order ASC
                LIMIT 1
            ");
            $imgStmt->execute(['pid' => $p['id']]);
            $img = $imgStmt->fetch();
            if ($img) $image = $img['image'];

            $rows[] = [
                'id'         => $p['id'],
                'name'       => $p['name'],
                'sku'        => $p['sku'],
                'category'   => $p['category'] ?: '—',
                'price'      => (float)$p['price'],
                'sale_price' => $p['sale_price'] !== null ? (float)$p['sale_price'] : null,
                'stock'      => (int)$p['stock'],
                'status'     => ucfirst($p['status']),
                'image'      => $image ?: $p['image'] ?: app_url('/ms-logo-icon.png'),
            ];
        }

        return View::renderTemplate('pages/admin/products/index', 'admin', [
            'title' => 'Products | Admin',
            'products' => $rows,
        ]);
    }

    public function create()
    {
        $db = Model::getDB();

        $categories = $db->query("SELECT id, name, slug FROM categories WHERE status = 'active' ORDER BY name ASC")->fetchAll();
        $brands     = $db->query("SELECT id, name FROM brands WHERE status = 'active' ORDER BY name ASC")->fetchAll();

        return View::renderTemplate('pages/admin/products/form', 'admin', [
            'title' => 'Add Product | Admin',
            'product' => null,
            'mode' => 'create',
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function edit($id)
    {
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();

        if (!$product) {
            Session::set('error', 'Product not found.');
            $this->redirect('/admin/products');
        }

        $product['status'] = ucfirst($product['status']);

        $gallery = $db->prepare(
            "SELECT image, is_featured, sort_order FROM product_images WHERE product_id = :id ORDER BY sort_order ASC, id ASC"
        );
        $gallery->execute(['id' => $id]);
        $product['images'] = array_column($gallery->fetchAll(), 'image');

        $categories = $db->query("SELECT id, name, slug FROM categories WHERE status = 'active' ORDER BY name ASC")->fetchAll();
        $brands     = $db->query("SELECT id, name FROM brands WHERE status = 'active' ORDER BY name ASC")->fetchAll();

        return View::renderTemplate('pages/admin/products/form', 'admin', [
            'title' => 'Edit Product | Admin',
            'product' => $product,
            'mode' => 'edit',
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function store()
    {
        return $this->save(null);
    }

    public function update($id)
    {
        return $this->save($id);
    }

    private function save($id = null)
    {
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }

        $db = Model::getDB();

        $name        = trim((string)($_POST['name'] ?? ''));
        $slug        = trim((string)($_POST['slug'] ?? ''));
        $sku         = trim((string)($_POST['sku'] ?? ''));
        $barcode     = trim((string)($_POST['barcode'] ?? ''));
        $categoryId  = (int)($_POST['category_id'] ?? 0);
        $brandId     = (int)($_POST['brand_id'] ?? 0);
        $shortDesc   = trim((string)($_POST['short_description'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $price       = (float)($_POST['price'] ?? 0);
        $salePrice   = ($_POST['sale_price'] ?? '') !== '' ? (float)$_POST['sale_price'] : null;
        $costPrice   = ($_POST['cost_price'] ?? '') !== '' ? (float)$_POST['cost_price'] : null;
        $stock       = (int)($_POST['stock'] ?? 0);
        $minQty      = (int)($_POST['moq'] ?? 1) ?: 1;
        $maxQty      = ($_POST['max_quantity'] ?? '') !== '' ? (int)$_POST['max_quantity'] : null;
        $weight      = ($_POST['weight'] ?? '') !== '' ? (float)$_POST['weight'] : null;
        $dimensions  = trim((string)($_POST['dimensions'] ?? ''));
        $badge       = trim((string)($_POST['badge'] ?? ''));
        $isFeatured  = !empty($_POST['is_featured']) ? 1 : 0;
        $isNew       = !empty($_POST['is_new']) ? 1 : 0;
        $isBestSeller= !empty($_POST['is_best_seller']) ? 1 : 0;
        $availability = in_array($_POST['availability'] ?? '', ['in_stock', 'store_pickup', 'preorder'], true) ? $_POST['availability'] : 'in_stock';
        $metaTitle   = trim((string)($_POST['meta_title'] ?? ''));
        $metaDesc    = trim((string)($_POST['meta_description'] ?? ''));

        // Auto-generate SEO fields when left blank.
        if ($metaTitle === '') {
            $metaTitle = mb_strimwidth($name . ' | Marigold Signature', 0, 70, '');
        }
        if ($metaDesc === '') {
            $plain = trim(strip_tags($description !== '' ? $description : $shortDesc));
            if ($plain === '') $plain = $name;
            $metaDesc = mb_strimwidth($plain, 0, 155, '…');
        }

        // products.status enum: draft|published|hidden|archived. "scheduled" maps to draft.
        if (!empty($_POST['save_draft'])) {
            $status = 'draft';
        } else {
            $statusMap = ['draft' => 'draft', 'published' => 'published', 'scheduled' => 'draft', 'hidden' => 'hidden', 'archived' => 'archived'];
            $status = $statusMap[strtolower(trim((string)($_POST['status'] ?? 'published')))] ?? 'published';
        }

        if ($name === '' || $sku === '') {
            Session::set('error', 'Product name and SKU are required.');
            $this->redirect('/admin/products');
        }

        if ($slug === '') {
            $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $name), '-'));
        }

        // SKU uniqueness (allow self when updating)
        $stmt = $db->prepare("SELECT id FROM products WHERE sku = :sku" . ($id ? " AND id != :id" : "") . " LIMIT 1");
        $params = ['sku' => $sku];
        if ($id) $params['id'] = $id;
        $stmt->execute($params);
        if ($stmt->fetch()) {
            Session::set('error', 'A product with this SKU already exists.');
            $this->redirect('/admin/products');
        }

        // Slug uniqueness (allow self when updating)
        $base = $slug;
        $n = 2;
        while (true) {
            $stmt = $db->prepare("SELECT id FROM products WHERE slug = :slug" . ($id ? " AND id != :id" : "") . " LIMIT 1");
            $params = ['slug' => $slug];
            if ($id) $params['id'] = $id;
            $stmt->execute($params);
            if (!$stmt->fetch()) break;
            $slug = $base . '-' . $n++;
        }

        // Featured image: uploaded file wins, else a provided URL, else keep existing.
        $image = trim((string)($_POST['image_url'] ?? ''));
        if (isset($_FILES['featured_image']) && is_uploaded_file($_FILES['featured_image']['tmp_name'])) {
            $uploaded = self::handleImageUpload($_FILES['featured_image'], 'products');
            if ($uploaded) $image = $uploaded;
        }
        if ($image === '' && $id) {
            $stmt = $db->prepare("SELECT image FROM products WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $image = (string)($stmt->fetch()['image'] ?? '');
        }

        if ($id) {
            $stmt = $db->prepare("SELECT price, sale_price, stock_quantity, status, name FROM products WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $old = $stmt->fetch() ?: [];

            $stmt = $db->prepare("
                UPDATE products SET
                    sku = :sku, barcode = :barcode, name = :name, slug = :slug,
                    short_description = :short, description = :description,
                    brand_id = :brand_id, category_id = :category_id,
                    status = :status, price = :price, sale_price = :sale_price,
                    cost_price = :cost_price, stock_quantity = :stock,
                    minimum_order_quantity = :moq, maximum_order_quantity = :max_qty,
                    weight = :weight, dimensions = :dimensions,
                    is_featured = :is_featured, is_new = :is_new, is_best_seller = :is_best_seller,
                    badge = :badge, image = :image,
                    availability = :availability,
                    meta_title = :meta_title, meta_description = :meta_description
                WHERE id = :id
            ");
            $stmt->execute([
                'sku' => $sku, 'barcode' => $barcode, 'name' => $name, 'slug' => $slug,
                'short' => $shortDesc, 'description' => $description,
                'brand_id' => $brandId ?: null, 'category_id' => $categoryId ?: null,
                'status' => $status, 'price' => $price, 'sale_price' => $salePrice,
                'cost_price' => $costPrice, 'stock' => $stock, 'moq' => $minQty,
                'max_qty' => $maxQty, 'weight' => $weight, 'dimensions' => $dimensions,
                'is_featured' => $isFeatured, 'is_new' => $isNew, 'is_best_seller' => $isBestSeller,
                'badge' => $badge ?: null, 'image' => $image,
                'availability' => $availability,
                'meta_title' => $metaTitle ?: null, 'meta_description' => $metaDesc ?: null,
                'id' => $id,
            ]);
            $productId = (int)$id;

            $new = ['price' => $price, 'sale_price' => $salePrice, 'stock_quantity' => $stock, 'status' => $status, 'name' => $name];
            $changed = [];
            foreach ($new as $k => $v) {
                if ((string)($old[$k] ?? '') !== (string)$v) {
                    $changed[$k] = ['from' => $old[$k] ?? null, 'to' => $v];
                }
            }
            AuditService::act('product.updated', 'products', $productId, $old, $changed);

            $message = 'Product updated successfully.';
        } else {
            $uuid = self::uuid4();
            $stmt = $db->prepare("
                INSERT INTO products
                    (uuid, sku, barcode, name, slug, short_description, description,
                     brand_id, category_id, status, price, sale_price, cost_price,
                     stock_quantity, minimum_order_quantity, maximum_order_quantity,
                     weight, dimensions, is_featured, is_new, is_best_seller, badge, image,
                     availability, meta_title, meta_description)
                VALUES
                    (:uuid, :sku, :barcode, :name, :slug, :short, :description,
                     :brand_id, :category_id, :status, :price, :sale_price, :cost_price,
                     :stock, :moq, :max_qty, :weight, :dimensions, :is_featured, :is_new,
                     :is_best_seller, :badge, :image, :availability, :meta_title, :meta_description)
            ");
            $stmt->execute([
                'uuid' => $uuid, 'sku' => $sku, 'barcode' => $barcode, 'name' => $name,
                'slug' => $slug, 'short' => $shortDesc, 'description' => $description,
                'brand_id' => $brandId ?: null, 'category_id' => $categoryId ?: null,
                'status' => $status, 'price' => $price, 'sale_price' => $salePrice,
                'cost_price' => $costPrice, 'stock' => $stock, 'moq' => $minQty,
                'max_qty' => $maxQty, 'weight' => $weight, 'dimensions' => $dimensions,
                'is_featured' => $isFeatured, 'is_new' => $isNew, 'is_best_seller' => $isBestSeller,
                'badge' => $badge ?: null, 'image' => $image,
                'availability' => $availability,
                'meta_title' => $metaTitle ?: null, 'meta_description' => $metaDesc ?: null,
            ]);
            $productId = (int)$db->lastInsertId();
            AuditService::act('product.created', 'products', $productId, [], ['name' => $name, 'sku' => $sku, 'price' => $price, 'status' => $status]);
            $message = 'Product created successfully.';
        }

        // Gallery images → product_images rows
        if (isset($_FILES['gallery']) && is_array($_FILES['gallery']['name'])) {
            $names = $_FILES['gallery']['name'];
            $i = 0;
            foreach ($names as $gName) {
                if ($gName === '') { $i++; continue; }
                $gFile = [
                    'name' => $gName,
                    'type' => $_FILES['gallery']['type'][$i],
                    'tmp_name' => $_FILES['gallery']['tmp_name'][$i],
                    'error' => $_FILES['gallery']['error'][$i],
                    'size' => $_FILES['gallery']['size'][$i],
                ];
                if ($gFile['error'] !== UPLOAD_ERR_OK) { $i++; continue; }
                $uploaded = self::handleImageUpload($gFile, 'products');
                if ($uploaded) {
                    $isFeat = ($image === '' || $image === $uploaded) ? 1 : 0;
                    $stmt = $db->prepare("INSERT INTO product_images (product_id, image, is_featured, sort_order) VALUES (:pid, :img, :feat, :ord)");
                    $stmt->execute(['pid' => $productId, 'img' => $uploaded, 'feat' => $isFeat, 'ord' => $i]);
                    if ($image === '') $image = $uploaded;
                }
                $i++;
            }
            if ($image !== '' && $id) {
                $stmt = $db->prepare("UPDATE products SET image = :img WHERE id = :id");
                $stmt->execute(['img' => $image, 'id' => $id]);
            }
        } elseif ($image !== '' && $id) {
            $stmt = $db->prepare("UPDATE products SET image = :img WHERE id = :id");
            $stmt->execute(['img' => $image, 'id' => $id]);
        }

        \App\Core\Catalogue::forget();

        Session::set('success', $message);
        $this->redirect('/admin/products');
    }

    public function destroy($id)
    {
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }

        $db = Model::getDB();
        $stmt = $db->prepare("SELECT id, name, sku FROM products WHERE id = :id AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['id' => $id]);
        $old = $stmt->fetch() ?: [];

        $stmt = $db->prepare("UPDATE products SET deleted_at = NOW() WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() > 0) {
            \App\Core\Catalogue::forget();
            AuditService::act('product.deleted', 'products', $id, $old);
            Session::success('Product "' . ($old['name'] ?? $id) . '" deleted successfully.');
        } else {
            Session::error('Product not found or already deleted.');
        }
        $this->redirect('/admin/products');
    }

    private static function uuid4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private static function handleImageUpload(array $file, string $dir): string
    {
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) return '';
        if ((int)$file['size'] > 5 * 1024 * 1024) return '';

        $targetDir = BASE_PATH . '/public/uploads/' . $dir;
        if (!is_dir($targetDir) && !@mkdir($targetDir, 0775, true)) return '';

        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        if (!@move_uploaded_file($file['tmp_name'], $targetDir . '/' . $filename)) return '';

        return '/uploads/' . $dir . '/' . $filename;
    }
}
