<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Model;
use App\Core\Session;
use App\Core\View;

class CatalogueController extends Controller
{
    public function categories()
    {
        $db = Model::getDB();

        $rows = $db->query("
            SELECT
                c.id,
                c.name,
                c.slug,
                c.parent_id,
                c.sort_order,
                c.status,
                p.name AS parent_name,
                (SELECT COUNT(*) FROM products pr WHERE pr.category_id = c.id AND pr.deleted_at IS NULL) AS product_count
            FROM categories c
            LEFT JOIN categories p ON p.id = c.parent_id
            ORDER BY c.sort_order ASC, c.name ASC
        ")->fetchAll();

        $categories = [];
        foreach ($rows as $c) {
            $categories[] = [
                'id'       => $c['id'],
                'name'     => $c['name'],
                'slug'     => $c['slug'],
                'parent_id' => $c['parent_id'] ? (int)$c['parent_id'] : null,
                'parent'   => $c['parent_name'] ?: null,
                'products' => (int)$c['product_count'],
                'sort'     => (int)$c['sort_order'],
                'status'   => ucfirst($c['status']),
            ];
        }

        return View::renderTemplate('pages/admin/catalogue/categories', 'admin', [
            'title' => 'Categories | Admin',
            'categories' => $categories,
        ]);
    }

    public function store()
    {
        return $this->saveCategory(null);
    }

    public function update($id)
    {
        return $this->saveCategory((int)$id);
    }

    public function destroy($id)
    {
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }

        $db = Model::getDB();
        $stmt = $db->prepare("SELECT COUNT(*) AS cnt FROM categories WHERE parent_id = :id");
        $stmt->execute(['id' => $id]);
        $children = (int)$stmt->fetch()['cnt'];

        if ($children > 0) {
            Session::set('error', 'Cannot delete a category that has sub-categories.');
            $this->redirect('/admin/categories');
        }

        $stmt = $db->prepare("SELECT COUNT(*) AS cnt FROM products WHERE category_id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        $products = (int)$stmt->fetch()['cnt'];

        if ($products > 0) {
            Session::set('error', 'Cannot delete a category that still has products assigned.');
            $this->redirect('/admin/categories');
        }

        $stmt = $db->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);

        Session::set('success', 'Category deleted successfully.');
        $this->redirect('/admin/categories');
    }

    private function saveCategory($id = null)
    {
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }

        $name   = trim((string)($_POST['name'] ?? ''));
        $slug   = trim((string)($_POST['slug'] ?? ''));
        $parent = (int)($_POST['parent_id'] ?? 0);
        $sort   = (int)($_POST['sort_order'] ?? 0);
        $status = in_array($_POST['status'] ?? '', ['Active', 'Hidden']) ? ($_POST['status'] === 'Hidden' ? 'inactive' : 'active') : 'active';

        if ($name === '') {
            Session::set('error', 'Category name is required.');
            $this->redirect('/admin/categories');
        }

