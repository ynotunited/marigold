<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;

class AdminCmsController extends Controller
{
    public function index()
    {
        $db = Model::getDB();

        $pages = $db->query("
            SELECT
                p.id,
                p.title,
                p.slug,
                p.status,
                p.updated_at,
                (SELECT COUNT(*) FROM page_sections s WHERE s.page_id = p.id AND s.status = 'active') AS section_count
            FROM pages p
            ORDER BY p.updated_at DESC
        ")->fetchAll();

        $rows = [];
        foreach ($pages as $p) {
            $rows[] = [
                'id'       => $p['id'],
                'title'    => $p['title'],
                'slug'     => '/' . ltrim($p['slug'], '/'),
                'status'   => ucfirst($p['status']),
                'updated'  => date('M j, Y', strtotime($p['updated_at'])),
                'sections' => (int)$p['section_count'],
            ];
        }

        return View::renderTemplate('pages/admin/cms/index', 'admin', [
            'title' => 'Pages (CMS) | Admin',
            'pages' => $rows,
        ]);
    }

    public function builder($id)
    {
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT id, title, slug, status FROM pages WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $page = $stmt->fetch();

        if (!$page) {
            http_response_code(404);
            return View::renderTemplate('pages/public/errors/404', 'main', ['title' => 'Page not found']);
        }

        $secStmt = $db->prepare("
            SELECT id, section_type, sort_order, status, configuration_json
            FROM page_sections
            WHERE page_id = :page_id
            ORDER BY sort_order ASC
        ");
        $secStmt->execute(['page_id' => $page['id']]);

        $sections = [];
        foreach ($secStmt->fetchAll() as $s) {
            $cfg = $s['configuration_json'] ? json_decode($s['configuration_json'], true) : [];
            $sections[] = [
                'id'     => $s['id'],
                'type'   => ucwords(str_replace(['-', '_'], ' ', $s['section_type'])),
                'title'  => $cfg['title'] ?? 'Section',
                'hidden' => $s['status'] !== 'active',
            ];
        }

        $page['status'] = ucfirst($page['status']);

        return View::renderTemplate('pages/admin/cms/builder', 'admin', [
            'title' => 'Page Builder | Admin',
            'page' => $page,
            'sections' => $sections
        ]);
    }
}
