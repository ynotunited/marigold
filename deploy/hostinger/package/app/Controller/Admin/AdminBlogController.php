<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Model;
use App\Core\Session;
use App\Core\View;

class AdminBlogController extends Controller
{
    public function index()
    {
        $db = Model::getDB();

        $posts = $db->query("
            SELECT
                p.id,
                p.title,
                p.status,
                p.created_at,
                p.published_at,
                p.featured_image,
                u.first_name,
                u.last_name,
                (SELECT pc.name
                 FROM post_post_categories ppc
                 JOIN post_categories pc ON pc.id = ppc.category_id
                 WHERE ppc.post_id = p.id
                 LIMIT 1) AS category
            FROM posts p
            LEFT JOIN users u ON u.id = p.author_id
            ORDER BY p.created_at DESC
        ")->fetchAll();

        $rows = [];
        foreach ($posts as $p) {
            $statusRaw = $p['status'];
            $status = ucfirst($statusRaw);
            // posts use draft/published/archived; display scheduled when published_at is future
            if ($statusRaw === 'draft' && $p['published_at'] && strtotime($p['published_at']) > time()) {
                $status = 'Scheduled';
            }
            $rows[] = [
                'id'       => $p['id'],
                'title'    => $p['title'],
                'author'   => trim(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? '')) ?: '—',
                'category' => $p['category'] ?: 'Uncategorized',
                'status'   => $status,
                'views'    => 0,
                'date'     => date('Y-m-d', strtotime($p['published_at'] ?: $p['created_at'])),
                'featured' => !empty($p['featured_image']),
            ];
        }

        return View::renderTemplate('pages/admin/blog/index', 'admin', [
            'title' => 'Blog Posts | Admin',
            'posts' => $rows,
        ]);
    }

    public function create()
    {
        $db = Model::getDB();
        $authors = $db->query("SELECT id, CONCAT_WS(' ', first_name, last_name) AS name FROM users ORDER BY name ASC")->fetchAll();
        $categories = $db->query("SELECT id, name FROM post_categories ORDER BY name ASC")->fetchAll();

        return View::renderTemplate('pages/admin/blog/form', 'admin', [
            'title' => 'New Post | Admin',
            'post' => null,
            'mode' => 'create',
            'authors' => $authors,
            'categories' => $categories,
        ]);
    }

