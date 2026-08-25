<?php $inv = $invoice; ?>
<div class="min-h-screen bg-[var(--bg-primary)]">
    <!-- Header -->
    <header class="border-b border-[var(--border)]">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <img src="<?= app_url('/ms-logo.png') ?>" alt="Marigold Signature" class="h-[30px] w-auto object-contain">
            </a>
            <span class="text-xs text-[var(--text-muted)]">Invoice</span>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-16">
        <!-- Success -->
        <div class="text-center mb-12">
            <div class="w-20 h-20 rounded-full bg-green-500/10 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="check-circle" class="w-10 h-10 text-green-400"></i>
            </div>
            <h1 class="text-3xl font-bold font-[var(--font-display)] mb-3">Payment Successful!</h1>
            <p class="text-[var(--text-secondary)] text-lg">Thank you for your payment.</p>
        </div>

        <!-- Invoice Summary -->
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[16px] p-8 max-w-lg mx-auto mb-10">
            <div class="text-center mb-6">
                <p class="text-xs uppercase tracking-wider text-[var(--text-muted)] mb-1">Invoice Number</p>
                <p class="text-lg font-bold text-white"><?= htmlspecialchars($inv['invoice_number']) ?></p>
            </div>

            <div class="border-t border-[var(--border)] pt-4 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-[var(--text-secondary)]">Date Paid</span>
                    <span class="text-white"><?= date('F j, Y g:i A', strtotime($inv['paid_at'])) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[var(--text-secondary)]">Payment Method</span>
                    <span class="text-white"><?= htmlspecialchars(ucfirst($inv['payment_gateway'] ?? '—')) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[var(--text-secondary)]">Reference</span>
                    <span class="text-white font-mono text-xs"><?= htmlspecialchars($inv['payment_reference'] ?? '—') ?></span>
                </div>
                <div class="flex justify-between pt-3 border-t border-[var(--border)]">
                    <span class="text-white font-bold">Amount Paid</span>
                    <span class="text-[var(--gold)] font-bold text-lg"><?= money_format((float)$inv['total'], $inv['currency'] ?: 'NGN') ?></span>
                </div>
            </div>
        </div>

        <!-- Items Paid -->
        <div class="max-w-lg mx-auto mb-10">
            <h3 class="text-sm font-bold text-[var(--text-muted)] uppercase tracking-wider mb-3">Items Paid</h3>
            <div class="space-y-2">
                <?php foreach ($inv['items'] as $item): ?>
                    <div class="flex justify-between text-sm py-2 border-b border-[var(--border)] last:border-0">
                        <span class="text-white"><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                        <span class="text-[var(--text-secondary)]"><?= money_format((float)$item['total'], $inv['currency'] ?: 'NGN') ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Actions -->
        <div class="text-center space-y-3">
            <a href="/shop" class="btn btn-primary h-11 px-8 inline-flex items-center gap-2">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i> Continue Shopping
            </a>
            <div>
                <a href="/" class="text-sm text-[var(--text-muted)] hover:text-white transition-colors">Return to Home</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-[var(--border)] mt-10">
        <div class="max-w-4xl mx-auto px-6 py-6 text-center text-xs text-[var(--text-muted)]">
            © <?= date('Y') ?> Marigold Signature. All rights reserved.
        </div>
    </footer>
</div>

<script>lucide.createIcons();</script>
