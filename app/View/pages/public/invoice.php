<?php
$inv = $invoice;
$isCancelled = $cancelled ?? false;
?>
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

    <main class="max-w-4xl mx-auto px-6 py-10">
        <?php if ($isCancelled): ?>
            <div class="text-center py-16">
                <div class="w-16 h-16 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="x-circle" class="w-8 h-8 text-red-400"></i>
                </div>
                <h1 class="text-2xl font-bold mb-2">Invoice Cancelled</h1>
                <p class="text-[var(--text-secondary)]">This invoice has been cancelled and is no longer payable.</p>
                <a href="/" class="btn btn-primary mt-6 inline-flex items-center gap-2">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Shop
                </a>
            </div>
        <?php else: ?>
            <!-- Invoice Header -->
            <div class="flex flex-col md:flex-row justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-2xl font-bold font-[var(--font-display)] text-[var(--gold)]">INVOICE</h1>
                    <p class="text-lg font-medium mt-1"><?= htmlspecialchars($inv['invoice_number']) ?></p>
                    <p class="text-sm text-[var(--text-muted)] mt-1">
                        Issued: <?= date('F j, Y', strtotime($inv['created_at'])) ?>
                        <?php if ($inv['due_date']): ?>
                            · Due: <?= date('F j, Y', strtotime($inv['due_date'])) ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="text-right">
                    <img src="<?= app_url('/ms-logo.png') ?>" alt="Marigold Signature" class="h-[35px] w-auto object-contain ml-auto mb-2">
                    <p class="text-sm text-[var(--text-secondary)]">Marigold Signature</p>
                </div>
            </div>

            <!-- Bill To -->
            <div class="mb-8">
                <p class="text-xs uppercase tracking-wider text-[var(--text-muted)] mb-2">Bill To</p>
                <p class="text-white font-medium"><?= htmlspecialchars($inv['customer_name']) ?></p>
                <p class="text-sm text-[var(--text-secondary)]"><?= htmlspecialchars($inv['customer_email']) ?></p>
                <?php if ($inv['customer_phone']): ?>
                    <p class="text-sm text-[var(--text-secondary)]"><?= htmlspecialchars($inv['customer_phone']) ?></p>
                <?php endif; ?>
            </div>

            <?php if ($isCancelled === false): ?>
            <!-- Status Badge -->
            <?php if ($inv['status'] === 'paid'): ?>
                <div class="bg-green-500/5 border border-green-500/20 rounded-[12px] p-4 mb-8 flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-400"></i>
                    <div>
                        <p class="text-green-400 font-medium text-sm">Payment Confirmed</p>
                        <p class="text-xs text-green-400/70">Paid on <?= date('F j, Y g:i A', strtotime($inv['paid_at'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Items -->
            <div class="border border-[var(--border)] rounded-[12px] overflow-hidden mb-8">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)]">
                            <th class="px-6 py-3 font-medium">Item</th>
                            <th class="px-6 py-3 font-medium text-center">Qty</th>
                            <th class="px-6 py-3 font-medium text-right">Price</th>
                            <th class="px-6 py-3 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inv['items'] as $item): ?>
                            <tr class="border-t border-[var(--border)]">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-white"><?= htmlspecialchars($item['name']) ?></p>
                                    <?php if ($item['description']): ?>
                                        <p class="text-xs text-[var(--text-muted)] mt-0.5"><?= htmlspecialchars($item['description']) ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-center text-[var(--text-secondary)]"><?= $item['quantity'] ?></td>
                                <td class="px-6 py-4 text-sm text-right text-[var(--text-secondary)]"><?= money_format((float)$item['unit_price'], $inv['currency'] ?: 'NGN') ?></td>
                                <td class="px-6 py-4 text-sm text-right font-medium text-white"><?= money_format((float)$item['total'], $inv['currency'] ?: 'NGN') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="flex justify-end mb-10">
                <div class="w-full max-w-xs space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[var(--text-secondary)]">Subtotal</span>
                        <span class="text-white"><?= money_format((float)$inv['subtotal'], $inv['currency'] ?: 'NGN') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[var(--text-secondary)]">Tax (<?= $inv['tax_rate'] ?>%)</span>
                        <span class="text-white"><?= money_format((float)$inv['tax_amount'], $inv['currency'] ?: 'NGN') ?></span>
                    </div>
                    <?php if ((float)$inv['discount_amount'] > 0): ?>
                    <div class="flex justify-between">
                        <span class="text-[var(--text-secondary)]">Discount</span>
                        <span class="text-green-400">-<?= money_format((float)$inv['discount_amount'], $inv['currency'] ?: 'NGN') ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between pt-3 border-t border-[var(--border)]">
                        <span class="text-white font-bold text-lg">Total Due</span>
                        <span class="text-[var(--gold)] font-bold text-lg"><?= money_format((float)$inv['total'], $inv['currency'] ?: 'NGN') ?></span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if ($inv['notes']): ?>
            <div class="mb-8">
                <p class="text-xs uppercase tracking-wider text-[var(--text-muted)] mb-2">Notes</p>
                <p class="text-sm text-[var(--text-secondary)] whitespace-pre-wrap"><?= htmlspecialchars($inv['notes']) ?></p>
            </div>
            <?php endif; ?>

            <!-- Action Buttons (only if not paid) -->
            <?php if ($inv['status'] !== 'paid'): ?>
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[16px] p-6" x-data="invoicePayment()">
                <h3 class="font-bold text-white mb-4">Complete Payment</h3>

                <!-- Gateway Selection -->
                <div class="flex gap-3 mb-6">
                    <button type="button" @click="gateway = 'paystack'" :class="gateway === 'paystack' ? 'border-[var(--gold)] bg-[var(--gold)]/10 text-[var(--gold)]' : 'border-[var(--border)] text-[var(--text-secondary)] hover:text-white'" class="flex-1 h-14 rounded-[12px] border-2 flex items-center justify-center gap-2 transition-all font-medium text-sm">
                        Paystack
                    </button>
                    <button type="button" @click="gateway = 'flutterwave'" :class="gateway === 'flutterwave' ? 'border-[var(--gold)] bg-[var(--gold)]/10 text-[var(--gold)]' : 'border-[var(--border)] text-[var(--text-secondary)] hover:text-white'" class="flex-1 h-14 rounded-[12px] border-2 flex items-center justify-center gap-2 transition-all font-medium text-sm">
                        Flutterwave
                    </button>
                </div>

                <!-- Pay Button -->
                <button type="button" @click="pay()" :disabled="loading" class="btn btn-primary w-full h-12 text-base inline-flex items-center justify-center gap-2">
                    <template x-if="!loading">
                        <span class="inline-flex items-center gap-2"><i data-lucide="credit-card" class="w-5 h-5"></i> Pay <?= money_format((float)$inv['total'], $inv['currency'] ?: 'NGN') ?></span>
                    </template>
                    <template x-if="loading">
                        <span>Processing...</span>
                    </template>
                </button>

                <!-- Error -->
                <template x-if="error">
                    <p class="text-red-400 text-sm mt-3 text-center" x-text="error"></p>
                </template>
            </div>
            <?php endif; ?>

            <!-- Continue Shopping -->
            <div class="text-center mt-6 mb-10">
                <a href="/shop" class="btn btn-secondary h-11 px-8 inline-flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i> Continue Shopping
                </a>
                <p class="text-xs text-[var(--text-muted)] mt-2">Browse our shop and add more products before paying.</p>
            </div>

        <?php endif; ?>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="border-t border-[var(--border)] mt-10">
        <div class="max-w-4xl mx-auto px-6 py-6 text-center text-xs text-[var(--text-muted)]">
            © <?= date('Y') ?> Marigold Signature. All rights reserved.
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
lucide.createIcons();
function invoicePayment() {
    return {
        gateway: 'paystack',
        loading: false,
        error: '',
        pay: function() {
            this.loading = true;
            this.error = '';
            var self = this;
            var token = '<?= $inv['token'] ?>';
            var formData = new FormData();
            formData.append('gateway', this.gateway);

            fetch('/invoice/' + token + '/pay', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.url) {
                    window.location.href = data.url;
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (data.error) {
                    self.error = data.error;
                    self.loading = false;
                }
            })
            .catch(function() {
                self.error = 'Network error. Please try again.';
                self.loading = false;
            });
        }
    };
}
</script>
