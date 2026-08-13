<?php

namespace App\Service;

use App\Core\Model;

/**
 * Reads configuration values persisted in the `settings` table.
 */
class Settings
{
    private static ?array $cache = null;

    /**
     * Load all settings once per request.
     */
    private static function all(): array
    {
        if (self::$cache === null) {
            $cache = [];
            try {
                $rows = Model::getDB()->query("SELECT `key`, `value` FROM settings")->fetchAll();
                foreach ($rows as $r) {
                    $cache[$r['key']] = $r['value'];
                }
            } catch (\Throwable $e) {
                // Table may not exist during migrations/install — fall back to defaults.
            }
            self::$cache = $cache;
        }
        return self::$cache;
    }

    /**
     * Get a setting value with a fallback default.
     */
    public static function get(string $key, $default = null)
    {
        return self::all()[$key] ?? $default;
    }

    /**
     * Get a numeric setting (e.g. tax rate in percent) as a float.
     */
    public static function getFloat(string $key, float $default = 0.0): float
    {
        return (float) (self::get($key, $default) ?? $default);
    }
}
