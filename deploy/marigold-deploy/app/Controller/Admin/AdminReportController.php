<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;

class AdminReportController extends Controller
{
    public function index()
    {
        $db = Model::getDB();

        // ─── Top-level metrics ───
        $revStmt = $db->query("SELECT COALESCE(SUM(grand_total), 0) AS total FROM orders WHERE status != 'cancelled' AND payment_status = 'paid'");
        $totalRevenue = (float)$revStmt->fetch()['total'];

        $ordersCount = (int)$db->query("SELECT COUNT(*) AS c FROM orders")->fetch()['c'];
        $avgOrderValue = $ordersCount > 0 ? round($totalRevenue / $ordersCount) : 0;

        $quotesConverted = (int)$db->query("SELECT COUNT(*) AS c FROM quotes WHERE status = 'converted'")->fetch()['c'];
        $quotesTotal = (int)$db->query("SELECT COUNT(*) AS c FROM quotes")->fetch()['c'];
        $quoteRate = $quotesTotal > 0 ? round(($quotesConverted / $quotesTotal) * 100) : 0;

        $newCustomers = (int)$db->query("SELECT COUNT(*) AS c FROM users WHERE deleted_at IS NULL")->fetch()['c'];

        $metrics = [
            'total_revenue'    => (int)round($totalRevenue),
            'orders_count'     => $ordersCount,
            'avg_order_value'  => $avgOrderValue,
            'quotes_converted' => $quotesConverted,
            'quotes_total'     => $quotesTotal,
            'quotes_rate'      => $quoteRate,
            'new_customers'    => $newCustomers,
        ];

        // ─── Sales & Revenue tab ───
        // Revenue trend: last 7 months (paid orders only)
        $chartLabels = [];
        $revenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $monthStart = date('Y-m-01 00:00:00', strtotime("first day of -$i months"));
            $monthEnd   = date('Y-m-t 23:59:59', strtotime("last day of -$i months"));
            $chartLabels[] = date('M Y', strtotime($monthStart));
            $stmt = $db->prepare("
                SELECT COALESCE(SUM(grand_total), 0) AS revenue
                FROM orders
                WHERE status != 'cancelled' AND payment_status = 'paid'
                  AND created_at BETWEEN :start AND :end
            ");
            $stmt->execute(['start' => $monthStart, 'end' => $monthEnd]);
            $revenueData[] = (float) $stmt->fetch()['revenue'];
        }

        // Payment method split
        $payRows = $db->query("
            SELECT COALESCE(NULLIF(payment_method, ''), 'unknown') AS method, COUNT(*) AS cnt,
                   COALESCE(SUM(grand_total), 0) AS total
            FROM orders
            WHERE status != 'cancelled'
            GROUP BY COALESCE(NULLIF(payment_method, ''), 'unknown')
            ORDER BY total DESC
        ")->fetchAll();
        $paymentMethods = [];
        foreach ($payRows as $p) {
            $paymentMethods[] = [
                'method' => ucfirst($p['method']),
                'count'  => (int)$p['cnt'],
                'total'  => (float)$p['total'],
            ];
        }

        // Order status split
        $statusRows = $db->query("
            SELECT status, COUNT(*) AS cnt
            FROM orders
            GROUP BY status
            ORDER BY cnt DESC
        ")->fetchAll();
        $orderStatuses = [];
        foreach ($statusRows as $s) {
            $orderStatuses[] = [
                'status' => ucfirst($s['status']),
                'count'  => (int)$s['cnt'],
            ];
        }

        // ─── Customers & Quotes tab ───
        $customerRows = $db->query("
            SELECT u.id, u.first_name, u.last_name, u.email, u.created_at,
                   (SELECT COUNT(*) FROM orders o JOIN order_addresses a ON a.order_id = o.id AND a.type = 'shipping' WHERE o.status != 'cancelled' AND LOWER(a.email) = LOWER(u.email)) AS orders,
                   (SELECT COALESCE(SUM(o.grand_total), 0) FROM orders o JOIN order_addresses a ON a.order_id = o.id AND a.type = 'shipping' WHERE o.status != 'cancelled' AND LOWER(a.email) = LOWER(u.email)) AS spent
            FROM users u
            WHERE u.deleted_at IS NULL
            ORDER BY spent DESC, u.created_at DESC
            LIMIT 8
        ")->fetchAll();
        $customers = [];
        foreach ($customerRows as $c) {
            $customers[] = [
                'name'  => trim(($c['first_name'] ?? '') . ' ' . ($c['last_name'] ?? '')) ?: $c['email'],
                'email' => $c['email'],
                'orders'=> (int)$c['orders'],
                'spent' => (float)$c['spent'],
                'since' => date('M Y', strtotime($c['created_at'])),
            ];
        }

        $quoteRows = $db->query("SELECT status, COUNT(*) AS cnt FROM quotes GROUP BY status ORDER BY cnt DESC")->fetchAll();
        $quoteStatuses = [];
        foreach ($quoteRows as $q) {
            $quoteStatuses[] = ['status' => ucfirst($q['status']), 'count' => (int)$q['cnt']];
        }

        // ─── Products & Categories tab ───
        $topRows = $db->query("
            SELECT oi.name, SUM(oi.quantity) AS sold, SUM(oi.subtotal) AS revenue
            FROM order_items oi
            GROUP BY oi.product_id, oi.name
            ORDER BY sold DESC
            LIMIT 5
        ")->fetchAll();
        $topProducts = [];
        foreach ($topRows as $t) {
            $topProducts[] = [
                'name'    => $t['name'],
                'sold'    => (int)$t['sold'],
                'revenue' => (int)round((float)$t['revenue']),
            ];
        }

        $prodRows = $db->query("
            SELECT p.id, p.name, p.sku, p.price, p.stock_quantity, p.status,
                   c.name AS category
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            WHERE p.deleted_at IS NULL
            ORDER BY p.created_at DESC
            LIMIT 8
        ")->fetchAll();
        $products = [];
        $totalStockValue = 0.0;
        foreach ($prodRows as $p) {
            $products[] = [
                'name'     => $p['name'],
                'sku'      => $p['sku'],
                'category' => $p['category'] ?: 'Uncategorised',
                'price'    => (float)$p['price'],
                'stock'    => (int)$p['stock_quantity'],
                'status'   => ucfirst($p['status']),
            ];
            $totalStockValue += (float)$p['price'] * (int)$p['stock_quantity'];
        }

        $categoryRows = $db->query("
            SELECT c.name, COUNT(p.id) AS products,
                   COALESCE(SUM(p.price * p.stock_quantity), 0) AS stock_value
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id AND p.deleted_at IS NULL
            GROUP BY c.id, c.name
            ORDER BY products DESC, c.name ASC
            LIMIT 8
        ")->fetchAll();
        $categories = [];
        foreach ($categoryRows as $cat) {
            $categories[] = [
                'name'        => $cat['name'],
                'products'    => (int)$cat['products'],
                'stock_value' => (float)$cat['stock_value'],
            ];
        }

        // ─── Marketing tab ───
        $subscriberTotal   = (int)$db->query("SELECT COUNT(*) AS c FROM newsletters")->fetch()['c'];
        $subscriberActive  = (int)$db->query("SELECT COUNT(*) AS c FROM newsletters WHERE status = 'subscribed'")->fetch()['c'];
        $subscriberNew     = (int)$db->query("SELECT COUNT(*) AS c FROM newsletters WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch()['c'];
        $sourceRows = $db->query("SELECT COALESCE(NULLIF(source, ''), 'Unknown') AS source, COUNT(*) AS cnt FROM newsletters GROUP BY COALESCE(NULLIF(source, ''), 'Unknown') ORDER BY cnt DESC")->fetchAll();
        $subSources = [];
        foreach ($sourceRows as $s) {
            $subSources[] = ['source' => $s['source'], 'count' => (int)$s['cnt']];
        }

        $couponTotal  = (int)$db->query("SELECT COUNT(*) AS c FROM coupons")->fetch()['c'];
        $couponActive = (int)$db->query("SELECT COUNT(*) AS c FROM coupons WHERE status = 'active'")->fetch()['c'];

        $msgTotal   = (int)$db->query("SELECT COUNT(*) AS c FROM contact_messages")->fetch()['c'];
        $msgUnread  = (int)$db->query("SELECT COUNT(*) AS c FROM contact_messages WHERE status = 'new'")->fetch()['c'];

        $marketing = [
            'subscribers_total'  => $subscriberTotal,
            'subscribers_active' => $subscriberActive,
            'subscribers_new'    => $subscriberNew,
            'subscriber_sources' => $subSources,
            'coupons_total'      => $couponTotal,
            'coupons_active'     => $couponActive,
            'messages_total'     => $msgTotal,
            'messages_unread'    => $msgUnread,
        ];

        return View::renderTemplate('pages/admin/reports/index', 'admin', [
            'title' => 'Reports & Analytics | Admin',
            'metrics' => $metrics,
            'topProducts' => $topProducts,
            'chartLabels' => $chartLabels,
            'revenueData' => $revenueData,
            'paymentMethods' => $paymentMethods,
            'orderStatuses' => $orderStatuses,
            'customers' => $customers,
            'quoteStatuses' => $quoteStatuses,
            'products' => $products,
            'totalStockValue' => $totalStockValue,
            'categories' => $categories,
            'marketing' => $marketing,
        ]);
    }
}
