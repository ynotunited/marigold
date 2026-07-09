<?php // app/View/pages/public/checkout.php ?>

<div class="pt-24 pb-20 px-4 sm:px-8 bg-[var(--bg-primary)] min-h-[80vh]" x-data="checkoutPage()">
    <div class="container mx-auto max-w-6xl">
        
        <h1 class="font-['Manrope'] text-3xl sm:text-5xl font-extrabold mb-8">Checkout</h1>

        <div class="flex flex-col lg:flex-row gap-10 xl:gap-16">
            
            <!-- Main Checkout Form -->
            <div class="w-full lg:w-3/5 space-y-8">
                
                <!-- Account / Guest -->
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-['Manrope'] text-xl font-bold flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-[var(--gold)] text-black flex items-center justify-center text-sm">1</span>
                            Contact Information
                        </h2>
                        <a href="/login" class="text-sm text-[var(--gold)] hover:underline">Log in</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Email Address</label>
                            <input type="email" placeholder="john@company.com" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Phone Number</label>
                            <input type="tel" placeholder="+234..." class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-8">
                    <h2 class="font-['Manrope'] text-xl font-bold mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-[var(--gold)] text-black flex items-center justify-center text-sm">2</span>
                        Shipping Address
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">First Name</label>
                            <input type="text" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Last Name</label>
                            <input type="text" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Company Name (Optional)</label>
                            <input type="text" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Street Address</label>
                            <input type="text" placeholder="House number and street name" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white mb-3">
                            <input type="text" placeholder="Apartment, suite, unit, etc. (optional)" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">City</label>
                            <input type="text" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">State</label>
                            <select class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white appearance-none">
                                <option value="Lagos">Lagos</option>
                                <option value="Abuja">Abuja</option>
                                <option value="Rivers">Rivers</option>
                            </select>
                        </div>
                    </div>

                    <!-- Billing Toggle -->
                    <label class="flex items-center gap-3 cursor-pointer mt-6">
                        <input type="checkbox" x-model="sameAsShipping" class="w-5 h-5 rounded border-[var(--border)] bg-[var(--surface)] accent-[var(--gold)]">
                        <span class="text-sm font-semibold text-white">Billing address is same as shipping</span>
                    </label>

                    <!-- Billing Address (Hidden if same) -->
                    <div x-show="!sameAsShipping" x-collapse class="mt-6 pt-6 border-t border-[var(--border)]">
                        <h3 class="font-['Manrope'] font-bold text-lg mb-4">Billing Address</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Same fields as shipping conceptually -->
                            <div class="md:col-span-2">
                                <input type="text" placeholder="Street Address" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-8">
                    <h2 class="font-['Manrope'] text-xl font-bold mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-[var(--gold)] text-black flex items-center justify-center text-sm">3</span>
                        Payment Method
                    </h2>

                    <div class="space-y-4">
                        <!-- Paystack -->
                        <label class="block border rounded-2xl p-5 cursor-pointer transition-colors"
                               :class="paymentMethod === 'paystack' ? 'border-[var(--gold)] bg-[var(--gold)]/5' : 'border-[var(--border)] bg-[var(--card)]'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" value="paystack" x-model="paymentMethod" class="w-5 h-5 accent-[var(--gold)]">
                                    <span class="font-bold text-white">Pay Online (Paystack)</span>
                                </div>
                                <div class="flex gap-1">
                                    <i data-lucide="credit-card" class="w-5 h-5 text-[var(--text-muted)]"></i>
                                </div>
                            </div>
                            <div x-show="paymentMethod === 'paystack'" x-collapse>
                                <p class="text-sm text-[var(--text-secondary)] mt-3 pl-8">Pay securely using your Visa, Mastercard, Verve, or Bank Transfer via Paystack.</p>
                            </div>
                        </label>

                        <!-- Direct Bank Transfer -->
                        <label class="block border rounded-2xl p-5 cursor-pointer transition-colors"
                               :class="paymentMethod === 'transfer' ? 'border-[var(--gold)] bg-[var(--gold)]/5' : 'border-[var(--border)] bg-[var(--card)]'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" value="transfer" x-model="paymentMethod" class="w-5 h-5 accent-[var(--gold)]">
                                    <span class="font-bold text-white">Direct Bank Transfer</span>
                                </div>
                                <i data-lucide="building-2" class="w-5 h-5 text-[var(--text-muted)]"></i>
                            </div>
                            <div x-show="paymentMethod === 'transfer'" x-collapse>
                                <div class="mt-4 pl-8">
                                    <p class="text-sm text-[var(--text-secondary)] mb-3">Make your payment directly into our bank account. Please use your Order ID as the payment reference.</p>
                                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-xl p-4 text-sm font-mono text-[var(--text-muted)]">
                                        Bank: GTBank<br>
                                        Account Name: Marigold Signature Ltd<br>
                                        Account No: 0123456789
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Order Notes -->
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-8">
                    <h2 class="font-['Manrope'] text-xl font-bold mb-4">Order Notes (Optional)</h2>
                    <textarea rows="3" placeholder="Notes about your order, e.g. special notes for delivery." class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white resize-none"></textarea>
                </div>

            </div>

            <!-- Order Summary (Sidebar) -->
            <div class="w-full lg:w-2/5">
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl sticky top-28 overflow-hidden">
                    
                    <!-- Mobile Accordion Toggle -->
                    <button @click="summaryOpen = !summaryOpen" class="w-full p-6 flex items-center justify-between lg:hidden border-b border-[var(--border)]">
                        <span class="font-['Manrope'] font-bold flex items-center gap-2">
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            <span x-text="summaryOpen ? 'Hide order summary' : 'Show order summary'"></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="summaryOpen ? 'rotate-180' : ''"></i>
                        </span>
                        <span class="font-['Manrope'] font-bold text-[var(--gold)]">₦468,500</span>
                    </button>

                    <div :class="summaryOpen ? 'block' : 'hidden lg:block'">
                        <div class="p-6 lg:p-8 border-b border-[var(--border)] bg-[var(--card)] max-h-[40vh] overflow-y-auto no-scrollbar">
                            <!-- Items -->
                            <div class="space-y-4">
                                <!-- Item 1 -->
                                <div class="flex gap-4">
                                    <div class="relative w-16 h-16 rounded-xl bg-[var(--surface)] border border-[var(--border)] shrink-0 overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1544816278-ca5e3f4abd8c?q=80&w=200&auto=format&fit=crop" class="w-full h-full object-cover">
                                        <span class="absolute -top-2 -right-2 bg-[var(--gold)] text-black text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center z-10">10</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-white leading-tight mb-1">Executive Leather Notebook</h4>
                                        <p class="text-xs text-[var(--text-muted)]">Matte Black</p>
                                    </div>
                                    <div class="text-right font-semibold text-sm">₦285,000</div>
                                </div>
                                <!-- Item 2 -->
                                <div class="flex gap-4">
                                    <div class="relative w-16 h-16 rounded-xl bg-[var(--surface)] border border-[var(--border)] shrink-0 overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=200&auto=format&fit=crop" class="w-full h-full object-cover">
                                        <span class="absolute -top-2 -right-2 bg-[var(--gold)] text-black text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center z-10">25</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-white leading-tight mb-1">Branded Vacuum Flask 1L</h4>
                                        <p class="text-xs text-[var(--text-muted)]">Navy Blue</p>
                                    </div>
                                    <div class="text-right font-semibold text-sm">₦145,000</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 lg:p-8 bg-[var(--surface)]">
                            <div class="space-y-3 text-sm mb-6">
                                <div class="flex justify-between">
                                    <span class="text-[var(--text-secondary)]">Subtotal</span>
                                    <span class="font-bold">₦430,000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[var(--text-secondary)]">Shipping</span>
                                    <span class="font-bold">₦15,000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[var(--text-secondary)]">Taxes (VAT 7.5%)</span>
                                    <span class="font-bold">₦23,500</span>
                                </div>
                            </div>
                            <div class="border-t border-[var(--border)] pt-4 mb-8 flex justify-between items-end">
                                <span class="font-bold text-white">Total</span>
                                <span class="font-['Manrope'] text-3xl font-extrabold text-[var(--gold)]">₦468,500</span>
                            </div>

                            <button @click="processPayment()" :disabled="processing" class="w-full bg-[var(--gold)] text-black font-bold py-4 rounded-xl hover:bg-[#D4AF37] transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                                <span x-show="!processing" class="flex items-center gap-2"><i data-lucide="lock" class="w-4 h-4"></i> Place Order</span>
                                <span x-show="processing" class="flex items-center gap-2"><i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Processing...</span>
                            </button>
                            
                            <p class="text-xs text-[var(--text-muted)] text-center mt-4">By placing your order you agree to our Terms of Service and Privacy Policy.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Paystack Inline Script -->
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
function checkoutPage() {
    return {
        sameAsShipping: true,
        paymentMethod: 'paystack',
        summaryOpen: false,
        processing: false,

        processPayment() {
            this.processing = true;
            
            if (this.paymentMethod === 'paystack') {
                // Mock Paystack integration
                setTimeout(() => {
                    let handler = PaystackPop.setup({
                        key: 'pk_test_xxxxxxxxxx',
                        email: 'customer@email.com',
                        amount: 468500 * 100, // in kobo
                        currency: 'NGN',
                        callback: function(response){
                            window.location.href = '/order-confirmation?ref=' + response.reference;
                        },
                        onClose: () => {
                            this.processing = false;
                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Payment cancelled', type: 'error' }}));
                        }
                    });
                    
                    // Simulate successful redirect for demo purposes
                    setTimeout(() => {
                        window.location.href = '/order-confirmation?ref=DEMO_' + Math.floor(Math.random() * 1000000);
                    }, 1500);

                    // handler.openIframe(); // Commented out for demo
                }, 500);
            } else {
                // Bank transfer
                setTimeout(() => {
                    window.location.href = '/order-confirmation?ref=TRF_' + Math.floor(Math.random() * 1000000) + '&method=transfer';
                }, 1500);
            }
        }
    }
}
</script>
<style>
    .no-scrollbar { scrollbar-width: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>
