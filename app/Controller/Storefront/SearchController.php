<?php
namespace App\Controller\Storefront;

use App\Core\Controller;
use App\Core\View;

class SearchController extends Controller
{
    /**
     * AJAX endpoint for the instant search dropdown
     */
    public function ajaxSearch()
    {
        header('Content-Type: application/json');
        
        $query = $_GET['q'] ?? '';
        
        // Mock data response based on typical search
        if (empty(trim($query))) {
            echo json_encode(['results' => [], 'popular' => ['Corporate Gifting', 'Leather Notebooks', 'Vacuum Flasks', 'Pen Sets']]);
            exit;
        }

        // Simulate no results state if query is "none"
        if (strtolower($query) === 'none') {
            echo json_encode([
                'results' => [],
                'suggestions' => ['Try searching for "Notebook"', 'Browse Categories', 'Contact Sales for Custom Orders']
            ]);
            exit;
        }

        // Mock grouped results
        $results = [
            'Products' => [
                ['title' => 'Executive Leather Notebook', 'url' => '/product/executive-leather-notebook', 'image' => 'https://images.unsplash.com/photo-1531346878377-a541ba67f401?w=100', 'price' => '₦15,000'],
                ['title' => 'Minimalist Pen Set', 'url' => '/product/minimalist-pen-set', 'image' => 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?w=100', 'price' => '₦8,500'],
            ],
            'Categories' => [
                ['title' => 'Premium Notebooks', 'url' => '/category/notebooks'],
                ['title' => 'Writing Instruments', 'url' => '/category/pens'],
            ],
            'Blog' => [
                ['title' => 'Top 10 Corporate Gifts for 2026', 'url' => '/blog/top-10-corporate-gifts'],
            ]
        ];

        echo json_encode(['results' => $results]);
        exit;
    }

    /**
     * Full search results page
     */
    public function index()
    {
        $query = $_GET['q'] ?? 'Notebook';

        // Mock full results
        $products = [
            ['id' => 1, 'name' => 'Executive Leather Notebook', 'price' => 15000, 'category' => 'Notebooks', 'image' => 'https://images.unsplash.com/photo-1531346878377-a541ba67f401?w=500'],
            ['id' => 2, 'name' => 'Eco-Friendly Bamboo Notebook', 'price' => 12000, 'category' => 'Notebooks', 'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=500'],
            ['id' => 3, 'name' => 'Wirebound Corporate Jotter', 'price' => 5000, 'category' => 'Notebooks', 'image' => 'https://images.unsplash.com/photo-1517842645767-c639042777db?w=500'],
            ['id' => 4, 'name' => 'Luxury Pen & Notebook Set', 'price' => 35000, 'category' => 'Gift Sets', 'image' => 'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=500'],
        ];

        return View::renderTemplate('pages/storefront/search/index', 'admin', [ // using admin layout temporarily for rapid mock UI mapping
            'title' => "Search Results for '{$query}' | Marigold Signature",
            'query' => $query,
            'products' => $products
        ]);
    }
}
