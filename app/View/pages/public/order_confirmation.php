<?php // app/View/pages/public/order_confirmation.php

$ref = htmlspecialchars($_GET['ref'] ?? 'DEMO_123456');
$method = htmlspecialchars($_GET['method'] ?? 'paystack');
?>

<div class="pt-32 pb-24 px-4 sm:px-8 bg-[var(--bg-primary)] min-h-screen flex items-center justify-center">
    <div class="container mx-auto max-w-2xl">
        
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-12 text-center">
            
            <div class="w-20 h-20 rounded-full bg-green-500/10 border border-green-500/30 flex items-center justify-center mx-auto mb-8">
                <i data-lucide="check-circle" class="w-10 h-10 text-green-500"></i>
            </div>

            <h1 class="font-['Manrope'] text-3xl sm:text-4xl font-extrabold mb-4 text-white">
                <?= $method === 'transfer' ? 'Order Received!' : 'Payment Successful!' ?>
            </h1>
            
            <p class="text-[var(--text-secondary)] text-lg mb-8">
                Thank you for your order. We've sent a confirmation email to <strong class="text-white">john@company.com</strong> with your order details.
            </p>

            <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-6 mb-8 text-left">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-bold mb-1">Order Number</p>
                        <p class="font-mono font-bold text-white"><?= $ref ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-bold mb-1">Date</p>
                        <p class="text-white"><?= date('F j, Y') ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-bold mb-1">Total Amount</p>
                        <p class="font-['Manrope'] font-bold text-[var(--gold)]">₦468,500</p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-bold mb-1">Payment Method</p>
                        <p class="text-white"><?= $method === 'transfer' ? 'Bank Transfer (Pending)' : 'Paystack (Paid)' ?></p>
                    </div>
                </div>
            </div>

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
                            Reference: <?= $ref ?>
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
