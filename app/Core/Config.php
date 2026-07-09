<?php

namespace App\Core;

class Config
{
    protected static array $settings = [];

    /**
     * Load settings into memory, typically from the database or a config file
     */
    public static function load(array $config): void
    {
        self::$settings = array_merge(self::$settings, $config);
    }

    /**
     * Get a configuration value
     */
    public static function get(string $key, $default = null)
    {
        return self::$settings[$key] ?? $default;
    }

    /**
     * Set a configuration value temporarily in memory
     */
    public static function set(string $key, $value): void
    {
        self::$settings[$key] = $value;
    }
}
