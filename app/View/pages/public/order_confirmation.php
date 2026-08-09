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
?>

<div class="pt-32 pb-24 px-4 sm:px-8 bg-[var(--bg-primary)] min-h-screen flex items-center justify-center">
    <div class="container mx-auto max-w-2xl">

        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-12 text-center">

            <?php if ($success || $pending): ?>
            <div class="w-20 h-20 rounded-full bg-green-500/10 border border-green-500/30 flex items-center justify-center mx-auto mb-8">
                <i data-lucide="check-circle" class="w-10 h-10 text-green-500"></i>
            </div>
            <?php elseif ($failed): ?>
            <div class="w-20 h-20 rounded-full bg-red-500/10 border border-red-500/30 flex items-center justify-center mx-auto mb-8">
                <i data-lucide="alert-circle" class="w-10 h-10 text-red-500"></i>
            </div>
            <?php elseif ($refunded): ?>
            <div class="w-20 h-20 rounded-full bg-yellow-500/10 border border-yellow-500/30 flex items-center justify-center mx-auto mb-8">
                <i data-lucide="rotate-ccw" class="w-10 h-10 text-yellow-500"></i>
            </div>
            <?php endif; ?>

            <h1 class="font-['Manrope'] text-3xl sm:text-4xl font-extrabold mb-4 text-white">
                <?= htmlspecialchars($statusLabel) ?>
            </h1>

            <?php if ($found && $order): ?>
            <p class="text-[var(--text-secondary)] text-lg mb-8">
                Thank you for your order<?= $email ? ' from <strong class="text-white">' . htmlspecialchars($email) . '</strong>' : '' ?>. We'll confirm by email with your order details.
            </p>
            <?php else: ?>
            <p class="text-[var(--text-secondary)] text-lg mb-8">
                We've received your request<?= $ref !== '' ? ' (Reference: <strong class="text-white">' . htmlspecialchars($ref) . '</strong>)' : '' ?>.
            </p>
            <?php endif; ?>

            <?php if ($found && $order): ?>
            <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-6 mb-8 text-left">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-bold mb-1">Order Number</p>
                        <p class="font-mono font-bold text-white"><?= htmlspecialchars((string) $order['order_number']) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-bold mb-1">Date</p>
                        <p class="text-white"><?= htmlspecialchars((string) $date) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-bold mb-1">Total Amount</p>
                        <p class="font-['Manrope'] font-bold text-[var(--gold)]">₦<?= htmlspecialchars((string) $amount) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-bold mb-1">Payment Method</p>
                        <p class="text-white"><?= $method === 'transfer' ? 'Bank Transfer (Pending)' : 'Paystack (Paid)' ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($method === 'transfer'): ?>
            <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-2xl p-6 mb-8 text-left">
                <div class="flex items-start gap-4">
                    <i data-lucide="info" class="w-6 h-6 text-yellow-500 shrink-0 mt-1"></i>
                    <div>
                        <h4 class="font-bold text-white mb-2">Awaiting Payment</h4>
                        <p class="text-sm text-[var(--text-secondary)] mb-4">Your order will not be processed until the funds have cleared in our account. Please make your payment directly into our bank account:</p>
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-xl p-4 text-sm font-mono text-[var(--text-muted)]">
                            Bank: GTBank<br>
                            Account Name: Marigold Signature Ltd<br>
                            Account No: 0123456789<br>
                            Reference: <?= htmlspecialchars((string) ($order['order_number'] ?? $ref)) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/shop" class="w-full sm:w-auto bg-[var(--gold)] text-black font-bold px-8 py-4 rounded-xl hover:bg-[#D4AF37] transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i> Continue Shopping
                </a>
                <button onclick="window.print()" class="w-full sm:w-auto bg-[var(--card)] border border-[var(--border)] text-white font-bold px-8 py-4 rounded-xl hover:border-[var(--gold)] hover:text-[var(--gold)] transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="download" class="w-5 h-5"></i> Download Invoice
                </button>
            </div>

        </div>
    </div>
</div>
