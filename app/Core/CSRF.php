<?php

namespace App\Core;

class CSRF
{
    /**
     * Generate and store a CSRF token
     */
    public static function generate(): string
    {
        if (empty(Session::get('csrf_token'))) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('csrf_token');
    }

    /**
     * Verify the CSRF token from a request
     */
    public static function verify(string $token): bool
    {
        $stored = Session::get('csrf_token');
        if (empty($stored) || empty($token)) {
            return false;
        }
        return hash_equals($stored, $token);
    }

    /**
     * Get HTML hidden input field for CSRF
     */
    public static function field(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
