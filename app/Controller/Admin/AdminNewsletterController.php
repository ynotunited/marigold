<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class AdminNewsletterController extends Controller
{
    public function subscribers()
    {
        $subscribers = [
            ['id' => 1, 'email' => 'david@techsol.ng', 'status' => 'Subscribed', 'source' => 'Checkout', 'date' => '2026-03-12', 'consent' => true],
            ['id' => 2, 'email' => 'adaeze@company.ng', 'status' => 'Subscribed', 'source' => 'Footer Form', 'date' => '2026-04-05', 'consent' => true],
            ['id' => 3, 'email' => 'blessing@nw.ng', 'status' => 'Unsubscribed', 'source' => 'Popup', 'date' => '2026-05-14', 'consent' => false],
            ['id' => 4, 'email' => 'info@novatech.ng', 'status' => 'Subscribed', 'source' => 'Quote Request', 'date' => '2026-06-22', 'consent' => true],
        ];
        return View::renderTemplate('pages/admin/newsletter/subscribers', 'admin', ['title' => 'Subscribers | Admin', 'subscribers' => $subscribers]);
    }

    public function campaign()
    {
        return View::renderTemplate('pages/admin/newsletter/campaign', 'admin', ['title' => 'Send Campaign | Admin']);
    }
}
