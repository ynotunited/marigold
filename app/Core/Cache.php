<?php
namespace App\Core;

class Cache
{
    private static $cacheDir = __DIR__ . '/../../storage/cache/';

    /**
     * Get item from file-based cache
     */
    public static function get($key, $default = null)
    {
        $file = self::$cacheDir . md5($key) . '.cache';
        
        if (file_exists($file)) {
            $data = unserialize(file_get_contents($file));
            if ($data['expiry'] > time()) {
                return $data['value'];
            }
            // Expired
            unlink($file);
        }
        return $default;
    }

    /**
     * Set item in file-based cache
     */
    public static function set($key, $value, $ttl = 3600)
    {
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true);
        }

        $file = self::$cacheDir . md5($key) . '.cache';
        $data = [
            'value' => $value,
            'expiry' => time() + $ttl
        ];
        
        return file_put_contents($file, serialize($data)) !== false;
    }

    /**
     * Invalidate (delete) cache item
     */
    public static function forget($key)
    {
        $file = self::$cacheDir . md5($key) . '.cache';
        if (file_exists($file)) {
            return unlink($file);
        }
        return true;
    }

    /**
     * Clear all cached files
     */
    public static function flush()
    {
        if (!is_dir(self::$cacheDir)) return true;
        
        $files = glob(self::$cacheDir . '*.cache');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }
}