        if ($slug === '') {
            $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $name), '-'));
        }

        $db = Model::getDB();
        $parentId = null;
        if ($parent > 0) {
            $pStmt = $db->prepare("SELECT id FROM categories WHERE id = :id LIMIT 1");
            $pStmt->execute(['id' => $parent]);
            if ($pStmt->fetch()) $parentId = $parent;
        }

        // Slug uniqueness (allow self when updating)
        $base = $slug;
        $n = 2;
        while (true) {
            $stmt = $db->prepare("SELECT id FROM categories WHERE slug = :slug" . ($id ? " AND id != :id" : "") . " LIMIT 1");
            $params = ['slug' => $slug];
            if ($id) $params['id'] = $id;
            $stmt->execute($params);
            if (!$stmt->fetch()) break;
            $slug = $base . '-' . $n++;
        }

        if ($id) {
            $stmt = $db->prepare("UPDATE categories SET name = :name, slug = :slug, parent_id = :parent_id, sort_order = :sort_order, status = :status WHERE id = :id");
            $stmt->execute([
                'name'      => $name,
                'slug'      => $slug,
                'parent_id' => $parentId,
                'sort_order' => $sort,
                'status'    => $status,
                'id'        => $id,
            ]);
            Session::set('success', 'Category updated successfully.');
        } else {
            $stmt = $db->prepare("INSERT INTO categories (name, slug, parent_id, sort_order, status) VALUES (:name, :slug, :parent_id, :sort_order, :status)");
            $stmt->execute([
                'name'      => $name,
                'slug'      => $slug,
                'parent_id' => $parentId,
                'sort_order' => $sort,
                'status'    => $status,
            ]);
            Session::set('success', 'Category created successfully.');
        }

        $this->redirect('/admin/categories');
    }

    public function brands()
    {
        $db = Model::getDB();

        $rows = $db->query("
            SELECT
                b.id,
                b.name,
                b.slug,
                b.logo,
                b.website,
                b.description,
                b.featured,
                b.status,
                (SELECT COUNT(*) FROM products pr WHERE pr.brand_id = b.id AND pr.deleted_at IS NULL) AS product_count
            FROM brands b
            ORDER BY b.name ASC
        ")->fetchAll();

        $brands = [];
        foreach ($rows as $b) {
            $brands[] = [
                'id'          => $b['id'],
                'name'        => $b['name'],
                'slug'        => $b['slug'],
                'logo'        => $b['logo'] ?: '',
                'website'     => $b['website'] ?: '',
                'description' => $b['description'] ?: '',
                'products'    => (int)$b['product_count'],
                'featured'    => (bool)$b['featured'],
                'status'      => ucfirst($b['status']),
            ];
        }

        return View::renderTemplate('pages/admin/catalogue/brands', 'admin', [
            'title' => 'Brands | Admin',
            'brands' => $brands,
        ]);
    }

    public function storeBrand()
    {
        return $this->saveBrand(null);
    }

    public function updateBrand($id)
    {
        return $this->saveBrand((int)$id);
    }

    public function destroyBrand($id)
    {
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }

        $db = Model::getDB();
        $stmt = $db->prepare("SELECT COUNT(*) AS cnt FROM products WHERE brand_id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        $products = (int)$stmt->fetch()['cnt'];

        if ($products > 0) {
            Session::set('error', 'Cannot delete a brand that still has products assigned.');
            $this->redirect('/admin/brands');
        }

        $stmt = $db->prepare("DELETE FROM brands WHERE id = :id");
        $stmt->execute(['id' => $id]);

        Session::set('success', 'Brand deleted successfully.');
        $this->redirect('/admin/brands');
    }

    private function saveBrand($id = null)
    {
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }

        $name        = trim((string)($_POST['name'] ?? ''));
        $slug        = trim((string)($_POST['slug'] ?? ''));
        $website     = trim((string)($_POST['website'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $featured    = !empty($_POST['featured']) ? 1 : 0;
        $status      = in_array($_POST['status'] ?? '', ['Active', 'Hidden']) ? ($_POST['status'] === 'Hidden' ? 'inactive' : 'active') : 'active';

        if ($name === '') {
            Session::set('error', 'Brand name is required.');
            $this->redirect('/admin/brands');
        }

        if ($slug === '') {
            $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $name), '-'));
        }

        $logo = trim((string)($_POST['logo_url'] ?? ''));
        if (isset($_FILES['logo']) && is_uploaded_file($_FILES['logo']['tmp_name'])) {
            $logo = self::handleImageUpload($_FILES['logo'], 'brands') ?: $logo;
        }

        $db = Model::getDB();

        // Slug uniqueness (allow self when updating)
        $base = $slug;
        $n = 2;
        while (true) {
            $stmt = $db->prepare("SELECT id FROM brands WHERE slug = :slug" . ($id ? " AND id != :id" : "") . " LIMIT 1");
            $params = ['slug' => $slug];
            if ($id) $params['id'] = $id;
            $stmt->execute($params);
            if (!$stmt->fetch()) break;
            $slug = $base . '-' . $n++;
        }

        if ($id) {
            $stmt = $db->prepare("UPDATE brands SET name = :name, slug = :slug, logo = :logo, website = :website, description = :description, featured = :featured, status = :status WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'slug' => $slug,
                'logo' => $logo,
                'website' => $website,
                'description' => $description,
                'featured' => $featured,
                'status' => $status,
                'id' => $id,
            ]);
            Session::set('success', 'Brand updated successfully.');
        } else {
            $stmt = $db->prepare("INSERT INTO brands (name, slug, logo, website, description, featured, status) VALUES (:name, :slug, :logo, :website, :description, :featured, :status)");
            $stmt->execute([
                'name' => $name,
                'slug' => $slug,
                'logo' => $logo,
                'website' => $website,
                'description' => $description,
                'featured' => $featured,
                'status' => $status,
            ]);
            Session::set('success', 'Brand created successfully.');
        }

        $this->redirect('/admin/brands');
    }

    /**
     * Persist an uploaded image under public/uploads/<dir> and return a web path.
     * Returns '' on failure. (PHP files are blocked there by .htaccess.)
     */
    private static function handleImageUpload(array $file, string $dir): string
    {
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) return '';
        if ((int)$file['size'] > 5 * 1024 * 1024) return '';

        $targetDir = BASE_PATH . '/public/uploads/' . $dir;
        if (!is_dir($targetDir) && !@mkdir($targetDir, 0775, true)) return '';

        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        if (!@move_uploaded_file($file['tmp_name'], $targetDir . '/' . $filename)) return '';

        return '/uploads/' . $dir . '/' . $filename;
    }

    public function collections()
    {
        $db = Model::getDB();

        $rows = $db->query("
            SELECT
                c.id,
                c.name,
                c.status,
                (SELECT COUNT(*) FROM products pr WHERE pr.collection_id = c.id AND pr.deleted_at IS NULL) AS product_count
            FROM collections c
            ORDER BY c.name ASC
        ")->fetchAll();

        $collections = [];
        foreach ($rows as $c) {
            $collections[] = [
                'id'       => $c['id'],
                'name'     => $c['name'],
                'status'   => ucfirst($c['status']),
                'products' => (int)$c['product_count'],
            ];
        }

        // Solutions come from a corporate_solutions table if present, else derived from collections.
        $solutions = [];
        try {
            $solRows = $db->query("
                SELECT id, name, icon, status,
                       (SELECT COUNT(*) FROM products pr WHERE pr.collection_id = c.id AND pr.deleted_at IS NULL) AS product_count
                FROM collections c
                WHERE c.name LIKE '%Solution%' OR c.name LIKE '%Corporate%' OR c.name LIKE '%Gift%'
                ORDER BY c.name ASC
            ")->fetchAll();
            foreach ($solRows as $s) {
                $solutions[] = [
                    'id'       => $s['id'],
                    'name'     => $s['name'],
                    'icon'     => $s['icon'] ?: 'gift',
                    'products' => (int)$s['product_count'],
                    'status'   => ucfirst($s['status']),
                ];
            }
        } catch (\Throwable $e) {
            $solutions = [];
        }

        return View::renderTemplate('pages/admin/catalogue/collections', 'admin', [
            'title' => 'Collections | Admin',
            'collections' => $collections,
            'solutions' => $solutions,
        ]);
    }

    public function solutions()
    {
        $data = $this->collections();
        return $data;
    }
}
