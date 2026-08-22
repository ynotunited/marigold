<?php

namespace App\Core;

class Session
{
    /**
     * Start the session
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $isHttps = filter_var($_ENV['SESSION_SECURE'] ?? false, FILTER_VALIDATE_BOOLEAN)
                || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (($_SERVER['SERVER_PORT'] ?? null) == 443);
            
            session_name($_ENV['SESSION_NAME'] ?? 'ms_sess');
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Lax');

            $sessionPath = defined('BASE_PATH') ? BASE_PATH . '/storage/sessions' : sys_get_temp_dir();
            if (!is_dir($sessionPath)) {
                @mkdir($sessionPath, 0755, true);
            }
            if (is_dir($sessionPath) && is_writable($sessionPath)) {
                session_save_path($sessionPath);
            }

            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            @session_start();

            if (!isset($_SESSION['_created_at'])) {
                $_SESSION['_created_at'] = time();
            }
        }
    }

    /**
     * Set a session variable
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Delete a session variable
     */
    public static function remove(string $key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy the session
     */
    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 3600,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Strict',
            ]);
        }
    }

    /**
     * Regenerate session ID for security
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
        $_SESSION['_created_at'] = time();
        unset($_SESSION['csrf_token']);
    }

    /**
     * Flash a message that persists for one request only.
     * Supports type (success, error, warning, info) and a title.
     */
    public static function flash(string $key, string $message, string $type = 'info', string $title = ''): void
    {
        if (!isset($_SESSION['_flash'])) {
            $_SESSION['_flash'] = [];
        }
        $_SESSION['_flash'][$key] = [
            'message' => $message,
            'type'    => $type,
            'title'   => $title,
        ];
    }

    /**
     * Convenience: flash a success message.
     */
    public static function success(string $message, string $title = 'Success'): void
    {
        self::flash('success', $message, 'success', $title);
    }

    /**
     * Convenience: flash an error message.
     */
    public static function error(string $message, string $title = 'Error'): void
    {
        self::flash('error', $message, 'error', $title);
    }

    /**
     * Convenience: flash a warning message.
     */
    public static function warning(string $message, string $title = 'Warning'): void
    {
        self::flash('warning', $message, 'warning', $title);
    }

    /**
     * Convenience: flash an info message.
     */
    public static function info(string $message, string $title = ''): void
    {
        self::flash('info', $message, 'info', $title);
    }

    /**
     * Get all pending flash messages and consume them (one-time read).
     * Returns array of ['key' => ..., 'message' => ..., 'type' => ..., 'title' => ...].
     */
    public static function getFlashes(): array
    {
        $flashes = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $flashes;
    }

    /**
     * Read a single flash message by key (consumes it).
     */
    public static function getFlash(string $key): ?array
    {
        $msg = $_SESSION['_flash'][$key] ?? null;
        if ($msg !== null) {
            unset($_SESSION['_flash'][$key]);
        }
        return $msg;
    }
}
