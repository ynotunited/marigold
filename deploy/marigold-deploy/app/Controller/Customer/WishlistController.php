<?php

namespace App\Controller\Customer;

use App\Core\Controller;
use App\Core\View;
use App\Core\Model;
use App\Core\Money;
use App\Core\Session;

class WishlistController extends Controller
{
    public function index()
    {
        $customerId = $this->customerId();
        $stmt = Model::getDB()->prepare("
            SELECT p.id, p.name, p.price, p.sale_price, p.stock_quantity, pi.image
            FROM wishlists w
            JOIN products p ON p.id = w.product_id
            LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_featured = 1
            WHERE w.customer_id = :customer_id
              AND p.deleted_at IS NULL
            ORDER BY w.created_at DESC
        ");
        $stmt->execute(['customer_id' => $customerId]);

        $products = array_map(fn($row) => [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => Money::formatSession((float)($row['sale_price'] ?: $row['price'])),
            'image' => $row['image'] ?: app_url('/ms-logo-icon.png'),
            'in_stock' => (int)$row['stock_quantity'] > 0,
        ], $stmt->fetchAll());

        return View::renderTemplate('pages/customer/wishlist', 'customer', [
            'title' => 'My Wishlist | Marigold Signature',
            'products' => $products
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
