<?php

namespace App\Controller\Customer;

use App\Core\Controller;
use App\Core\View;
use App\Core\Model;
use App\Core\Session;

class SettingsController extends Controller
{
    public function index()
    {
        $stmt = Model::getDB()->prepare("
            SELECT u.first_name, u.last_name, u.email, u.phone, u.avatar,
                   c.company_name AS company
            FROM users u
            LEFT JOIN customers c ON c.user_id = u.id
            WHERE u.id = :user_id
            LIMIT 1
        ");
        $stmt->execute(['user_id' => Session::get('user_id')]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new \Exception('Account not found', 404);
        }

        $user['newsletter'] = false;

        return View::renderTemplate('pages/customer/settings', 'customer', [
            'title' => 'Account Settings | Marigold Signature',
            'user' => $user
        ]);
    }
}
