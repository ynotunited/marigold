<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;

class ShopController extends Controller
{
    public function index()
    {
        return View::renderTemplate('pages/public/shop', 'main', [
            'title' => 'Shop — Marigold Signature | Corporate Gifts & Branded Merchandise',
            'meta_description' => 'Browse premium branded merchandise and corporate gift collections from Marigold Signature Nigeria Limited — corporate gifts, apparel, desk & office, drinkware, tech and event essentials.',
            'page_key' => 'shop'
        ]);
    }
}
