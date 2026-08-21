<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;

class AdminMessagesController extends Controller
{
    public function index()
    {
        $db = Model::getDB();
        $messages = $db->query(
            "SELECT id, name, company, email, phone, subject, message, status, created_at
             FROM contact_messages ORDER BY id DESC"
        )->fetchAll();

        $unread = 0;
        foreach ($messages as $m) {
            if ($m['status'] === 'new') {
                $unread++;
            }
        }

        return View::renderTemplate('pages/admin/messages/index', 'admin', [
            'title'    => 'Messages | Admin',
            'messages' => $messages,
            'unread'   => $unread,
        ]);
    }
}
