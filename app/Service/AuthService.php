<?php

namespace App\Service;

use App\Core\Service;
use App\Core\Model;
use App\Core\Session;
use App\Core\Logger;

class AuthService extends Service
{
    public static function login(string $email, string $password, bool $remember = false): bool
    {
        $db = Model::getDB();
        $email = strtolower(trim($email));

        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        $dummyHash = password_hash('invalid-password-placeholder', PASSWORD_BCRYPT, ['cost' => self::hashCost()]);
        $hash = $user['password'] ?? $dummyHash;

        if (!$user || !password_verify($password, $hash)) {
            Logger::warning("Login failed - invalid credentials. Email: $email IP: " . ($_SERVER['REMOTE_ADDR'] ?? ''), 'auth');
            return false;
        }

        if (($user['status'] ?? '') !== 'active') {
            Logger::warning("Login failed - inactive account. Email: $email", 'auth');
            return false;
        }

        if (empty($user['email_verified_at'])) {
            Logger::warning("Login failed - unverified email. Email: $email", 'auth');
            return false;
        }

        if (password_needs_rehash($user['password'], PASSWORD_BCRYPT, ['cost' => self::hashCost()])) {
            $db->prepare("UPDATE users SET password = :password WHERE id = :id")->execute([
                'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => self::hashCost()]),
                'id' => $user['id'],
            ]);
        }

        self::establishSession($user);
        $db->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id")->execute(['id' => $user['id']]);

        if ($remember) {
            self::generateRememberToken((int)$user['id']);
        }

        Logger::info("User logged in. ID: {$user['id']}", 'auth');
        return true;
    }

    public static function register(array $data): array
    {
        $db = Model::getDB();
        $email = strtolower(trim($data['email'] ?? ''));
        $password = (string)($data['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Please enter a valid email address.'];
        }
        if (strlen($password) < 12) {
            return ['error' => 'Password must be at least 12 characters.'];
        }

        $exists = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $exists->execute(['email' => $email]);
        if ($exists->fetchColumn()) {
            return ['error' => 'An account with this email already exists.'];
        }

        $verifyToken = bin2hex(random_bytes(32));
        $verifyHash = hash('sha256', $verifyToken);
        $uuid = self::uuid();

        $db->beginTransaction();
        try {
            $db->prepare("
                INSERT INTO users
                    (uuid, first_name, last_name, email, phone, password, status,
                     email_verify_token, email_verify_expires, created_at)
                VALUES
                    (:uuid, :first_name, :last_name, :email, :phone, :password, 'active',
                     :verify_token, DATE_ADD(NOW(), INTERVAL 24 HOUR), NOW())
            ")->execute([
                'uuid' => $uuid,
                'first_name' => trim($data['first_name'] ?? ''),
                'last_name' => trim($data['last_name'] ?? ''),
                'email' => $email,
                'phone' => trim($data['phone'] ?? '') ?: null,
                'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => self::hashCost()]),
                'verify_token' => $verifyHash,
            ]);

            $userId = (int)$db->lastInsertId();

            $role = $db->prepare("SELECT id FROM roles WHERE slug = 'customer' LIMIT 1");
            $role->execute();
            if ($roleId = $role->fetchColumn()) {
                $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)")
                    ->execute(['user_id' => $userId, 'role_id' => $roleId]);
            }

            $db->prepare("INSERT INTO customers (user_id, created_at) VALUES (:user_id, NOW())")
                ->execute(['user_id' => $userId]);

            $db->commit();
            Logger::info("New user registered. ID: $userId", 'auth');

