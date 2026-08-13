<?php // app/View/pages/public/shop.php

// Active category from query param (mirrors CATEGORIES ids used by the JS shop)
$categories = [['id' => 'all', 'label' => 'All Products']];
try {
    $__catalogue = \App\Core\Catalogue::all();
    $categories = array_merge($categories, $__catalogue['categories']);
} catch (\Throwable $__e) {
    // fall back to the static list below
    $categories = [
        ['id' => 'all',            'label' => 'All Products'],
        ['id' => 'corporate-gifts', 'label' => 'Corporate Gifts'],
        ['id' => 'apparel',        'label' => 'Branded Apparel'],
        ['id' => 'desk-office',    'label' => 'Desk & Office'],
        ['id' => 'drinkware',      'label' => 'Drinkware'],
        ['id' => 'tech-gadgets',   'label' => 'Tech & Gadgets'],
        ['id' => 'event',          'label' => 'Event Essentials'],
    ];
}
$activeCat = htmlspecialchars($_GET['cat'] ?? 'all');
$validCats = array_column($categories, 'id');
if (!in_array($activeCat, $validCats, true)) {
    $activeCat = 'all';
}

$availability = ['New Arrivals', 'Best Sellers', 'In Stock', 'Pre-order', 'Quote Only'];
?>

<div style="background: var(--ivory); color: var(--ink);">

    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="<?= app_url('/') ?>">Home</a><span>/</span><span>Shop</span></div>
            <span class="eyebrow reveal">The Catalogue</span>
            <h1 class="display h1 reveal">Gifts that work <span class="gold-text">as hard as your brand</span></h1>
            <p class="lead reveal">Every piece can be personalised with your mark — from single units to nationwide campaigns.</p>
        </div>
    </section>

    <section class="section" style="padding-top:10px">
        <div class="container">
            <div class="shop-layout">
                <!-- ======================= SIDEBAR FILTERS ======================= -->
                <aside class="shop-side reveal d1">
                    <div class="side-card">
                        <h3 class="side-h">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            Search
                        </h3>
                        <div class="side-search">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            <input id="sideSearch" type="text" placeholder="Search products..." aria-label="Search products">
                        </div>
                    </div>

                    <div class="side-card">
                        <h3 class="side-h">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3.22H4a1 1 0 0 0-1 1v5.59a2 2 0 0 0 .59 1.41l9.59 9.59a2 2 0 0 0 2.82 0l4.59-4.59a2 2 0 0 0 0-2.81Z"/><path d="M7 7h.01"/></svg>
                            Category
                        </h3>
                        <div class="side-list">
                            <?php foreach ($categories as $c): ?>
                            <a href="<?= app_url('/shop') ?><?= $c['id'] !== 'all' ? '?cat=' . $c['id'] : '' ?>"
                               class="<?= $activeCat === $c['id'] ? 'active' : '' ?>">
                                <?= $c['label'] ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="side-card">
                        <h3 class="side-h">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v18H3z"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                            Price
                            <button class="side-clear" id="priceClear" type="button">Clear</button>
                        </h3>
                        <div class="range-row">
                            <div class="range-input">
                                <span>&#8358;</span>
                                <input id="priceMin" type="number" min="0" step="500" placeholder="Min" aria-label="Minimum price">
                            </div>
                            <span class="range-sep">&ndash;</span>
                            <div class="range-input">
                                <span>&#8358;</span>
                                <input id="priceMax" type="number" min="0" step="500" placeholder="Max" aria-label="Maximum price">
                            </div>
                        </div>
                    </div>

                    <div class="side-card">
                        <h3 class="side-h">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                            Availability
                        </h3>
                        <div class="avail-list">
                            <?php foreach ($availability as $a): ?>
                            <label class="avail-item">
                                <input type="checkbox">
                                <span><?= $a ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="side-card">
                        <h3 class="side-h">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3.22H4a1 1 0 0 0-1 1v5.59a2 2 0 0 0 .59 1.41l9.59 9.59a2 2 0 0 0 2.82 0l4.59-4.59a2 2 0 0 0 0-2.81Z"/><path d="M7 7h.01"/></svg>
                            Featured
                            <a class="side-view" href="<?= app_url('/shop') ?>">View all &rarr;</a>
                        </h3>
                        <div class="feat-list" id="sideFeatured"></div>
                    </div>
                </aside>

                <!-- ======================= PRODUCT GRID ======================= -->
                <div class="flex-1 min-w-0">
                    <div class="grid-head reveal">
                        <p class="shop-count" id="shopCount"></p>
                        <div class="sort-box">
                            <select id="shopSort" aria-label="Sort products">
                                <option value="featured">Featured</option>
                                <option value="price-asc">Price: Low &rarr; High</option>
                                <option value="price-desc">Price: High &rarr; Low</option>
                                <option value="name">Name A&ndash;Z</option>
                            </select>
                        </div>
                    </div>
                    <div class="pgrid" id="shopGrid"></div>
                    <div class="shop-pagination" id="shopPagination"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section dark cta">
        <div class="container">
            <span class="eyebrow reveal">Need something bespoke?</span>
            <h2 class="display h2 reveal">Looking for a full branding programme?</h2>
            <p class="lead reveal">Tell us your audience, budget and timeline — our studio will craft a tailored proposal.</p>
            <div class="cta-actions reveal">
                <a href="<?= app_url('/contact') ?>" class="btn btn-gold btn-lg">Request a Proposal <span class="arr">&rarr;</span></a>
                <a href="<?= app_url('/events') ?>" class="btn btn-ghost-light btn-lg">See Our Events</a>
            </div>
        </div>
    </section>

</div>
