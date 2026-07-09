<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class AdminNavigationController extends Controller
{
    public function index()
    {
        $menus = [
            'Header' => [
                ['id' => 1, 'title' => 'Home', 'url' => '/', 'type' => 'Page', 'status' => 'Active', 'children' => []],
                ['id' => 2, 'title' => 'Shop', 'url' => '/shop', 'type' => 'Custom', 'status' => 'Active', 'children' => [
                    ['id' => 21, 'title' => 'All Products', 'url' => '/shop', 'type' => 'Custom', 'status' => 'Active'],
                    ['id' => 22, 'title' => 'Notebooks', 'url' => '/category/notebooks', 'type' => 'Category', 'status' => 'Active'],
                    ['id' => 23, 'title' => 'Tech Accessories', 'url' => '/category/tech', 'type' => 'Category', 'status' => 'Active'],
                ]],
                ['id' => 3, 'title' => 'Corporate Solutions', 'url' => '/corporate-solutions', 'type' => 'Page', 'status' => 'Active', 'badge' => 'New', 'children' => []],
                ['id' => 4, 'title' => 'About Us', 'url' => '/about', 'type' => 'Page', 'status' => 'Active', 'children' => []],
                ['id' => 5, 'title' => 'Contact', 'url' => '/contact', 'type' => 'Page', 'status' => 'Active', 'children' => []],
            ],
            'Footer' => [
                ['id' => 6, 'title' => 'Privacy Policy', 'url' => '/privacy', 'type' => 'Page', 'status' => 'Active', 'children' => []],
                ['id' => 7, 'title' => 'Terms of Service', 'url' => '/terms', 'type' => 'Page', 'status' => 'Active', 'children' => []],
                ['id' => 8, 'title' => 'FAQ', 'url' => '/faq', 'type' => 'Page', 'status' => 'Active', 'children' => []],
            ]
        ];

        return View::renderTemplate('pages/admin/navigation/index', 'admin', [
            'title' => 'Navigation Manager | Admin',
            'menus' => $menus
        ]);
    }
}
