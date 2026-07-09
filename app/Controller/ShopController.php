<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;

class ShopController extends Controller
{
    public function index()
    {
        return View::renderTemplate('pages/public/shop', 'main', [
            'title' => 'Shop All Products | Marigold Signature'
        ]);
    }
}
