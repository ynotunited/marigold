<?php

namespace App\Controller\Customer;


use App\Core\Controller;
use App\Core\View;

class WishlistController extends Controller
{
    public function index()
    {
        // Mock data
        $products = [
            [
                'id' => 1,
                'name' => 'Executive Leather Folio',
                'price' => '₦45,000',
                'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=400&auto=format&fit=crop',
                'in_stock' => true
            ],
            [
                'id' => 2,
                'name' => 'Premium Metal Pen Set',
                'price' => '₦28,000',
                'image' => 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=400&auto=format&fit=crop',
                'in_stock' => true
            ],
            [
                'id' => 3,
                'name' => 'Custom Moleskine Notebook',
                'price' => '₦15,000',
                'image' => 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?q=80&w=400&auto=format&fit=crop',
                'in_stock' => false
            ],
            [
                'id' => 4,
                'name' => 'Insulated Smart Flask',
                'price' => '₦18,500',
                'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=400&auto=format&fit=crop',
                'in_stock' => true
            ]
        ];

        return View::renderTemplate('pages/customer/wishlist', 'customer', [
            'title' => 'My Wishlist | Marigold Signature',
            'products' => $products
        ]);
    }
}
