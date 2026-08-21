<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;

class AdminQuoteController extends Controller
{
    public function index()
    {
        $db = Model::getDB();

        $quotes = $db->query("
            SELECT
                q.id,
                q.quote_number,
                q.status,
                q.grand_total,
                q.created_at,
                u.first_name,
                u.last_name,
                u.email,
                (SELECT COUNT(*) FROM quote_items qi WHERE qi.quote_id = q.id) AS item_count
            FROM quotes q
            LEFT JOIN users u ON u.id = q.customer_id
            ORDER BY q.created_at DESC
        ")->fetchAll();

        $rows = [];
        foreach ($quotes as $q) {
            $rows[] = [
                'id'       => $q['quote_number'],
                'customer' => trim(($q['first_name'] ?? '') . ' ' . ($q['last_name'] ?? '')) ?: 'Guest',
                'email'    => $q['email'] ?? '—',
                'items'    => (int)$q['item_count'],
                'status'   => ucfirst($q['status']),
                'date'     => date('Y-m-d', strtotime($q['created_at'])),
                'total'    => (float)$q['grand_total'],
            ];
        }

        return View::renderTemplate('pages/admin/quotes/index', 'admin', [
            'title' => 'Quotes | Admin',
            'quotes' => $rows,
        ]);
    }

    public function show($id)
    {
        $db = Model::getDB();

        $stmt = $db->prepare("
            SELECT
                q.id,
                q.quote_number,
                q.status,
                q.notes,
                q.expiry_date,
                q.created_at,
                u.id AS customer_user_id,
                u.first_name,
                u.last_name,
                u.email,
                u.phone,
                c.company_name
            FROM quotes q
            LEFT JOIN users u ON u.id = q.customer_id
            LEFT JOIN customers c ON c.user_id = u.id
            WHERE q.quote_number = :id OR q.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $q = $stmt->fetch();

        if (!$q) {
            http_response_code(404);
            return View::renderTemplate('pages/public/errors/404', 'main', ['title' => 'Quote not found']);
        }

        // Items
        $itemStmt = $db->prepare("
            SELECT qi.name, qi.quantity, qi.price, qi.notes
            FROM quote_items qi
            WHERE qi.quote_id = :quote_id
        ");
        $itemStmt->execute(['quote_id' => $q['id']]);
        $items = [];
        foreach ($itemStmt->fetchAll() as $it) {
            $items[] = [
                'name'       => $it['name'] ?: 'Product',
                'qty'        => (int)$it['quantity'],
                'unit_price' => (float)$it['price'],
                'notes'      => $it['notes'] ?: '',
            ];
        }

        // Files
        $fileStmt = $db->prepare("
            SELECT file_path, file_type
            FROM quote_files
            WHERE quote_id = :quote_id
        ");
        $fileStmt->execute(['quote_id' => $q['id']]);
        $files = [];
        foreach ($fileStmt->fetchAll() as $f) {
            $icon = 'file';
            $ext  = strtolower(pathinfo($f['file_path'], PATHINFO_EXTENSION));
            if ($ext === 'pdf') $icon = 'file-text';
            elseif (in_array($ext, ['ai', 'svg'])) $icon = 'file-code';
            elseif (in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) $icon = 'image';
            $files[] = [
                'name' => basename($f['file_path']),
                'size' => '—',
                'icon' => $icon,
            ];
        }

        // Messages
        $msgStmt = $db->prepare("
            SELECT qm.message, qm.created_at, u.first_name, u.last_name, u.id AS sender_id
            FROM quote_messages qm
            LEFT JOIN users u ON u.id = qm.sender_id
            WHERE qm.quote_id = :quote_id
            ORDER BY qm.created_at ASC
        ");
        $msgStmt->execute(['quote_id' => $q['id']]);
        $messages = [];
        $adminUser = null;
        foreach ($msgStmt->fetchAll() as $m) {
            $isCustomer = $m['sender_id'] == $q['customer_user_id'] && $q['customer_user_id'] !== null;
            if ($isCustomer) $adminUser = $m['sender_id'];
            $messages[] = [
                'sender'     => trim(($m['first_name'] ?? '') . ' ' . ($m['last_name'] ?? '')) ?: 'User',
                'is_customer'=> (bool)$isCustomer,
                'message'    => $m['message'],
                'time'       => date('M j, Y h:i A', strtotime($m['created_at'])),
            ];
        }

        $customerName = trim(($q['first_name'] ?? '') . ' ' . ($q['last_name'] ?? '')) ?: 'Guest';

        $quote = [
            'id'            => $q['quote_number'],
            'date'          => date('Y-m-d', strtotime($q['created_at'])),
            'valid_until'   => $q['expiry_date'] ?: date('Y-m-d', strtotime('+30 days', strtotime($q['created_at']))),
            'status'        => ucfirst($q['status']),
            'customer'      => [
                'name'    => $customerName,
                'email'   => $q['email'] ?? '—',
                'phone'   => $q['phone'] ?: '—',
                'company' => $q['company_name'] ?: '—',
            ],
            'items'         => $items,
            'files'         => $files,
            'customer_notes'=> $q['notes'] ?: '',
            'messages'      => $messages,
        ];

        return View::renderTemplate('pages/admin/quotes/show', 'admin', [
            'title' => "Quote {$q['quote_number']} | Admin",
            'quote' => $quote,
        ]);
    }
}
