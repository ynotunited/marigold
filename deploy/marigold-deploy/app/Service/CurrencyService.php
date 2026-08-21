<?php

namespace App\Service;

use App\Core\Model;
use App\Core\FileCache;

/**
 * Currency exchange rate service.
 *
 * Rates are stored in the `settings` table as JSON under the key
 * `currency_rates` and cached in FileCache for fast reads. Rates are
 * base-to-target, e.g. "NGN_USD" => 0.00065 means 1 NGN = 0.00065 USD.
 *
 * For a new rate period, call CurrencyService::refresh() which fetches
 * from a free API and persists to the DB.
 */
class CurrencyService
{
    private const CACHE_KEY = 'currency_rates_v1';
    private const CACHE_TTL = 3600; // 1 hour
    private const FREE_API = 'https://open.er-api.com/v6/latest/NGN';

    /**
     * Get the exchange rate from $from to $to (both ISO 4217).
     * Rates are cached for performance.
     *
     * @return float|null  The rate, or null if unknown pair
     */
    public static function rate(string $from, string $to): ?float
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) {
            return 1.0;
        }

        $rates = self::allRates();

        // Try direct: $from_$to
        $key = $from . '_' . $to;
        if (isset($rates[$key])) {
            return (float) $rates[$key];
        }

        // Try inverse: $to_$to = 1 / ($to_$from)
        $inverseKey = $to . '_' . $from;
        if (isset($rates[$inverseKey]) && (float) $rates[$inverseKey] > 0) {
            return 1.0 / (float) $rates[$inverseKey];
        }

        // Cross-rate through NGN
        $toNgn = $from . '_NGN';
        $fromNgn = 'NGN_' . $to;
        if (isset($rates[$toNgn]) && (float) $rates[$toNgn] > 0 && isset($rates[$fromNgn])) {
            return (float) $rates[$fromNgn] / (float) $rates[$toNgn];
        }

        return null;
    }

    /**
     * Get all cached rates as an associative array.
     * Key format: "FROM_TO" => rate
     */
    public static function allRates(): array
    {
        $cached = FileCache::get(self::CACHE_KEY);
        if (is_array($cached) && !empty($cached)) {
            return $cached;
        }

        return self::loadFromDb();
    }

    /**
     * Refresh rates from the free API and persist to DB.
     * Called by admin action or scheduled job.
     */
    public static function refresh(): array
    {
        $rates = self::fetchFromApi();
        if (!empty($rates)) {
            self::persistToDb($rates);
            FileCache::set(self::CACHE_KEY, $rates, self::CACHE_TTL);
        }
        return $rates;
    }

    /**
     * Fetch rates from the free exchange rate API.
     * Returns key => value pairs like "NGN_USD" => 0.00065
     */
    private static function fetchFromApi(): array
    {
        $rates = [];

        $ch = curl_init(self::FREE_API);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $body = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$body) {
            \App\Core\Logger::error("Currency API fetch failed: {$err}", 'currency');
            return [];
        }

        $json = json_decode($body, true);
        if (!is_array($json) || ($json['result'] ?? '') !== 'success') {
            \App\Core\Logger::warning("Currency API returned non-success", 'currency');
            return [];
        }

        $baseCurrency = strtoupper($json['base_code'] ?? 'NGN');
        $apiRates = $json['rates'] ?? [];

        foreach ($apiRates as $code => $rate) {
            $code = strtoupper($code);
            if ($code !== $baseCurrency && is_numeric($rate)) {
                $rates[$baseCurrency . '_' . $code] = (float) $rate;
                // Also store inverse for cross-rate calculations
                if ((float) $rate > 0) {
                    $rates[$code . '_' . $baseCurrency] = round(1.0 / (float) $rate, 8);
                }
            }
        }

        return $rates;
    }

    /**
     * Persist rates to the settings table.
     */
    private static function persistToDb(array $rates): void
    {
        try {
            $db = Model::getDB();
            $json = json_encode($rates, JSON_UNESCAPED_SLASHES);

            $stmt = $db->prepare("
                INSERT INTO settings (`key`, `value`, `type`, `updated_at`)
                VALUES ('currency_rates', :v, 'json', NOW())
                ON DUPLICATE KEY UPDATE `value` = :v2, `updated_at` = NOW()
            ");
            $stmt->execute(['v' => $json, 'v2' => $json]);
        } catch (\Throwable $e) {
            \App\Core\Logger::error("Failed to persist currency rates: {$e->getMessage()}", 'currency');
        }
    }

    /**
     * Load rates from the settings table.
     */
    private static function loadFromDb(): array
    {
        try {
            $db = Model::getDB();
            $stmt = $db->query("SELECT `value` FROM settings WHERE `key` = 'currency_rates' LIMIT 1");
            $row = $stmt->fetch();
            if ($row && !empty($row['value'])) {
                $rates = json_decode($row['value'], true);
                if (is_array($rates)) {
                    FileCache::set(self::CACHE_KEY, $rates, self::CACHE_TTL);
                    return $rates;
                }
            }
        } catch (\Throwable $e) {
            \App\Core\Logger::error("Failed to load currency rates from DB: {$e->getMessage()}", 'currency');
        }

        return [];
    }

    /**
     * Get a formatted display string for a currency pair.
     * e.g. "1 USD = 1,540.00 NGN"
     */
    public static function pairDisplay(string $from, string $to): ?string
    {
        $rate = self::rate($from, $to);
        if ($rate === null) {
            return null;
        }

        $meta = \App\Core\Money::meta($to);
        $formatted = number_format($rate, $meta['fraction'] ?? 2, $meta['dec'] ?? '.', $meta['thou'] ?? ',');

        return '1 ' . strtoupper($from) . ' = ' . $formatted . ' ' . strtoupper($to);
    }
}
