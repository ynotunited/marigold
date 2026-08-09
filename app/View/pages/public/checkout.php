<?php // app/View/pages/public/checkout.php
$productImages = [
    'MS-EXEC-001' => 'https://images.unsplash.com/photo-1544816278-ca5e3f4abd8c?q=80&w=200&auto=format&fit=crop',
    'MS-FLASK-001' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=200&auto=format&fit=crop',
    'MS-PEN-003' => 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=200&auto=format&fit=crop',
    'MS-TECH-004' => 'https://images.unsplash.com/photo-1612815292673-ab2ad8a5a95b?q=80&w=200&auto=format&fit=crop',
];
$jsProducts = array_map(function ($p) use ($productImages) {
    $price = (float) $p['price'];
    $sale = $p['sale_price'] !== null ? (float) $p['sale_price'] : null;
    $effective = ($sale !== null && $sale < $price) ? $sale : $price;
    return [
        'id' => (int) $p['id'],
        'name' => $p['name'],
        'sku' => $p['sku'],
        'price' => $effective,
        'image' => $productImages[$p['sku']] ?? '',
    ];
}, $products ?? []);
$csrfToken = \App\Core\CSRF::generate();
?>

<div class="pt-24 pb-20 px-4 sm:px-8 bg-[var(--bg-primary)] min-h-[80vh]" x-data="checkoutPage()">
    <div class="container mx-auto max-w-6xl">

        <h1 class="font-['Manrope'] text-3xl sm:text-5xl font-extrabold mb-8">Checkout</h1>

        <form id="checkoutForm" @submit.prevent="processPayment()" novalidate>
            <div class="flex flex-col lg:flex-row gap-10 xl:gap-16">

                <!-- Main Checkout Form -->
                <div class="w-full lg:w-3/5 space-y-8">

                    <!-- Contact -->
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
                                <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Email Address *</label>
                                <input type="email" name="email" x-model="form.email" placeholder="john@company.com" required class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Phone Number</label>
                                <input type="tel" name="phone" x-model="form.phone" placeholder="+234..." class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
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
                                <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">First Name *</label>
                                <input type="text" name="first_name" x-model="form.first_name" required class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Last Name *</label>
                                <input type="text" name="last_name" x-model="form.last_name" required class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Company Name (Optional)</label>
                                <input type="text" name="company" x-model="form.company" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Street Address *</label>
                                <input type="text" name="address_line1" x-model="form.address_line1" placeholder="House number and street name" required class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white mb-3">
                                <input type="text" name="address_line2" x-model="form.address_line2" placeholder="Apartment, suite, unit, etc. (optional)" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">City *</label>
                                <input type="text" name="city" x-model="form.city" required class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">State</label>
                                <select name="state" x-model="form.state" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white appearance-none">
                                    <option value="Lagos">Lagos</option>
                                    <option value="Abuja">Abuja</option>
                                    <option value="Rivers">Rivers</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Postal Code</label>
                                <input type="text" name="postal_code" x-model="form.postal_code" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
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
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Street Address</label>
                                    <input type="text" name="billing_address_line1" x-model="form.billing_address_line1" placeholder="Street Address" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">City</label>
                                    <input type="text" name="billing_city" x-model="form.billing_city" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">State</label>
                                    <input type="text" name="billing_state" x-model="form.billing_state" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Select Items -->
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-8">
                        <h2 class="font-['Manrope'] text-xl font-bold mb-1 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-[var(--gold)] text-black flex items-center justify-center text-sm">3</span>
                            Your Items
                        </h2>
                        <p class="text-sm text-[var(--text-secondary)] mb-6 ml-11">Set a quantity above zero for each item you'd like to order.</p>

                        <div class="space-y-4">
                            <template x-for="p in products" :key="p.id">
                                <div class="flex gap-4 items-center">
                                    <div class="relative w-16 h-16 rounded-xl bg-[var(--card)] border border-[var(--border)] shrink-0 overflow-hidden">
                                        <template x-if="p.image">
                                            <img :src="p.image" class="w-full h-full object-cover" alt="">
                                        </template>
                                        <template x-if="!p.image">
                                            <div class="w-full h-full bg-gradient-to-br from-[var(--gold)]/30 to-[var(--surface)] flex items-center justify-center">
                                                <span class="font-extrabold text-[var(--gold)]" x-text="p.name.charAt(0)"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-white leading-tight mb-1" x-text="p.name"></h4>
                                        <p class="text-xs text-[var(--text-muted)]" x-text="'₦' + formatNaira(p.price) + ' / unit'"></p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="setQty(p.id, qty[p.id] - 1)" class="w-8 h-8 rounded-lg border border-[var(--border)] text-[var(--text-secondary)] hover:text-white transition-colors text-lg leading-none">-</button>
                                        <input type="number" min="0" :name="'qty_' + p.id" x-model.number="qty[p.id] || 0" class="w-16 text-center bg-[var(--card)] border border-[var(--border)] rounded-lg px-2 py-1.5 text-sm text-white focus:outline-none focus:border-[var(--gold)]">
                                        <button type="button" @click="setQty(p.id, qty[p.id] + 1)" class="w-8 h-8 rounded-lg border border-[var(--border)] text-[var(--text-secondary)] hover:text-white transition-colors text-lg leading-none">+</button>
                                    </div>
                                </div>
                            </template>
                            <p x-show="selectedCount() === 0" class="text-sm text-[var(--text-muted)] pt-2">No items selected yet.</p>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-8">
                        <h2 class="font-['Manrope'] text-xl font-bold mb-6 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-[var(--gold)] text-black flex items-center justify-center text-sm">4</span>
                            Payment Method
                        </h2>

                        <div class="space-y-4">
                            <!-- Paystack -->
                            <label class="block border rounded-2xl p-5 cursor-pointer transition-colors"
                                   :class="paymentMethod === 'paystack' ? 'border-[var(--gold)] bg-[var(--gold)]/5' : 'border-[var(--border)] bg-[var(--card)]'">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" value="paystack" name="payment_method" x-model="paymentMethod" class="w-5 h-5 accent-[var(--gold)]">
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
                                        <input type="radio" value="transfer" name="payment_method" x-model="paymentMethod" class="w-5 h-5 accent-[var(--gold)]">
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
                        <textarea name="notes" x-model="form.notes" rows="3" placeholder="Notes about your order, e.g. special notes for delivery." class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white resize-none"></textarea>
                    </div>

                </div>

                <!-- Order Summary (Sidebar) -->
                <div class="w-full lg:w-2/5">
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl sticky top-28 overflow-hidden">

                        <!-- Mobile Accordion Toggle -->
                        <button type="button" @click="summaryOpen = !summaryOpen" class="w-full p-6 flex items-center justify-between lg:hidden border-b border-[var(--border)]">
                            <span class="font-['Manrope'] font-bold flex items-center gap-2">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                <span x-text="summaryOpen ? 'Hide order summary' : 'Show order summary'"></span>
                                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="summaryOpen ? 'rotate-180' : ''"></i>
                            </span>
                            <span class="font-['Manrope'] font-bold text-[var(--gold)]" x-text="'₦' + formatNaira(total())"></span>
                        </button>

                        <div :class="summaryOpen ? 'block' : 'hidden lg:block'">
                            <div class="p-6 lg:p-8 border-b border-[var(--border)] bg-[var(--card)] max-h-[40vh] overflow-y-auto no-scrollbar">
                                <div class="space-y-4">
                                    <template x-for="p in selected()" :key="p.id">
                                        <div class="flex gap-4">
                                            <div class="relative w-16 h-16 rounded-xl bg-[var(--surface)] border border-[var(--border)] shrink-0 overflow-hidden">
                                                <template x-if="p.image">
                                                    <img :src="p.image" class="w-full h-full object-cover" alt="">
                                                </template>
                                                <template x-if="!p.image">
                                                    <div class="w-full h-full bg-gradient-to-br from-[var(--gold)]/30 to-[var(--surface)]"></div>
                                                </template>
                                                <span class="absolute -top-2 -right-2 bg-[var(--gold)] text-black text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center z-10" x-text="qty[p.id]"></span>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-bold text-white leading-tight mb-1" x-text="p.name"></h4>
                                                <p class="text-xs text-[var(--text-muted)]" x-text="p.sku"></p>
                                            </div>
                                            <div class="text-right font-semibold text-sm" x-text="'₦' + formatNaira(p.price * qty[p.id])"></div>
                                        </div>
                                    </template>
                                    <p x-show="selectedCount() === 0" class="text-sm text-[var(--text-muted)]">Your summary is empty.</p>
                                </div>
                            </div>

                            <div class="p-6 lg:p-8 bg-[var(--surface)]">
                                <div class="space-y-3 text-sm mb-6">
                                    <div class="flex justify-between">
                                        <span class="text-[var(--text-secondary)]">Subtotal</span>
                                        <span class="font-bold" x-text="'₦' + formatNaira(subtotal())"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[var(--text-secondary)]">Shipping</span>
                                        <span class="font-bold" x-text="'₦' + formatNaira(shipping())"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[var(--text-secondary)]">Taxes (VAT 7.5%)</span>
                                        <span class="font-bold" x-text="'₦' + formatNaira(tax())"></span>
                                    </div>
                                </div>
                                <div class="border-t border-[var(--border)] pt-4 mb-8 flex justify-between items-end">
                                    <span class="font-bold text-white">Total</span>
                                    <span class="font-['Manrope'] text-3xl font-extrabold text-[var(--gold)]" x-text="'₦' + formatNaira(total())"></span>
                                </div>

                                <button type="submit" :disabled="processing || selectedCount() === 0" class="w-full bg-[var(--gold)] text-black font-bold py-4 rounded-xl hover:bg-[#D4AF37] transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                                    <span x-show="!processing" class="flex items-center gap-2"><i data-lucide="lock" class="w-4 h-4"></i> Place Order</span>
                                    <span x-show="processing" class="flex items-center gap-2"><i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Processing...</span>
                                </button>

                                <p x-show="errorMessage" class="text-sm text-red-400 text-center mt-4" x-text="errorMessage"></p>
                                <p class="text-xs text-[var(--text-muted)] text-center mt-4">By placing your order you agree to our Terms of Service and Privacy Policy.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
