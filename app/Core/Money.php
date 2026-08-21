<?php

namespace App\Core;

/**
 * Locale-aware money formatting and conversion.
 *
 * Base currency is always NGN (Nigerian Naira). All product prices in the
 * database are stored in NGN. Conversion happens at display/checkout time
 * using rates from CurrencyService.
 */
class Money
{
    private const BASE_CURRENCY = 'NGN';

    /**
     * Currency metadata: symbol, thousands separator, decimal separator, locale.
     */
    private const CURRENCIES = [
        'NGN' => ['symbol' => '₦', 'dec' => '.', 'thou' => ',', 'fraction' => 0, 'locale' => 'en-NG'],
        'USD' => ['symbol' => '$', 'dec' => '.', 'thou' => ',', 'fraction' => 2, 'locale' => 'en-US'],
        'GBP' => ['symbol' => '£', 'dec' => '.', 'thou' => ',', 'fraction' => 2, 'locale' => 'en-GB'],
        'EUR' => ['symbol' => '€', 'dec' => ',', 'thou' => '.', 'fraction' => 2, 'locale' => 'de-DE'],
        'GHS' => ['symbol' => 'GH₵', 'dec' => '.', 'thou' => ',', 'fraction' => 2, 'locale' => 'en-GH'],
        'ZAR' => ['symbol' => 'R', 'dec' => '.', 'thou' => ' ', 'fraction' => 2, 'locale' => 'en-ZA'],
        'KES' => ['symbol' => 'KSh', 'dec' => '.', 'thou' => ',', 'fraction' => 2, 'locale' => 'en-KE'],
        'XAF' => ['symbol' => 'FCFA', 'dec' => '.', 'thou' => ',', 'fraction' => 0, 'locale' => 'fr-CM'],
        'GMD' => ['symbol' => 'D', 'dec' => '.', 'thou' => ',', 'fraction' => 2, 'locale' => 'en-GM'],
    ];

    /**
     * Country code to default currency mapping.
     */
    public const COUNTRY_CURRENCY = [
        'NG' => 'NGN', 'US' => 'USD', 'GB' => 'GBP', 'DE' => 'EUR', 'FR' => 'EUR',
        'GH' => 'GHS', 'ZA' => 'ZAR', 'KE' => 'KES', 'CM' => 'XAF', 'SN' => 'XOF',
        'GM' => 'GMD', 'UG' => 'UGX', 'TZ' => 'TZS', 'ET' => 'ETB', 'RW' => 'RWF',
        'AE' => 'AED', 'SA' => 'SAR', 'EG' => 'EGP', 'JP' => 'JPY', 'CN' => 'CNY',
        'IN' => 'INR', 'CA' => 'CAD', 'AU' => 'AUD', 'SG' => 'SGD', 'JP' => 'JPY',
    ];

    /**
     * Format a price amount in a given currency.
     *
     * @param float  $amount  Price in major units (e.g. 15000 for ₦15,000)
     * @param string $code    ISO 4217 currency code (default: NGN)
     * @return string         Formatted string e.g. "₦15,000" or "$125.00"
     */
    public static function format(float $amount, string $code = self::BASE_CURRENCY): string
    {
        $code = strtoupper($code);
        $meta = self::CURRENCIES[$code] ?? self::CURRENCIES[self::BASE_CURRENCY];

        if ($code !== self::BASE_CURRENCY) {
            $amount = self::convert($amount, $code);
        }

        $negative = $amount < 0;
        $abs = abs($amount);
        $formatted = number_format($abs, $meta['fraction'], $meta['dec'], $meta['thou']);

        return ($negative ? '-' : '') . $meta['symbol'] . $formatted;
    }

    /**
     * Format with explicit currency code label after the number.
     * e.g. "NGN 15,000" or "USD 125.00"
     */
    public static function formatWithCode(float $amount, string $code = self::BASE_CURRENCY): string
    {
        $code = strtoupper($code);
        $meta = self::CURRENCIES[$code] ?? self::CURRENCIES[self::BASE_CURRENCY];

        $formatted = number_format($amount, $meta['fraction'], $meta['dec'], $meta['thou']);

        return $code . ' ' . $formatted;
    }

