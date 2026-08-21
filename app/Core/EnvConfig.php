<?php

namespace App\Core;

/**
 * Environment-aware configuration.
 *
 * Reads APP_ENV from the loaded .env and provides helper methods to
 * distinguish development from production. Controllers and services can
 * use this to branch behaviour (e.g. dummy payment gateways in dev,
 * different DB prefixes, debug features).
 */
class EnvConfig
{
    /**
     * Check if the application is running in development mode.
     */
    public static function isDevelopment(): bool
    {
        return self::environment() === 'development';
    }

    /**
     * Check if the application is running in production.
     */
    public static function isProduction(): bool
    {
        return self::environment() === 'production';
    }

    /**
     * Check if the application is running in staging.
     */
    public static function isStaging(): bool
    {
        return self::environment() === 'staging';
    }

    /**
     * Get the current environment name.
     * Valid values: 'development', 'production', 'staging', 'testing'
     */
    public static function environment(): string
    {
        $env = strtolower(trim($_ENV['APP_ENV'] ?? 'development'));
        return in_array($env, ['development', 'production', 'staging', 'testing'], true)
            ? $env
            : 'development';
    }

    /**
     * Whether debug mode is enabled.
     * Always true in development; must be explicitly enabled in production.
     */
    public static function debug(): bool
    {
        if (self::isDevelopment()) {
            return true;
        }
        return ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
    }

    /**
     * Whether payment gateways should use sandbox/test mode.
     * Returns true in development and staging, unless APP_LIVE_PAYMENTS=true.
     */
    public static function useSandboxPayments(): bool
    {
        if (self::isProduction()) {
            return ($_ENV['APP_LIVE_PAYMENTS'] ?? 'false') !== 'true';
        }
        return true;
    }

    /**
     * Whether email sending is disabled (dev/staging default).
     * Set APP_MAIL_ENABLED=true to force email on in non-production.
     */
    public static function mailEnabled(): bool
    {
        if (self::isProduction()) {
            return true;
        }
        return ($_ENV['APP_MAIL_ENABLED'] ?? 'false') === 'true';
    }

    /**
     * Get a config value with a default fallback.
     */
    public static function get(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }

    /**
     * Get a required config value. Throws if missing.
     *
     * @throws \RuntimeException
     */
    public static function required(string $key)
    {
        if (!isset($_ENV[$key]) || $_ENV[$key] === '') {
            throw new \RuntimeException("Missing required environment variable: {$key}");
        }
        return $_ENV[$key];
    }
}
