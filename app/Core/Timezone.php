<?php

namespace App\Core;

/**
 * Timezone-aware helpers.
 *
 * All scheduled events (emails, notifications, announcements) are stored and
 * processed in UTC. User-facing times are converted to the user's stored
 * timezone before display.
 */
class Timezone
{
    private const FALLBACK = 'Africa/Lagos';

    /**
     * Get the server default timezone from .env (APP_TIMEZONE).
     */
    public static function serverDefault(): string
    {
        $env = $_ENV['APP_TIMEZONE'] ?? getenv('APP_TIMEZONE');
        return ($env && in_array($env, \DateTimeZone::listIdentifiers(), true)) ? $env : self::FALLBACK;
    }

    /**
     * Get the timezone for the current user (logged in) or the detected
     * timezone from the session/cookie.
     */
    public static function forCurrentUser(): string
    {
        $userId = Session::get('user_id');
        if ($userId) {
            $tz = self::forUser((int) $userId);
            if ($tz) {
                return $tz;
            }
        }

        return Session::get('timezone') ?? self::detectFromRequest() ?? self::serverDefault();
    }

    /**
     * Get the timezone for a specific user from the DB.
     */
    public static function forUser(int $userId): ?string
    {
        try {
            $stmt = Model::getDB()->prepare("SELECT timezone FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $userId]);
            $tz = $stmt->fetchColumn();
            return ($tz && in_array($tz, \DateTimeZone::listIdentifiers(), true)) ? $tz : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Detect timezone from browser Intl API (passed as cookie/header) or
     * from country-based heuristic.
     */
    public static function detectFromRequest(): ?string
    {
        // Explicit cookie/header (set by JS Intl.DateTimeFormat)
        $tz = $_COOKIE['ms_tz'] ?? $_SERVER['HTTP_X_TIMEZONE'] ?? null;
        if ($tz && in_array($tz, \DateTimeZone::listIdentifiers(), true)) {
            return $tz;
        }

        // Country-based heuristic
        $country = \App\Core\Money::detectCountry();
        if ($country) {
            return self::timezoneForCountry($country);
        }

        return null;
    }

    /**
     * Convert a UTC datetime string to a target timezone and format it.
     *
     * @param string $utcDate    DATETIME string in UTC
     * @param string $tzName     IANA timezone name
     * @param string $format     PHP date format
     * @return string            Formatted datetime string
     */
    public static function convert(string $utcDate, string $tzName = self::FALLBACK, string $format = 'F j, Y'): string
    {
        try {
            $dt = new \DateTime($utcDate, new \DateTimeZone('UTC'));
            $dt->setTimezone(new \DateTimeZone($tzName));
            return $dt->format($format);
        } catch (\Throwable $e) {
            return $utcDate;
        }
    }

    /**
     * Get the current time in a specific timezone.
     */
    public static function now(string $tzName = self::FALLBACK, string $format = 'Y-m-d H:i:s'): string
    {
        return (new \DateTime('now', new \DateTimeZone($tzName)))->format($format);
    }

    /**
     * Get UTC datetime for a scheduled event in a user's timezone.
     * Used when you want to send an email at e.g. "9am in the user's timezone".
     *
     * @param string $localTime  Time string in the user's timezone (e.g. "2026-08-22 09:00:00")
     * @param string $tzName     IANA timezone name of the user
     * @return string            UTC datetime string
     */
    public static function toUtc(string $localTime, string $tzName): string
    {
        try {
            $dt = new \DateTime($localTime, new \DateTimeZone($tzName));
            $dt->setTimezone(new \DateTimeZone('UTC'));
            return $dt->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return $localTime;
        }
    }

    /**
     * Check if a UTC datetime has passed in a given timezone.
     */
    public static function hasPassed(string $utcDate, string $tzName = self::FALLBACK): bool
    {
        try {
            $now = new \DateTime('now', new \DateTimeZone('UTC'));
            $target = new \DateTime($utcDate, new \DateTimeZone('UTC'));
            return $now >= $target;
        } catch (\Throwable $e) {
            return true;
        }
    }

    /**
     * Timezone identifier list grouped by region (for select dropdowns).
     */
    public static function groupedOptions(): array
    {
        $grouped = [];
        $regions = ['Africa', 'America', 'Asia', 'Europe', 'Australia', 'Pacific'];
        $all = \DateTimeZone::listIdentifiers();

        foreach ($all as $tz) {
            $parts = explode('/', $tz);
            if (count($parts) < 2) continue;
            $region = $parts[0];
            if (in_array($region, $regions, true)) {
                $grouped[$region][] = $tz;
            }
        }

        return $grouped;
    }

    /**
     * Get the IANA timezone for a 2-letter country code.
     */
    private static function timezoneForCountry(string $cc): ?string
    {
        $map = [
            'NG' => 'Africa/Lagos', 'GH' => 'Africa/Accra', 'KE' => 'Africa/Nairobi',
            'ZA' => 'Africa/Johannesburg', 'EG' => 'Africa/Cairo', 'TZ' => 'Africa/Dar_es_Salaam',
            'UG' => 'Africa/Kampala', 'ET' => 'Africa/Addis_Ababa', 'CM' => 'Africa/Douala',
            'SN' => 'Africa/Dakar', 'GM' => 'Africa/Banjul', 'RW' => 'Africa/Kigali',
            'US' => 'America/New_York', 'GB' => 'Europe/London', 'DE' => 'Europe/Berlin',
            'FR' => 'Europe/Paris', 'JP' => 'Asia/Tokyo', 'CN' => 'Asia/Shanghai',
            'IN' => 'Asia/Kolkata', 'AE' => 'Asia/Dubai', 'SA' => 'Asia/Riyadh',
            'SG' => 'Asia/Singapore', 'AU' => 'Australia/Sydney', 'CA' => 'America/Toronto',
        ];

        return $map[strtoupper($cc)] ?? null;
    }
}
