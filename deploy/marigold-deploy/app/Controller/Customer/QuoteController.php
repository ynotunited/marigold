<?php

namespace App\Controller\Customer;

use App\Core\Controller;
use App\Core\View;
use App\Core\Model;
use App\Core\Money;
use App\Core\Session;

class QuoteController extends Controller
{
    public function index()
    {
        $customerId = $this->customerId();
        $stmt = Model::getDB()->prepare("
            SELECT q.quote_number AS id, q.created_at AS date, q.status, COUNT(qi.id) AS items
            FROM quotes q
            LEFT JOIN quote_items qi ON qi.quote_id = q.id
            WHERE q.customer_id = :customer_id
            GROUP BY q.id
            ORDER BY q.created_at DESC
        ");
        $stmt->execute(['customer_id' => $customerId]);

        $quotes = array_map(fn($quote) => [
            'id' => $quote['id'],
            'date' => $quote['date'],
            'status' => ucwords(str_replace('_', ' ', $quote['status'])),
            'status_key' => $quote['status'],
            'items' => (int)$quote['items'],
        ], $stmt->fetchAll());

        return View::renderTemplate('pages/customer/quotes/index', 'customer', [
            'title' => 'My Quotes | Marigold Signature',
            'quotes' => $quotes
        ]);
    }

    public function show($id)
    {
        $customerId = $this->customerId();
        $db = Model::getDB();

        $stmt = $db->prepare("
            SELECT *
            FROM quotes
            WHERE quote_number = :quote_number
              AND customer_id = :customer_id
            LIMIT 1
        ");
        $stmt->execute([
            'quote_number' => $id,
            'customer_id' => $customerId,
        ]);
        $row = $stmt->fetch();

        if (!$row) {
            throw new \Exception('Quote not found', 404);
        }

        $itemsStmt = $db->prepare("
            SELECT qi.quantity, qi.price, p.name
            FROM quote_items qi
            LEFT JOIN products p ON p.id = qi.product_id
            WHERE qi.quote_id = :quote_id
            ORDER BY qi.id ASC
        ");
        $itemsStmt->execute(['quote_id' => $row['id']]);

        $items = array_map(fn($item) => [
            'name' => $item['name'] ?? 'Product',
            'quantity' => (int)$item['quantity'],
            'price' => ((float)$item['price'] > 0) ? Money::formatSession((float)$item['price']) : 'TBD',
            'total' => ((float)$item['price'] > 0) ? Money::formatSession((float)$item['price'] * (int)$item['quantity']) : 'TBD',
            'notes' => '',
        ], $itemsStmt->fetchAll());

        $messagesStmt = $db->prepare("
            SELECT qm.message, qm.created_at, qm.sender_id, u.first_name, u.last_name
            FROM quote_messages qm
            LEFT JOIN users u ON u.id = qm.sender_id
            WHERE qm.quote_id = :quote_id
            ORDER BY qm.created_at ASC
        ");
        $messagesStmt->execute(['quote_id' => $row['id']]);

        $currentUserId = (int)Session::get('user_id');
        $messages = array_map(fn($message) => [
            'sender' => trim(($message['first_name'] ?? '') . ' ' . ($message['last_name'] ?? '')) ?: 'Marigold',
            'is_customer' => (int)$message['sender_id'] === $currentUserId,
            'message' => $message['message'],
            'time' => date('M j, Y h:i A', strtotime($message['created_at'])),
        ], $messagesStmt->fetchAll());

        $quote = [
            'id' => $row['quote_number'],
            'date' => $row['created_at'],
            'status' => ucwords(str_replace('_', ' ', $row['status'])),
            'valid_until' => $row['expiry_date'] ?: date('Y-m-d', strtotime($row['created_at'] . ' +14 days')),
            'total' => ((float)$row['grand_total'] > 0) ? Money::formatSession((float)$row['grand_total']) : 'Pending pricing',
            'items' => $items,
            'messages' => $messages,
        ];

        return View::renderTemplate('pages/customer/quotes/show', 'customer', [
            'title' => 'Quote ' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . ' | Marigold Signature',
            'quote' => $quote
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
