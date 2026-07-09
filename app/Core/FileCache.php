<?php

namespace App\Core;

class FileCache
{
    protected static string $cacheDir = BASE_PATH . '/storage/cache/';

    /**
     * Set a value in the cache
     */
    public static function set(string $key, $value, int $ttl = 3600): void
    {
        $file = self::$cacheDir . md5($key) . '.cache';
        $data = [
            'expires' => time() + $ttl,
            'data' => serialize($value)
        ];
        
        file_put_contents($file, serialize($data), LOCK_EX);
    }

    /**
     * Get a value from the cache
     */
    public static function get(string $key)
    {
        $file = self::$cacheDir . md5($key) . '.cache';
        
        if (!file_exists($file)) {
            return null;
        }

        $content = file_get_contents($file);
        if (!$content) {
            return null;
        }

        $data = unserialize($content);
        
        if (time() > $data['expires']) {
            unlink($file);
            return null;
        }

        return unserialize($data['data']);
    }

    /**
     * Delete a value from the cache
     */
    public static function forget(string $key): void
    {
        $file = self::$cacheDir . md5($key) . '.cache';
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Clear entire cache
     */
    public static function clear(): void
    {
        $files = glob(self::$cacheDir . '*.cache');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
