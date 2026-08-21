<?php

namespace App\Controller\Customer;

use App\Core\Controller;
use App\Core\View;
use App\Core\Model;
use App\Core\Money;
use App\Core\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $customerId = $this->customerId();
        $db = Model::getDB();

        // Real authenticated user identity
        $user = [
            'first_name' => Session::get('user_first_name') ?: 'Valued',
            'last_name'  => Session::get('user_last_name') ?: 'Customer',
        ];

        $hour = date('H');
        if ($hour < 12) {
            $greeting = 'Good morning';
        } elseif ($hour < 17) {
            $greeting = 'Good afternoon';
        } else {
            $greeting = 'Good evening';
        }

        // Stats scoped to the authenticated customer
        $orderStats = $db->prepare("
            SELECT
                SUM(status IN ('pending', 'processing')) AS pending,
                SUM(status = 'completed')               AS completed
            FROM orders
            WHERE customer_id = :customer_id
        ");
        $orderStats->execute(['customer_id' => $customerId]);
        $orderRow = $orderStats->fetch();

        $quoteCount = $db->prepare("
            SELECT COUNT(*) FROM quotes
            WHERE customer_id = :customer_id
              AND status IN ('pending', 'reviewed')
        ");
        $quoteCount->execute(['customer_id' => $customerId]);

        $wishlistCount = $db->prepare("
            SELECT COUNT(*) FROM wishlists WHERE customer_id = :customer_id
        ");
        $wishlistCount->execute(['customer_id' => $customerId]);

        $stats = [
            'pending_orders'   => (int)($orderRow['pending'] ?? 0),
            'completed_orders' => (int)($orderRow['completed'] ?? 0),
            'pending_quotes'   => (int)$quoteCount->fetchColumn(),
            'wishlist_count'   => (int)$wishlistCount->fetchColumn(),
        ];

        // Recent orders scoped to the customer
        $recentStmt = $db->prepare("
            SELECT order_number, created_at AS date, status, grand_total AS total
            FROM orders
            WHERE customer_id = :customer_id
            ORDER BY created_at DESC
            LIMIT 5
        ");
        $recentStmt->execute(['customer_id' => $customerId]);
        $recent_orders = array_map(fn($o) => [
            'order_number' => $o['order_number'],
            'date'         => $o['date'],
            'status'       => ucfirst($o['status']),
            'total'        => Money::formatSession((float)$o['total']),
        ], $recentStmt->fetchAll());

        // Pending quotes scoped to the customer
        $quoteStmt = $db->prepare("
            SELECT q.quote_number, q.created_at AS date, q.status, COUNT(qi.id) AS items
            FROM quotes q
            LEFT JOIN quote_items qi ON qi.quote_id = q.id
            WHERE q.customer_id = :customer_id
              AND q.status IN ('pending', 'reviewed')
            GROUP BY q.id
            ORDER BY q.created_at DESC
            LIMIT 5
        ");
        $quoteStmt->execute(['customer_id' => $customerId]);
        $pending_quotes = array_map(fn($q) => [
            'quote_number' => $q['quote_number'],
            'date'         => $q['date'],
            'status'       => ucwords(str_replace('_', ' ', $q['status'])),
            'items'        => (int)$q['items'],
        ], $quoteStmt->fetchAll());

        // Recommended products (unchanged)
        $recStmt = $db->query("
            SELECT products.name, products.price, products.sale_price, products.is_new, products.is_best_seller, products.image
            FROM products
            LEFT JOIN product_images ON product_images.product_id = products.id AND product_images.is_featured = 1
            WHERE products.deleted_at IS NULL AND products.status = 'published'
            ORDER BY products.is_featured DESC, products.created_at DESC
            LIMIT 4
        ");
        $recommended_products = array_map(fn($p) => [
            'name'  => $p['name'],
            'price' => Money::formatSession((float)($p['sale_price'] ?: $p['price'])),
            'image' => $p['image'] ?: '/ms-logo-icon.png',
            'badge' => $p['is_best_seller'] ? 'Bestseller' : ($p['is_new'] ? 'New' : ''),
        ], $recStmt->fetchAll());

        // Assigned account manager (real user with the account-manager role)
        $manager = null;
        $mgStmt = $db->prepare("
            SELECT u.first_name, u.last_name, u.email, u.avatar
            FROM customers c
            JOIN users u ON u.id = c.account_manager_id
            WHERE c.id = :customer_id
              AND u.deleted_at IS NULL
              AND u.status = 'active'
            LIMIT 1
        ");
        $mgStmt->execute(['customer_id' => $customerId]);
        $mgRow = $mgStmt->fetch();
        if ($mgRow) {
            $manager = [
                'name'   => trim(($mgRow['first_name'] ?? '') . ' ' . ($mgRow['last_name'] ?? '')),
                'email'  => $mgRow['email'] ?? '',
                'avatar' => $mgRow['avatar'] ?? '',
            ];
        }

        return View::renderTemplate('pages/customer/dashboard', 'customer', [
            'title' => 'Dashboard | Marigold Signature',
            'user' => $user,
            'greeting' => $greeting,
            'stats' => $stats,
            'recent_orders' => $recent_orders,
            'pending_quotes' => $pending_quotes,
            'recommended_products' => $recommended_products,
            'account_manager' => $manager,
        ]);
    }

    private function customerId(): int
    {
        $stmt = Model::getDB()->prepare("SELECT id FROM customers WHERE user_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => Session::get('user_id')]);
        $id = $stmt->fetchColumn();
        if (!$id) {
            throw new \Exception('Customer profile not found', 403);
        }
        return (int)$id;
    }
}
