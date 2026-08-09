<?php

namespace App\Controller\Customer;

use App\Core\Controller;
use App\Core\View;
use App\Core\Model;
use App\Core\Session;

class AddressController extends Controller
{
    public function index()
    {
        $customerId = $this->customerId();
        $stmt = Model::getDB()->prepare("
            SELECT ca.*, c.company_name, u.first_name, u.last_name
            FROM customer_addresses ca
            JOIN customers c ON c.id = ca.customer_id
            LEFT JOIN users u ON u.id = c.user_id
            WHERE ca.customer_id = :customer_id
            ORDER BY ca.is_default DESC, ca.id ASC
        ");
        $stmt->execute(['customer_id' => $customerId]);

        $addresses = array_map(fn($row) => [
            'id' => $row['id'],
            'label' => $row['is_default'] ? 'Default Address' : ucfirst($row['type']) . ' Address',
            'name' => trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? '')),
            'company' => $row['company_name'] ?? '',
            'street' => $row['address_line_1'],
            'city' => $row['city'],
            'state' => $row['state'],
            'country' => $row['country'],
            'phone' => $row['phone'],
            'type' => ucfirst($row['type']),
            'is_default' => (bool)$row['is_default'],
        ], $stmt->fetchAll());

        return View::renderTemplate('pages/customer/address/index', 'customer', [
            'title' => 'Address Book | Marigold Signature',
            'addresses' => $addresses
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
