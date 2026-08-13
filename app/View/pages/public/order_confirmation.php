<?php // app/View/pages/public/order_confirmation.php

// $order_page: ref, method, order (array|null), status_label, amount, date,
//              customer_email, found
$ref = $order_page['ref'] ?? htmlspecialchars($_GET['ref'] ?? '');
$method = $order_page['method'] ?? 'paystack';
$order = $order_page['order'] ?? null;
$statusLabel = $order_page['status_label'] ?? 'Thank you for your order';
$amount = $order_page['amount'] ?? null;
$date = $order_page['date'] ?? null;
$email = $order_page['customer_email'] ?? null;
$found = (bool) ($order_page['found'] ?? false);

$success = $found && ($order === null || in_array($order['payment_status'] ?? '', ['paid'], true))
    && in_array($statusLabel, ['Payment Successful!', 'Order Received!', 'Thank you for your order'], true);
$pending = in_array($statusLabel, ['Payment Pending', 'Order Received!', 'Thank you for your order'], true);
$failed = $statusLabel === 'Payment Failed';
$refunded = $statusLabel === 'Payment Refunded';

$methodLabel = match ((string) ($order['payment_method'] ?? $method)) {
    'flutterwave' => 'Flutterwave (Paid)',
    'paystack' => 'Paystack (Paid)',
    'transfer' => 'Bank Transfer (Pending)',
    default => $method === 'transfer' ? 'Bank Transfer (Pending)' : 'Online Payment',
};

// Compute the app base (mirrors public/index.php + header.php) so root links work
// regardless of the host or subdirectory the page is served from.
$__path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$__base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($__base === '/' || $__base === '.') {
    $__base = '';
}
if ($__base !== '' && $__base !== '/') {
    if (stripos($__path, $__base) === 0) {
        $__path = substr($__path, strlen($__base)) ?: '/';
    }
}
$__url = function (string $path) use ($__base): string {
    return $__base . '/' . ltrim($path, '/');
};
?>

<div style="background: var(--ivory); color: var(--ink); min-height: 100vh;">

    <section class="section" style="min-height: 80vh; display: grid; align-items: center; padding: 80px 0;">
        <div class="container" style="max-width: 640px;">
            <div class="cart-panel" style="padding: 40px 34px;">

                <div class="success-state">
                    <?php if ($success || $pending): ?>
                    <div class="ss-ico" style="color: #2e7d32; background: rgba(46, 125, 50, 0.1);">
                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    </div>
                    <?php elseif ($failed): ?>
                    <div class="ss-ico" style="color: var(--danger, #b53422); background: rgba(181, 52, 34, 0.1);">
                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    </div>
                    <?php elseif ($refunded): ?>
                    <div class="ss-ico" style="color: #9a7426; background: rgba(200, 155, 60, 0.14);">
                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    </div>
                    <?php endif; ?>

                    <h3><?= htmlspecialchars($statusLabel) ?></h3>

                    <?php if ($found && $order): ?>
                    <p>Thank you for your order<?= $email ? ' from <strong style="color: var(--ink);">' . htmlspecialchars($email) . '</strong>' : '' ?>. We'll confirm by email with your order details.</p>
                    <?php else: ?>
                    <p>We've received your request<?= $ref !== '' ? ' (Reference: <strong style="color: var(--ink);">' . htmlspecialchars($ref) . '</strong>)' : '' ?>.</p>
                    <?php endif; ?>
                </div>

                <?php if ($found && $order): ?>
                <div class="order-sum" style="margin: 26px 0 0;">
                    <div class="row"><span>Order Number</span><strong style="color: var(--ink); font-family: monospace;"><?= htmlspecialchars((string) $order['order_number']) ?></strong></div>
                    <div class="row"><span>Date</span><span><?= htmlspecialchars((string) $date) ?></span></div>
                    <div class="row"><span>Subtotal</span><span>&#8358;<?= number_format((float) $order['subtotal'], 2) ?></span></div>
                    <div class="row"><span>VAT</span><span>&#8358;<?= number_format((float) $order['tax'], 2) ?></span></div>
                    <div class="row"><span>Shipping</span><span>&#8358;<?= number_format((float) $order['shipping'], 2) ?></span></div>
                    <div class="row"><span>Total Amount</span><strong style="color: var(--gold-deep);">&#8358;<?= htmlspecialchars((string) $amount) ?></strong></div>
                    <div class="row"><span>Payment Method</span><span><?= htmlspecialchars($methodLabel) ?></span></div>
                </div>
                <?php endif; ?>

                <?php if ($method === 'transfer'): ?>
                <div class="order-sum" style="margin: 16px 0 0; background: var(--gold-fade); border-color: var(--gold);">
                    <p style="font-weight: 800; font-size: 14.5px; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--gold-deep);"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        Awaiting Payment
                    </p>
                    <p style="font-size: 13.5px; color: var(--ink-mute); margin-bottom: 14px;">Your order will not be processed until the funds have cleared in our account. Please make your payment directly into our bank account:</p>
                    <div class="bank-card">
                        Bank: GTBank<br>
                        Account Name: Marigold Signature Ltd<br>
                        Account No: 0123456789<br>
                        Reference: <?= htmlspecialchars((string) ($order['order_number'] ?? $ref)) ?>
                    </div>
                </div>
                <?php endif; ?>

                <div style="display: flex; gap: 14px; justify-content: center; margin-top: 30px; flex-wrap: wrap;">
                    <a href="<?= $__url('/shop') ?>" class="btn btn-gold btn-lg">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                        Continue Shopping
                    </a>
                    <button onclick="window.print()" class="btn btn-ghost btn-lg">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        Download Invoice
                    </button>
                </div>

            </div>
        </div>
    </section>

</div>
