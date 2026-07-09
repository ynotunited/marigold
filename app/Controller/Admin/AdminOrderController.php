<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class AdminOrderController extends Controller
{
    private function getMockOrders(): array
    {
        return [
            ['id' => 'ORD-9824', 'customer' => 'Adaeze Williams', 'email' => 'adaeze@company.ng', 'total' => 385000, 'payment' => 'Paid',   'status' => 'Pending',    'date' => date('Y-m-d', strtotime('-1 hour'))],
            ['id' => 'ORD-9823', 'customer' => 'David Okon',      'email' => 'david@techsol.ng',  'total' => 450000, 'payment' => 'Paid',   'status' => 'Processing', 'date' => date('Y-m-d', strtotime('-2 days'))],
            ['id' => 'ORD-9820', 'customer' => 'Seun Adeyemi',    'email' => 'seun@adeyemi.ng',   'total' => 1200000,'payment' => 'Paid',   'status' => 'Shipped',    'date' => date('Y-m-d', strtotime('-4 days'))],
            ['id' => 'ORD-9815', 'customer' => 'Tunde Bakare',    'email' => 'tunde@bak.ng',      'total' => 72000,  'payment' => 'Paid',   'status' => 'Completed',  'date' => date('Y-m-d', strtotime('-5 days'))],
            ['id' => 'ORD-9810', 'customer' => 'David Okon',      'email' => 'david@techsol.ng',  'total' => 1200000,'payment' => 'Paid',   'status' => 'Completed',  'date' => date('Y-m-d', strtotime('-15 days'))],
            ['id' => 'ORD-9800', 'customer' => 'Blessing Nwosu',  'email' => 'blessing@nw.ng',    'total' => 95000,  'payment' => 'Pending','status' => 'Cancelled',  'date' => date('Y-m-d', strtotime('-20 days'))],
        ];
    }

    public function index()
    {
        return View::renderTemplate('pages/admin/orders/index', 'admin', [
            'title' => 'Orders | Admin',
            'orders' => $this->getMockOrders(),
        ]);
    }

    public function show($id)
    {
        $order = [
            'id' => $id, 'date' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'status' => 'Processing', 'payment_status' => 'Paid', 'payment_method' => 'Bank Transfer',
            'total' => 450000, 'subtotal' => 420000, 'shipping' => 30000,
            'customer' => ['name' => 'David Okon', 'email' => 'david@techsol.ng', 'phone' => '+234 801 234 5678', 'company' => 'TechSolutions Inc'],
            'shipping_address' => ['street' => '14 Adeola Odeku St', 'city' => 'Victoria Island', 'state' => 'Lagos', 'country' => 'Nigeria'],
            'items' => [
                ['name' => 'Executive Leather Folio', 'sku' => 'MS-EXEC-001', 'qty' => 10, 'price' => 45000, 'total' => 450000, 'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=80&auto=format&fit=crop'],
            ],
            'notes' => [],
            'timeline' => [
                ['event' => 'Order placed',       'time' => date('M j, Y g:i A', strtotime('-2 days')),       'done' => true],
                ['event' => 'Payment confirmed',   'time' => date('M j, Y g:i A', strtotime('-2 days +1 hour')),'done' => true],
                ['event' => 'Processing',          'time' => 'In progress',                                     'done' => true, 'current' => true],
                ['event' => 'Shipped',             'time' => 'Pending',                                         'done' => false],
                ['event' => 'Delivered',           'time' => 'Pending',                                         'done' => false],
            ],
        ];
        return View::renderTemplate('pages/admin/orders/show', 'admin', ['title' => "Order $id | Admin", 'order' => $order]);
    }
}
