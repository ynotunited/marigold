<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;

class CheckoutController extends Controller
{
    public function index()
    {
        return View::renderTemplate('pages/public/checkout', 'main', [
            'title' => 'Checkout | Marigold Signature'
        ]);
    }

    public function confirmation()
    {
        return View::renderTemplate('pages/public/order_confirmation', 'main', [
            'title' => 'Order Confirmation | Marigold Signature'
        ]);
    }
}
