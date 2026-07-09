<?php

namespace App\Core;

class Logger
{
    /**
     * Log a message to a specific file
     */
    public static function log(string $message, string $level = 'INFO', string $channel = 'application'): void
    {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $file = BASE_PATH . "/storage/logs/{$channel}_{$date}.log";
        
        $logMessage = "[$time] [$level] $message" . PHP_EOL;
        
        file_put_contents($file, $logMessage, FILE_APPEND | LOCK_EX);
    }
    
    public static function info(string $message, string $channel = 'application'): void
    {
        self::log($message, 'INFO', $channel);
    }
    
    public static function error(string $message, string $channel = 'application'): void
    {
        self::log($message, 'ERROR', $channel);
    }

    public static function warning(string $message, string $channel = 'application'): void
    {
        self::log($message, 'WARNING', $channel);
    }
}
