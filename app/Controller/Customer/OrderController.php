<?php

namespace App\Controller\Customer;


use App\Core\Controller;
use App\Core\View;

class OrderController extends Controller
{
    public function index()
    {
        // Mock data
        $orders = [
            ['id' => 'ORD-9823', 'date' => date('Y-m-d', strtotime('-2 days')), 'status' => 'Processing', 'total' => '₦450,000'],
            ['id' => 'ORD-9810', 'date' => date('Y-m-d', strtotime('-15 days')), 'status' => 'Completed', 'total' => '₦1,200,000'],
            ['id' => 'ORD-9755', 'date' => date('Y-m-d', strtotime('-45 days')), 'status' => 'Completed', 'total' => '₦85,000'],
            ['id' => 'ORD-9701', 'date' => date('Y-m-d', strtotime('-90 days')), 'status' => 'Cancelled', 'total' => '₦220,000'],
        ];

        return View::renderTemplate('pages/customer/orders/index', 'customer', [
            'title' => 'My Orders | Marigold Signature',
            'orders' => $orders
        ]);
    }

    public function show($id)
    {
        // Mock detailed order
        $order = [
            'id' => $id,
            'date' => date('Y-m-d', strtotime('-2 days')),
            'status' => 'Processing',
            'total' => '₦450,000',
            'subtotal' => '₦420,000',
            'shipping' => '₦30,000',
            'items' => [
                [
                    'name' => 'Executive Leather Folio',
                    'quantity' => 10,
                    'price' => '₦45,000',
                    'total' => '₦450,000',
                    'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=200&auto=format&fit=crop'
                ]
            ],
            'shipping_address' => [
                'name' => 'David Okon',
                'company' => 'TechSolutions Inc',
                'street' => '14 Adeola Odeku St',
                'city' => 'Victoria Island',
                'state' => 'Lagos',
                'country' => 'Nigeria'
            ]
        ];

        return View::renderTemplate('pages/customer/orders/show', 'customer', [
            'title' => 'Order ' . $id . ' | Marigold Signature',
            'order' => $order
        ]);
    }
}
