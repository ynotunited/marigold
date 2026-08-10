<?php // app/View/pages/public/cart.php

// Mock Cart Data
$cartItems = [
    [
        'id' => 1,
        'name' => 'Executive Leather Notebook & Pen Set',
        'price' => 28500,
        'qty' => 10,
        'image' => 'https://images.unsplash.com/photo-1544816278-ca5e3f4abd8c?q=80&w=600&auto=format&fit=crop',
        'variant' => 'Matte Black'
    ],
    [
        'id' => 2,
        'name' => 'Branded Vacuum Flask 1L',
        'price' => 14500,
        'qty' => 25,
        'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=600&auto=format&fit=crop',
        'variant' => 'Navy Blue'
    ]
];
?>

<div style="background: var(--ivory); color: var(--ink);" x-data="cartPage()">

    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="/">Home</a><span>/</span><span>Cart</span></div>
            <div class="eyebrow center reveal">Your Selection</div>
            <h1 class="display reveal">Your <span class="gold-text">Cart</span></h1>
            <p class="lead reveal">Review your corporate gift order before proceeding to checkout.</p>
        </div>
    </section>

    <section class="section" style="padding-top: 10px;">
        <div class="container">

            <template x-if="items.length === 0">
                <div class="cart-panel reveal" style="padding: 30px;">
                    <div class="success-state">
                        <div class="ss-ico">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        </div>
                        <h3>Your cart is empty</h3>
                        <p>Looks like you haven't added any corporate gifts yet.</p>
                        <a href="/shop" class="btn btn-gold btn-lg">Start Shopping</a>
                    </div>
                </div>
            </template>

            <template x-if="items.length > 0">
                <div class="cart-layout">

                    <!-- Cart Items -->
                    <div>
                        <div class="cart-panel reveal">
                            <div class="cart-head">
                                <span>Product</span>
                                <span class="center">Price</span>
                                <span class="center">Quantity</span>
                                <span style="text-align: right;">Total</span>
                            </div>

                            <template x-for="(item, index) in items" :key="item.id">
                                <div class="cart-item">
                                    <div class="ci-thumb">
                                        <img :src="item.image" :alt="item.name" loading="lazy">
                                    </div>
                                    <div class="ci-info">
                                        <p class="ci-name" x-text="item.name"></p>
                                        <p class="ci-var" x-text="'Variant: ' + item.variant"></p>
                                    </div>
                                    <div class="ci-price" x-text="'&#8358;' + item.price.toLocaleString()"></div>
                                    <div class="ci-qty">
                                        <div class="qty">
                                            <button type="button" @click="updateQty(index, item.qty - 1)" aria-label="Decrease quantity">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M5 12h14"/></svg>
                                            </button>
                                            <input type="number" x-model.number="item.qty" @change="updateQty(index, item.qty)" aria-label="Quantity">
                                            <button type="button" @click="updateQty(index, item.qty + 1)" aria-label="Increase quantity">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ci-total">
                                        <span class="amt" x-text="'&#8358;' + (item.price * item.qty).toLocaleString()"></span>
                                        <button type="button" class="ci-remove" @click="removeItem(index)" aria-label="Remove item">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <aside class="summary-card reveal d1">
                        <h2 class="sc-title">Order Summary</h2>

                        <div class="row">
                            <span>Subtotal</span>
                            <span x-text="'&#8358;' + subtotal.toLocaleString()"></span>
                        </div>
                        <div class="row" x-show="discount > 0" style="display: none;">
                            <span>Discount (<span x-text="couponCode"></span>)</span>
                            <span style="color: #2e7d32; font-weight: 700;" x-text="'-&#8358;' + discount.toLocaleString()"></span>
                        </div>
                        <div class="row">
                            <span>Estimated Shipping</span>
                            <span>Calculated at checkout</span>
                        </div>
                        <div class="row total">
                            <span>Total</span>
                            <span class="amt" x-text="'&#8358;' + total.toLocaleString()"></span>
                        </div>
                        <p class="sc-tax">Taxes included if applicable.</p>

                        <!-- Promo Code -->
                        <div class="promo" x-data="{ code: '' }">
                            <input type="text" x-model="code" placeholder="Promo code" class="field" aria-label="Promo code">
                            <button type="button" @click="applyCoupon(code)" class="btn btn-ghost">Apply</button>
                        </div>

                        <a href="/checkout" class="btn btn-gold btn-lg btn-block">
                            Proceed to Checkout
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>

                        <div class="trust-row">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        </div>
                    </aside>

                </div>
            </template>

        </div>
    </section>

    <!-- Mobile Sticky Checkout Bar -->
    <div x-show="items.length > 0" class="sticky-bar lg-hide">
        <div>
            <span style="font-size: 12.5px; color: var(--muted); display: block;">Total</span>
            <span style="font-family: var(--font-display); font-weight: 520; font-size: 22px; color: var(--gold-deep);" x-text="'&#8358;' + total.toLocaleString()"></span>
        </div>
        <a href="/checkout" class="btn btn-gold btn-lg">Checkout Now</a>
    </div>
</div>

<script>
function cartPage() {
    return {
        items: <?= json_encode($cartItems) ?>,
        discount: 0,
        couponCode: '',

        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        get total() {
            return Math.max(0, this.subtotal - this.discount);
        },

        updateQty(index, newQty) {
            if (newQty < 1) newQty = 1;
            this.items[index].qty = parseInt(newQty) || 1;
        },

        removeItem(index) {
            this.items.splice(index, 1);
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Item removed from cart', type: 'success' }}));
        },

        applyCoupon(code) {
            if (code.toUpperCase() === 'CORP10') {
                this.discount = this.subtotal * 0.10;
                this.couponCode = code.toUpperCase();
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: '10% discount applied!', type: 'success' }}));
            } else {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Invalid or expired coupon code', type: 'error' }}));
            }
        }
    }
}
</script>
