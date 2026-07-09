<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;

class QuoteRequestController extends Controller
{
    /**
     * Show the multi-product quote basket form
     */
    public function index()
    {
        // Pre-populate if a product_id was passed (from product/shop page)
        $product_id = $_GET['product_id'] ?? null;

        $preSelected = null;
        if ($product_id) {
            // In production this would query the DB. Using mock data for now.
            $allProducts = [
                1 => ['id' => 1, 'name' => 'Executive Leather Notebook & Pen Set', 'sku' => 'MS-EXEC-001', 'image' => 'https://images.unsplash.com/photo-1544816278-ca5e3f4abd8c?q=80&w=200&auto=format&fit=crop'],
                2 => ['id' => 2, 'name' => 'Branded Vacuum Flask 1L', 'sku' => 'MS-FLASK-001', 'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=200&auto=format&fit=crop'],
                3 => ['id' => 3, 'name' => 'Slim Metal Pen Set (3pcs)', 'sku' => 'MS-PEN-003', 'image' => 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=200&auto=format&fit=crop'],
                4 => ['id' => 4, 'name' => 'USB-C Hub & Organiser', 'sku' => 'MS-TECH-004', 'image' => 'https://images.unsplash.com/photo-1612815292673-ab2ad8a5a95b?q=80&w=200&auto=format&fit=crop'],
            ];
            $preSelected = $allProducts[$product_id] ?? null;
        }

        return View::renderTemplate('pages/public/quote_request', 'main', [
            'title' => 'Request a Quote | Marigold Signature',
            'meta_description' => 'Request a custom corporate merchandise quote from Marigold Signature. Bulk pricing, branded items, and bespoke gifting solutions for your business.',
            'preSelected' => $preSelected,
        ]);
    }

    /**
     * Handle quote form submission
     */
    public function submit()
    {
        // In production: validate, store to DB, send emails
        // For now: redirect to a success page
        header('Location: /quote-request/success');
        exit;
    }
}
