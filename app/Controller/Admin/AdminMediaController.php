<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class AdminMediaController extends Controller
{
    public function index()
    {
        $folders = [
            ['name' => 'Products', 'files' => 245, 'size' => '1.2 GB'],
            ['name' => 'Blog Images', 'files' => 84, 'size' => '320 MB'],
            ['name' => 'Brand Logos', 'files' => 12, 'size' => '15 MB'],
            ['name' => 'Banners', 'files' => 28, 'size' => '145 MB'],
        ];

        $files = [
            ['id' => 1, 'name' => 'leather-notebook-hero.webp', 'folder' => 'Products', 'size' => '245 KB', 'dimensions' => '1200x1200px', 'type' => 'image/webp', 'date' => '2 hrs ago', 'thumbnail' => 'https://images.unsplash.com/photo-1544816278-ca5e3f4abd8c?q=80&w=150&auto=format&fit=crop'],
            ['id' => 2, 'name' => 'vacuum-flask-black.webp', 'folder' => 'Products', 'size' => '180 KB', 'dimensions' => '1200x1200px', 'type' => 'image/webp', 'date' => '4 hrs ago', 'thumbnail' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=150&auto=format&fit=crop'],
            ['id' => 3, 'name' => 'moleskine-logo-dark.svg', 'folder' => 'Brand Logos', 'size' => '12 KB', 'dimensions' => 'Auto', 'type' => 'image/svg+xml', 'date' => '1 day ago', 'thumbnail' => 'https://via.placeholder.com/150/111111/C8A96E?text=M'],
            ['id' => 4, 'name' => 'corporate-gifting-banner.webp', 'folder' => 'Banners', 'size' => '540 KB', 'dimensions' => '1920x800px', 'type' => 'image/webp', 'date' => '3 days ago', 'thumbnail' => 'https://images.unsplash.com/photo-1513682121497-80211f36a7d3?q=80&w=150&auto=format&fit=crop'],
            ['id' => 5, 'name' => 'unused-old-banner.jpg', 'folder' => 'Banners', 'size' => '1.2 MB', 'dimensions' => '1920x1080px', 'type' => 'image/jpeg', 'date' => '2 months ago', 'thumbnail' => 'https://images.unsplash.com/photo-1522204523234-8729aa6e3d5f?q=80&w=150&auto=format&fit=crop', 'unused' => true],
            ['id' => 6, 'name' => 'pen-set-box.webp', 'folder' => 'Products', 'size' => '195 KB', 'dimensions' => '1000x1000px', 'type' => 'image/webp', 'date' => '3 months ago', 'thumbnail' => 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=150&auto=format&fit=crop'],
        ];

        return View::renderTemplate('pages/admin/media/index', 'admin', [
            'title' => 'Media Library | Admin',
            'folders' => $folders,
            'files' => $files,
            'storage_used' => '1.68 GB',
            'storage_total' => '10 GB',
            'storage_percent' => 16.8,
        ]);
    }
}
