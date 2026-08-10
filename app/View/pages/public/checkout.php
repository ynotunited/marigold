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

<div style="background: var(--ivory); color: var(--ink);" x-data="checkoutPage()">

    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="/">Home</a><span>/</span><a href="/cart">Cart</a><span>/</span><span>Checkout</span></div>
            <div class="eyebrow center reveal">Almost There</div>
            <h1 class="display reveal">Secure <span class="gold-text">Checkout</span></h1>
            <p class="lead reveal">Fill in your details below — we'll confirm your order by email and WhatsApp.</p>
        </div>
    </section>

    <section class="section" style="padding-top: 10px;">
        <div class="container">

            <form id="checkoutForm" @submit.prevent="processPayment()" novalidate>
                <div class="checkout-layout">

                    <!-- Main Checkout Form -->
                    <div class="checkout-main">

                        <!-- Contact -->
                        <div class="cstep">
                            <div class="cstep-head">
                                <h2 class="cstep-title">
                                    <span class="step-no">1</span>
                                    Contact Information
                                </h2>
                                <a href="/login" class="lnk">Log in</a>
                            </div>
                            <div class="form-grid">
                                <div class="form-row">
                                    <label>Email Address <em>*</em></label>
                                    <input type="email" name="email" x-model="form.email" placeholder="john@company.com" required class="field">
                                </div>
                                <div class="form-row">
                                    <label>Phone Number</label>
                                    <input type="tel" name="phone" x-model="form.phone" placeholder="+234..." class="field">
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="cstep">
                            <div class="cstep-head">
                                <h2 class="cstep-title">
                                    <span class="step-no">2</span>
                                    Shipping Address
                                </h2>
                            </div>

                            <div class="form-grid">
                                <div class="form-row">
                                    <label>First Name <em>*</em></label>
                                    <input type="text" name="first_name" x-model="form.first_name" required class="field">
                                </div>
                                <div class="form-row">
                                    <label>Last Name <em>*</em></label>
                                    <input type="text" name="last_name" x-model="form.last_name" required class="field">
                                </div>
                                <div class="form-row full">
                                    <label>Company Name (Optional)</label>
                                    <input type="text" name="company" x-model="form.company" class="field">
                                </div>
                                <div class="form-row full">
                                    <label>Street Address <em>*</em></label>
                                    <input type="text" name="address_line1" x-model="form.address_line1" placeholder="House number and street name" required class="field" style="margin-bottom: 12px;">
                                    <input type="text" name="address_line2" x-model="form.address_line2" placeholder="Apartment, suite, unit, etc. (optional)" class="field">
                                </div>
                                <div class="form-row">
                                    <label>City <em>*</em></label>
                                    <input type="text" name="city" x-model="form.city" required class="field">
                                </div>
                                <div class="form-row">
                                    <label>State</label>
                                    <select name="state" x-model="form.state" class="field">
                                        <option value="Lagos">Lagos</option>
                                        <option value="Abuja">Abuja</option>
                                        <option value="Rivers">Rivers</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label>Postal Code</label>
                                    <input type="text" name="postal_code" x-model="form.postal_code" class="field">
                                </div>
                            </div>

                            <!-- Billing Toggle -->
                            <label class="chk-toggle">
                                <input type="checkbox" x-model="sameAsShipping">
                                <span>Billing address is same as shipping</span>
                            </label>

                            <!-- Billing Address (Hidden if same) -->
                            <div class="collapse-box" :class="{ open: !sameAsShipping }">
                                <div>
                                    <div style="margin-top: 22px; padding-top: 22px; border-top: 1px solid var(--line);">
                                        <h3 style="font-family: var(--font-display); font-weight: 520; font-size: 18px; margin-bottom: 16px;">Billing Address</h3>
                                        <div class="form-grid">
                                            <div class="form-row full">
                                                <label>Street Address</label>
                                                <input type="text" name="billing_address_line1" x-model="form.billing_address_line1" placeholder="Street Address" class="field">
                                            </div>
                                            <div class="form-row">
                                                <label>City</label>
                                                <input type="text" name="billing_city" x-model="form.billing_city" class="field">
                                            </div>
                                            <div class="form-row">
                                                <label>State</label>
                                                <input type="text" name="billing_state" x-model="form.billing_state" class="field">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Method -->
                        <div class="cstep">
                            <div class="cstep-head">
                                <h2 class="cstep-title">
                                    <span class="step-no">+</span>
                                    Delivery Method
                                </h2>
                            </div>

                            <!-- Office Pickup -->
                            <label class="pay-opt" :class="{ on: deliveryMethod === 'pickup' }">
                                <div class="po-row">
                                    <div class="po-label">
                                        <input type="radio" value="pickup" name="delivery_method" x-model="deliveryMethod">
                                        <span>Office Pickup — Free</span>
                                    </div>
                                    <span class="po-ico">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 9h1"/><path d="M14 9h1"/><path d="M9 13h1"/><path d="M14 13h1"/></svg>
                                    </span>
                                </div>
                                <div class="collapse-box" :class="{ open: deliveryMethod === 'pickup' }">
                                    <div>
                                        <p class="po-body">Collect your order from our office: 6 Oluwole Omole Street, Opebi, Lagos. No delivery fee.</p>
                                    </div>
                                </div>
                            </label>

                            <!-- Courier Delivery (ShipBubble) -->
                            <label class="pay-opt" :class="{ on: deliveryMethod === 'delivery' }">
                                <div class="po-row">
                                    <div class="po-label">
                                        <input type="radio" value="delivery" name="delivery_method" x-model="deliveryMethod">
                                        <span>Courier Delivery</span>
                                    </div>
                                    <span class="po-ico">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3v-5l3-6h10l3 6v5h-2"/><circle cx="7.5" cy="17.5" r="2"/><circle cx="16.5" cy="17.5" r="2"/><path d="M8 6h8v11H8z"/></svg>
                                    </span>
                                </div>
                                <div class="collapse-box" :class="{ open: deliveryMethod === 'delivery' }">
                                    <div>
                                        <div style="margin-top: 18px; padding-top: 18px; border-top: 1px solid var(--line);">
                                            <p class="po-body" x-show="!couriers.length && !ratesLoading && !ratesError">Enter your shipping address above, then get live rates from our delivery partners.</p>

                                            <button type="button" class="btn btn-ghost btn-lg" @click="loadRates()" :disabled="ratesLoading || processing" style="margin-bottom: 14px;">
                                                <span x-show="!ratesLoading">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="m3.27 6.96 8.73 5.09 8.73-5.09"/><path d="M12 22.08V12"/></svg>
                                                    Get delivery rates
                                                </span>
                                                <span x-show="ratesLoading">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 0.9s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                                                    Fetching rates...
                                                </span>
                                            </button>

                                            <p x-show="ratesError" class="form-error" style="margin-bottom: 14px;" x-text="ratesError"></p>
                                            <p x-show="simulated && !ratesLoading && !ratesError" class="cstep-sub" style="margin-bottom: 14px;">Demo mode — showing sample rates. Configure a ShipBubble API key to get live quotes.</p>

                                            <template x-for="c in couriers" :key="c.courier_id">
                                                <label class="pay-opt" :class="{ on: selectedCourierId === c.courier_id }">
                                                    <div class="po-row">
                                                        <div class="po-label">
                                                            <input type="radio" name="courier" :value="c.courier_id" x-model="selectedCourierId">
                                                            <span>
                                                                <span x-text="c.courier_name"></span>
                                                                <small class="cstep-sub" x-show="c.delivery_eta" x-text="' — ' + c.delivery_eta"></small>
                                                            </span>
                                                        </div>
                                                        <span class="po-price" x-text="'&#8358;' + formatNaira(c.total)"></span>
                                                    </div>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Select Items -->
                        <div class="cstep">
                            <div class="cstep-head">
                                <h2 class="cstep-title">
                                    <span class="step-no">3</span>
                                    Your Items
                                </h2>
                            </div>
                            <p class="cstep-sub" style="margin-bottom: 6px;">Set a quantity above zero for each item you'd like to order.</p>

                            <template x-for="p in products" :key="p.id">
                                <div class="chk-item">
                                    <div class="chk-thumb">
                                        <template x-if="p.image">
                                            <img :src="p.image" alt="" loading="lazy">
                                        </template>
                                        <template x-if="!p.image">
                                            <div style="width: 100%; height: 100%; display: grid; place-items: center; background: linear-gradient(135deg, var(--gold-fade), var(--ivory)); color: var(--gold-deep); font-weight: 800; font-family: var(--font-display);" x-text="p.name.charAt(0)"></div>
                                        </template>
                                    </div>
                                    <div style="min-width: 0;">
                                        <p class="chk-name" x-text="p.name"></p>
                                        <p class="chk-sku" x-text="'&#8358;' + formatNaira(p.price) + ' / unit'"></p>
                                    </div>
                                    <div class="qty" style="border-radius: 999px;">
                                        <button type="button" @click="setQty(p.id, qty[p.id] - 1)" aria-label="Decrease quantity">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M5 12h14"/></svg>
                                        </button>
                                        <input type="number" min="0" :name="'qty_' + p.id" x-model.number="qty[p.id] || 0" aria-label="Quantity" style="width: 44px;">
                                        <button type="button" @click="setQty(p.id, qty[p.id] + 1)" aria-label="Increase quantity">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <p x-show="selectedCount() === 0" class="cstep-sub" style="margin-top: 12px;">No items selected yet.</p>
                        </div>

                        <!-- Payment Method -->
                        <div class="cstep">
                            <div class="cstep-head">
                                <h2 class="cstep-title">
                                    <span class="step-no">4</span>
                                    Payment Method
                                </h2>
                            </div>

                            <!-- Paystack -->
                            <label class="pay-opt" :class="{ on: paymentMethod === 'paystack' }">
                                <div class="po-row">
                                    <div class="po-label">
                                        <input type="radio" value="paystack" name="payment_method" x-model="paymentMethod">
                                        <span>Pay Online (Paystack)</span>
                                    </div>
                                    <span class="po-ico">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                    </span>
                                </div>
                                <div class="collapse-box" :class="{ open: paymentMethod === 'paystack' }">
                                    <div>
                                        <p class="po-body">Pay securely using your Visa, Mastercard, Verve, or Bank Transfer via Paystack.</p>
                                    </div>
                                </div>
                            </label>

                            <!-- Flutterwave -->
                            <label class="pay-opt" :class="{ on: paymentMethod === 'flutterwave' }">
                                <div class="po-row">
                                    <div class="po-label">
                                        <input type="radio" value="flutterwave" name="payment_method" x-model="paymentMethod">
                                        <span>Pay Online (Flutterwave)</span>
                                    </div>
                                    <span class="po-ico">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/><path d="m6 15 4-4 3 2 3-3"/></svg>
                                    </span>
                                </div>
                                <div class="collapse-box" :class="{ open: paymentMethod === 'flutterwave' }">
                                    <div>
                                        <p class="po-body">Pay with Card, Bank Transfer, USSD or Mobile Money via Flutterwave.</p>
                                    </div>
                                </div>
                            </label>

                            <!-- Direct Bank Transfer -->
                            <label class="pay-opt" :class="{ on: paymentMethod === 'transfer' }">
                                <div class="po-row">
                                    <div class="po-label">
                                        <input type="radio" value="transfer" name="payment_method" x-model="paymentMethod">
                                        <span>Direct Bank Transfer</span>
                                    </div>
                                    <span class="po-ico">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 9h1"/><path d="M14 9h1"/><path d="M9 13h1"/><path d="M14 13h1"/><path d="M9 17h1"/><path d="M14 17h1"/></svg>
                                    </span>
                                </div>
                                <div class="collapse-box" :class="{ open: paymentMethod === 'transfer' }">
                                    <div>
                                        <div class="po-body">
                                            <p style="margin-bottom: 12px;">Make your payment directly into our bank account. Please use your Order ID as the payment reference.</p>
                                            <div class="bank-card">
                                                Bank: GTBank<br>
                                                Account Name: Marigold Signature Ltd<br>
                                                Account No: 0123456789
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Order Notes -->
                        <div class="cstep">
                            <div class="cstep-head">
                                <h2 class="cstep-title">
                                    <span class="step-no">+</span>
                                    Order Notes (Optional)
                                </h2>
                            </div>
                            <textarea name="notes" x-model="form.notes" rows="3" placeholder="Notes about your order, e.g. special notes for delivery." class="field"></textarea>
                        </div>

                    </div>

                    <!-- Order Summary (Sidebar) -->
                    <aside class="checkout-side">
                        <div class="summary-card" style="position: sticky;">
                            <h2 class="sc-title">Order Summary</h2>

                            <div class="summary-items">
                                <template x-for="p in selected()" :key="p.id">
                                    <div class="chk-item" style="border-bottom: 1px dashed var(--line);">
                                        <div class="chk-thumb">
                                            <template x-if="p.image">
                                                <img :src="p.image" alt="" loading="lazy">
                                            </template>
                                            <template x-if="!p.image">
                                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--gold-fade), var(--ivory));"></div>
                                            </template>
                                            <span class="qty-badge" x-text="qty[p.id]"></span>
                                        </div>
                                        <div style="min-width: 0;">
                                            <p class="chk-name" x-text="p.name"></p>
                                            <p class="chk-sku" x-text="p.sku"></p>
                                        </div>
                                        <span class="chk-price" x-text="'&#8358;' + formatNaira(p.price * qty[p.id])"></span>
                                    </div>
                                </template>
                                <p x-show="selectedCount() === 0" class="cstep-sub" style="margin-top: 12px;">Your summary is empty.</p>
                            </div>

                            <div class="row">
                                <span>Subtotal</span>
                                <span x-text="'&#8358;' + formatNaira(subtotal())"></span>
                            </div>
                            <div class="row">
                                <span>Shipping</span>
                                <span x-text="'&#8358;' + formatNaira(shipping())"></span>
                            </div>
                            <div class="row">
                                <span>Taxes (VAT 7.5%)</span>
                                <span x-text="'&#8358;' + formatNaira(tax())"></span>
                            </div>
                            <div class="row total">
                                <span>Total</span>
                                <span class="amt" x-text="'&#8358;' + formatNaira(total())"></span>
                            </div>

                            <button type="submit" :disabled="processing || selectedCount() === 0" class="btn btn-gold btn-lg btn-block" style="margin-top: 22px;">
                                <span x-show="!processing" class="btn-content">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    Place Order
                                </span>
                                <span x-show="processing" class="btn-content">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 0.9s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                                    Processing...
                                </span>
                            </button>

                            <p x-show="errorMessage" class="form-error" style="margin-top: 14px;" x-text="errorMessage"></p>
                            <p class="cstep-sub" style="text-align: center; margin-top: 14px;">By placing your order you agree to our <a href="/terms-and-conditions" style="color: var(--gold-deep); text-decoration: underline;">Terms of Service</a> and <a href="/privacy-policy" style="color: var(--gold-deep); text-decoration: underline;">Privacy Policy</a>.</p>
                        </div>
                    </aside>

                </div>
            </form>

        </div>
    </section>
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
        deliveryMethod: 'pickup',
        couriers: [],
        selectedCourierId: null,
        rateToken: '',
        receiverAddressCode: null,
        ratesLoading: false,
        ratesError: '',
        simulated: false,
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
        selectedCourier() {
            return this.couriers.find(c => c.courier_id === this.selectedCourierId) || null;
        },
        shipping() {
            if (this.deliveryMethod === 'delivery' && this.selectedCourier()) {
                return this.selectedCourier().total;
            }
            return 0;
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
        loadRates() {
            this.ratesError = '';
            if (this.selectedCount() === 0) {
                this.ratesError = 'Add at least one item to your order first.';
                return;
            }
            if (!this.form.email || !this.form.first_name || !this.form.last_name || !this.form.address_line1 || !this.form.city || !this.form.state) {
                this.ratesError = 'Complete your contact and shipping address (step 2) to get delivery rates.';
                return;
            }

            this.ratesLoading = true;
            const payload = {
                email: this.form.email,
                phone: this.form.phone,
                first_name: this.form.first_name,
                last_name: this.form.last_name,
                address_line1: this.form.address_line1,
                address_line2: this.form.address_line2,
                city: this.form.city,
                state: this.form.state,
                postal_code: this.form.postal_code,
                items: this.selected().map(p => ({ product_id: p.id, quantity: this.qty[p.id] }))
            };

            fetch('/api/shipping/rates', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': this.csrfToken
                },
                body: JSON.stringify(payload)
            })
            .then(async (res) => {
                const body = await res.json().catch(() => ({}));
                if (!res.ok) {
                    this.ratesError = body.error || 'We could not fetch delivery rates. Please try again.';
                    return;
                }
                this.couriers = body.couriers || [];
                this.rateToken = body.request_token || '';
                this.receiverAddressCode = body.receiver_address_code || null;
                this.simulated = !!body.simulated;
                this.selectedCourierId = null;
                if (this.couriers.length === 0) {
                    this.ratesError = 'No courier rates are available for that address right now. Try office pickup instead.';
                }
            })
            .catch(() => {
                this.ratesError = 'Network error while fetching delivery rates. Please try again.';
            })
            .finally(() => {
                this.ratesLoading = false;
            });
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
            if (this.deliveryMethod === 'delivery' && (!this.rateToken || !this.selectedCourier())) {
                this.errorMessage = 'Choose a courier delivery option (get rates above) or switch to office pickup.';
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
                delivery_method: this.deliveryMethod,
                notes: this.form.notes,
                items: this.selected().map(p => ({ product_id: p.id, quantity: this.qty[p.id] }))
            };
            if (this.deliveryMethod === 'delivery' && this.selectedCourier()) {
                payload.shipbubble_request_token = this.rateToken;
                payload.shipbubble_service_code = this.selectedCourier().service_code;
                payload.shipbubble_courier_id = this.selectedCourier().courier_id;
            }
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
