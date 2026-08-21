<?php

namespace App\Service;

use App\Core\Model;

/**
 * GDPR data export service — collects every piece of data held on a user
 * across all tables and produces a structured JSON + HTML report.
 */
class GdprExportService
{
    /**
     * Generate a complete data export for a user.
     *
     * @return array Structured data ready for JSON encoding or view rendering.
     */
    public static function export(int $userId): array
    {
        $db = Model::getDB();

        // ── User profile ──
        $stmt = $db->prepare("
            SELECT id, first_name, last_name, email, phone, status, timezone,
                   created_at, last_login_at, deleted_at
            FROM users WHERE id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();
        if (!$user) return [];

        // ── Roles ──
        $stmt = $db->prepare("
            SELECT r.name, r.slug FROM roles r
            JOIN user_roles ur ON ur.role_id = r.id
            WHERE ur.user_id = :uid
        ");
        $stmt->execute(['uid' => $userId]);
        $roles = $stmt->fetchAll();

        // ── Customer profiles ──
        $stmt = $db->prepare("SELECT * FROM customers WHERE user_id = :uid");
        $stmt->execute(['uid' => $userId]);
        $customers = $stmt->fetchAll();
        $customerIds = array_column($customers, 'id');

        // ── Orders (via customer_id) ──
        $orders = [];
        $orderIds = [];
        if ($customerIds) {
            $ph = implode(',', array_fill(0, count($customerIds), '?'));
            $stmt = $db->prepare("
                SELECT o.* FROM orders o
                WHERE o.customer_id IN ($ph)
                ORDER BY o.created_at DESC
            ");
            $stmt->execute($customerIds);
            $orders = $stmt->fetchAll();
            $orderIds = array_column($orders, 'id');
        }

        // ── Order items ──
        $orderItems = [];
        if ($orderIds) {
            $ph = implode(',', array_fill(0, count($orderIds), '?'));
            $stmt = $db->prepare("SELECT * FROM order_items WHERE order_id IN ($ph)");
            $stmt->execute($orderIds);
            $orderItems = $stmt->fetchAll();
        }

        // ── Order addresses ──
        $orderAddresses = [];
        if ($orderIds) {
            $ph = implode(',', array_fill(0, count($orderIds), '?'));
            $stmt = $db->prepare("SELECT * FROM order_addresses WHERE order_id IN ($ph)");
            $stmt->execute($orderIds);
            $orderAddresses = $stmt->fetchAll();
        }

        // ── Payment intents ──
        $paymentIntents = [];
        if ($orderIds) {
            $ph = implode(',', array_fill(0, count($orderIds), '?'));
            $stmt = $db->prepare("SELECT * FROM payment_intents WHERE order_id IN ($ph)");
            $stmt->execute($orderIds);
            $paymentIntents = $stmt->fetchAll();
        }

        // ── Quotes ──
        $quotes = [];
        $quoteIds = [];
        if ($customerIds) {
            $ph = implode(',', array_fill(0, count($customerIds), '?'));
            $stmt = $db->prepare("SELECT * FROM quotes WHERE customer_id IN ($ph) ORDER BY created_at DESC");
            $stmt->execute($customerIds);
            $quotes = $stmt->fetchAll();
            $quoteIds = array_column($quotes, 'id');
        }

        // ── Quote items ──
        $quoteItems = [];
        if ($quoteIds) {
            $ph = implode(',', array_fill(0, count($quoteIds), '?'));
            $stmt = $db->prepare("SELECT * FROM quote_items WHERE quote_id IN ($ph)");
            $stmt->execute($quoteIds);
            $quoteItems = $stmt->fetchAll();
        }

        // ── Wishlists ──
        $wishlists = [];
        if ($customerIds) {
            $ph = implode(',', array_fill(0, count($customerIds), '?'));
            $stmt = $db->prepare("
                SELECT w.*, p.name AS product_name FROM wishlists w
                LEFT JOIN products p ON p.id = w.product_id
                WHERE w.customer_id IN ($ph)
            ");
            $stmt->execute($customerIds);
            $wishlists = $stmt->fetchAll();
        }

        // ── Reviews ──
        $reviews = [];
        if ($customerIds) {
            $ph = implode(',', array_fill(0, count($customerIds), '?'));
            $stmt = $db->prepare("
                SELECT r.*, p.name AS product_name FROM reviews r
                LEFT JOIN products p ON p.id = r.product_id
                WHERE r.customer_id IN ($ph)
            ");
            $stmt->execute($customerIds);
            $reviews = $stmt->fetchAll();
        }

        // ── Notifications ──
        $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = :uid ORDER BY created_at DESC");
        $stmt->execute(['uid' => $userId]);
        $notifications = $stmt->fetchAll();

        // ── Scheduled notifications ──
        $stmt = $db->prepare("SELECT * FROM scheduled_notifications WHERE user_id = :uid");
        $stmt->execute(['uid' => $userId]);
        $scheduledNotifications = $stmt->fetchAll();

        // ── Newsletter subscription ──
        $stmt = $db->prepare("SELECT * FROM newsletters WHERE LOWER(email) = LOWER(:email) LIMIT 1");
        $stmt->execute(['email' => $user['email']]);
        $newsletter = $stmt->fetch() ?: null;

        // ── Contact messages (by email) ──
        $stmt = $db->prepare("SELECT * FROM contact_messages WHERE LOWER(email) = LOWER(:email) ORDER BY created_at DESC");
        $stmt->execute(['email' => $user['email']]);
        $contactMessages = $stmt->fetchAll();

        // ── Audit log (will be deleted on hard-delete, but include in export) ──
        $stmt = $db->prepare("SELECT * FROM audit_log WHERE user_id = :uid ORDER BY created_at DESC LIMIT 500");
        $stmt->execute(['uid' => $userId]);
        $auditLog = $stmt->fetchAll();

        // ── Customer addresses (from orders) ──
        $customerAddresses = [];
        if ($customerIds) {
            $ph = implode(',', array_fill(0, count($customerIds), '?'));
            $stmt = $db->prepare("SELECT * FROM customer_addresses WHERE customer_id IN ($ph)");
            $stmt->execute($customerIds);
            $customerAddresses = $stmt->fetchAll();
        }

        return [
            'export_version' => '1.0',
            'generated_at' => date('c'),
            'platform' => 'Marigold Signature',
            'user' => [
                'id' => (int)$user['id'],
                'email' => $user['email'],
                'name' => trim($user['first_name'] . ' ' . $user['last_name']),
                'phone' => $user['phone'],
                'status' => $user['status'],
                'timezone' => $user['timezone'],
                'created_at' => $user['created_at'],
                'last_login_at' => $user['last_login_at'],
                'deleted_at' => $user['deleted_at'],
            ],
            'roles' => $roles,
            'customer_profiles' => $customers,
            'orders' => $orders,
            'order_items' => $orderItems,
            'order_addresses' => $orderAddresses,
            'payment_intents' => $paymentIntents,
            'quotes' => $quotes,
            'quote_items' => $quoteItems,
            'wishlists' => $wishlists,
            'reviews' => $reviews,
            'customer_addresses' => $customerAddresses,
            'notifications' => $notifications,
            'scheduled_notifications' => $scheduledNotifications,
            'newsletter_subscription' => $newsletter,
            'contact_messages' => $contactMessages,
            'audit_trail' => $auditLog,
        ];
    }

    /**
     * Generate a human-readable HTML summary of the export.
     */
    public static function exportHtml(int $userId): string
    {
        $data = self::export($userId);
        if (!$data) return '<p>No data found for this user.</p>';

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>GDPR Data Export — ' . htmlspecialchars($data['user']['email']) . '</title>';
        $html .= '<style>body{font-family:system-ui,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem;color:#1a1a1a;line-height:1.6;}';
        $html .= 'h1{font-size:1.5rem;border-bottom:2px solid #C89B3C;padding-bottom:0.5rem;}';
        $html .= 'h2{font-size:1.2rem;color:#C89B3C;margin-top:2rem;}';
        $html .= 'table{width:100%;border-collapse:collapse;margin:1rem 0;font-size:0.9rem;}';
        $html .= 'th,td{padding:0.5rem;border:1px solid #ddd;text-align:left;}';
        $html .= 'th{background:#f5f5f5;font-weight:600;}';
        $html .= '.meta{color:#666;font-size:0.85rem;}';
        $html .= '.badge{display:inline-block;padding:2px 8px;border-radius:4px;font-size:0.75rem;font-weight:600;}';
        $html .= '.badge-active{background:#d4edda;color:#155724;}.badge-pending{background:#fff3cd;color:#856404;}';
        $html .= '</style></head><body>';

        $html .= '<h1>GDPR Data Export</h1>';
        $html .= '<p class="meta">Generated: ' . htmlspecialchars($data['generated_at']) . ' | Platform: ' . htmlspecialchars($data['platform']) . '</p>';

        // User profile
        $u = $data['user'];
        $html .= '<h2>User Profile</h2><table>';
        $html .= '<tr><th>ID</th><td>' . $u['id'] . '</td></tr>';
        $html .= '<tr><th>Name</th><td>' . htmlspecialchars($u['name']) . '</td></tr>';
        $html .= '<tr><th>Email</th><td>' . htmlspecialchars($u['email']) . '</td></tr>';
        $html .= '<tr><th>Phone</th><td>' . htmlspecialchars($u['phone'] ?: '—') . '</td></tr>';
        $html .= '<tr><th>Status</th><td>' . htmlspecialchars($u['status']) . '</td></tr>';
        $html .= '<tr><th>Registered</th><td>' . htmlspecialchars($u['created_at']) . '</td></tr>';
        $html .= '<tr><th>Last Login</th><td>' . htmlspecialchars($u['last_login_at'] ?: 'Never') . '</td></tr>';
        $html .= '</table>';

        // Roles
        if ($data['roles']) {
            $html .= '<h2>Roles</h2><ul>';
            foreach ($data['roles'] as $r) {
                $html .= '<li>' . htmlspecialchars($r['name']) . ' (' . htmlspecialchars($r['slug']) . ')</li>';
            }
            $html .= '</ul>';
        }

        // Orders
        if ($data['orders']) {
            $html .= '<h2>Orders (' . count($data['orders']) . ')</h2><table>';
            $html .= '<tr><th>Order #</th><th>Date</th><th>Status</th><th>Payment</th><th>Total</th></tr>';
            foreach ($data['orders'] as $o) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($o['order_number']) . '</td>';
                $html .= '<td>' . htmlspecialchars($o['created_at']) . '</td>';
                $html .= '<td>' . htmlspecialchars(ucfirst($o['status'])) . '</td>';
                $html .= '<td>' . htmlspecialchars(ucfirst($o['payment_status'])) . '</td>';
                $html .= '<td>' . htmlspecialchars(number_format((float)$o['grand_total'], 2)) . ' ' . htmlspecialchars($o['currency'] ?? 'NGN') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
        }

        // Quotes
        if ($data['quotes']) {
            $html .= '<h2>Quotes (' . count($data['quotes']) . ')</h2><table>';
            $html .= '<tr><th>Quote #</th><th>Date</th><th>Status</th></tr>';
            foreach ($data['quotes'] as $q) {
                $html .= '<tr><td>' . htmlspecialchars($q['quote_number']) . '</td>';
                $html .= '<td>' . htmlspecialchars($q['created_at']) . '</td>';
                $html .= '<td>' . htmlspecialchars(ucfirst($q['status'])) . '</td></tr>';
            }
            $html .= '</table>';
        }

        // Reviews
        if ($data['reviews']) {
            $html .= '<h2>Reviews (' . count($data['reviews']) . ')</h2><table>';
            $html .= '<tr><th>Product</th><th>Rating</th><th>Date</th></tr>';
            foreach ($data['reviews'] as $r) {
                $html .= '<tr><td>' . htmlspecialchars($r['product_name'] ?? '—') . '</td>';
                $html .= '<td>' . ((int)($r['rating'] ?? 0)) . '/5</td>';
                $html .= '<td>' . htmlspecialchars($r['created_at'] ?? '') . '</td></tr>';
            }
            $html .= '</table>';
        }

        // Wishlists
        if ($data['wishlists']) {
            $html .= '<h2>Wishlist (' . count($data['wishlists']) . ')</h2><ul>';
            foreach ($data['wishlists'] as $w) {
                $html .= '<li>' . htmlspecialchars($w['product_name'] ?? 'Product #' . $w['product_id']) . '</li>';
            }
            $html .= '</ul>';
        }

        // Newsletter
        if ($data['newsletter_subscription']) {
            $html .= '<h2>Newsletter Subscription</h2><table>';
            $html .= '<tr><th>Email</th><td>' . htmlspecialchars($data['newsletter_subscription']['email']) . '</td></tr>';
            $html .= '<tr><th>Status</th><td>' . htmlspecialchars($data['newsletter_subscription']['status']) . '</td></tr>';
            $html .= '<tr><th>Consent</th><td>' . ($data['newsletter_subscription']['consent'] ? 'Yes' : 'No') . '</td></tr>';
            $html .= '</table>';
        }

        // Audit trail
        if ($data['audit_trail']) {
            $html .= '<h2>Audit Trail (' . count($data['audit_trail']) . ' entries)</h2><table>';
            $html .= '<tr><th>Date</th><th>Action</th><th>Entity</th></tr>';
            foreach ($data['audit_trail'] as $a) {
                $html .= '<tr><td>' . htmlspecialchars($a['created_at']) . '</td>';
                $html .= '<td>' . htmlspecialchars($a['action']) . '</td>';
                $html .= '<td>' . htmlspecialchars($a['entity_type'] ?: '—') . '</td></tr>';
            }
            $html .= '</table>';
        }

        $html .= '<hr><p class="meta">This export was generated by Marigold Signature in accordance with GDPR Article 20 (Right to Data Portability).</p>';
        $html .= '</body></html>';

        return $html;
    }
}
