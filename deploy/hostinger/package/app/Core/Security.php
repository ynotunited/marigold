<?php
namespace App\Core;

class Security
{
    /**
     * Generate a CSRF token and store it in session
     */
    public static function generateCsrfToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token from request
     */
    public static function validateCsrfToken($token)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            throw new \Exception('Invalid CSRF token');
        }
        return true;
    }

    /**
     * Hash password using bcrypt
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify password against hash
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Basic rate limiting mock (in-memory or file-based in production)
     */
    public static function checkRateLimit($action, $ip, $maxAttempts = 5, $decayMinutes = 1)
    {
        // Mock implementation
        return true; 
    }
}
