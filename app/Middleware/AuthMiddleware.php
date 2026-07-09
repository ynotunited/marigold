<?php

namespace App\Middleware;

use App\Core\Session;
use App\Service\AuthService;

class AuthMiddleware
{
    /**
     * Ensure the user is authenticated
     */
    public static function handle()
    {
        if (!Session::get('user_id') && !AuthService::loginWithCookie()) {
            Session::set('error', 'Please login to access this page.');
            header("Location: /login");
            exit;
        }

        // Session timeout handling (e.g. 1 hour of inactivity)
        $lastActivity = Session::get('last_activity');
        $timeout = 3600; // 60 mins

        if ($lastActivity && (time() - $lastActivity > $timeout)) {
            AuthService::logout();
            header("Location: /login?expired=1");
            exit;
        }

        Session::set('last_activity', time());
    }
}
