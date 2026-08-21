<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;

class AdminNewsletterController extends Controller
{
    public function subscribers()
    {
        $db = Model::getDB();
        $rows = $db->query(
            "SELECT id, email, source, consent, status, created_at
             FROM newsletters ORDER BY id DESC"
        )->fetchAll();

        $subscribers = array_map(function ($r) {
            return [
                'id'      => (int) $r['id'],
                'email'   => $r['email'],
                'status'  => $r['status'] === 'unsubscribed' ? 'Unsubscribed' : 'Subscribed',
                'source'  => $r['source'] ?? 'Footer',
                'date'    => date('Y-m-d', strtotime($r['created_at'])),
                'consent' => (bool) $r['consent'],
            ];
        }, $rows);

        return View::renderTemplate('pages/admin/newsletter/subscribers', 'admin', ['title' => 'Subscribers | Admin', 'subscribers' => $subscribers]);
    }

    public function campaign()
    {
        return View::renderTemplate('pages/admin/newsletter/campaign', 'admin', ['title' => 'Send Campaign | Admin']);
    }
}
