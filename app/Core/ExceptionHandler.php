<?php

namespace App\Core;

class ExceptionHandler
{
    /**
     * Exception handler — renders a user-friendly error page or returns JSON
     * for API/AJAX requests. In debug mode, shows full stack trace.
     */
    public static function handle(\Throwable $exception): void
    {
        $status = (int)$exception->getCode();
        if (!in_array($status, [403, 404, 405, 429], true)) {
            $status = 500;
        }

        http_response_code($status);

        $errorId = self::logException($exception, $status);

        $isAjax = self::isAjaxRequest();
        $isApi  = stripos($_SERVER['REQUEST_URI'] ?? '', '/api') === 0;

        // Debug mode: show full trace (only for non-AJAX/non-API in development)
        if (($_ENV['APP_DEBUG'] ?? 'false') === 'true' && !$isAjax && !$isApi) {
            self::renderDebug($exception, $errorId);
            return;
        }

        // API / AJAX: return JSON error
        if ($isAjax || $isApi) {
            header('Content-Type: application/json');
            echo json_encode([
                'error'   => true,
                'status'  => $status,
                'message' => self::friendlyMessage($status),
                'detail'  => ($_ENV['APP_DEBUG'] ?? 'false') === 'true'
                    ? $exception->getMessage()
                    : null,
                'error_id' => $errorId,
            ]);
            return;
        }

        // Web: render the appropriate error page
        $viewMap = [
            403 => 'pages/public/errors/403',
            404 => 'pages/public/errors/404',
            429 => 'pages/public/errors/429',
            500 => 'pages/public/errors/500',
        ];

        $view = $viewMap[$status] ?? 'pages/public/errors/500';
        $data = [
            'title'    => self::pageTitle($status),
            'error_id' => $errorId,
        ];

        // Graceful fallback: if the view template fails, serve a minimal HTML page
        try {
            View::renderTemplate($view, 'main', $data);
        } catch (\Throwable $fallback) {
            self::renderMinimal($status, $errorId);
        }
    }

    /**
     * Error handler — only escalate fatal-level PHP errors to exceptions.
     */
    public static function errorHandler($level, $message, $file, $line): void
    {
        if (error_reporting() !== 0 && in_array($level, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Human-readable message for each status code.
     */
    private static function friendlyMessage(int $status): string
    {
        return match ($status) {
            403 => 'You don\'t have permission to access this resource.',
            404 => 'The page you\'re looking for doesn\'t exist or has been moved.',
            429 => 'Too many requests. Please wait a moment and try again.',
            default => 'Something went wrong on our end. Please try again later.',
        };
    }

    /**
     * Page title for each status code.
     */
    private static function pageTitle(int $status): string
    {
        return match ($status) {
            403 => 'Access Denied',
            404 => 'Page Not Found',
            429 => 'Too Many Requests',
            default => 'Internal Server Error',
        };
    }

    /**
     * Detect if the request is AJAX (XHR or fetch).
     */
    private static function isAjaxRequest(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            || !empty($_SERVER['HTTP_ACCEPT'])
            && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json');
    }

    /**
     * Debug page (dev only) — shows full trace with styling.
     */
    private static function renderDebug(\Throwable $exception, string $errorId): void
    {
        $class = htmlspecialchars(get_class($exception), ENT_QUOTES, 'UTF-8');
        $msg   = htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
        $file  = htmlspecialchars($exception->getFile(), ENT_QUOTES, 'UTF-8');
        $trace = htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES, 'UTF-8');
        $line  = (int)$exception->getLine();
        $uri   = htmlspecialchars($_SERVER['REQUEST_URI'] ?? '—', ENT_QUOTES, 'UTF-8');
        $ip    = htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? '—', ENT_QUOTES, 'UTF-8');

        echo <<<HTML
<!DOCTYPE html>
<html><head><title>Error {$errorId}</title>
<style>
body{font-family:monospace;background:#111;color:#e5e5e5;padding:2rem;line-height:1.6;}
h1{color:#ef4444;font-size:1.4rem;}
h2{color:#f59e0b;font-size:1.1rem;margin-top:1.5rem;}
pre{background:#1a1a1a;border:1px solid #333;padding:1rem;border-radius:8px;overflow-x:auto;font-size:0.85rem;}
.code{color:#9ca3af;} .line{color:#f59e0b;font-weight:bold;}
</style></head><body>
<h1>Fatal Error <span style="color:#6b7280">{$errorId}</span></h1>
<p><strong>{$class}</strong>: {$msg}</p>
<p>Thrown in <span class="code">{$file}</span> on line <span class="line">{$line}</span></p>
<h2>Stack Trace</h2>
<pre>{$trace}</pre>
<p style="color:#6b7280;margin-top:2rem;">URI: {$uri} | IP: {$ip}</p>
</body></html>
HTML;
    }

    /**
     * Last-resort minimal HTML when even the error view fails.
     */
    private static function renderMinimal(int $status, string $errorId): void
    {
        $title = htmlspecialchars(self::pageTitle($status), ENT_QUOTES, 'UTF-8');
        $msg   = htmlspecialchars(self::friendlyMessage($status), ENT_QUOTES, 'UTF-8');
        header('Content-Type: text/html; charset=utf-8');
        echo <<<HTML
<!DOCTYPE html>
<html><head><title>{$title}</title></head>
<body style="font-family:system-ui,sans-serif;background:#f7f1e3;color:#1b1a15;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;">
<div style="text-align:center;max-width:500px;padding:2rem;">
<h1 style="font-size:3rem;color:#C89B3C;margin-bottom:0.5rem;">{$status}</h1>
<h2>{$title}</h2>
<p>{$msg}</p>
<p style="font-size:0.8rem;color:#999;">Reference: {$errorId}</p>
<a href="/" style="display:inline-block;margin-top:1.5rem;padding:0.75rem 1.5rem;background:#C89B3C;color:#1b1a15;text-decoration:none;border-radius:999px;font-weight:600;">Return to homepage</a>
</div></body></html>
HTML;
    }

    /**
     * Persist the exception to the application log and return a correlation ID.
     */
    private static function logException(\Throwable $exception, int $status): string
    {
        $errorId = substr(bin2hex(random_bytes(8)), 0, 16);

        $context = sprintf(
            '[%s] %s: %s in %s:%d | URI: %s | IP: %s',
            $errorId,
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $_SERVER['REQUEST_URI'] ?? '',
            $_SERVER['REMOTE_ADDR'] ?? ''
        );

        if (in_array($status, [403, 404, 405, 429], true)) {
            Logger::warning($context, 'http');
        } else {
            Logger::error($context . "\n" . $exception->getTraceAsString(), 'error');
        }

        return $errorId;
    }
}
