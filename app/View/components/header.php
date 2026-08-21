<?php
// app/View/components/header.php

// Compute the current path relative to the app base (mirrors public/index.php)
$__path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$__base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($__base === '/' || $__base === '.') {
    $__base = '';
}
if ($__base !== '' && $__base !== '/') {
    if (stripos($__path, $__base) === 0) {
        $__path = substr($__path, strlen($__base)) ?: '/';
    }
}
$__path = rtrim($__path, '/');
if ($__path === '') {
    $__path = '/';
}

// Return true when the current path matches any given pattern (exact or nested)
$__isActive = function (string ...$patterns) use ($__path): bool {
    foreach ($patterns as $p) {
        $p = rtrim($p, '/');
        if ($p === '') {
            $p = '/';
        }
        if ($__path === $p) {
            return true;
        }
        if ($p !== '/' && strpos($__path, $p . '/') === 0) {
            return true;
        }
    }
    return false;
};
$__url = function (string $path) use ($__base): string {
    return $__base . '/' . ltrim($path, '/');
};
?>
<!-- top bar -->
<div class="topbar">
    <div class="marquee">
        <span>The right gift, at the right moment, changes how you're remembered &nbsp;✦&nbsp; 15+ years of gifting excellence &nbsp;✦&nbsp; Bespoke curation &amp; an in-house branding studio &nbsp;✦&nbsp; Strategic event support, delivered nationwide &nbsp;✦&nbsp; Every piece designed to make your brand unforgettable &nbsp;✦&nbsp;</span>
        <span>The right gift, at the right moment, changes how you're remembered &nbsp;✦&nbsp; 15+ years of gifting excellence &nbsp;✦&nbsp; Bespoke curation &amp; an in-house branding studio &nbsp;✦&nbsp; Strategic event support, delivered nationwide &nbsp;✦&nbsp; Every piece designed to make your brand unforgettable &nbsp;✦&nbsp;</span>
    </div>
</div>

<!-- nav -->
<nav class="nav">
    <div class="container nav-inner">
        <a class="brand" href="<?= $__url('/') ?>" aria-label="Marigold Signature — Home">
            <img src="<?= $__url('/ms-logo.png') ?>" alt="Marigold Signature">
        </a>
        <div class="nav-links" id="navLinks">
            <a href="<?= $__url('/') ?>" data-nav="home" class="<?= $__isActive('/') ? 'active' : '' ?>">Home</a>
            <a href="<?= $__url('/shop') ?>" data-nav="shop" class="<?= $__isActive('/shop', '/product', '/cart', '/checkout', '/quote-request', '/order-confirmation') ? 'active' : '' ?>">Shop</a>
            <div class="nav-dd">
                <a class="dd-parent <?= $__isActive('/events') ? 'active' : '' ?>" href="<?= $__url('/events') ?>" data-nav="events">Events <svg class="dd-caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></a>
                <div class="dd-menu">
                    <a href="<?= $__url('/events/corporate-meeting') ?>">Corporate Meeting</a>
                    <a href="<?= $__url('/events/conference') ?>">Conference</a>
                    <a href="<?= $__url('/events/dinner') ?>">Dinner</a>
                </div>
            </div>
            <a href="<?= $__url('/blog') ?>" data-nav="blog" class="<?= $__isActive('/blog') ? 'active' : '' ?>">Blog</a>
            <a href="<?= $__url('/about') ?>" data-nav="about" class="<?= $__isActive('/about') ? 'active' : '' ?>">About</a>
            <a href="<?= $__url('/contact') ?>" data-nav="contact" class="<?= $__isActive('/contact') ? 'active' : '' ?>">Contact</a>
        </div>
        <div class="nav-cta">
            <div class="cur-sel" style="position:relative;margin-right:4px">
                <button id="curToggle" aria-label="Currency" style="background:none;border:1px solid rgba(255,255,255,.18);border-radius:6px;padding:4px 8px;color:inherit;font-size:12px;cursor:pointer;font-weight:600;letter-spacing:.03em">
                    <?= strtoupper(\App\Core\Session::get('currency') ?? 'NGN') ?>
                </button>
                <div id="curMenu" style="display:none;position:absolute;right:0;top:100%;margin-top:4px;background:var(--ink,#1B1A15);border:1px solid rgba(255,255,255,.12);border-radius:8px;padding:4px 0;min-width:80px;z-index:200;box-shadow:0 8px 24px rgba(0,0,0,.3)">
                    <?php foreach (\App\Core\Money::supportedCodes() as $code): ?>
                    <a href="?currency=<?= $code ?>" style="display:block;padding:6px 14px;text-decoration:none;font-size:12px;color:rgba(255,255,255,.75);<?= strtoupper(\App\Core\Session::get('currency') ?? 'NGN') === $code ? 'font-weight:700;color:var(--gold,#C89B3C)' : '' ?>"><?= $code ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <a class="btn btn-dark" href="<?= $__url('/shop') ?>">Browse Gifts</a>
            <a class="cart-btn" href="<?= $__url('/login') ?>" aria-label="Sign in" title="Sign in / Register">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
            <button class="cart-btn" id="cartOpen" aria-label="Open cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <span class="cart-count" id="cartCount">0</span>
            </button>
            <button class="burger" id="burger" aria-label="Menu"><span></span><span></span><span></span></button>
        </div>
    </div>
</nav>
