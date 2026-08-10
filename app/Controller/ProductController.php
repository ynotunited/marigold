<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;

class ProductController extends Controller
{
    public function show($slug = null)
    {
        return View::renderTemplate('pages/public/product', 'main', [
            'title' => 'Product | Marigold Signature',
            'meta_description' => 'Explore premium branded merchandise and corporate gift collections by Marigold Signature Nigeria Limited — fully customisable with your brand.',
            'page_key' => 'product',
            'slug' => $slug
        ]);
    }
}
