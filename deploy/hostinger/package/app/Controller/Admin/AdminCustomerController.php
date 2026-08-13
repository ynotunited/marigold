<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;

class AdminCustomerController extends Controller
{
    public function index()
    {
        $db = Model::getDB();

        // Registered customers only (users with the 'customer' role — staff excluded).
        $rows = $db->query("
            SELECT
                u.id,
                u.first_name,
                u.last_name,
                u.email,
                u.phone,
                u.status AS user_status,
                u.created_at,
                c.company_name,
                c.status AS corp_status
            FROM users u
            LEFT JOIN customers c ON c.user_id = u.id
            WHERE u.deleted_at IS NULL
              AND EXISTS (
                  SELECT 1
                  FROM user_roles ur
                  JOIN roles r ON r.id = ur.role_id
                  WHERE ur.user_id = u.id AND r.slug = 'customer'
              )
            ORDER BY u.created_at DESC
        ")->fetchAll();

        // Order aggregates per customer (email match against order_addresses).
        $aggRows = $db->query("
            SELECT
                a.email,
                COUNT(o.id) AS orders_count,
                COALESCE(SUM(o.grand_total), 0) AS total_spent
            FROM order_addresses a
            JOIN orders o ON o.id = a.order_id AND o.status != 'cancelled'
            WHERE a.type = 'shipping' AND a.email IS NOT NULL AND a.email != ''
            GROUP BY a.email
        ")->fetchAll();
        $agg = [];
        foreach ($aggRows as $r) {
            $agg[strtolower($r['email'])] = ['orders' => (int)$r['orders_count'], 'spent' => (float)$r['total_spent']];
        }

        $customers = [];
        foreach ($rows as $r) {
            $email = strtolower($r['email'] ?? '');
            $orders = $agg[$email]['orders'] ?? 0;
            $spent  = $agg[$email]['spent'] ?? 0.0;
            $customers[] = [
                'id'         => $r['id'],
                'name'       => trim(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? '')) ?: $r['email'],
                'email'      => $r['email'],
                'company'    => $r['company_name'] ?: '',
                'type'       => $r['company_name'] ? 'Corporate' : 'Individual',
                'status'     => ucfirst($r['corp_status'] ?: $r['user_status']),
                'orders'     => $orders,
                'spent'      => $spent,
                'registered' => date('Y-m-d', strtotime($r['created_at'])),
            ];
        }

        return View::renderTemplate('pages/admin/customers/index', 'admin', [
            'title' => 'Customers | Admin',
            'customers' => $customers,
        ]);
    }

    public function show($id)
    {
        $db = Model::getDB();

        $stmt = $db->prepare("
            SELECT
                u.id,
                u.first_name,
                u.last_name,
                u.email,
                u.phone,
                u.status AS user_status,
                u.last_login_at,
                u.created_at,
                c.company_name,
                c.industry,
                c.notes AS internal_notes,
                c.status AS corp_status
            FROM users u
            LEFT JOIN customers c ON c.user_id = u.id
            WHERE u.id = :id
              AND u.deleted_at IS NULL
              AND EXISTS (
                  SELECT 1
                  FROM user_roles ur
                  JOIN roles r ON r.id = ur.role_id
                  WHERE ur.user_id = u.id AND r.slug = 'customer'
              )
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $u = $stmt->fetch();

        if (!$u) {
            http_response_code(404);
            return View::renderTemplate('pages/public/errors/404', 'main', ['title' => 'Customer not found']);
        }

        $email = strtolower($u['email']);
        $customerName = trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? '')) ?: $u['email'];

        // Order stats
        $stmt2 = $db->prepare("
            SELECT COUNT(*) AS cnt, COALESCE(SUM(grand_total), 0) AS total
            FROM orders o
            JOIN order_addresses a ON a.order_id = o.id AND a.type = 'shipping'
            WHERE o.status != 'cancelled' AND LOWER(a.email) = :email
        ");
        $stmt2->execute(['email' => $email]);
        $stats = $stmt2->fetch();
        $totalOrders = (int)$stats['cnt'];
        $lifetime = (float)$stats['total'];

        // Recent orders
        $ordStmt = $db->prepare("
            SELECT o.order_number, o.grand_total, o.status, o.created_at,
                   a.email
            FROM orders o
            LEFT JOIN order_addresses a ON a.order_id = o.id AND a.type = 'shipping'
            WHERE LOWER(COALESCE(a.email, '')) = :email
            ORDER BY o.created_at DESC LIMIT 6
        ");
        $ordStmt->execute(['email' => $email]);
        $ordRows = $ordStmt->fetchAll();
        $recent_orders = [];
        foreach ($ordRows as $o) {
            $recent_orders[] = [
                'id'     => $o['order_number'],
                'date'   => date('M j, Y', strtotime($o['created_at'])),
                'status' => ucfirst($o['status']),
                'total'  => (float)$o['grand_total'],
            ];
        }

        // Quotes (by customer email or user id)
        $qStmt = $db->prepare("
            SELECT q.quote_number, q.status, q.created_at,
                   (SELECT COUNT(*) FROM quote_items qi WHERE qi.quote_id = q.id) AS items
            FROM quotes q
            LEFT JOIN users us ON us.id = q.customer_id
            WHERE q.customer_id = :uid
               OR LOWER(us.email) = :email
            ORDER BY q.created_at DESC LIMIT 6
        ");
        $qStmt->execute(['uid' => $id, 'email' => $email]);
        $qRows = $qStmt->fetchAll();
        $recent_quotes = [];
        foreach ($qRows as $q) {
            $recent_quotes[] = [
                'id'     => $q['quote_number'],
                'date'   => date('M j, Y', strtotime($q['created_at'])),
                'items'  => (int)$q['items'],
                'status' => ucfirst($q['status']),
            ];
        }

        // Addresses
        $addrStmt = $db->prepare("
            SELECT a.type, a.address_line1, a.address_line2, a.city, a.state
            FROM order_addresses a
            JOIN orders o ON o.id = a.order_id
            WHERE LOWER(COALESCE(a.email, '')) = :email
            GROUP BY a.type, a.address_line1, a.address_line2, a.city, a.state
            ORDER BY MAX(o.created_at) DESC LIMIT 4
        ");
        $addrStmt->execute(['email' => $email]);
        $addrRows = $addrStmt->fetchAll();
        $addresses = [];
        foreach ($addrRows as $i => $a) {
            $addresses[] = [
                'label'  => ($a['type'] === 'shipping' ? 'Shipping' : 'Billing') . ($i === 0 ? ' (Default)' : ''),
                'street' => trim(($a['address_line1'] ?? '') . ' ' . ($a['address_line2'] ?? '')),
                'city'   => $a['city'] ?? '',
                'state'  => $a['state'] ?? '',
            ];
        }

        $customer = [
            'id'              => $u['id'],
            'name'            => $customerName,
            'email'           => $u['email'],
            'phone'           => $u['phone'] ?: '—',
            'company'         => $u['company_name'] ?: '—',
            'type'            => $u['company_name'] ? 'Corporate' : 'Individual',
            'status'          => ucfirst($u['corp_status'] ?: $u['user_status']),
            'registered'      => date('M j, Y', strtotime($u['created_at'])),
            'last_login'      => $u['last_login_at'] ? date('M j, Y', strtotime($u['last_login_at'])) : 'Never',
            'lifetime_value'  => $lifetime,
            'total_orders'    => $totalOrders,
            'avg_order_value' => $totalOrders > 0 ? round($lifetime / $totalOrders) : 0,
            'addresses'       => $addresses,
            'recent_orders'   => $recent_orders,
            'recent_quotes'   => $recent_quotes,
            'internal_notes'  => $u['internal_notes'] ?: '',
        ];

        return View::renderTemplate('pages/admin/customers/show', 'admin', [
            'title' => "Customer {$customer['name']} | Admin",
            'customer' => $customer,
        ]);
    }
}