function checkoutPage() {
    return {
        products: <?= json_encode($jsProducts, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS) ?>,
        csrfToken: <?= json_encode($csrfToken, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS) ?>,
        qty: {},
        form: {
            email: '', phone: '', first_name: '', last_name: '', company: '',
            address_line1: '', address_line2: '', city: '', state: 'Lagos', postal_code: '',
            billing_address_line1: '', billing_city: '', billing_state: '',
            notes: ''
        },
        sameAsShipping: true,
        paymentMethod: 'paystack',
        summaryOpen: false,
        processing: false,
        errorMessage: '',

        setQty(id, v) {
            if (v < 0) v = 0;
            this.qty[id] = v;
            if (v > 0) this.summaryOpen = true;
        },
        selected() {
            return this.products.filter(p => (this.qty[p.id] || 0) > 0);
        },
        selectedCount() {
            return this.selected().length;
        },
        subtotal() {
            return this.selected().reduce((s, p) => s + p.price * (this.qty[p.id] || 0), 0);
        },
        shipping() {
            return this.subtotal() > 0 ? 15000 : 0;
        },
        tax() {
            return this.subtotal() * 0.075;
        },
        total() {
            return this.subtotal() + this.shipping() + this.tax();
        },
        formatNaira(n) {
            return Number(n.toFixed(2)).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        uuidV4() {
            return crypto.randomUUID();
        },
        processPayment() {
            this.errorMessage = '';
            if (this.selectedCount() === 0) {
                this.errorMessage = 'Add at least one item to your order.';
                return;
            }
            if (!this.form.email || !this.form.first_name || !this.form.last_name || !this.form.address_line1 || !this.form.city) {
                this.errorMessage = 'Please complete the required contact and shipping fields.';
                return;
            }

            this.processing = true;
            const payload = {
                email: this.form.email,
                phone: this.form.phone,
                first_name: this.form.first_name,
                last_name: this.form.last_name,
                company: this.form.company,
                address_line1: this.form.address_line1,
                address_line2: this.form.address_line2,
                city: this.form.city,
                state: this.form.state,
                postal_code: this.form.postal_code,
                payment_method: this.paymentMethod,
                notes: this.form.notes,
                items: this.selected().map(p => ({ product_id: p.id, quantity: this.qty[p.id] }))
            };
            if (!this.sameAsShipping) {
                payload.billing_address_line1 = this.form.billing_address_line1;
                payload.billing_city = this.form.billing_city;
                payload.billing_state = this.form.billing_state;
            }

            fetch('/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': this.csrfToken,
                    'Idempotency-Key': this.uuidV4()
                },
                body: JSON.stringify(payload)
            })
            .then(async (res) => {
                const body = await res.json().catch(() => ({}));
                if (!res.ok) {
                    this.errorMessage = body.error || 'We could not place your order. Please try again.';
                    this.processing = false;
                    return;
                }
                window.location.href = body.redirect_url;
            })
            .catch(() => {
                this.errorMessage = 'Network error. Please try again.';
                this.processing = false;
            });
        }
    }
}
</script>
<style>
    .no-scrollbar { scrollbar-width: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>
