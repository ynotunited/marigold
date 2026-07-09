<?php

namespace App\Controller;


use App\Core\Controller;
class SitemapController extends Controller
{
    public function index()
    {
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $date = date('Y-m-d');

        // Static Routes
        $routes = [
            '/',
            '/shop',
            '/about',
            '/solutions',
            '/contact',
            '/blog',
            '/faq',
            '/privacy-policy',
            '/terms-and-conditions',
            '/shipping-policy',
            '/return-policy'
        ];

        header("Content-Type: application/xml; charset=utf-8");
        
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($routes as $route) {
            echo '<url>';
            echo '<loc>' . $baseUrl . $route . '</loc>';
            echo '<lastmod>' . $date . '</lastmod>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>' . ($route === '/' ? '1.0' : '0.8') . '</priority>';
            echo '</url>';
        }
        
        // In the future, dynamic routes like products and posts will be queried here and added to the XML.

        echo '</urlset>';
    }
}