    public function edit($id)
    {
        $db = Model::getDB();
        $stmt = $db->prepare("
            SELECT p.*, u.first_name, u.last_name
            FROM posts p
            LEFT JOIN users u ON u.id = p.author_id
            WHERE p.id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $post = $stmt->fetch();

        if (!$post) {
            Session::set('error', 'Post not found.');
            $this->redirect('/admin/blog');
        }

        $post['excerpt']  = $post['excerpt'] ?: '';
        $post['author']   = trim(($post['first_name'] ?? '') . ' ' . ($post['last_name'] ?? '')) ?: 'Super Admin';
        $post['featured'] = !empty($post['featured_image']);

        $stmt = $db->prepare("
            SELECT pc.name FROM post_post_categories ppc
            JOIN post_categories pc ON pc.id = ppc.category_id
            WHERE ppc.post_id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $cat = $stmt->fetch();
        $post['category'] = $cat['name'] ?? '';

        return View::renderTemplate('pages/admin/blog/form', 'admin', [
            'title' => 'Edit Post | Admin',
            'post' => $post,
            'mode' => 'edit',
            'authors' => $db->query("SELECT id, CONCAT_WS(' ', first_name, last_name) AS name FROM users ORDER BY name ASC")->fetchAll(),
            'categories' => $db->query("SELECT id, name FROM post_categories ORDER BY name ASC")->fetchAll(),
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

        $title        = trim((string)($_POST['title'] ?? ''));
        $slug         = trim((string)($_POST['slug'] ?? ''));
        $excerpt      = trim((string)($_POST['excerpt'] ?? ''));
        $content      = (string)($_POST['content'] ?? '');
        $metaTitle    = trim((string)($_POST['meta_title'] ?? ''));
        $metaDesc     = trim((string)($_POST['meta_description'] ?? ''));
        $authorId     = (int)($_POST['author_id'] ?? 0);
        $publishedAt  = trim((string)($_POST['published_at'] ?? ''));
        $statusRaw    = strtolower(trim((string)($_POST['status'] ?? 'published')));

        // posts.status enum: draft|published|archived
        $statusMap = ['draft' => 'draft', 'published' => 'published', 'scheduled' => 'draft', 'archived' => 'archived', 'hidden' => 'draft'];
        $status = $statusMap[$statusRaw] ?? 'published';
        if (!empty($_POST['save_draft'])) $status = 'draft';

        if ($title === '') {
            Session::set('error', 'Post title is required.');
            $this->redirect('/admin/blog');
        }

        if ($slug === '') {
            $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $title), '-'));
        }

        // Slug uniqueness (allow self when updating)
        $base = $slug;
        $n = 2;
        while (true) {
            $stmt = $db->prepare("SELECT id FROM posts WHERE slug = :slug" . ($id ? " AND id != :id" : "") . " LIMIT 1");
            $params = ['slug' => $slug];
            if ($id) $params['id'] = $id;
            $stmt->execute($params);
            if (!$stmt->fetch()) break;
            $slug = $base . '-' . $n++;
        }

        // Featured image: uploaded file wins, else a provided URL, else keep existing.
        $featured = trim((string)($_POST['featured_image_url'] ?? ''));
        if (isset($_FILES['featured_image']) && is_uploaded_file($_FILES['featured_image']['tmp_name'])) {
            $uploaded = self::handleImageUpload($_FILES['featured_image'], 'blog');
            if ($uploaded) $featured = $uploaded;
        }
        if ($featured === '' && $id) {
            $stmt = $db->prepare("SELECT featured_image FROM posts WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $featured = (string)($stmt->fetch()['featured_image'] ?? '');
        }

        $pubDate = null;
        if ($publishedAt !== '') {
            $pubDate = date('Y-m-d H:i:s', strtotime($publishedAt));
        } elseif ($status === 'published') {
            $pubDate = date('Y-m-d H:i:s');
        }

        if ($id) {
            $stmt = $db->prepare("
                UPDATE posts SET title = :title, slug = :slug, excerpt = :excerpt,
                    content = :content, featured_image = :featured,
                    status = :status, published_at = :published_at,
                    author_id = :author_id, meta_title = :meta_title,
                    meta_description = :meta_description
                WHERE id = :id
            ");
            $stmt->execute([
                'title' => $title, 'slug' => $slug, 'excerpt' => $excerpt,
                'content' => $content, 'featured' => $featured ?: null,
                'status' => $status, 'published_at' => $pubDate,
                'author_id' => $authorId ?: null, 'meta_title' => $metaTitle ?: null,
                'meta_description' => $metaDesc ?: null, 'id' => $id,
            ]);
            $postId = (int)$id;
            $message = 'Blog post updated successfully.';
        } else {
            $stmt = $db->prepare("
                INSERT INTO posts (title, slug, excerpt, content, featured_image, status, published_at, author_id, meta_title, meta_description)
                VALUES (:title, :slug, :excerpt, :content, :featured, :status, :published_at, :author_id, :meta_title, :meta_description)
            ");
            $stmt->execute([
                'title' => $title, 'slug' => $slug, 'excerpt' => $excerpt,
                'content' => $content, 'featured' => $featured ?: null,
                'status' => $status, 'published_at' => $pubDate,
                'author_id' => $authorId ?: null, 'meta_title' => $metaTitle ?: null,
                'meta_description' => $metaDesc ?: null,
            ]);
            $postId = (int)$db->lastInsertId();
            $message = 'Blog post created successfully.';
        }

        // Assign category via the pivot table
        $categoryId = (int)($_POST['category_id'] ?? 0);
        if ($categoryId > 0) {
            $stmt = $db->prepare("SELECT id FROM post_categories WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $categoryId]);
            if ($stmt->fetch()) {
                $stmt = $db->prepare("DELETE FROM post_post_categories WHERE post_id = :pid");
                $stmt->execute(['pid' => $postId]);
                $stmt = $db->prepare("INSERT INTO post_post_categories (post_id, category_id) VALUES (:pid, :cid)");
                $stmt->execute(['pid' => $postId, 'cid' => $categoryId]);
            }
        }

        Session::set('success', $message);
        $this->redirect('/admin/blog');
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
