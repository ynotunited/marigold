<?php

namespace App\Controller\Customer;


use App\Core\Controller;
use App\Core\View;

class SettingsController extends Controller
{
    public function index()
    {
        $user = [
            'first_name' => 'David',
            'last_name' => 'Okon',
            'email' => 'david.okon@techsolutions.ng',
            'phone' => '+234 801 234 5678',
            'company' => 'TechSolutions Inc',
            'avatar' => null, // null = use initials
            'newsletter' => true
        ];

        return View::renderTemplate('pages/customer/settings', 'customer', [
            'title' => 'Account Settings | Marigold Signature',
            'user' => $user
        ]);
    }
}