    /**
     * Convert an amount from NGN to a target currency.
     *
     * @param float  $amountNgn  Amount in NGN (major units)
     * @param string $toCurrency Target currency code
     * @return float             Converted amount in the target currency
     */
    public static function convert(float $amountNgn, string $toCurrency): float
    {
        $toCurrency = strtoupper($toCurrency);
        if ($toCurrency === self::BASE_CURRENCY) {
            return $amountNgn;
        }

        $rate = \App\Service\CurrencyService::rate(self::BASE_CURRENCY, $toCurrency);
        if ($rate === null || $rate <= 0) {
            return $amountNgn;
        }

        $meta = self::CURRENCIES[$toCurrency] ?? self::CURRENCIES[self::BASE_CURRENCY];

        return round($amountNgn * $rate, $meta['fraction']);
    }

    /**
     * Get the currency metadata for a given code.
     */
    public static function meta(string $code): array
    {
        $code = strtoupper($code);
        return self::CURRENCIES[$code] ?? self::CURRENCIES[self::BASE_CURRENCY];
    }

    /**
     * Get all supported currency codes.
     */
    public static function supportedCodes(): array
    {
        return array_keys(self::CURRENCIES);
    }

    /**
     * Alias for supportedCodes() — some callers expect this name.
     */
    public static function getAllCurrencies(): array
    {
        return self::supportedCodes();
    }

    /**
     * Format using the session-selected currency (or NGN fallback).
     * Convenience shorthand for customer-facing code.
     */
    public static function formatSession(float $amount): string
    {
        $code = \App\Core\Session::get('currency') ?? self::BASE_CURRENCY;
        return self::format($amount, $code);
    }

    /**
     * Resolve a currency code from a country code (2-letter ISO).
     */
    public static function currencyForCountry(string $countryCode): string
    {
        return self::COUNTRY_CURRENCY[strtoupper($countryCode)] ?? self::BASE_CURRENCY;
    }

    /**
     * Resolve currency from the browser's Intl API or IP geolocation header.
     * Falls back to NGN.
     */
    public static function detectFromRequest(): string
    {
        $country = self::detectCountry();
        if ($country) {
            return self::currencyForCountry($country);
        }
        return self::BASE_CURRENCY;
    }

    /**
     * Best-effort country detection from Cloudflare, GeoIP, or Accept-Language.
     */
    public static function detectCountry(): ?string
    {
        // Cloudflare header (most reliable on shared hosting behind CF)
        $cf = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? null;
        if ($cf && $cf !== 'XX' && strlen($cf) === 2) {
            return strtoupper($cf);
        }

        // MaxMind GeoIP header (if configured on reverse proxy)
        $geo = $_SERVER['HTTP_X_COUNTRY_CODE'] ?? null;
        if ($geo && strlen($geo) === 2) {
            return strtoupper($geo);
        }

        return null;
    }

    /**
     * JSON payload for JS currency context — injected into every page.
     */
    public static function jsContext(): array
    {
        $detected = self::detectFromRequest();
        $currency = Session::get('currency') ?? self::BASE_CURRENCY;
        $allRates = \App\Service\CurrencyService::allRates();
        $ratesToBase = [];
        foreach ($allRates as $pair => $rate) {
            $codes = explode('_', $pair);
            if (isset($codes[0]) && $codes[0] === self::BASE_CURRENCY && isset($codes[1])) {
                $ratesToBase[$codes[1]] = $rate;
            }
        }

        return [
            'base' => self::BASE_CURRENCY,
            'selected' => strtoupper($currency),
            'detected' => $detected ? strtoupper($detected) : null,
            'rates' => $ratesToBase,
            'currencies' => self::supportedCodes(),
        ];
    }
}
