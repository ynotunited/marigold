<?php

namespace App\Controller\Admin;


use App\Core\Controller;
use App\Core\View;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Mock KPI stats
        $kpis = [
            ['label' => 'Total Revenue', 'value' => '₦18,430,000', 'change' => '+12.4%', 'up' => true, 'icon' => 'trending-up', 'color' => 'gold'],
            ['label' => 'Total Orders', 'value' => '342', 'change' => '+8.1%', 'up' => true, 'icon' => 'package', 'color' => 'blue'],
            ['label' => 'Pending Quotes', 'value' => '17', 'change' => '+5 new', 'up' => true, 'icon' => 'file-text', 'color' => 'yellow'],
            ['label' => 'Total Products', 'value' => '214', 'change' => '+3 this week', 'up' => true, 'icon' => 'box', 'color' => 'purple'],
            ['label' => 'Customers', 'value' => '1,284', 'change' => '+22 this month', 'up' => true, 'icon' => 'users', 'color' => 'green'],
            ['label' => 'Visitors (30d)', 'value' => '9,521', 'change' => '-2.3%', 'up' => false, 'icon' => 'eye', 'color' => 'red'],
        ];

        // Mock chart data (monthly revenue - last 7 months)
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
        $revenueData = [8200000, 11500000, 9800000, 14200000, 12800000, 16400000, 18430000];
        $ordersData  = [124, 189, 155, 238, 204, 291, 342];

        // Mock category breakdown
        $categories = [
            ['name' => 'Stationery', 'sales' => 42, 'color' => '#C8A96E'],
            ['name' => 'Tech Accessories', 'sales' => 28, 'color' => '#60a5fa'],
            ['name' => 'Drinkware', 'sales' => 18, 'color' => '#34d399'],
            ['name' => 'Apparel', 'sales' => 12, 'color' => '#a78bfa'],
        ];

        // Mock recent activity
        $activity = [
            ['icon' => 'package', 'color' => 'blue', 'message' => 'New order <strong>ORD-9824</strong> placed by Adaeze Williams', 'time' => '5 min ago'],
            ['icon' => 'file-text', 'color' => 'yellow', 'message' => 'Quote <strong>QT-1046</strong> submitted by TechCorp Nigeria', 'time' => '22 min ago'],
            ['icon' => 'user-plus', 'color' => 'green', 'message' => 'New customer registered: <strong>Emeka Eze</strong>', 'time' => '1 hr ago'],
            ['icon' => 'check-circle', 'color' => 'green', 'message' => 'Order <strong>ORD-9810</strong> marked as Delivered', 'time' => '3 hr ago'],
            ['icon' => 'alert-circle', 'color' => 'red', 'message' => 'Low stock alert: <strong>USB-C Hub</strong> (2 units left)', 'time' => '5 hr ago'],
            ['icon' => 'tag', 'color' => 'purple', 'message' => 'Product <strong>Executive Leather Folio</strong> updated', 'time' => 'Yesterday'],
        ];

        // Mock recent orders
        $recent_orders = [
            ['id' => 'ORD-9824', 'customer' => 'Adaeze Williams', 'total' => '₦385,000', 'status' => 'Pending', 'date' => date('M j')],
            ['id' => 'ORD-9823', 'customer' => 'David Okon', 'total' => '₦450,000', 'status' => 'Processing', 'date' => date('M j', strtotime('-1 day'))],
            ['id' => 'ORD-9820', 'customer' => 'Seun Adeyemi', 'total' => '₦1,200,000', 'status' => 'Shipped', 'date' => date('M j', strtotime('-2 days'))],
            ['id' => 'ORD-9815', 'customer' => 'Tunde Bakare', 'total' => '₦72,000', 'status' => 'Completed', 'date' => date('M j', strtotime('-3 days'))],
            ['id' => 'ORD-9810', 'customer' => 'David Okon', 'total' => '₦1,200,000', 'status' => 'Completed', 'date' => date('M j', strtotime('-4 days'))],
        ];

        return View::renderTemplate('pages/admin/dashboard', 'admin', [
            'title' => 'Admin Dashboard | Marigold Signature',
            'kpis' => $kpis,
            'chartLabels' => $chartLabels,
            'revenueData' => $revenueData,
            'ordersData' => $ordersData,
            'categories' => $categories,
            'activity' => $activity,
            'recent_orders' => $recent_orders,
        ]);
    }
}
