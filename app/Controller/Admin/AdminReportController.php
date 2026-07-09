<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class AdminReportController extends Controller
{
    public function index()
    {
        // Mock data for the unified reports dashboard
        $metrics = [
            'total_revenue' => 14500000,
            'orders_count' => 124,
            'avg_order_value' => 116935,
            'quotes_converted' => 45,
            'new_customers' => 28,
        ];

        $topProducts = [
            ['name' => 'Custom Leather Notebook', 'sold' => 450, 'revenue' => 4500000],
            ['name' => 'Vacuum Flask Set', 'sold' => 320, 'revenue' => 3200000],
            ['name' => 'Executive Pen Collection', 'sold' => 180, 'revenue' => 1800000],
            ['name' => 'Corporate Gift Box Tier 1', 'sold' => 85, 'revenue' => 4250000],
        ];

        return View::renderTemplate('pages/admin/reports/index', 'admin', [
            'title' => 'Reports & Analytics | Admin',
            'metrics' => $metrics,
            'topProducts' => $topProducts
        ]);
    }
}
