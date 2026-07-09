<?php

namespace App\Controller\Customer;


use App\Core\Controller;
use App\Core\View;

class QuoteController extends Controller
{
    public function index()
    {
        // Mock data
        $quotes = [
            ['id' => 'QT-1045', 'date' => date('Y-m-d', strtotime('-1 days')), 'status' => 'Pending Review', 'items' => 5],
            ['id' => 'QT-1022', 'date' => date('Y-m-d', strtotime('-30 days')), 'status' => 'Approved', 'items' => 20],
            ['id' => 'QT-0980', 'date' => date('Y-m-d', strtotime('-60 days')), 'status' => 'Expired', 'items' => 2],
        ];

        return View::renderTemplate('pages/customer/quotes/index', 'customer', [
            'title' => 'My Quotes | Marigold Signature',
            'quotes' => $quotes
        ]);
    }

    public function show($id)
    {
        // Mock detailed quote
        $quote = [
            'id' => $id,
            'date' => date('Y-m-d', strtotime('-1 days')),
            'status' => 'Pending Review',
            'valid_until' => date('Y-m-d', strtotime('+13 days')),
            'total' => 'Pending pricing', // Status is pending review
            'items' => [
                [
                    'name' => 'Custom Moleskine Notebook (Branded)',
                    'quantity' => 500,
                    'price' => 'TBD',
                    'total' => 'TBD',
                    'notes' => 'Gold foil stamping on cover'
                ],
                [
                    'name' => 'Premium Metal Pen Set',
                    'quantity' => 500,
                    'price' => 'TBD',
                    'total' => 'TBD',
                    'notes' => 'Laser engraving on barrel'
                ]
            ],
            'messages' => [
                [
                    'sender' => 'David Okon',
                    'is_customer' => true,
                    'message' => 'Hello Sarah, I submitted this quote request. We need these delivered by the 15th of next month. Is that possible?',
                    'time' => date('M j, Y h:i A', strtotime('-24 hours'))
                ],
                [
                    'sender' => 'Sarah Jenkins (Account Manager)',
                    'is_customer' => false,
                    'message' => 'Hi David, thanks for reaching out. Yes, we can definitely meet that timeline for the notebooks, but the metal pens might require expedited shipping. Let me crunch the numbers and I will update this quote with pricing shortly.',
                    'time' => date('M j, Y h:i A', strtotime('-18 hours'))
                ]
            ]
        ];

        return View::renderTemplate('pages/customer/quotes/show', 'customer', [
            'title' => 'Quote ' . $id . ' | Marigold Signature',
            'quote' => $quote
        ]);
    }
}
