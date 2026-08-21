<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;

class AdminOrderController extends Controller
{
    public function index()
    {
        $db = Model::getDB();

        $orders = $db->query("
            SELECT
                o.id,
                o.order_number,
                o.status,
                o.payment_status,
                o.grand_total,
                o.payment_method,
                o.customer_id,
                o.created_at,
                a.first_name,
                a.last_name,
                a.email,
                a.phone
            FROM orders o
            LEFT JOIN order_addresses a ON a.order_id = o.id AND a.type = 'shipping'
            ORDER BY o.created_at DESC
        ")->fetchAll();

        $rows = [];
        foreach ($orders as $o) {
            $rows[] = [
                'id'       => $o['order_number'],
                'customer' => trim(($o['first_name'] ?? '') . ' ' . ($o['last_name'] ?? '')) ?: 'Guest',
                'email'    => $o['email'] ?? '—',
                'phone'    => $o['phone'] ?? '',
                'is_guest' => empty($o['customer_id']),
                'date'     => $o['created_at'],
                'payment'  => ucfirst($o['payment_status'] ?? 'pending'),
                'status'   => ucfirst($o['status'] ?? 'pending'),
                'total'    => (float)($o['grand_total'] ?? 0),
            ];
        }

        return View::renderTemplate('pages/admin/orders/index', 'admin', [
            'title'  => 'Orders | Admin',
            'orders' => $rows,
        ]);
    }

    public function show($id)
    {
        $db = Model::getDB();

        $stmt = $db->prepare("
            SELECT
                o.id,
                o.order_number,
                o.status,
                o.payment_status,
                o.shipping_status,
                o.delivery_method,
                o.subtotal,
                o.discount,
                o.tax,
                o.shipping,
                o.grand_total,
                o.payment_method,
                o.transaction_reference,
                o.customer_id,
                o.whatsapp,
                o.notes,
                o.created_at
            FROM orders o
            WHERE o.order_number = :id OR o.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $o = $stmt->fetch();

        if (!$o) {
            http_response_code(404);
            return View::renderTemplate('pages/public/errors/404', 'main', ['title' => 'Order not found']);
        }

        // Customer + shipping info from the address tables
        $addrStmt = $db->prepare("
            SELECT type, first_name, last_name, email, phone, company,
                   address_line1, address_line2, city, state, postal_code, country
            FROM order_addresses
            WHERE order_id = :order_id
        ");
        $addrStmt->execute(['order_id' => $o['id']]);
        $addresses = $addrStmt->fetchAll();

        $shipping = null;
        $billing  = null;
        foreach ($addresses as $a) {
            if ($a['type'] === 'shipping') {
                $shipping = $a;
            } else {
                $billing = $a;
            }
        }
        $contact = $shipping ?: $billing;

        // Order items
        $itemStmt = $db->prepare("
            SELECT oi.product_id, oi.name, oi.variant_id, oi.quantity, oi.price, oi.subtotal,
                   p.sku
            FROM order_items oi
            LEFT JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = :order_id
        ");
        $itemStmt->execute(['order_id' => $o['id']]);
        $itemRows = $itemStmt->fetchAll();

        // Product images (first featured image per product)
        $images = [];
        $productIds = array_filter(array_unique(array_column($itemRows, 'product_id')));
        if ($productIds) {
            $in = implode(',', array_fill(0, count($productIds), '?'));
            $imgStmt = $db->prepare("
                SELECT product_id, image
                FROM product_images
                WHERE product_id IN ($in)
                ORDER BY is_featured DESC, sort_order ASC
            ");
            $imgStmt->execute(array_values($productIds));
            foreach ($imgStmt->fetchAll() as $im) {
                if (!isset($images[$im['product_id']])) {
                    $images[$im['product_id']] = $im['image'];
                }
            }
        }

        $items = [];
        foreach ($itemRows as $row) {
            $items[] = [
                'name'  => $row['name'],
                'sku'   => $row['sku'] ?: ('MS-ITEM-' . str_pad((string)$row['product_id'], 4, '0', STR_PAD_LEFT)),
                'qty'   => (int)$row['quantity'],
                'price' => (float)$row['price'],
                'total' => (float)$row['subtotal'],
                'image' => !empty($row['product_id']) && isset($images[$row['product_id']])
                    ? $images[$row['product_id']]
                    : app_url('/ms-logo-icon.png'),
            ];
        }

        $customerName = $contact
            ? trim(($contact['first_name'] ?? '') . ' ' . ($contact['last_name'] ?? ''))
            : 'Guest';
        $customerEmail = $contact['email'] ?? '';
        $customerPhone = $contact['phone'] ?? ($o['whatsapp'] ?? '');

        $customer = [
            'name'    => $customerName ?: 'Guest',
            'email'   => $customerEmail,
            'phone'   => $customerPhone,
            'company' => $contact['company'] ?? '',
            'is_guest'=> empty($o['customer_id']),
        ];

        $shippingAddress = null;
        if ($shipping) {
            $shippingAddress = [
                'street'  => trim(($shipping['address_line1'] ?? '') . ' ' . ($shipping['address_line2'] ?? '')),
                'city'    => $shipping['city'] ?? '',
                'state'   => $shipping['state'] ?? '',
                'country' => $shipping['country'] ?? '',
            ];
        }

        // Timeline derived from status
        $statusOrder = ['pending', 'processing', 'completed', 'cancelled', 'refunded'];
        $statusIndex = array_search(strtolower($o['status']), $statusOrder);
        $timeline = [
            ['event' => 'Order placed',     'time' => date('M j, Y g:i A', strtotime($o['created_at'])), 'done' => true],
            ['event' => 'Payment confirmed', 'time' => $o['payment_status'] === 'paid' ? date('M j, Y g:i A', strtotime($o['created_at'])) : 'Pending', 'done' => $o['payment_status'] === 'paid'],
        ];
        if ($statusIndex !== false && $statusIndex > 0) {
            $timeline[] = ['event' => 'Processing', 'time' => 'In progress', 'done' => true, 'current' => true];
        }
        $timeline[] = ['event' => 'Shipped',   'time' => 'Pending', 'done' => false];
        $timeline[] = ['event' => 'Delivered', 'time' => 'Pending', 'done' => false];

        $order = [
            'id'              => $o['order_number'],
            'date'            => $o['created_at'],
            'status'          => ucfirst($o['status']),
            'payment_status'  => ucfirst($o['payment_status']),
            'payment_method'  => $o['payment_method'] ?: '—',
            'delivery_method' => $o['delivery_method'],
            'total'           => (float)$o['grand_total'],
            'subtotal'        => (float)$o['subtotal'],
            'tax'             => (float)$o['tax'],
            'shipping'        => (float)$o['shipping'],
            'customer'        => $customer,
            'shipping_address'=> $shippingAddress,
            'items'           => $items,
            'notes'           => $o['notes'] ? explode("\n", $o['notes']) : [],
            'timeline'        => $timeline,
        ];

        return View::renderTemplate('pages/admin/orders/show', 'admin', [
            'title' => "Order {$o['order_number']} | Admin",
            'order' => $order,
        ]);
    }
}
