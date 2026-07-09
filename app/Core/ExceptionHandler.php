<?php

namespace App\Core;

class ExceptionHandler
{
    /**
     * Exception handler
     */
    public static function handle(\Throwable $exception): void
    {
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        
        http_response_code($code);

        if ($_ENV['APP_DEBUG'] === 'true') {
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        } else {
            $log = BASE_PATH . '/storage/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);
            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();
            error_log($message);
            
            // Render user-friendly error page
            if ($code == 404) {
                View::renderTemplate("pages/public/errors/404", "main", ['title' => 'Page Not Found']);
            } else {
                View::renderTemplate("pages/public/errors/500", "main", ['title' => 'Internal Server Error']);
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
}
