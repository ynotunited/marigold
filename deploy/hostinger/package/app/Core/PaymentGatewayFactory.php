<?php

namespace App\Core;

use App\Service\FlutterwaveGateway;
use App\Service\PaystackGateway;

/**
 * Payment gateway registry. Maps a provider name onto its adapter so
 * PaymentService can route every operation (intent, capture, refund,
 * webhook) to the correct provider without knowing provider details.
 */
final class PaymentGatewayFactory
{
    public const GATEWAYS = ['paystack', 'flutterwave'];

    public static function make(string $gateway): PaymentGateway
    {
        return match ($gateway) {
            'paystack' => new PaystackGateway(),
            'flutterwave' => new FlutterwaveGateway(),
            default => throw new \InvalidArgumentException("Unsupported payment gateway: {$gateway}", 422),
        };
    }

    /**
     * Normalise aliases ('card', 'flw', …) onto canonical gateway names and
     * validate the result. Throws on an unknown gateway.
     */
    public static function normalize(string $gateway): string
    {
        $name = strtolower(trim($gateway));
        if (in_array($name, ['paystack', 'card'], true)) {
            return 'paystack';
        }
        if (in_array($name, ['flutterwave', 'flw'], true)) {
            return 'flutterwave';
        }
        throw new \InvalidArgumentException("Unsupported payment gateway: {$gateway}", 422);
    }
}
