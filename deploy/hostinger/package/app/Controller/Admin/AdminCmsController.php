<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class AdminCmsController extends Controller
{
    private function getMockPages(): array
    {
        return [
            ['id' => 1, 'title' => 'Homepage', 'slug' => '/', 'status' => 'Published', 'updated' => '2 days ago', 'sections' => 8],
            ['id' => 2, 'title' => 'About Us', 'slug' => '/about', 'status' => 'Published', 'updated' => '1 month ago', 'sections' => 4],
            ['id' => 3, 'title' => 'Corporate Solutions', 'slug' => '/corporate-solutions', 'status' => 'Published', 'updated' => '1 week ago', 'sections' => 6],
            ['id' => 4, 'title' => 'Summer Campaign 2026', 'slug' => '/campaigns/summer-26', 'status' => 'Draft', 'updated' => '2 hrs ago', 'sections' => 3],
        ];
    }

    public function index()
    {
        return View::renderTemplate('pages/admin/cms/index', 'admin', [
            'title' => 'Pages (CMS) | Admin',
            'pages' => $this->getMockPages(),
        ]);
    }

    public function builder($id)
    {
        $page = ['id' => $id, 'title' => 'Homepage', 'slug' => '/', 'status' => 'Published'];
        
        // Mock sections for the builder
        $sections = [
            ['id' => 's1', 'type' => 'Hero Banner', 'title' => 'Main Hero', 'hidden' => false],
            ['id' => 's2', 'type' => 'Logo Cloud', 'title' => 'Trusted By', 'hidden' => false],
            ['id' => 's3', 'type' => 'Product Carousel', 'title' => 'Featured Products', 'hidden' => false],
            ['id' => 's4', 'type' => 'Image+Text', 'title' => 'About Marigold', 'hidden' => false],
            ['id' => 's5', 'type' => 'Testimonials', 'title' => 'Client Reviews', 'hidden' => true],
            ['id' => 's6', 'type' => 'Latest Blog Posts', 'title' => 'Insights', 'hidden' => false],
            ['id' => 's7', 'type' => 'Newsletter', 'title' => 'Footer CTA', 'hidden' => false],
        ];

        return View::renderTemplate('pages/admin/cms/builder', 'admin', [
            'title' => 'Page Builder | Admin',
            'page' => $page,
            'sections' => $sections
        ]);
    }
}
