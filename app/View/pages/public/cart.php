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

<div class="pt-24 pb-20 px-4 sm:px-8 bg-[var(--bg-primary)] min-h-[80vh]" x-data="cartPage()">
    <div class="container mx-auto max-w-5xl">
        
        <h1 class="font-['Manrope'] text-3xl sm:text-5xl font-extrabold mb-8">Your Cart</h1>

        <template x-if="items.length === 0">
            <div class="text-center py-20 bg-[var(--surface)] rounded-3xl border border-[var(--border)]">
                <i data-lucide="shopping-bag" class="w-16 h-16 mx-auto mb-4 text-[var(--text-muted)]"></i>
                <h2 class="text-2xl font-bold mb-2 text-white">Your cart is empty</h2>
                <p class="text-[var(--text-secondary)] mb-6">Looks like you haven't added any corporate gifts yet.</p>
                <a href="/shop" class="inline-flex bg-[var(--gold)] text-black font-bold px-8 py-4 rounded-xl hover:bg-[#D4AF37] transition-colors">
                    Start Shopping
                </a>
            </div>
        </template>

        <template x-if="items.length > 0">
            <div class="flex flex-col lg:flex-row gap-10">
                
                <!-- Cart Items -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-[var(--surface)] rounded-3xl border border-[var(--border)] overflow-hidden">
                        
                        <div class="hidden sm:grid grid-cols-12 gap-4 p-6 border-b border-[var(--border)] text-sm font-bold text-[var(--text-secondary)] uppercase tracking-wider">
                            <div class="col-span-6">Product</div>
                            <div class="col-span-2 text-center">Price</div>
                            <div class="col-span-2 text-center">Quantity</div>
                            <div class="col-span-2 text-right">Total</div>
                        </div>

                        <div class="divide-y divide-[var(--border)]">
                            <template x-for="(item, index) in items" :key="item.id">
                                <div class="p-6 flex flex-col sm:grid sm:grid-cols-12 sm:items-center gap-4 group transition-colors hover:bg-[var(--card)]">
                                    
                                    <!-- Mobile Remove -->
                                    <button @click="removeItem(index)" class="sm:hidden absolute right-6 text-[var(--text-muted)] hover:text-red-500"><i data-lucide="x" class="w-5 h-5"></i></button>

                                    <!-- Product -->
                                    <div class="col-span-6 flex items-center gap-4">
                                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-[var(--bg-primary)] overflow-hidden shrink-0 border border-[var(--border)]">
                                            <img :src="item.image" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-sm sm:text-base leading-snug mb-1 text-white pr-6 sm:pr-0" x-text="item.name"></h3>
                                            <p class="text-xs text-[var(--text-muted)] mb-2" x-text="'Variant: ' + item.variant"></p>
                                            
                                            <!-- Mobile Price & Qty -->
                                            <div class="sm:hidden flex items-center justify-between mt-2">
                                                <span class="font-['Manrope'] font-bold text-[var(--gold)]" x-text="'₦' + item.price.toLocaleString()"></span>
                                                <div class="flex items-center border border-[var(--border)] rounded-lg bg-[var(--bg-primary)] h-8 w-24">
                                                    <button @click="updateQty(index, item.qty - 1)" class="flex-1 text-[var(--text-muted)]"><i data-lucide="minus" class="w-3 h-3 mx-auto"></i></button>
                                                    <input type="number" x-model.number="item.qty" @change="updateQty(index, item.qty)" class="w-8 text-center bg-transparent text-xs font-bold focus:outline-none p-0 m-0">
                                                    <button @click="updateQty(index, item.qty + 1)" class="flex-1 text-[var(--text-muted)]"><i data-lucide="plus" class="w-3 h-3 mx-auto"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Desktop Price -->
                                    <div class="hidden sm:block col-span-2 text-center">
                                        <span class="font-['Manrope'] font-bold text-[var(--text-secondary)]" x-text="'₦' + item.price.toLocaleString()"></span>
                                    </div>

                                    <!-- Desktop Qty -->
                                    <div class="hidden sm:flex col-span-2 justify-center">
                                        <div class="flex items-center border border-[var(--border)] rounded-xl bg-[var(--bg-primary)] h-10 w-24">
                                            <button @click="updateQty(index, item.qty - 1)" class="flex-1 hover:text-white text-[var(--text-muted)]"><i data-lucide="minus" class="w-3 h-3 mx-auto"></i></button>
                                            <input type="number" x-model.number="item.qty" @change="updateQty(index, item.qty)" class="w-8 text-center bg-transparent text-sm font-bold focus:outline-none p-0 m-0">
                                            <button @click="updateQty(index, item.qty + 1)" class="flex-1 hover:text-white text-[var(--text-muted)]"><i data-lucide="plus" class="w-3 h-3 mx-auto"></i></button>
                                        </div>
                                    </div>

                                    <!-- Desktop Total & Remove -->
                                    <div class="hidden sm:flex col-span-2 items-center justify-end gap-3">
                                        <span class="font-['Manrope'] font-bold text-white" x-text="'₦' + (item.price * item.qty).toLocaleString()"></span>
                                        <button @click="removeItem(index)" class="text-[var(--text-muted)] hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-[var(--surface)] rounded-3xl border border-[var(--border)] p-6 lg:p-8 sticky top-28">
                        <h2 class="font-['Manrope'] text-xl font-bold mb-6">Order Summary</h2>
                        
                        <div class="space-y-4 mb-6 text-sm">
                            <div class="flex justify-between">
                                <span class="text-[var(--text-secondary)]">Subtotal</span>
                                <span class="font-bold text-white" x-text="'₦' + subtotal.toLocaleString()"></span>
                            </div>
                            
                            <div x-show="discount > 0" class="flex justify-between text-green-500" style="display: none;">
                                <span>Discount (<span x-text="couponCode"></span>)</span>
                                <span class="font-bold" x-text="'-₦' + discount.toLocaleString()"></span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-[var(--text-secondary)]">Estimated Shipping</span>
                                <span class="text-[var(--text-muted)]">Calculated at checkout</span>
                            </div>
                        </div>

                        <div class="border-t border-[var(--border)] py-4 mb-6">
                            <div class="flex justify-between items-end">
                                <span class="font-bold text-white">Total</span>
                                <span class="font-['Manrope'] text-3xl font-extrabold text-[var(--gold)]" x-text="'₦' + total.toLocaleString()"></span>
                            </div>
                            <p class="text-[10px] text-[var(--text-muted)] text-right mt-1">Taxes included if applicable.</p>
                        </div>

                        <!-- Promo Code -->
                        <div class="mb-6" x-data="{ code: '' }">
                            <label class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2 block">Promo Code</label>
                            <div class="flex gap-2">
                                <input type="text" x-model="code" placeholder="Enter code" class="flex-1 bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--gold)] text-white">
                                <button @click="applyCoupon(code)" class="bg-[var(--card)] border border-[var(--border)] text-white px-4 rounded-xl text-sm font-bold hover:border-[var(--gold)] hover:text-[var(--gold)] transition-colors">Apply</button>
                            </div>
                        </div>

                        <a href="/checkout" class="block w-full bg-[var(--gold)] text-black text-center font-bold py-4 rounded-xl hover:bg-[#D4AF37] transition-colors flex items-center justify-center gap-2">
                            Proceed to Checkout <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>

                        <!-- Trust -->
                        <div class="mt-6 flex flex-wrap justify-center gap-4 opacity-60">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                            <i data-lucide="lock" class="w-6 h-6"></i>
                            <i data-lucide="credit-card" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>

            </div>
        </template>
        
        <!-- Mobile Sticky Checkout Bar (Optional extra convenience) -->
        <div x-show="items.length > 0" class="lg:hidden fixed bottom-0 left-0 right-0 bg-[var(--surface)] border-t border-[var(--border)] p-4 z-40 pb-safe shadow-[0_-10px_20px_rgba(0,0,0,0.5)]">
            <div class="flex justify-between items-center mb-3 px-2">
                <span class="text-sm text-[var(--text-secondary)]">Total:</span>
                <span class="font-['Manrope'] font-bold text-xl text-[var(--gold)]" x-text="'₦' + total.toLocaleString()"></span>
            </div>
            <a href="/checkout" class="block w-full bg-[var(--gold)] text-black text-center font-bold py-3.5 rounded-xl">
                Checkout Now
            </a>
        </div>
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
