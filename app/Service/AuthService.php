<?php

namespace App\Service;

use App\Core\Service;
use App\Core\Model;
use App\Core\Session;
use App\Core\Logger;

class AuthService extends Service
{
    /**
     * Authenticate a user by email and password
     */
    public static function login(string $email, string $password, bool $remember = false): bool
    {
        $db = Model::getDB(); // Extends Model trick to get PDO
        
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND deleted_at IS NULL");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                Logger::warning("Login failed - Account inactive. Email: $email", 'auth');
                return false;
            }

            // Success
            Session::regenerate();
            Session::set('user_id', $user['id']);
            Session::set('user_uuid', $user['uuid']);
            
            // Load roles
            $roleStmt = $db->prepare("
                SELECT r.slug FROM roles r
                JOIN user_roles ur ON r.id = ur.role_id
                WHERE ur.user_id = :user_id
            ");
            $roleStmt->execute(['user_id' => $user['id']]);
            $roles = $roleStmt->fetchAll(\PDO::FETCH_COLUMN);
            Session::set('user_roles', $roles);

            // Update last login
            $updateStmt = $db->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id");
            $updateStmt->execute(['id' => $user['id']]);

            if ($remember) {
                self::generateRememberToken($user['id']);
            }

            Logger::info("User logged in. ID: {$user['id']}", 'auth');
            return true;
        }

        Logger::warning("Login failed - Invalid credentials. Email: $email", 'auth');
        return false;
    }

    /**
     * Logout the current user
     */
    public static function logout(): void
    {
        $userId = Session::get('user_id');
        if ($userId) {
            self::clearRememberToken($userId);
            Logger::info("User logged out. ID: $userId", 'auth');
        }
        
        Session::destroy();
        
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
    }

    /**
     * Generate a remember token
     */
    protected static function generateRememberToken(int $userId): void
    {
        $token = bin2hex(random_bytes(32));
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        
        $db = Model::getDB();
        $stmt = $db->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
        $stmt->execute(['token' => $hashedToken, 'id' => $userId]);

        // 30 days
        setcookie('remember_token', $userId . '|' . $token, time() + (86400 * 30), '/', '', false, true);
    }

    /**
     * Clear remember token
     */
    protected static function clearRememberToken(int $userId): void
    {
        $db = Model::getDB();
        $stmt = $db->prepare("UPDATE users SET remember_token = NULL WHERE id = :id");
        $stmt->execute(['id' => $userId]);
    }
    
    /**
     * Attempt login via Remember Me cookie
     */
    public static function loginWithCookie(): bool
    {
        if (!isset($_COOKIE['remember_token'])) {
            return false;
        }

        $parts = explode('|', $_COOKIE['remember_token']);
        if (count($parts) !== 2) {
            return false;
        }

        [$userId, $token] = $parts;

        $db = Model::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL AND status = 'active'");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        if ($user && $user['remember_token'] && password_verify($token, $user['remember_token'])) {
            Session::regenerate();
            Session::set('user_id', $user['id']);
            Session::set('user_uuid', $user['uuid']);
            
            // Reload roles
            $roleStmt = $db->prepare("SELECT r.slug FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = :user_id");
            $roleStmt->execute(['user_id' => $user['id']]);
            Session::set('user_roles', $roleStmt->fetchAll(\PDO::FETCH_COLUMN));

            Logger::info("User logged in via remember cookie. ID: {$user['id']}", 'auth');
            return true;
        }

        return false;
    }
}
