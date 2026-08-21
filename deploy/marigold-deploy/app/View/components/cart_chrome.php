<?php
// app/View/components/cart_chrome.php
// Global cart drawer, checkout modal and toast container (design chrome).
// Works with public/assets/js/app.js
?>
<!-- cart drawer -->
<div class="drawer-overlay" id="drawerOverlay"></div>
<aside class="cart-drawer" id="cartDrawer" aria-label="Shopping cart">
    <div class="cd-head">
        <h3>Your Cart</h3>
        <button class="cd-close" id="cdClose" aria-label="Close cart"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="cd-body" id="cdBody"></div>
    <div class="cd-foot">
        <div class="cd-totals">
            <div class="row"><span id="cdCount">0 items</span><span id="cdTotal" data-symbol="<?= \App\Core\Money::meta(\App\Core\Session::get('currency') ?? 'NGN')['symbol'] ?>"><?= \App\Core\Money::meta(\App\Core\Session::get('currency') ?? 'NGN')['symbol'] ?>0</span></div>
            <div class="row total"><span>Subtotal</span><span id="cdSubtotal" data-symbol="<?= \App\Core\Money::meta(\App\Core\Session::get('currency') ?? 'NGN')['symbol'] ?>"><?= \App\Core\Money::meta(\App\Core\Session::get('currency') ?? 'NGN')['symbol'] ?>0</span></div>
        </div>
        <button class="btn btn-gold btn-block btn-lg" id="checkoutBtn">Proceed to Checkout</button>
        <p class="cd-note">Prices are indicative. Final quotes confirmed on order.</p>
    </div>
</aside>

<!-- checkout modal -->
<div class="modal-overlay" id="checkoutOverlay">
    <div class="modal">
        <div class="modal-head">
            <h3>Checkout</h3>
            <button class="cd-close" id="checkoutClose" aria-label="Close"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
        </div>
        <div class="modal-body">
            <form id="checkoutForm" novalidate>
                <?= \App\Core\CSRF::field() ?>
                <h4>Contact</h4>
                <div class="form-grid">
                    <div class="form-row">
                        <label for="ckName">Full name <em>*</em></label>
                        <input class="field" id="ckName" required placeholder="e.g. Adaeze Okonkwo">
                    </div>
                    <div class="form-row">
                        <label for="ckCompany">Company</label>
                        <input class="field" id="ckCompany" placeholder="Organisation (optional)">
                    </div>
                    <div class="form-row">
                        <label for="ckEmail">Email <em>*</em></label>
                        <input class="field" id="ckEmail" type="email" required placeholder="you@company.com">
                    </div>
                    <div class="form-row">
                        <label for="ckPhone">Phone <em>*</em></label>
                        <input class="field" id="ckPhone" type="tel" required placeholder="+234 ...">
                    </div>
                </div>

                <h4>How would you like your order?</h4>
                <div class="pay-opts" id="fulOptions">
                    <label class="pay-opt sel"><input type="radio" name="ful" value="pickup" checked><span><span class="po-name">Office pickup</span><span class="po-sub" style="display:block">Free — 6 Oluwole Omole Street, Opebi, Lagos</span></span></label>
                    <label class="pay-opt"><input type="radio" name="ful" value="delivery"><span><span class="po-name">Courier Delivery</span><span class="po-sub" style="display:block">Live rates from our delivery partners</span></span></label>
                </div>

                <div id="ckDeliveryPanel" style="display:none">
                    <div class="form-grid">
                        <div class="form-row full">
                            <label for="ckAddress">Street address <em>*</em></label>
                            <input class="field" id="ckAddress" placeholder="House no., street, area">
                        </div>
                        <div class="form-row">
                            <label for="ckCity">City <em>*</em></label>
                            <input class="field" id="ckCity" placeholder="e.g. Ikeja">
                        </div>
                        <div class="form-row">
                            <label for="ckState">State <em>*</em></label>
                            <select class="field" id="ckState">
                                <option value="">Select state</option>
                                <option>Abia</option><option>Adamawa</option><option>Akwa Ibom</option><option>Anambra</option>
                                <option>Bauchi</option><option>Bayelsa</option><option>Benue</option><option>Borno</option>
                                <option>Cross River</option><option>Delta</option><option>Ebonyi</option><option>Edo</option>
                                <option>Ekiti</option><option>Enugu</option><option>FCT - Abuja</option><option>Gombe</option>
                                <option>Imo</option><option>Jigawa</option><option>Kaduna</option><option>Kano</option>
                                <option>Katsina</option><option>Kebbi</option><option>Kogi</option><option>Kwara</option>
                                <option>Lagos</option><option>Nasarawa</option><option>Niger</option><option>Ogun</option>
                                <option>Ondo</option><option>Osun</option><option>Oyo</option><option>Plateau</option>
                                <option>Rivers</option><option>Sokoto</option><option>Taraba</option><option>Yobe</option>
                                <option>Zamfara</option>
                            </select>
                        </div>
                        <div class="form-row full">
                            <label for="ckWhatsapp">WhatsApp number <em>*</em></label>
                            <input class="field" id="ckWhatsapp" type="tel" placeholder="+234 801 234 5678">
                        </div>
                    </div>
                    <button type="button" class="btn btn-ghost btn-block" id="ckGetRates" style="margin-top:4px">
                        <span id="ckGetRatesLabel">Get delivery rates</span>
                    </button>
                    <p class="form-error" id="ckRatesError" style="display:none"></p>
                    <div id="ckCourierList" style="margin-top:10px"></div>
                    <p class="field-hint" id="ckRatesHint" style="display:none"></p>
                </div>

                <div class="form-row full">
                    <label for="ckNote">Special instructions</label>
                    <textarea class="field" id="ckNote" placeholder="Personalisation, delivery dates, notes..."></textarea>
                </div>

                <h4>Payment method</h4>
                <div class="pay-opts" id="payOptionsWrap">
                    <label class="pay-opt sel"><input type="radio" name="pay" value="bank" checked><span><span class="po-name">Bank transfer / invoice</span><span class="po-sub" style="display:block">Corporate invoicing with approved terms</span></span></label>
                    <label class="pay-opt"><input type="radio" name="pay" value="paystack"><span><span class="po-name">Pay Online (Paystack)</span><span class="po-sub" style="display:block">Visa, Mastercard, Verve</span></span></label>
                    <label class="pay-opt"><input type="radio" name="pay" value="flutterwave"><span><span class="po-name">Pay Online (Flutterwave)</span><span class="po-sub" style="display:block">Card, bank, USSD, transfer</span></span></label>
                </div>

                <h4>Create an account</h4>
                <label class="acc-row"><input type="checkbox" id="ckAccount"><span><strong>Create an account</strong> — track your orders in your dashboard.</span></label>
                <div class="form-grid" id="ckAccountFields" style="display:none">
                    <div class="form-row">
                        <label for="ckPass">Password <em>*</em></label>
                        <input class="field" id="ckPass" type="password" placeholder="Minimum 8 characters" autocomplete="new-password">
                    </div>
                    <div class="form-row">
                        <label for="ckPass2">Confirm password <em>*</em></label>
                        <input class="field" id="ckPass2" type="password" placeholder="Repeat password" autocomplete="new-password">
                    </div>
                </div>

                <h4>Order summary</h4>
                <div class="order-sum" id="orderSummary"></div>
                <p class="form-error" id="ckError" style="display:none"></p>
                <button class="btn btn-gold btn-block btn-lg" id="placeOrderBtn" style="margin-top:20px">Place Order Request</button>            </form>
        </div>
    </div>
</div>

<div class="toasts" id="toasts"></div>
