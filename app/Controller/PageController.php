<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;

class PageController extends Controller
{
    // ── Category Pages ──────────────────────────────────────────
    public function categoryDrinkware()
    {
        return View::renderTemplate('pages/public/categories/drinkware', 'main', [
            'title' => 'Drinkware | Marigold Signature'
        ]);
    }
    public function categoryTechnology()
    {
        return View::renderTemplate('pages/public/categories/technology-accessories', 'main', [
            'title' => 'Technology & Accessories | Marigold Signature'
        ]);
    }
    public function categoryBags()
    {
        return View::renderTemplate('pages/public/categories/bags-travel', 'main', [
            'title' => 'Bags & Travel | Marigold Signature'
        ]);
    }
    public function categoryApparels()
    {
        return View::renderTemplate('pages/public/categories/apparels', 'main', [
            'title' => 'Apparels | Marigold Signature'
        ]);
    }
    public function categoryCorporateGifts()
    {
        return View::renderTemplate('pages/public/categories/corporate-gifts', 'main', [
            'title' => 'Corporate Gifts | Marigold Signature'
        ]);
    }
    public function categorySouvenirs()
    {
        return View::renderTemplate('pages/public/categories/souvenirs', 'main', [
            'title' => 'Souvenirs | Marigold Signature'
        ]);
    }
    public function categorySeasonalGifts()
    {
        return View::renderTemplate('pages/public/categories/seasonal-gifts', 'main', [
            'title' => 'Seasonal Gifts | Marigold Signature'
        ]);
    }

    // ── Event Pages ──────────────────────────────────────────────
    public function corporateMeeting()
    {
        return View::renderTemplate('pages/public/events/corporate-meeting', 'main', [
            'title' => 'Corporate Meeting Solutions | Marigold Signature'
        ]);
    }

    public function conference()
    {
        return View::renderTemplate('pages/public/events/conference', 'main', [
            'title' => 'Conference Solutions | Marigold Signature'
        ]);
    }

    public function dinner()
    {
        return View::renderTemplate('pages/public/events/dinner', 'main', [
            'title' => 'Corporate Dinner Solutions | Marigold Signature'
        ]);
    }

    public function about()
    {
        return View::renderTemplate('pages/public/about', 'main', [
            'title' => 'About Us | Marigold Signature'
        ]);
    }

    public function solutions()
    {
        return View::renderTemplate('pages/public/solutions', 'main', [
            'title' => 'Corporate Solutions | Marigold Signature'
        ]);
    }

    public function contact()
    {
        return View::renderTemplate('pages/public/contact', 'main', [
            'title' => 'Contact Us | Marigold Signature'
        ]);
    }

    public function privacy()
    {
        return View::renderTemplate('pages/public/static/privacy', 'main', [
            'title' => 'Privacy Policy | Marigold Signature'
        ]);
    }

    public function terms()
    {
        return View::renderTemplate('pages/public/static/terms', 'main', [
            'title' => 'Terms & Conditions | Marigold Signature'
        ]);
    }

    public function shipping()
    {
        return View::renderTemplate('pages/public/static/shipping', 'main', [
            'title' => 'Shipping Policy | Marigold Signature'
        ]);
    }

    public function returns()
    {
        return View::renderTemplate('pages/public/static/returns', 'main', [
            'title' => 'Return Policy | Marigold Signature'
        ]);
    }
}
