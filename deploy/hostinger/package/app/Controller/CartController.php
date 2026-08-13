<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;

class CartController extends Controller
{
    public function index()
    {
        return View::renderTemplate('pages/public/cart', 'main', [
            'title' => 'Your Cart | Marigold Signature'
        ]);
    }
}
