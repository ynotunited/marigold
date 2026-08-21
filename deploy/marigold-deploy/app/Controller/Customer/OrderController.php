<?php

namespace App\Controller\Customer;

use App\Core\Controller;
use App\Core\View;
use App\Core\Model;
use App\Core\Money;
use App\Core\Session;

class OrderController extends Controller
{
    public function index()
    {
        $customerId = $this->customerId();
        $stmt = Model::getDB()->prepare("
            SELECT order_number AS id, created_at AS date, status, grand_total AS total
            FROM orders
            WHERE customer_id = :customer_id
            ORDER BY created_at DESC
        ");
        $stmt->execute(['customer_id' => $customerId]);

        $orders = array_map(fn($order) => [
            'id' => $order['id'],
            'date' => $order['date'],
            'status' => ucfirst($order['status']),
            'status_key' => $order['status'],
            'total' => Money::formatSession((float)$order['total']),
        ], $stmt->fetchAll());

        return View::renderTemplate('pages/customer/orders/index', 'customer', [
            'title' => 'My Orders | Marigold Signature',
            'orders' => $orders
        ]);
    }

    public function show($id)
    {
        $customerId = $this->customerId();
        $db = Model::getDB();

        $stmt = $db->prepare("
            SELECT *
            FROM orders
            WHERE order_number = :order_number
              AND customer_id = :customer_id
            LIMIT 1
        ");
        $stmt->execute([
            'order_number' => $id,
            'customer_id' => $customerId,
        ]);
        $row = $stmt->fetch();

        if (!$row) {
            throw new \Exception('Order not found', 404);
        }

        $itemsStmt = $db->prepare("
            SELECT oi.quantity, oi.price, oi.subtotal, p.name, pi.image
            FROM order_items oi
            LEFT JOIN products p ON p.id = oi.product_id
            LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_featured = 1
            WHERE oi.order_id = :order_id
            ORDER BY oi.id ASC
        ");
        $itemsStmt->execute(['order_id' => $row['id']]);

        $items = array_map(fn($item) => [
            'name' => $item['name'] ?? 'Product',
            'quantity' => (int)$item['quantity'],
            'price' => Money::formatSession((float)$item['price']),
            'total' => Money::formatSession((float)$item['subtotal']),
            'image' => $item['image'] ?: app_url('/ms-logo-icon.png'),
        ], $itemsStmt->fetchAll());

        $address = $this->shippingAddress($customerId);
        $order = [
            'id' => $row['order_number'],
            'date' => $row['created_at'],
            'status' => ucfirst($row['status']),
            'total' => Money::formatSession((float)$row['grand_total']),
            'subtotal' => Money::formatSession((float)$row['subtotal']),
            'tax' => Money::formatSession((float)$row['tax']),
            'shipping' => Money::formatSession((float)$row['shipping']),
            'items' => $items,
            'shipping_address' => $address,
        ];

        return View::renderTemplate('pages/customer/orders/show', 'customer', [
            'title' => 'Order ' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . ' | Marigold Signature',
            'order' => $order
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

    private function shippingAddress(int $customerId): array
    {
        $stmt = Model::getDB()->prepare("
            SELECT ca.*, c.company_name, u.first_name, u.last_name
            FROM customer_addresses ca
            JOIN customers c ON c.id = ca.customer_id
            LEFT JOIN users u ON u.id = c.user_id
            WHERE ca.customer_id = :customer_id
            ORDER BY ca.is_default DESC, ca.id ASC
            LIMIT 1
        ");
        $stmt->execute(['customer_id' => $customerId]);
        $address = $stmt->fetch();

        return [
            'name' => trim(($address['first_name'] ?? '') . ' ' . ($address['last_name'] ?? '')) ?: 'Customer',
            'company' => $address['company_name'] ?? '',
            'street' => $address['address_line_1'] ?? '',
            'city' => $address['city'] ?? '',
            'state' => $address['state'] ?? '',
            'country' => $address['country'] ?? '',
        ];
    }
}
