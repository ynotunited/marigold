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
    public function events()
    {
        return View::renderTemplate('pages/public/events/index', 'main', [
            'title' => 'Events — Marigold Signature | Corporate Meetings, Conferences & Dinners',
            'meta_description' => 'Marigold Signature Nigeria Limited delivers branded merchandise and event solutions for corporate meetings, conferences and corporate dinners across Nigeria.',
            'page_key' => 'events'
        ]);
    }

    public function corporateMeeting()
    {
        return View::renderTemplate('pages/public/events/corporate-meeting', 'main', [
            'title' => 'Corporate Meetings | Marigold Signature',
            'meta_description' => 'Premium promotional merchandise and brand solutions for corporate meetings — branded notebooks, delegate kits, name tags, conference folders and more.',
            'page_key' => 'events'
        ]);
    }

    public function conference()
    {
        return View::renderTemplate('pages/public/events/conference', 'main', [
            'title' => 'Conferences | Marigold Signature',
            'meta_description' => 'Premium promotional merchandise and brand solutions for conferences — delegate bags, conference kits, exhibition giveaways, speaker gifts and more.',
            'page_key' => 'events'
        ]);
    }

    public function dinner()
    {
        return View::renderTemplate('pages/public/events/dinner', 'main', [
            'title' => 'Corporate Dinners | Marigold Signature',
            'meta_description' => 'Premium promotional merchandise and brand solutions for corporate dinners — executive gift sets, awards, table gifts, branded gift boxes and more.',
            'page_key' => 'events'
        ]);
    }

    public function about()
    {
        return View::renderTemplate('pages/public/about', 'main', [
            'title' => 'About — Marigold Signature | 15+ Years of Corporate Gifting Excellence',
            'meta_description' => "For over 15 years, Marigold Signature Nigeria Limited has partnered with Nigeria's most recognised organisations to design and deliver premium corporate gifts and branded merchandise.",
            'page_key' => 'about'
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
            'title' => 'Contact Us | Marigold Signature',
            'meta_description' => 'Contact Marigold Signature Nigeria Limited for corporate gifting, branded merchandise and event support. Lagos, Nigeria. Nationwide delivery.',
            'page_key' => 'contact'
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

    public function dataCompliance()
    {
        return View::renderTemplate('pages/public/static/data-compliance', 'main', [
            'title' => 'Data & Compliance | Marigold Signature'
        ]);
    }

    public function ipInfringement()
    {
        return View::renderTemplate('pages/public/static/ip-infringement', 'main', [
            'title' => 'Intellectual Property Infringement | Marigold Signature'
        ]);
    }
}
