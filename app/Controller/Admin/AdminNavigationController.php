<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;

class AdminNavigationController extends Controller
{
    public function index()
    {
        $db = Model::getDB();

        // Header menu built from real published pages + active categories.
        $pageRows = $db->query("
            SELECT id, title, slug
            FROM pages
            WHERE status = 'published'
            ORDER BY title ASC
        ")->fetchAll();

        $categoryRows = $db->query("
            SELECT id, name, slug
            FROM categories
            WHERE status = 'active'
            ORDER BY name ASC
        ")->fetchAll();

        $header = [];
        $n = 0;
        foreach ($pageRows as $p) {
            $n++;
            $header[] = [
                'id'       => $n,
                'title'    => $p['title'],
                'url'      => '/' . ltrim($p['slug'], '/'),
                'type'     => 'Page',
                'status'   => 'Active',
                'children' => [],
            ];
        }
        $childOffset = $n + 1;
        $n = 0;
        foreach ($categoryRows as $c) {
            $n++;
            $header[] = [
                'id'       => $childOffset + $n,
                'title'    => $c['name'],
                'url'      => '/category/' . $c['slug'],
                'type'     => 'Category',
                'status'   => 'Active',
                'children' => [],
            ];
        }

        $menus = [
            'Header' => $header,
            'Footer' => [],
        ];

        return View::renderTemplate('pages/admin/navigation/index', 'admin', [
            'title' => 'Navigation Manager | Admin',
            'menus' => $menus
        ]);
    }
}
