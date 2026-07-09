<?php

namespace App\Controller\Customer;


use App\Core\Controller;
use App\Core\View;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = [
            [
                'id' => 1,
                'type' => 'order',
                'icon' => 'package',
                'title' => 'Order ORD-9823 is being processed',
                'message' => 'Your order for 10× Executive Leather Folio is now in production.',
                'time' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'is_read' => false,
                'link' => '/account/orders/ORD-9823'
            ],
            [
                'id' => 2,
                'type' => 'quote',
                'icon' => 'file-text',
                'title' => 'New message on Quote QT-1045',
                'message' => 'Sarah Jenkins has updated your quote with pricing details.',
                'time' => date('Y-m-d H:i:s', strtotime('-18 hours')),
                'is_read' => false,
                'link' => '/account/quotes/QT-1045'
            ],
            [
                'id' => 3,
                'type' => 'promotion',
                'icon' => 'tag',
                'title' => 'New catalogue available',
                'message' => 'Our 2026 corporate merchandise catalogue is ready for download.',
                'time' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'is_read' => true,
                'link' => '/account/downloads'
            ],
            [
                'id' => 4,
                'type' => 'order',
                'icon' => 'check-circle',
                'title' => 'Order ORD-9810 delivered',
                'message' => 'Your order has been successfully delivered. We hope you love it!',
                'time' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'is_read' => true,
                'link' => '/account/orders/ORD-9810'
            ]
        ];

        return View::renderTemplate('pages/customer/notifications', 'customer', [
            'title' => 'Notifications | Marigold Signature',
            'notifications' => $notifications,
            'unread_count' => count(array_filter($notifications, fn($n) => !$n['is_read']))
        ]);
    }
}
