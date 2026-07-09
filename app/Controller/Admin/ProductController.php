<?php

namespace App\Controller\Admin;


use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Session;
use App\Core\View;

class ProductController extends Controller
{
    private function getMockProducts(): array
    {
        return [
            ['id' => 1,  'name' => 'Executive Leather Notebook', 'sku' => 'MS-EXEC-001', 'category' => 'Stationery', 'price' => 24500, 'sale_price' => null, 'stock' => 45, 'status' => 'Published', 'image' => 'https://images.unsplash.com/photo-1544816278-ca5e3f4abd8c?q=80&w=80&auto=format&fit=crop'],
            ['id' => 2,  'name' => 'Branded Vacuum Flask 1L', 'sku' => 'MS-FLASK-001', 'category' => 'Drinkware', 'price' => 18000, 'sale_price' => 14500, 'stock' => 120, 'status' => 'Published', 'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=80&auto=format&fit=crop'],
            ['id' => 3,  'name' => 'Slim Metal Pen Set (3pcs)', 'sku' => 'MS-PEN-003', 'category' => 'Stationery', 'price' => 12000, 'sale_price' => null, 'stock' => 200, 'status' => 'Published', 'image' => 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=80&auto=format&fit=crop'],
            ['id' => 4,  'name' => 'USB-C Hub & Organiser', 'sku' => 'MS-TECH-004', 'category' => 'Tech', 'price' => 35000, 'sale_price' => null, 'stock' => 2, 'status' => 'Published', 'image' => 'https://images.unsplash.com/photo-1612815292673-ab2ad8a5a95b?q=80&w=80&auto=format&fit=crop'],
            ['id' => 5,  'name' => 'Premium Cotton Polo Shirt', 'sku' => 'MS-APP-005', 'category' => 'Apparel', 'price' => 9500, 'sale_price' => null, 'stock' => 80, 'status' => 'Draft', 'image' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?q=80&w=80&auto=format&fit=crop'],
            ['id' => 6,  'name' => 'Wireless Charging Pad', 'sku' => 'MS-TECH-006', 'category' => 'Tech', 'price' => 28000, 'sale_price' => 22000, 'stock' => 0, 'status' => 'Published', 'image' => 'https://images.unsplash.com/photo-1583394293253-3f8b5e4d2be6?q=80&w=80&auto=format&fit=crop'],
            ['id' => 7,  'name' => 'Executive Laptop Backpack', 'sku' => 'MS-BAGS-008', 'category' => 'Bags', 'price' => 55000, 'sale_price' => null, 'stock' => 18, 'status' => 'Published', 'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=80&auto=format&fit=crop'],
            ['id' => 8,  'name' => 'Smart Power Bank 20000mAh', 'sku' => 'MS-TECH-011', 'category' => 'Tech', 'price' => 42000, 'sale_price' => 36000, 'stock' => 34, 'status' => 'Archived', 'image' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?q=80&w=80&auto=format&fit=crop'],
        ];
    }

    public function index()
    {
        return View::renderTemplate('pages/admin/products/index', 'admin', [
            'title' => 'Products | Admin',
            'products' => $this->getMockProducts(),
        ]);
    }

    public function create()
    {
        return View::renderTemplate('pages/admin/products/form', 'admin', [
            'title' => 'Add Product | Admin',
            'product' => null,
            'mode' => 'create',
        ]);
    }

    public function edit($id)
    {
        // In production: fetch from DB by $id
        $products = $this->getMockProducts();
        $product = $products[0]; // Return first as mock

        return View::renderTemplate('pages/admin/products/form', 'admin', [
            'title' => 'Edit Product | Admin',
            'product' => $product,
            'mode' => 'edit',
        ]);
    }

    public function store()
    {
        return $this->save(null);
    }

    public function update($id)
    {
        return $this->save($id);
    }

    private function save($id = null)
    {
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            die('Invalid CSRF token');
        }

        Session::set('success', $id ? 'Product updated successfully.' : 'Product created successfully.');
        $this->redirect('/admin/products');
    }
}
