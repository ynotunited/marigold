<?php

namespace App\Core;

class ExceptionHandler
{
    /**
     * Exception handler
     */
    public static function handle(\Throwable $exception): void
    {
        $status = (int)$exception->getCode();
        if (!in_array($status, [403, 404, 405, 429], true)) {
            $status = 500;
        }

        http_response_code($status);

        $errorId = self::logException($exception, $status);

        if (($_ENV['APP_DEBUG'] ?? 'false') === 'true') {
            echo "<h1>Fatal error</h1>";
            echo "<p>Error ID: " . htmlspecialchars($errorId, ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8') . "'</p>";
            echo "<p>Stack trace:<pre>" . htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES, 'UTF-8') . "</pre></p>";
            echo "<p>Thrown in '" . htmlspecialchars($exception->getFile(), ENT_QUOTES, 'UTF-8') . "' on line " . $exception->getLine() . "</p>";
        } else {
            if ($status === 404) {
                View::renderTemplate("pages/public/errors/404", "main", ['title' => 'Page Not Found']);
            } else {
                View::renderTemplate("pages/public/errors/500", "main", [
                    'title' => $status === 403 ? 'Access Denied' : 'Internal Server Error',
                    'error_id' => $errorId,
                ]);
            }
        }
    }

    /**
     * Error handler
     */
    public static function errorHandler($level, $message, $file, $line): void
    {
        if (error_reporting() !== 0) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
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
