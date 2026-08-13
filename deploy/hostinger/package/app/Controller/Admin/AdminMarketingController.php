<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class AdminMarketingController extends Controller
{
    public function coupons()
    {
        $coupons = [
            ['id' => 1, 'code' => 'WELCOME26', 'type' => 'Percentage', 'value' => '10%', 'min_spend' => '₦0', 'usage' => '142 / ∞', 'expiry' => 'Never', 'status' => 'Active'],
            ['id' => 2, 'code' => 'CORP500K', 'type' => 'Fixed', 'value' => '₦50,000', 'min_spend' => '₦500,000', 'usage' => '12 / 50', 'expiry' => '2026-12-31', 'status' => 'Active'],
            ['id' => 3, 'code' => 'BLACKFRIDAY', 'type' => 'Percentage', 'value' => '20%', 'min_spend' => '₦10,000', 'usage' => '0 / ∞', 'expiry' => '2026-11-30', 'status' => 'Scheduled'],
            ['id' => 4, 'code' => 'XMAS25', 'type' => 'Fixed', 'value' => '₦10,000', 'min_spend' => '₦100,000', 'usage' => '45 / 100', 'expiry' => '2025-12-25', 'status' => 'Expired'],
        ];
        return View::renderTemplate('pages/admin/marketing/coupons', 'admin', ['title' => 'Coupons | Admin', 'coupons' => $coupons]);
    }

    public function reviews()
    {
        $reviews = [
            ['id' => 1, 'product' => 'Executive Leather Folio', 'customer' => 'David Okon', 'rating' => 5, 'comment' => 'Excellent quality and the embossed logo looks fantastic on the leather.', 'date' => '2 hrs ago', 'status' => 'Pending'],
            ['id' => 2, 'product' => 'Premium Metal Pen Set', 'customer' => 'Adaeze Williams', 'rating' => 4, 'comment' => 'Good pens, but the delivery took a bit longer than expected.', 'date' => '1 day ago', 'status' => 'Approved'],
            ['id' => 3, 'product' => 'Eco Notebook', 'customer' => 'Spam Bot', 'rating' => 1, 'comment' => 'Buy cheap pills here http://spam.com', 'date' => '2 days ago', 'status' => 'Rejected'],
            ['id' => 4, 'product' => 'Vacuum Flask', 'customer' => 'Seun Adeyemi', 'rating' => 5, 'comment' => 'Keeps water cold for a very long time. Highly recommend for corporate gifts.', 'date' => '1 week ago', 'status' => 'Approved'],
        ];
        return View::renderTemplate('pages/admin/marketing/reviews', 'admin', ['title' => 'Product Reviews | Admin', 'reviews' => $reviews]);
    }
}
