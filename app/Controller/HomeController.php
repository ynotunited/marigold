<?php

namespace App\Controller;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return \App\Core\View::renderTemplate('pages/public/home', 'main', [
            'title' => 'Marigold Signature | Premium Corporate Merchandise'
        ]);
    }
}
