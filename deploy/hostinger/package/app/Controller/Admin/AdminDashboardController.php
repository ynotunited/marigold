<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $db = Model::getDB();

        // ─── KPIs ───
        $totalRevenue = (float) $db->query("SELECT COALESCE(SUM(grand_total), 0) AS total FROM orders WHERE status != 'cancelled' AND payment_status = 'paid'")->fetch()['total'];
        $totalOrders  = (int) $db->query("SELECT COUNT(*) AS c FROM orders")->fetch()['c'];
        $paidOrders   = (int) $db->query("SELECT COUNT(*) AS c FROM orders WHERE payment_status = 'paid' AND status != 'cancelled'")->fetch()['c'];
        $pendingQuotes = (int) $db->query("SELECT COUNT(*) AS c FROM quotes WHERE status IN ('pending','reviewed')")->fetch()['c'];
        $totalProducts = (int) $db->query("SELECT COUNT(*) AS c FROM products WHERE deleted_at IS NULL AND status = 'published'")->fetch()['c'];
        $customers     = (int) $db->query("SELECT COUNT(*) AS c FROM users WHERE deleted_at IS NULL")->fetch()['c'];
        $subscribers   = (int) $db->query("SELECT COUNT(*) AS c FROM newsletters WHERE status = 'subscribed'")->fetch()['c'];

        $kpis = [
            ['label' => 'Total Revenue', 'value' => '₦' . number_format($totalRevenue), 'change' => $paidOrders . ' paid', 'up' => true, 'icon' => 'trending-up', 'color' => 'gold'],
            ['label' => 'Total Orders', 'value' => number_format($totalOrders), 'change' => 'all time', 'up' => true, 'icon' => 'package', 'color' => 'blue'],
            ['label' => 'Pending Quotes', 'value' => number_format($pendingQuotes), 'change' => $pendingQuotes . ' awaiting review', 'up' => true, 'icon' => 'file-text', 'color' => 'yellow'],
            ['label' => 'Products', 'value' => number_format($totalProducts), 'change' => 'published', 'up' => true, 'icon' => 'box', 'color' => 'purple'],
            ['label' => 'Customers', 'value' => number_format($customers), 'change' => 'registered', 'up' => true, 'icon' => 'users', 'color' => 'green'],
            ['label' => 'Newsletter Subs', 'value' => number_format($subscribers), 'change' => 'subscribed', 'up' => true, 'icon' => 'mail', 'color' => 'red'],
        ];

        // ─── Chart data: last 7 months ───
        $chartLabels = [];
        $revenueData = [];
        $ordersData  = [];
        for ($i = 6; $i >= 0; $i--) {
            $monthStart = date('Y-m-01 00:00:00', strtotime("first day of -$i months"));
            $monthEnd   = date('Y-m-t 23:59:59', strtotime("last day of -$i months"));
            $label      = date('M', strtotime($monthStart));
            $chartLabels[] = $label;
            $stmt = $db->prepare("
                SELECT COALESCE(SUM(CASE WHEN status != 'cancelled' AND payment_status = 'paid' THEN grand_total ELSE 0 END), 0) AS revenue,
                       COUNT(*) AS cnt
                FROM orders
                WHERE created_at BETWEEN :start AND :end
            ");
            $stmt->execute(['start' => $monthStart, 'end' => $monthEnd]);
            $row = $stmt->fetch();
            $revenueData[] = (float) $row['revenue'];
            $ordersData[]  = (int) $row['cnt'];
        }

        // ─── Top categories by product count ───
        $categoryRows = $db->query("
            SELECT c.name, COUNT(p.id) AS products
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id AND p.deleted_at IS NULL
            WHERE c.status = 'active'
            GROUP BY c.id, c.name
            ORDER BY products DESC
            LIMIT 6
        ")->fetchAll();
        $palette = ['#C8A96E', '#60a5fa', '#34d399', '#a78bfa', '#f472b6', '#fbbf24'];
        $categories = [];
        $catTotal = array_sum(array_column($categoryRows, 'products')) ?: 1;
        foreach ($categoryRows as $i => $c) {
            $pct = round(($c['products'] / $catTotal) * 100, 1);
            $categories[] = ['name' => $c['name'], 'sales' => $pct, 'color' => $palette[$i % count($palette)]];
        }
        if (!$categories) {
            $categories[] = ['name' => 'No products yet', 'sales' => 100, 'color' => '#2a2a2a'];
        }

        // ─── Recent activity (orders, quotes, contact messages) ───
        $activity = [];
        $orderRows = $db->query("
            SELECT o.order_number, o.grand_total, o.created_at, a.first_name, a.last_name
            FROM orders o
            LEFT JOIN order_addresses a ON a.order_id = o.id AND a.type = 'shipping'
            ORDER BY o.created_at DESC LIMIT 4
        ")->fetchAll();
        foreach ($orderRows as $o) {
            $name = trim(($o['first_name'] ?? '') . ' ' . ($o['last_name'] ?? '')) ?: 'Guest';
            $activity[] = [
                'icon' => 'package',
                'color' => 'blue',
                'message' => 'New order <strong>' . htmlspecialchars($o['order_number']) . '</strong> placed by ' . htmlspecialchars($name) . ' (₦' . number_format((float)$o['grand_total']) . ')',
                'time' => $this->timeAgo($o['created_at']),
            ];
        }
        $quoteRows = $db->query("
            SELECT q.quote_number, q.created_at
            FROM quotes q
            ORDER BY q.created_at DESC LIMIT 2
        ")->fetchAll();
        foreach ($quoteRows as $q) {
            $activity[] = [
                'icon' => 'file-text',
                'color' => 'yellow',
                'message' => 'Quote <strong>' . htmlspecialchars($q['quote_number']) . '</strong> submitted',
                'time' => $this->timeAgo($q['created_at']),
            ];
        }
        $msgRows = $db->query("SELECT name, subject, created_at FROM contact_messages ORDER BY created_at DESC LIMIT 2")->fetchAll();
        foreach ($msgRows as $m) {
            $activity[] = [
                'icon' => 'message-square',
                'color' => 'purple',
                'message' => 'Contact message from <strong>' . htmlspecialchars($m['name']) . '</strong> — ' . htmlspecialchars($m['subject'] ?: 'no subject'),
                'time' => $this->timeAgo($m['created_at']),
            ];
        }

        // ─── Recent orders ───
        $recent = $db->query("
            SELECT o.order_number, o.grand_total, o.status, o.created_at, a.first_name, a.last_name
            FROM orders o
            LEFT JOIN order_addresses a ON a.order_id = o.id AND a.type = 'shipping'
            ORDER BY o.created_at DESC LIMIT 6
        ")->fetchAll();
        $recent_orders = [];
        foreach ($recent as $o) {
            $recent_orders[] = [
                'id'       => $o['order_number'],
                'customer' => trim(($o['first_name'] ?? '') . ' ' . ($o['last_name'] ?? '')) ?: 'Guest',
                'total'    => '₦' . number_format((float)$o['grand_total']),
                'status'   => ucfirst($o['status']),
                'date'     => date('M j', strtotime($o['created_at'])),
            ];
        }

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

    private function timeAgo(string $datetime): string
    {
        $ts = strtotime($datetime);
        if ($ts === false) return '';
        $diff = time() - $ts;
        if ($diff < 60) return 'just now';
        if ($diff < 3600) return floor($diff / 60) . ' min ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hr ago';
        if ($diff < 604800) return floor($diff / 86400) . ' days ago';
        return date('M j', $ts);
    }
}
