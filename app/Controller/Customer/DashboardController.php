<?php

namespace App\Controller\Customer;


use App\Core\Controller;
use App\Core\View;

class DashboardController extends Controller
{
    public function index()
    {
        // Simulated Authentication State for visual testing
        $user = [
            'first_name' => 'David',
            'last_name' => 'Okon'
        ];

        // Determine time of day for greeting
        $hour = date('H');
        if ($hour < 12) {
            $greeting = 'Good morning';
        } elseif ($hour < 17) {
            $greeting = 'Good afternoon';
        } else {
            $greeting = 'Good evening';
        }

        // Mock Stats
        $stats = [
            'pending_orders' => 2,
            'completed_orders' => 14,
            'pending_quotes' => 1,
            'wishlist_count' => 8
        ];

        // Mock Recent Orders
        $recent_orders = [
            ['order_number' => 'ORD-9823', 'date' => date('Y-m-d', strtotime('-2 days')), 'status' => 'Processing', 'total' => '₦450,000'],
            ['order_number' => 'ORD-9810', 'date' => date('Y-m-d', strtotime('-15 days')), 'status' => 'Completed', 'total' => '₦1,200,000'],
            ['order_number' => 'ORD-9755', 'date' => date('Y-m-d', strtotime('-45 days')), 'status' => 'Completed', 'total' => '₦85,000'],
        ];

        // Mock Pending Quotes
        $pending_quotes = [
            ['quote_number' => 'QT-1045', 'date' => date('Y-m-d', strtotime('-1 days')), 'status' => 'Pending Review', 'items' => 5],
        ];

        // Mock Recommended Products
        $recommended_products = [
            [
                'name' => 'Executive Leather Folio',
                'price' => '₦45,000',
                'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=400&auto=format&fit=crop',
                'badge' => 'Bestseller'
            ],
            [
                'name' => 'Premium Metal Pen Set',
                'price' => '₦28,000',
                'image' => 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=400&auto=format&fit=crop',
                'badge' => ''
            ],
            [
                'name' => 'Custom Moleskine Notebook',
                'price' => '₦15,000',
                'image' => 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?q=80&w=400&auto=format&fit=crop',
                'badge' => 'New'
            ],
            [
                'name' => 'Insulated Smart Flask',
                'price' => '₦18,500',
                'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=400&auto=format&fit=crop',
                'badge' => ''
            ]
        ];

        return View::renderTemplate('pages/customer/dashboard', 'customer', [
            'title' => 'Dashboard | Marigold Signature',
            'user' => $user,
            'greeting' => $greeting,
            'stats' => $stats,
            'recent_orders' => $recent_orders,
            'pending_quotes' => $pending_quotes,
            'recommended_products' => $recommended_products
        ]);
    }
}
