/* ==========================================================================
   MARIGOLD SIGNATURE — shared app behaviour
   Cart · Drawer · Checkout · Toasts · UI helpers
   ========================================================================== */

(function () {
  "use strict";

  const CART_KEY = "marigold_cart";

  /* ---- Currency system ---- */
  // Read currency from cookie FIRST — works even if PHP layout is OPcache-stale
  function _readCookie(name) {
    var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
    return m ? decodeURIComponent(m[1]) : null;
  }
  function _writeCookie(name, val) {
    document.cookie = name + '=' + encodeURIComponent(val) + ';path=/;max-age=31536000;SameSite=Lax';
  }

  // Detect ?currency=XXX in URL — set cookie via JS (bypasses stale PHP)
  var _urlParam = new URLSearchParams(window.location.search).get('currency');
  if (_urlParam) {
    _urlParam = _urlParam.toUpperCase();
    _writeCookie('ms_currency', _urlParam);
    // Clean the URL and reload so the page renders with the cookie
    var clean = window.location.pathname + window.location.hash;
    window.location.replace(clean);
    return; // stop executing — page is reloading
  }

  var _cookieCur = (_readCookie('ms_currency') || '').toUpperCase();
  var CUR = (window.MS_CURRENCY || { base: 'NGN', selected: 'NGN', rates: {} });
  // Override selected from cookie if PHP layout is stale
  if (_cookieCur && CUR.selected !== _cookieCur) {
    CUR.selected = _cookieCur;
  }
  // Ensure rates object exists — fallback hardcoded rates (NGN→target)
  if (!CUR.rates || Object.keys(CUR.rates).length === 0) {
    CUR.rates = { NGN:1, USD:0.000653, GBP:0.000485, EUR:0.000573, GHS:0.00801, ZAR:0.0118, KES:0.0838, XAF:0.361, GMD:0.0460 };
  }
  function fmtMoney(n, code) {
    code = code || CUR.selected || CUR.base || 'NGN';
    n = Number(n || 0);
    var sym = { NGN: '\u20A6', USD: '$', GBP: '\u00A3', EUR: '\u20AC', GHS: 'GH\u20B5', ZAR: 'R', KES: 'KSh', XAF: 'FCFA', GMD: 'D' };
    var frac = (code === 'NGN' || code === 'XAF') ? 0 : 2;
    var s = n.toFixed(frac);
    // Thousands separator
    var parts = s.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, code === 'ZAR' ? ' ' : ',');
    s = parts.join('.');
    return (sym[code] || code + ' ') + s;
  }
  function convertPrice(amountNgn, toCurrency) {
    toCurrency = toCurrency || CUR.selected || CUR.base;
    if (toCurrency === CUR.base) return amountNgn;
    var rate = CUR.rates && CUR.rates[toCurrency];
    if (!rate || rate <= 0) return amountNgn;
    var frac = (toCurrency === 'NGN' || toCurrency === 'XAF') ? 0 : 2;
    return Number((amountNgn * rate).toFixed(frac));
  }
  // Legacy naira() now routes through the currency system
  const naira = function(n) { return fmtMoney(convertPrice(n)); };

  /* ------------------------------- helpers -------------------------------- */
  function $(sel, ctx) { return (ctx || document).querySelector(sel); }
  function $$(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }
  function imgSrc(id, w) {
    if (!id) return "";
    if (/^(https?:|data:|blob:)/i.test(id)) return id;
    if (/^\//.test(id)) return window.appUrl ? window.appUrl(id) : id;
    return "https://images.unsplash.com/photo-" + id + "?w=" + (w || 800) + "&q=80&auto=format&fit=crop";
  }
  function slugCat(id) {
    const c = CATEGORIES.find((x) => x.id === id);
    return c ? c.label : id;
  }
  function clampText(s, n) {
    s = String(s || "").trim();
    if (s.length <= n) return s;
    const cut = s.slice(0, n);
    const sp = cut.lastIndexOf(" ");
    const end = sp > n * 0.6 ? sp : n;
    return cut.slice(0, end).replace(/[\s.,;:!?]+$/, "") + "…";
  }

  /* ------------------------------- cart store ------------------------------ */
  const Cart = {
    items: load(),
    save() { localStorage.setItem(CART_KEY, JSON.stringify(this.items)); },
    count() { return this.items.reduce((s, i) => s + i.qty, 0); },
    subtotal() { return this.items.reduce((s, i) => s + i.qty * i.price, 0); },
    add(id, qty, note) {
      const p = PRODUCTS.find((x) => x.id === id);
      if (!p) return;
      const existing = this.items.find((i) => i.id === id && i.note === (note || ""));
      if (existing) existing.qty += qty;
      else this.items.push({ id, qty, note: note || "", price: p.price });
      this.save();
      renderCartUI();
    },
    setQty(id, qty) {
      const it = this.items.find((i) => i.id === id);
      if (!it) return;
      if (qty <= 0) this.items = this.items.filter((i) => i.id !== id);
      else it.qty = qty;
      this.save();
      renderCartUI();
    },
    remove(id) { this.items = this.items.filter((i) => i.id !== id); this.save(); renderCartUI(); },
    clear() { this.items = []; this.save(); renderCartUI(); },
  };

  function load() {
    try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; }
    catch (e) { return []; }
  }

  /* -------------------------------- toasts --------------------------------- */
  function toast(msg) {
    const wrap = $("#toasts");
    if (!wrap) return;
    const el = document.createElement("div");
    el.className = "toast";
    el.innerHTML =
      '<span class="t-ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></span>' +
      "<span>" + msg + "</span>";
    wrap.appendChild(el);
    setTimeout(() => { el.classList.add("out"); setTimeout(() => el.remove(), 400); }, 2600);
  }

  /* --------------------------- newsletter signup --------------------------- */
  function csrfToken() {
    const el = document.querySelector('#checkoutForm input[name="csrf_token"]');
    return el ? el.value : "";
  }

  function subscribe(email, source) {
    return fetch(appUrl("/newsletter/subscribe"), {
      method: "POST",
      headers: { "Content-Type": "application/json", "X-CSRF-Token": csrfToken() },
      body: JSON.stringify({ email: email, source: source || "Footer", consent: true }),
    })
      .then(function (res) {
        return res.json().catch(function () { return {}; });
      })
      .then(function (body) {
        if (body.ok) toast(body.message || "You're subscribed! Welcome to the Marigold circle.");
        else toast(body.error || "We could not subscribe you. Please try again.");
      })
      .catch(function () {
        toast("Network error. Please try again.");
      });
  }

  /* ------------------------------- cart drawer ------------------------------ */
  function openDrawer() {
    const d = $("#cartDrawer"), o = $("#drawerOverlay");
    d.classList.add("open"); o.classList.add("open");
    document.body.style.overflow = "hidden";
    renderCartDrawer();
  }
  function closeDrawer() {
    $("#cartDrawer").classList.remove("open"); $("#drawerOverlay").classList.remove("open");
    document.body.style.overflow = "";
  }

  function renderCartDrawer() {
    const body = $("#cdBody");
    if (!body) return;
    if (!Cart.items.length) {
      body.innerHTML =
        '<div class="cd-empty">' +
        '<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>' +
        "<p>Your cart is empty.</p>" +
        '<a href="' + appUrl('/shop') + '" class="btn btn-dark btn-sm">Browse the catalogue</a>' +
        "</div>";
      return;
    }
    body.innerHTML = Cart.items.map(function (i) {
      const p = PRODUCTS.find((x) => x.id === i.id) || {};
      return (
        '<div class="cd-item">' +
        '<img src="' + imgSrc(p.img, 240) + '" alt="' + (p.name || "") + '" loading="lazy">' +
        '<div>' +
        '<div class="ci-name">' + (p.name || "Item") + "</div>" +
        '<div class="ci-cat">' + slugCat(p.cat) + "</div>" +
        (i.note ? '<div class="ci-meta">Note: ' + i.note + "</div>" : "") +
        '<div class="ci-price">' + naira(i.price) + "</div>" +
        "</div>" +
        '<div class="ci-right">' +
        '<div class="qty" style="height:34px">' +
        '<button data-act="dec" data-id="' + i.id + '">−</button>' +
        '<input readonly value="' + i.qty + '" style="width:34px;height:34px;font-size:13px">' +
        '<button data-act="inc" data-id="' + i.id + '">+</button>' +
        "</div>" +
        '<button class="ci-remove" data-act="del" data-id="' + i.id + '">Remove</button>' +
        "</div>" +
        "</div>"
      );
    }).join("");
    $("#cdTotal").textContent = naira(Cart.subtotal());
    $("#cdSubtotal").textContent = naira(Cart.subtotal());
    $("#cdCount").textContent = Cart.count() + (Cart.count() === 1 ? " item" : " items");
  }

  /* ---------------------------- render cart counts --------------------------- */
  function renderCartUI() {
    const n = Cart.count();
    $$(".cart-count").forEach((el) => {
      el.textContent = n;
      el.classList.toggle("show", n > 0);
    });
    const drawer = $("#cartDrawer");
    if (drawer && drawer.classList.contains("open")) renderCartDrawer();
    const chkBtn = $("#checkoutBtn");
    if (chkBtn) chkBtn.disabled = n === 0;
  }

  /* -------------------------------- checkout -------------------------------- */
  const checkoutState = {
    couriers: [],
    rateToken: "",
    selectedCourier: null,
    ratesLoading: false,
  };

  function openCheckout() {
    if (!Cart.items.length) return;
    const err = $("#ckError");
    if (err) err.style.display = "none";
    resetDeliveryRates();
    renderOrderSummary();
    $("#checkoutOverlay").classList.add("open");
    document.body.style.overflow = "hidden";
  }
  function closeCheckout() {
    $("#checkoutOverlay").classList.remove("open");
    if (!$("#cartDrawer").classList.contains("open")) document.body.style.overflow = "";
  }

  function currentFulfilment() {
    return ($('input[name="ful"]:checked') || {}).value || "pickup";
  }

  function selectedCourierFee() {
    if (currentFulfilment() !== "delivery" || !checkoutState.selectedCourier) return 0;
    return Number(checkoutState.selectedCourier.total) || 0;
  }

  function courierKey(courier) {
    if (!courier) return "";
    return String(courier.courier_id || "") + "::" + String(courier.service_code || "");
  }

  function renderOrderSummary() {
    const box = $("#orderSummary");
    if (!box) return;
    const VAT_RATE = 0.075;
    const sub = Cart.subtotal();
    const subDisplay = convertPrice(sub);
    const rows = Cart.items.map(function (i) {
      const p = PRODUCTS.find((x) => x.id === i.id) || {};
      return '<div class="row"><span>' + i.qty + " × " + (p.name || "Item") + "</span><span>" + fmtMoney(convertPrice(i.qty * i.price)) + "</span></div>";
    }).join("");
    const fee = selectedCourierFee();
    const feeDisplay = convertPrice(fee);
    const deliveryRow = currentFulfilment() === "delivery"
      ? '<div class="row"><span>Courier delivery</span><span>' + (checkoutState.selectedCourier ? fmtMoney(feeDisplay) : "Rates pending") + "</span></div>"
      : '<div class="row"><span>Office pickup</span><span>Free</span></div>';
    const tax = subDisplay * VAT_RATE;
    const grand = subDisplay + tax + feeDisplay;
    box.innerHTML =
      rows +
      deliveryRow +
      '<div class="row"><span>VAT (7.5%)</span><span>' + fmtMoney(tax) + "</span></div>" +
      '<div class="row total"><span>Total</span><span>' + fmtMoney(grand) + "</span></div>";
  }

  function toggleDeliveryPanel(on) {
    const panel = $("#ckDeliveryPanel");
    if (panel) panel.style.display = on ? "block" : "none";
    if (!on) resetDeliveryRates();
  }

  function resetDeliveryRates() {
    checkoutState.couriers = [];
    checkoutState.rateToken = "";
    checkoutState.selectedCourier = null;
    checkoutState.ratesLoading = false;
    const list = $("#ckCourierList");
    if (list) list.innerHTML = "";
    const err = $("#ckRatesError");
    if (err) { err.style.display = "none"; }
    const hint = $("#ckRatesHint");
    if (hint) hint.style.display = "none";
  }

  function renderCourierList() {
    const list = $("#ckCourierList");
    if (!list) return;
    if (!checkoutState.couriers.length) {
      list.innerHTML = "";
      return;
    }
    list.innerHTML = '<p class="field-hint courier-title">Choose your courier</p>' + checkoutState.couriers.map(function (c) {
      const key = courierKey(c);
      const checked = courierKey(checkoutState.selectedCourier) === key;
      return (
        '<label class="pay-opt courier-opt ' + (checked ? "sel" : "") + '" style="margin-bottom:8px">' +
        '<input type="radio" name="ckCourier" value="' + key + '" ' + (checked ? "checked" : "") + " data-service='" + (c.service_code || "") + "'>" +
        "<span><span class=\"po-name\">" + (c.courier_name || "Courier") + "</span>" +
        (c.delivery_eta ? '<span class="po-sub" style="display:block">' + c.delivery_eta + "</span>" : "") +
        (checked ? '<span class="po-sub courier-selected" style="display:block">Selected for this order</span>' : "") +
        '</span><span class="po-price">' + naira(c.total) + "</span></label>"
      );
    }).join("");
    $$('#ckCourierList input[name="ckCourier"]').forEach(function (input) {
      input.addEventListener("change", function () {
        checkoutState.selectedCourier = checkoutState.couriers.find(function (c) { return courierKey(c) === input.value; }) || null;
        renderCourierList();
        renderOrderSummary();
      });
    });
  }

  function getDeliveryRates() {
    const err = $("#ckRatesError");
    const hint = $("#ckRatesHint");
    const hideErr = function () { if (err) err.style.display = "none"; };
    hideErr();

    const name = splitName($("#ckName").value);
    const street = ($("#ckAddress") || {}).value || "";
    const city = ($("#ckCity") || {}).value || "";
    const state = ($("#ckState") || {}).value || "";
    const email = ($("#ckEmail") || {}).value || "";
    const phone = ($("#ckPhone") || {}).value || "";
    if (!name.first || !name.last || !email || !phone) {
      if (err) { err.textContent = "Complete your contact details (name, email, phone) first."; err.style.display = "block"; }
      return;
    }
    if (!street || !city || !state) {
      if (err) { err.textContent = "Enter the full delivery address (street, city and state)."; err.style.display = "block"; }
      return;
    }

    const payload = {
      email: email,
      phone: phone,
      first_name: name.first,
      last_name: name.last,
      address_line1: street,
      city: city,
      state: state,
      items: Cart.items.map(function (i) {
        const p = PRODUCTS.find((x) => x.id === i.id) || {};
        return { slug: i.id, name: p.name || i.id, quantity: i.qty, price: p.price || 0 };
      }),
    };

    checkoutState.ratesLoading = true;
    const btnLabel = $("#ckGetRatesLabel");
    if (btnLabel) btnLabel.textContent = "Fetching rates…";
    const btn = $("#ckGetRates");
    if (btn) btn.disabled = true;
    if (err) err.style.display = "none";
    if (hint) hint.style.display = "none";

    const csrf = $('input[name="csrf_token"]');
    fetch(appUrl("/api/shipping/rates"), {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-Token": csrf ? csrf.value : "",
      },
      body: JSON.stringify(payload),
    })
      .then(async (res) => {
        const body = await res.json().catch(() => ({}));
        if (!res.ok) {
          if (err) { err.textContent = body.error || "We could not fetch delivery rates. Please try again."; err.style.display = "block"; }
          return;
        }
        checkoutState.couriers = body.couriers || [];
        checkoutState.rateToken = body.request_token || "";
        checkoutState.selectedCourier = checkoutState.couriers.slice().sort(function (a, b) {
          return (Number(a.total) || 0) - (Number(b.total) || 0);
        })[0] || null;
        if (checkoutState.couriers.length === 0) {
          if (err) { err.textContent = "No courier rates are available for that address right now. Try office pickup instead."; err.style.display = "block"; }
        }
        if (hint && body.simulated) {
          hint.textContent = checkoutState.selectedCourier
            ? "Rates loaded. We selected the lowest courier fee; choose another courier below if you prefer."
            : "Demo mode — showing sample rates. Configure a ShipBubble API key for live quotes.";
          hint.style.display = "block";
        } else if (hint && checkoutState.selectedCourier) {
          hint.textContent = "Rates loaded. We selected the lowest courier fee; choose another courier below if you prefer.";
          hint.style.display = "block";
        }
        renderCourierList();
        renderOrderSummary();
      })
      .catch(function () {
        if (err) { err.textContent = "Network error while fetching delivery rates. Please try again."; err.style.display = "block"; }
      })
      .finally(function () {
        checkoutState.ratesLoading = false;
        if (btnLabel) btnLabel.textContent = "Get delivery rates";
        if (btn) btn.disabled = false;
      });
  }

  function showCheckoutError(msg) {
    const err = $("#ckError");
    if (err) { err.textContent = msg; err.style.display = msg ? "block" : "none"; }
    const btn = $("#placeOrderBtn");
    if (btn) { btn.disabled = false; btn.textContent = "Place Order Request"; }
  }

  function splitName(full) {
    const parts = String(full || "").trim().split(/\s+/);
    const first = parts.shift() || "";
    const last = parts.join(" ") || first;
    return { first, last };
  }

  function payOptions() {
    $$("#payOptionsWrap .pay-opt").forEach((el) => {
      el.addEventListener("click", function () {
        $$("#payOptionsWrap .pay-opt").forEach((x) => x.classList.remove("sel"));
        el.classList.add("sel");
        el.querySelector("input").checked = true;
      });
    });
  }

  function fulfilOptions() {
    $$("#fulOptions .pay-opt").forEach((el) => {
      el.addEventListener("click", function () {
        $$("#fulOptions .pay-opt").forEach((x) => x.classList.remove("sel"));
        el.classList.add("sel");
        el.querySelector("input").checked = true;
        toggleDeliveryPanel(currentFulfilment() === "delivery");
        renderOrderSummary();
      });
    });
  }

  function initAccountToggle() {
    const acct = $("#ckAccount");
    if (!acct) return;
    acct.addEventListener("change", function () {
      const fields = $("#ckAccountFields");
      if (fields) fields.style.display = this.checked ? "grid" : "none";
    });
  }

  function renderSuccess(res) {
    const modal = $("#checkoutOverlay");
    if (!modal) return;
    const isDelivery = res.delivery_method === "delivery";
    const courierName = res.shipbubble_courier_name || "";
    const whatsappBtn = isDelivery && res.whatsapp_link
      ? '<a class="btn btn-gold btn-block btn-lg" style="margin-bottom:10px" href="' + res.whatsapp_link + '" target="_blank" rel="noopener">Confirm delivery on WhatsApp</a>'
      : "";
    const acctNote = res.account_created
      ? '<p style="margin-top:-16px">Your account was created — <a href="' + appUrl('/account/dashboard') + '" style="color:var(--gold-2);font-weight:700">go to your dashboard</a> to track this order.</p>'
      : "";
    modal.querySelector(".modal-body").innerHTML =
      '<div class="success-state">' +
      '<div class="ss-ico"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></div>' +
      "<h3>Order received</h3>" +
      "<p>Thank you. Your order <strong>" + res.order_number + "</strong> for " + naira(res.grand_total) +
      " has been logged" +
      (isDelivery
        ? (courierName ? " for " + courierName + " delivery." : " for courier delivery.")
        : " — it will be ready for pickup at 6 Oluwole Omole Street, Opebi, Lagos.") +
      "</p>" +
      whatsappBtn +
      acctNote +
      '<a class="btn btn-dark btn-block" href="' + appUrl('/shop') + '">Continue browsing</a>' +
      "</div>";
    Cart.clear();
    toast("Order " + res.order_number + " received — we'll be in touch.");
  }

  /* ------------------------------ event bindings ----------------------------- */
  function bindCartEvents() {
    document.addEventListener("click", function (e) {
      const btn = e.target.closest("[data-act]");
      if (!btn) return;
      const act = btn.dataset.act;
      const id = btn.dataset.id;
      if (act === "inc") Cart.setQty(id, Cart.items.find((i) => i.id === id).qty + 1);
      else if (act === "dec") Cart.setQty(id, Cart.items.find((i) => i.id === id).qty - 1);
      else if (act === "del") Cart.remove(id);
      else if (act === "add") {
        const qty = parseInt($("#qtyInput") ? $("#qtyInput").value : "1", 10) || 1;
        const note = $("#personalNote") ? $("#personalNote").value : "";
        Cart.add(id, qty, note);
        toast('Added to cart — <strong style="color:var(--gold-2)">' + (PRODUCTS.find((p) => p.id === id) || {}).name + "</strong>");
        if ($("#cartDrawer") && $("#cartDrawer").classList.contains("open")) renderCartDrawer();
      }
    });
  }

  /* ------------------------------ product cards ------------------------------ */
  function productCard(p) {
    const cartFab =
      '<button class="cart-fab" data-act="add" data-id="' + p.id + '" aria-label="Add ' + p.name + ' to cart">' +
      '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>' +
      "</button>";
    return (
      '<article class="pcard reveal">' +
      '<div class="img-wrap">' +
      '<a class="img-link" href="' + appUrl('/product/') + p.id + '" aria-label="View ' + p.name + '">' +
      (p.badge ? '<span class="badge ' + (p.badge === "Premium" || p.badge === "New" ? "b-gold" : "") + '">' + p.badge + "</span>" : "") +
      '<img src="' + imgSrc(p.img, 700) + '" alt="' + p.name + '" loading="lazy">' +
      "</a>" +
      cartFab +
      '<button class="quick" data-act="add" data-id="' + p.id + '"><span class="btn btn-gold btn-block">Add to Cart</span></button>' +
      "</div>" +
      '<div class="p-body">' +
      '<span class="p-cat">' + slugCat(p.cat) + "</span>" +
      '<a class="p-name" href="' + appUrl('/product/') + p.id + '">' + clampText(p.name, 34) + "</a>" +
      '<p class="p-desc">' + p.short + "</p>" +
      '<div class="p-foot">' +
      '<span class="price">' + naira(p.price) + "</span>" +
      '<a class="link-more" href="' + appUrl('/product/') + p.id + '">Details <span class="arr">→</span></a>' +
      "</div>" +
      "</div>" +
      "</article>"
    );
  }

  /* --------------------------------- nav / ui -------------------------------- */
  function initNav() {
    const page = document.body.dataset.page;
    if (page) $$(".nav-links a[data-nav]").forEach((a) => a.classList.toggle("active", a.dataset.nav === page));
    const burger = $("#burger");
    const links = $("#navLinks");
    if (burger && links) {
      burger.addEventListener("click", function () {
        burger.classList.toggle("open");
        links.classList.toggle("open");
      });
      $$("a", links).forEach((a) => a.addEventListener("click", () => {
        burger.classList.remove("open"); links.classList.remove("open");
      }));
    }
    /* Events dropdown — first tap opens (touch), once open the link navigates
       to /events so the parent stays clickable on desktop hover */
    $$(".nav-dd").forEach(function (dd) {
      const parent = dd.querySelector(".dd-parent");
      if (!parent) return;
      parent.setAttribute("aria-haspopup", "true");
      parent.setAttribute("aria-expanded", "false");
      parent.addEventListener("click", function (e) {
        if (!dd.classList.contains("open")) {
          e.preventDefault();
          dd.classList.add("open");
          parent.setAttribute("aria-expanded", "true");
        }
      });
    });
    document.addEventListener("click", function (e) {
      if (e.target.closest(".nav-dd")) return;
      $$(".nav-dd.open").forEach(function (dd) {
        dd.classList.remove("open");
        const p = dd.querySelector(".dd-parent");
        if (p) p.setAttribute("aria-expanded", "false");
      });
    });
    window.addEventListener("scroll", function () {
      $(".nav").classList.toggle("scrolled", window.scrollY > 20);
    }, { passive: true });
  }

  function initReveal() {
    const io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add("in"); io.unobserve(en.target); }
      });
    }, { threshold: 0.12 });
    $$(".reveal").forEach((el) => io.observe(el));
  }

  /* ----------------------------- currency symbols ----------------------------- */
  function initCurrencySymbols() {
    var cur = CUR.selected || CUR.base || "NGN";
    var sym = { NGN: "\u20A6", USD: "$", GBP: "\u00A3", EUR: "\u20AC", GHS: "GH\u20B5", ZAR: "R", KES: "KSh", XAF: "FCFA", GMD: "D" };
    var s = sym[cur] || cur + " ";
    $$(".cur-sym").forEach(function(el) { el.textContent = s; });
  }

  /* --------------------------------- page init -------------------------------- */
  document.addEventListener("DOMContentLoaded", function () {
    initNav();
    initReveal();
    bindCartEvents();
    renderCartUI();
    payOptions();
    fulfilOptions();
    initAccountToggle();
    initCurrencySymbols();

    const drawer = $("#cartDrawer");
    if (drawer) {
      $("#cartOpen").addEventListener("click", openDrawer);
      $("#cartOpenMobile") && $("#cartOpenMobile").addEventListener("click", openDrawer);
      $("#cdClose").addEventListener("click", closeDrawer);
      $("#drawerOverlay").addEventListener("click", closeDrawer);
    }
    $("#checkoutBtn") && $("#checkoutBtn").addEventListener("click", openCheckout);
    $("#checkoutClose") && $("#checkoutClose").addEventListener("click", closeCheckout);
    $("#ckGetRates") && $("#ckGetRates").addEventListener("click", getDeliveryRates);
    $("#checkoutOverlay") && $("#checkoutOverlay").addEventListener("click", function (e) {
      if (e.target.id === "checkoutOverlay") closeCheckout();
    });

    const placeBtn = $("#placeOrderBtn");
    if (placeBtn) {
      placeBtn.addEventListener("click", function (e) {
        e.preventDefault();
        const form = $("#checkoutForm");
        if (!form) return;
        showCheckoutError("");

        const delivery = currentFulfilment() === "delivery";
        const addressEl = $("#ckAddress");
        const waEl = $("#ckWhatsapp");
        if (addressEl) addressEl.required = delivery;
        if (waEl) waEl.required = delivery;
        if (form && !form.checkValidity()) { form.reportValidity(); return; }

        const createAcct = !!$("#ckAccount") && $("#ckAccount").checked;
        if (createAcct) {
          const pass = ($("#ckPass") || {}).value || "";
          const pass2 = ($("#ckPass2") || {}).value || "";
          if (pass.length < 8) { showCheckoutError("Password must be at least 8 characters."); return; }
          if (pass !== pass2) { showCheckoutError("Passwords do not match."); return; }
        }

        const name = splitName($("#ckName").value);
        const addrParts = ($("#ckAddress") || {}).value
          ? $("#ckAddress").value.split(",").map((s) => s.trim()).filter(Boolean)
          : [];
        const city = ($("#ckCity") || {}).value || (addrParts.length > 1 ? addrParts[addrParts.length - 1] : (addrParts[0] || ""));
        const street = ($("#ckAddress") || {}).value || (addrParts.length > 1 ? addrParts.slice(0, -1).join(", ") : (addrParts[0] || ""));
        const state = ($("#ckState") || {}).value || "";
        if (delivery && !(checkoutState.rateToken && checkoutState.selectedCourier)) {
          showCheckoutError("Please fetch delivery rates and select a courier first.");
          return;
        }

        const method = ($('input[name="pay"]:checked') || {}).value || "bank";
        const csrfEl = form.querySelector('input[name="csrf_token"]');

        const payload = {
          first_name: name.first,
          last_name: name.last,
          company: ($("#ckCompany") || {}).value || "",
          email: ($("#ckEmail") || {}).value || "",
          phone: ($("#ckPhone") || {}).value || "",
          delivery_method: delivery ? "delivery" : "pickup",
          address_line1: street,
          city: city,
          state: state,
          whatsapp: delivery ? (($("#ckWhatsapp") || {}).value || "") : "",
          notes: ($("#ckNote") || {}).value || "",
          payment_method: method,
          create_account: createAcct,
          password: createAcct ? (($("#ckPass") || {}).value || "") : "",
          items: Cart.items.map(function (i) {
            const p = PRODUCTS.find((x) => x.id === i.id) || {};
            return { slug: i.id, name: p.name || i.id, quantity: i.qty, price: i.price };
          }),
          currency: CUR.selected || CUR.base || "NGN",
        };

        if (delivery && checkoutState.rateToken && checkoutState.selectedCourier) {
          payload.shipbubble_request_token = checkoutState.rateToken;
          payload.shipbubble_service_code = checkoutState.selectedCourier.service_code;
          payload.shipbubble_courier_id = checkoutState.selectedCourier.courier_id;
        }

        placeBtn.disabled = true;
        placeBtn.textContent = "Placing order…";

        fetch(appUrl("/checkout"), {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": csrfEl ? csrfEl.value : "",
            "Idempotency-Key": crypto.randomUUID ? crypto.randomUUID() : String(Date.now()),
          },
          body: JSON.stringify(payload),
        })
          .then(async (res) => {
            const body = await res.json().catch(() => ({}));
            if (!res.ok) {
              showCheckoutError(body.error || "We could not place your order. Please try again.");
              return;
            }
            if (body.redirect_url) {
              window.location.href = body.redirect_url;
              return;
            }
            renderSuccess(body);
          })
          .catch(() => {
            showCheckoutError("Network error. Please try again.");
          });
      });
    }
  });

  /* expose for inline usage */
  window.Marigold = { Cart, naira, imgSrc, productCard, toast, subscribe, fmtMoney, convertPrice, CUR };

  // Fix header currency button if PHP layout is OPcache-stale
  document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('curToggle');
    if (btn && _cookieCur) {
      btn.textContent = _cookieCur;
    }
    // Highlight active currency in dropdown
    var links = document.querySelectorAll('#curMenu a');
    for (var i = 0; i < links.length; i++) {
      var code = (links[i].getAttribute('href') || '').match(/currency=([A-Z]+)/);
      if (code && code[1] === _cookieCur) {
        links[i].style.fontWeight = '700';
        links[i].style.color = 'var(--gold, #C89B3C)';
      } else {
        links[i].style.fontWeight = '';
        links[i].style.color = 'rgba(255,255,255,.75)';
      }
    }
  });
})();
