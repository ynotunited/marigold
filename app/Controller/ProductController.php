<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;

class ProductController extends Controller
{
    public function show($slug = null)
    {
        return View::renderTemplate('pages/public/product', 'main', [
            'title' => 'Product Details | Marigold Signature',
            'slug' => $slug
        ]);
    }
}
