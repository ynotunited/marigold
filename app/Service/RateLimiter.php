<?php

namespace App\Service;

use App\Core\Service;
use App\Core\FileCache;

class RateLimiter extends Service
{
    /**
     * Check if an action is rate limited
     */
    public static function tooManyAttempts(string $key, int $maxAttempts = 5): bool
    {
        $attempts = FileCache::get('ratelimit_' . $key) ?? 0;
        return $attempts >= $maxAttempts;
    }

    /**
     * Increment the attempt count
     */
    public static function hit(string $key, int $decaySeconds = 60): int
    {
        $cacheKey = 'ratelimit_' . $key;
        $attempts = FileCache::get($cacheKey) ?? 0;
        $attempts++;
        FileCache::set($cacheKey, $attempts, $decaySeconds);
        return $attempts;
    }

    /**
     * Clear the attempts
     */
    public static function clear(string $key): void
    {
        FileCache::forget('ratelimit_' . $key);
    }
}
