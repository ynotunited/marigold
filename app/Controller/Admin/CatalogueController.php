<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class CatalogueController extends Controller
{
    public function categories()
    {
        $categories = [
            ['id' => 1, 'name' => 'Stationery',      'slug' => 'stationery',       'parent' => null,          'products' => 48, 'sort' => 1, 'status' => 'Active'],
            ['id' => 2, 'name' => 'Notebooks',        'slug' => 'notebooks',         'parent' => 'Stationery',  'products' => 22, 'sort' => 1, 'status' => 'Active'],
            ['id' => 3, 'name' => 'Pens & Writing',   'slug' => 'pens-writing',      'parent' => 'Stationery',  'products' => 18, 'sort' => 2, 'status' => 'Active'],
            ['id' => 4, 'name' => 'Drinkware',        'slug' => 'drinkware',         'parent' => null,          'products' => 30, 'sort' => 2, 'status' => 'Active'],
            ['id' => 5, 'name' => 'Tech Accessories', 'slug' => 'tech-accessories',  'parent' => null,          'products' => 25, 'sort' => 3, 'status' => 'Active'],
            ['id' => 6, 'name' => 'Apparel',          'slug' => 'apparel',           'parent' => null,          'products' => 40, 'sort' => 4, 'status' => 'Hidden'],
        ];
        return View::renderTemplate('pages/admin/catalogue/categories', 'admin', ['title' => 'Categories | Admin', 'categories' => $categories]);
    }

    public function brands()
    {
        $brands = [
            ['id' => 1, 'name' => 'Moleskine', 'logo' => 'https://via.placeholder.com/40', 'products' => 12, 'featured' => true, 'status' => 'Active'],
            ['id' => 2, 'name' => 'Thermos',   'logo' => 'https://via.placeholder.com/40', 'products' => 8,  'featured' => false, 'status' => 'Active'],
            ['id' => 3, 'name' => 'Anker',     'logo' => 'https://via.placeholder.com/40', 'products' => 15, 'featured' => true, 'status' => 'Active'],
            ['id' => 4, 'name' => 'Targus',    'logo' => 'https://via.placeholder.com/40', 'products' => 6,  'featured' => false, 'status' => 'Active'],
            ['id' => 5, 'name' => 'Custom',    'logo' => 'https://via.placeholder.com/40', 'products' => 80, 'featured' => false, 'status' => 'Active'],
        ];
        return View::renderTemplate('pages/admin/catalogue/brands', 'admin', ['title' => 'Brands | Admin', 'brands' => $brands]);
    }

    public function collections()
    {
        $collections = [
            ['id' => 1, 'name' => 'New Arrivals 2026',      'products' => 12, 'status' => 'Active'],
            ['id' => 2, 'name' => 'Executive Gift Sets',     'products' => 8,  'status' => 'Active'],
            ['id' => 3, 'name' => 'Eco-Friendly Selection',  'products' => 5,  'status' => 'Draft'],
            ['id' => 4, 'name' => 'End of Year Campaign',    'products' => 20, 'status' => 'Scheduled'],
        ];
        return View::renderTemplate('pages/admin/catalogue/collections', 'admin', ['title' => 'Collections | Admin', 'collections' => $collections]);
    }

    public function solutions()
    {
        $solutions = [
            ['id' => 1, 'name' => 'Corporate Gifting',    'icon' => 'gift',      'products' => 34, 'status' => 'Active'],
            ['id' => 2, 'name' => 'Event Merchandise',    'icon' => 'star',      'products' => 20, 'status' => 'Active'],
            ['id' => 3, 'name' => 'Staff Onboarding Kits','icon' => 'users',     'products' => 15, 'status' => 'Active'],
            ['id' => 4, 'name' => 'Branded Apparel',      'icon' => 'shirt',     'products' => 10, 'status' => 'Draft'],
        ];
        return View::renderTemplate('pages/admin/catalogue/solutions', 'admin', ['title' => 'Corporate Solutions | Admin', 'solutions' => $solutions]);
    }
}
