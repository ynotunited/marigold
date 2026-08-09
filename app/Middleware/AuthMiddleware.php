<?php

namespace App\Middleware;

use App\Core\Session;
use App\Core\Logger;
use App\Service\AuthService;

class AuthMiddleware
{
    /**
     * Ensure the user is authenticated.
     * Also redirects admins away from customer-facing routes.
     */
    public static function handle()
    {
        $base = defined('ASSET_BASE') ? ASSET_BASE : '';

        if (!Session::get('user_id') && !AuthService::loginWithCookie()) {
            Session::set('error', 'Please login to access this page.');
            header("Location: {$base}/login");
            exit;
        }

        // Session timeout handling (inactivity).
        $lastActivity = Session::get('last_activity');
        $timeout = (int)($_ENV['SESSION_TIMEOUT'] ?? 3600);

        if ($lastActivity && (time() - $lastActivity > $timeout)) {
            Logger::warning("Session expired due to inactivity. ID: " . Session::get('user_id'), 'auth');
            AuthService::logout();
            header("Location: {$base}/login?expired=1");
            exit;
        }

        // Absolute session lifetime (forces re-login after a fixed period).
        $absoluteTtl = (int)($_ENV['SESSION_ABSOLUTE_TTL'] ?? 28800);
        $createdAt = Session::get('_created_at');
        if ($createdAt && $absoluteTtl > 0 && (time() - (int)$createdAt > $absoluteTtl)) {
            Logger::warning("Session absolute lifetime reached. ID: " . Session::get('user_id'), 'auth');
            AuthService::logout();
            header("Location: {$base}/login?expired=1");
            exit;
        }

        Session::set('last_activity', time());

        // Admins should not access customer portal pages (separation of concerns).
        $roles       = Session::get('user_roles') ?? [];
        $isAdmin     = in_array('super-admin', $roles) || in_array('admin', $roles);
        $uri         = $_SERVER['REQUEST_URI'] ?? '';
        $isCustomer  = strpos($uri, '/account/') !== false || strpos($uri, '/customer/') !== false;

        if ($isAdmin && $isCustomer) {
            header("Location: {$base}/admin");
            exit;
        }
    }
}