            return ['user_id' => $userId, 'verify_token' => $verifyToken];
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function verifyEmail(string $token): bool
    {
        if (!preg_match('/^[a-f0-9]{64}$/i', $token)) {
            return false;
        }

        $db = Model::getDB();
        $stmt = $db->prepare("
            SELECT id FROM users
            WHERE email_verify_token = :token
              AND email_verify_expires > NOW()
              AND email_verified_at IS NULL
            LIMIT 1
        ");
        $stmt->execute(['token' => hash('sha256', $token)]);
        $userId = $stmt->fetchColumn();
        if (!$userId) {
            Logger::warning("Email verification failed - invalid/expired token. IP: " . ($_SERVER['REMOTE_ADDR'] ?? ''), 'auth');
            return false;
        }

        $db->prepare("
            UPDATE users
            SET email_verified_at = NOW(),
                email_verify_token = NULL,
                email_verify_expires = NULL
            WHERE id = :id
        ")->execute(['id' => $userId]);

        return true;
    }

    public static function createPasswordResetToken(string $email): ?string
    {
        $db = Model::getDB();
        $stmt = $db->prepare("
            SELECT id FROM users
            WHERE email = :email
              AND deleted_at IS NULL
              AND status = 'active'
              AND email_verified_at IS NOT NULL
            LIMIT 1
        ");
        $stmt->execute(['email' => strtolower(trim($email))]);
        $userId = $stmt->fetchColumn();
        if (!$userId) {
            return null;
        }

        $token = bin2hex(random_bytes(32));
        $db->prepare("
            UPDATE users
            SET password_reset_token = :token,
                password_reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR)
            WHERE id = :id
        ")->execute([
            'token' => hash('sha256', $token),
            'id' => $userId,
        ]);

        return $token;
    }

    public static function resetPassword(string $token, string $password): bool
    {
        if (!preg_match('/^[a-f0-9]{64}$/i', $token) || strlen($password) < 12) {
            return false;
        }

        $db = Model::getDB();
        $stmt = $db->prepare("
            SELECT id FROM users
            WHERE password_reset_token = :token
              AND password_reset_expires > NOW()
              AND deleted_at IS NULL
              AND status = 'active'
            LIMIT 1
        ");
        $stmt->execute(['token' => hash('sha256', $token)]);
        $userId = $stmt->fetchColumn();
        if (!$userId) {
            Logger::warning("Password reset failed - invalid/expired token. IP: " . ($_SERVER['REMOTE_ADDR'] ?? ''), 'auth');
            return false;
        }

        $db->prepare("
            UPDATE users
            SET password = :password,
                password_reset_token = NULL,
                password_reset_expires = NULL,
                remember_token = NULL
            WHERE id = :id
        ")->execute([
            'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => self::hashCost()]),
            'id' => $userId,
        ]);

        Logger::info("Password reset completed. ID: $userId", 'auth');
        return true;
    }

    public static function logout(): void
    {
        $userId = Session::get('user_id');
        if ($userId) {
            self::clearRememberToken((int)$userId);
            Logger::info("User logged out. ID: $userId", 'auth');
        }

        Session::destroy();
        self::expireRememberCookie();
    }

    protected static function generateRememberToken(int $userId): void
    {
        $selector = bin2hex(random_bytes(16));
        $verifier = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $verifier);

        Model::getDB()->prepare("UPDATE users SET remember_token = :token WHERE id = :id")
            ->execute(['token' => $selector . ':' . $tokenHash, 'id' => $userId]);

        setcookie('remember_token', $selector . ':' . $verifier, [
            'expires' => time() + 86400 * 30,
            'path' => '/',
            'secure' => self::isHttps(),
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
    }

    protected static function clearRememberToken(int $userId): void
    {
        Model::getDB()->prepare("UPDATE users SET remember_token = NULL WHERE id = :id")
            ->execute(['id' => $userId]);
    }

    public static function loginWithCookie(): bool
    {
        $cookie = $_COOKIE['remember_token'] ?? '';
        if (!$cookie || substr_count($cookie, ':') !== 1) {
            return false;
        }

        [$selector, $verifier] = explode(':', $cookie, 2);
        if (!preg_match('/^[a-f0-9]{32}$/i', $selector) || !preg_match('/^[a-f0-9]{64}$/i', $verifier)) {
            self::expireRememberCookie();
            return false;
        }

        $db = Model::getDB();
        $stmt = $db->prepare("
            SELECT * FROM users
            WHERE remember_token LIKE :selector
              AND deleted_at IS NULL
              AND status = 'active'
              AND email_verified_at IS NOT NULL
            LIMIT 1
        ");
        $stmt->execute(['selector' => $selector . ':%']);
        $user = $stmt->fetch();

        if (!$user || empty($user['remember_token'])) {
            self::expireRememberCookie();
            return false;
        }

        [, $storedHash] = explode(':', $user['remember_token'], 2);
        if (!hash_equals($storedHash, hash('sha256', $verifier))) {
            self::clearRememberToken((int)$user['id']);
            self::expireRememberCookie();
            Logger::warning("Remember-token mismatch. ID: {$user['id']}", 'auth');
            return false;
        }

        self::establishSession($user);
        self::generateRememberToken((int)$user['id']);
        Logger::info("User logged in via remember cookie. ID: {$user['id']}", 'auth');
        return true;
    }

    private static function establishSession(array $user): void
    {
        Session::regenerate();
        Session::set('user_id', (int)$user['id']);
        Session::set('user_uuid', $user['uuid']);
        Session::set('last_activity', time());

        $roleStmt = Model::getDB()->prepare("
            SELECT r.slug FROM roles r
            JOIN user_roles ur ON r.id = ur.role_id
            WHERE ur.user_id = :user_id
        ");
        $roleStmt->execute(['user_id' => $user['id']]);
        Session::set('user_roles', $roleStmt->fetchAll(\PDO::FETCH_COLUMN));
    }

    private static function hashCost(): int
    {
        return (int)($_ENV['BCRYPT_COST'] ?? 12);
    }

    private static function uuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private static function expireRememberCookie(): void
    {
        setcookie('remember_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => self::isHttps(),
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
    }

    private static function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? null) == 443);
    }
}
