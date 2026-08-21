<?php

namespace App\Core;

/**
 * Contract every payment gateway adapter must satisfy.
 *
 * Implementations must:
 *   - never accept secrets from the client;
 *   - return amounts in minor units (kobo) as integers;
 *   - expose a webhook signature verifier used by the webhook ingress.
 */
interface PaymentGateway
{
    /**
     * Create an intent on the provider side. Returns:
     *   ['reference' => string, 'access_code' => string, 'authorization_url' => string, 'raw' => array]
     */
    public function initialize(array $params): array;

    /**
     * Reconcile a payment against the provider. Returns:
     *   ['status' => 'success'|'failed'|'pending'|'abandoned', 'amount_kobo' => int, 'currency' => string, 'raw' => array]
     */
    public function verify(string $reference): array;

    /**
     * Refund a captured payment. Returns:
     *   ['reference' => string, 'status' => 'processed'|'pending'|'failed', 'raw' => array]
     */
    public function refund(string $reference, int $amountKobo, array $meta = []): array;

    /**
     * Constant-time verification of an asynchronous webhook payload.
     */
    public function verifySignature(string $rawBody, string $signature): bool;
}
