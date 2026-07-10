<?php // app/View/pages/public/shop.php

// Simulate filter state from query params
$activeCategory  = htmlspecialchars($_GET['category']  ?? '');
$activeBrand     = htmlspecialchars($_GET['brand']     ?? '');
$activeSort      = htmlspecialchars($_GET['sort']      ?? 'newest');
$priceMin        = (int)($_GET['price_min'] ?? 0);
$priceMax        = (int)($_GET['price_max'] ?? 200000);
$showNew         = !empty($_GET['new']);
$showBestSeller  = !empty($_GET['bestseller']);

// Demo product data (will come from DB later)
$products = [
    ['id'=>1,  'slug'=>'executive-leather-notebook',     'name'=>'Executive Leather Notebook',     'price'=>24500, 'sale_price'=>null,  'badge'=>'New',     'category'=>'Stationery',    'brand'=>'Moleskine',  'stock'=>true,  'img1'=>'https://picsum.photos/seed/notebook1/600/600',  'img2'=>'https://picsum.photos/seed/notebook2/600/600'],
    ['id'=>2,  'slug'=>'branded-vacuum-flask-1l',         'name'=>'Branded Vacuum Flask 1L',         'price'=>18000, 'sale_price'=>14500, 'badge'=>'Sale',    'category'=>'Drinkware',     'brand'=>'Thermos',    'stock'=>true,  'img1'=>'https://picsum.photos/seed/flask1/600/600',     'img2'=>'https://picsum.photos/seed/flask2/600/600'],
    ['id'=>3,  'slug'=>'slim-metal-pen-set',               'name'=>'Slim Metal Pen Set (3pcs)',        'price'=>12000, 'sale_price'=>null,  'badge'=>'',        'category'=>'Stationery',    'brand'=>'Cross',      'stock'=>true,  'img1'=>'https://picsum.photos/seed/pens1/600/600',      'img2'=>'https://picsum.photos/seed/pens2/600/600'],
    ['id'=>4,  'slug'=>'usb-c-hub-organiser',             'name'=>'USB-C Hub & Organiser',           'price'=>35000, 'sale_price'=>null,  'badge'=>'New',     'category'=>'Tech',          'brand'=>'Anker',      'stock'=>true,  'img1'=>'https://picsum.photos/seed/usbc1/600/600',      'img2'=>'https://picsum.photos/seed/usbc2/600/600'],
    ['id'=>5,  'slug'=>'premium-cotton-polo-shirt',        'name'=>'Premium Cotton Polo Shirt',        'price'=>9500,  'sale_price'=>null,  'badge'=>'',        'category'=>'Apparel',       'brand'=>'Custom',     'stock'=>true,  'img1'=>'https://picsum.photos/seed/polo1/600/600',      'img2'=>'https://picsum.photos/seed/polo2/600/600'],
    ['id'=>6,  'slug'=>'wireless-charging-pad',            'name'=>'Wireless Charging Pad',            'price'=>28000, 'sale_price'=>22000, 'badge'=>'Sale',    'category'=>'Tech',          'brand'=>'Anker',      'stock'=>false, 'img1'=>'https://picsum.photos/seed/charge1/600/600',    'img2'=>'https://picsum.photos/seed/charge2/600/600'],
    ['id'=>7,  'slug'=>'branded-canvas-tote-bag',          'name'=>'Branded Canvas Tote Bag',          'price'=>7500,  'sale_price'=>null,  'badge'=>'',        'category'=>'Bags',          'brand'=>'Custom',     'stock'=>true,  'img1'=>'https://picsum.photos/seed/tote1/600/600',      'img2'=>'https://picsum.photos/seed/tote2/600/600'],
    ['id'=>8,  'slug'=>'executive-laptop-backpack',        'name'=>'Executive Laptop Backpack',        'price'=>55000, 'sale_price'=>null,  'badge'=>'Popular', 'category'=>'Bags',          'brand'=>'Targus',     'stock'=>true,  'img1'=>'https://picsum.photos/seed/laptop1/600/600',    'img2'=>'https://picsum.photos/seed/laptop2/600/600'],
    ['id'=>9,  'slug'=>'leatherette-card-holder',          'name'=>'Leatherette Card Holder',          'price'=>6500,  'sale_price'=>null,  'badge'=>'New',     'category'=>'Accessories',   'brand'=>'Custom',     'stock'=>true,  'img1'=>'https://picsum.photos/seed/cardholder1/600/600','img2'=>'https://picsum.photos/seed/cardholder2/600/600'],
    ['id'=>10, 'slug'=>'bamboo-desk-organiser',            'name'=>'Bamboo Desk Organiser',            'price'=>15000, 'sale_price'=>null,  'badge'=>'',        'category'=>'Stationery',    'brand'=>'Custom',     'stock'=>true,  'img1'=>'https://picsum.photos/seed/bamboo1/600/600',    'img2'=>'https://picsum.photos/seed/bamboo2/600/600'],
    ['id'=>11, 'slug'=>'smart-power-bank-20000mah',        'name'=>'Smart Power Bank 20000mAh',        'price'=>42000, 'sale_price'=>36000, 'badge'=>'Sale',    'category'=>'Tech',          'brand'=>'Anker',      'stock'=>true,  'img1'=>'https://picsum.photos/seed/power1/600/600',     'img2'=>'https://picsum.photos/seed/power2/600/600'],
    ['id'=>12, 'slug'=>'branded-cap-collection',           'name'=>'Branded Cap Collection',           'price'=>5500,  'sale_price'=>null,  'badge'=>'',        'category'=>'Apparel',       'brand'=>'Custom',     'stock'=>true,  'img1'=>'https://picsum.photos/seed/cap1/600/600',       'img2'=>'https://picsum.photos/seed/cap2/600/600'],
];

$categories = ['Drinkware', 'Technology & Accessories', 'Bags & Travel', 'Apparels', 'Corporate Gifts', 'Souvenirs', 'Seasonal Gifts'];

$badgeColors = [
    'New'     => 'bg-[var(--gold)] text-black',
    'Sale'    => 'bg-red-600 text-white',
    'Popular' => 'bg-blue-600 text-white',
];

$total   = count($products);
$perPage = 12;
$page    = max(1, (int)($_GET['page'] ?? 1));
$shown   = min($total, $perPage);
?>

<!-- Page Hero -->
<div class="bg-[var(--surface)] border-b border-[var(--border)] pb-10 px-4 sm:px-8" style="padding-top: 120px;">
    <div class="container mx-auto max-w-[1440px]">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm text-[var(--text-muted)] mb-4">
            <a href="/" class="hover:text-[var(--gold)] transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <?php if ($activeCategory): ?>
                <a href="/shop" class="hover:text-[var(--gold)] transition-colors">Shop</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-white"><?= $activeCategory ?></span>
            <?php else: ?>
                <span class="text-white">Shop</span>
            <?php endif; ?>
        </nav>
        <h1 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold">
            <?= $activeCategory ?: 'All Products' ?>
        </h1>
        <p class="text-[var(--text-muted)] mt-2 mb-6">Premium branded merchandise for corporate Nigeria.</p>

        <!-- Category quick-links -->
        <div class="flex flex-wrap gap-2">
            <a href="/shop"
               class="px-4 py-2 rounded-full border text-sm font-semibold transition-colors
                      <?= !$activeCategory ? 'bg-[var(--gold)] text-black border-[var(--gold)]' : 'border-[var(--border)] text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-white' ?>">
                All
            </a>
            <?php foreach ($categories as $cat): ?>
            <a href="/shop?category=<?= urlencode($cat) ?>"
               class="px-4 py-2 rounded-full border text-sm font-semibold transition-colors
                      <?= $activeCategory === $cat ? 'bg-[var(--gold)] text-black border-[var(--gold)]' : 'border-[var(--border)] text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-white' ?>">
                <?= $cat ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Main shop layout -->
<div x-data="shopPage()" class="container mx-auto px-4 sm:px-8 max-w-[1440px] py-10">

    <!-- Active Filter Chips -->
    <div class="flex flex-wrap items-center gap-2 mb-6" x-show="activeFilters.length > 0">
        <span class="text-[var(--text-muted)] text-sm">Active filters:</span>
        <template x-for="f in activeFilters" :key="f.key">
            <button @click="removeFilter(f.key)"
                    class="flex items-center gap-1.5 bg-[var(--gold)]/10 border border-[var(--gold)]/30 text-[var(--gold)] text-xs font-semibold px-3 py-1.5 rounded-full hover:bg-[var(--gold)]/20 transition-colors">
                <span x-text="f.label"></span>
                <i data-lucide="x" class="w-3 h-3"></i>
            </button>
        </template>
        <button @click="clearAllFilters()" class="text-[var(--text-muted)] hover:text-white text-xs underline ml-1">Clear all</button>
    </div>

    <div class="flex gap-8">

        <!-- ========================================================
             SIDEBAR FILTERS — Desktop
             ======================================================== -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="sticky top-28 space-y-6">

                <!-- Category -->
                <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-5">
                    <h3 class="font-['Manrope'] font-bold mb-4 flex items-center gap-2">
                        <i data-lucide="tag" class="w-4 h-4 text-[var(--gold)]"></i> Category
                    </h3>
                    <ul class="space-y-2">
                        <li>
                            <button @click="toggleCategory('')"
                                    :class="selectedCategory === '' ? 'text-[var(--gold)] font-semibold' : 'text-[var(--text-secondary)] hover:text-white'"
                                    class="text-sm transition-colors w-full text-left flex justify-between">
                                All Categories <span class="text-[var(--text-muted)] text-xs"><?= $total ?></span>
                            </button>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                        <li>
                            <button @click="toggleCategory('<?= $cat ?>')"
                                    :class="selectedCategory === '<?= $cat ?>' ? 'text-[var(--gold)] font-semibold' : 'text-[var(--text-secondary)] hover:text-white'"
                                    class="text-sm transition-colors w-full text-left flex justify-between">
                                <?= $cat ?> <span class="text-[var(--text-muted)] text-xs"><?= rand(4,18) ?></span>
                            </button>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>


                <!-- Availability -->
                <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-5">
                    <h3 class="font-['Manrope'] font-bold mb-4 flex items-center gap-2">
                        <i data-lucide="package" class="w-4 h-4 text-[var(--gold)]"></i> Availability
                    </h3>
                    <ul class="space-y-2">
                        <?php
                        $avail = ['New Arrivals', 'Best Sellers', 'In Stock', 'Pre-order', 'Quote Only'];
                        foreach ($avail as $a): ?>
                        <li>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" class="w-4 h-4 rounded border-[var(--border)] bg-[var(--surface)] accent-[var(--gold)]">
                                <span class="text-sm text-[var(--text-secondary)] group-hover:text-white transition-colors"><?= $a ?></span>
                            </label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
        </aside>

        <!-- ========================================================
             PRODUCT GRID AREA
             ======================================================== -->
        <div class="flex-1 min-w-0">

            <!-- Toolbar: count + sort + mobile filter btn -->
            <div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
                <p class="text-[var(--text-muted)] text-sm">
                    Showing <span class="text-white font-semibold"><?= $shown ?></span> of <span class="text-white font-semibold"><?= $total ?></span> products
                </p>

                <div class="flex items-center gap-3">
                    <!-- Mobile filter button -->
                    <button @click="filterDrawerOpen = true"
                            class="lg:hidden flex items-center gap-2 border border-[var(--border)] rounded-xl px-4 py-2.5 text-sm hover:border-[var(--gold)] hover:text-[var(--gold)] transition-colors">
                        <i data-lucide="sliders-horizontal" class="w-4 h-4"></i> Filters
                        <span x-show="activeFilters.length > 0" class="bg-[var(--gold)] text-black text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center" x-text="activeFilters.length"></span>
                    </button>

                    <!-- Sort Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                                class="flex items-center gap-2 border border-[var(--border)] rounded-xl px-4 py-2.5 text-sm hover:border-[var(--gold)] transition-colors">
                            <i data-lucide="arrow-up-down" class="w-4 h-4"></i>
                            <span x-text="sortLabel"></span>
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 top-full mt-2 w-48 bg-[var(--card)] border border-[var(--border)] rounded-xl shadow-2xl overflow-hidden z-20">
                            <?php
                            $sorts = ['newest'=>'Newest First','price_asc'=>'Price: Low to High','price_desc'=>'Price: High to Low','popularity'=>'Most Popular','alpha'=>'Alphabetical'];
                            foreach ($sorts as $val => $label): ?>
                            <button @click="setSort('<?= $val ?>', '<?= $label ?>'); open = false"
                                    class="block w-full text-left px-4 py-3 text-sm hover:bg-[var(--surface)] hover:text-[var(--gold)] transition-colors"
                                    :class="sort === '<?= $val ?>' ? 'text-[var(--gold)] font-semibold' : 'text-[var(--text-secondary)]'">
                                <?= $label ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                <?php foreach ($products as $p): ?>
                <div class="group relative bg-[var(--card)] rounded-2xl overflow-hidden border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-300 hover:-translate-y-1"
                     x-data="{ wishlisted: false }"
                     data-preview-img="<?= htmlspecialchars($p['img1']) ?>"
                     data-preview-img2="<?= htmlspecialchars($p['img2']) ?>"
                     data-preview-name="<?= htmlspecialchars($p['name']) ?>">
                    <!-- Image container -->
                    <div class="relative aspect-square overflow-hidden bg-[var(--surface)]">
                        <a href="/product/<?= $p['slug'] ?>" class="block absolute inset-0" tabindex="-1" aria-hidden="true">
                        <img src="<?= $p['img1'] ?>" alt="<?= htmlspecialchars($p['name']) ?>"
                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0" loading="lazy"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x600/191919/444444?text=No+Image'">
                        <img src="<?= $p['img2'] ?>" alt="<?= htmlspecialchars($p['name']) ?> alt view"
                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 opacity-0 group-hover:opacity-100" loading="lazy"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x600/191919/444444?text=No+Image'">
                        </a>

                        <!-- Badge -->
                        <?php if ($p['badge']): ?>
                        <span class="absolute top-3 left-3 text-[10px] font-bold px-2 py-1 rounded-full <?= $badgeColors[$p['badge']] ?? 'bg-gray-600 text-white' ?>">
                            <?= $p['badge'] ?>
                        </span>
                        <?php endif; ?>

                        <!-- Out of stock overlay -->
                        <?php if (!$p['stock']): ?>
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                            <span class="bg-[var(--surface)] text-[var(--text-muted)] text-xs font-bold px-3 py-1.5 rounded-full border border-[var(--border)]">Out of Stock</span>
                        </div>
                        <?php endif; ?>

                        <!-- Wishlist btn -->
                        <button @click.prevent="wishlisted = !wishlisted"
                                class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 backdrop-blur-sm flex items-center justify-center text-white transition-all opacity-0 group-hover:opacity-100 hover:bg-[var(--gold)] hover:text-black">
                            <i data-lucide="heart" class="w-4 h-4" :class="wishlisted ? 'fill-current text-red-400' : ''"></i>
                        </button>

                        <!-- Quick actions (desktop hover) -->
                        <?php if ($p['stock']): ?>
                        <div class="absolute bottom-0 left-0 right-0 p-3 flex gap-2 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <button class="flex-1 bg-[var(--gold)] text-black text-xs font-bold py-2.5 rounded-xl hover:bg-[#D4AF37] transition-colors">
                                Add to Cart
                            </button>
                            <a href="/quote-request?product=<?= $p['slug'] ?>" class="px-3 bg-black/70 backdrop-blur-sm border border-[var(--border)] text-white text-xs font-bold rounded-xl hover:bg-[var(--gold)] hover:text-black hover:border-[var(--gold)] transition-colors flex items-center">
                                Quote
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Product info -->
                    <div class="p-4">
                        <p class="text-[var(--text-muted)] text-[10px] uppercase tracking-wide mb-1"><?= htmlspecialchars($p['category']) ?></p>
                        <a href="/product/<?= $p['slug'] ?>" class="block mb-2">
                            <h3 class="text-sm font-semibold leading-snug line-clamp-2 hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($p['name']) ?></h3>
                        </a>
                        <div class="flex items-center gap-2">
                            <span class="font-['Manrope'] font-bold text-[var(--gold)]">
                                ₦<?= number_format($p['sale_price'] ?? $p['price']) ?>
                            </span>
                            <?php if ($p['sale_price']): ?>
                            <span class="text-[var(--text-muted)] text-xs line-through">₦<?= number_format($p['price']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Empty State (hidden by default, shown via JS when no results) -->
            <div id="empty-state" class="hidden text-center py-24">
                <div class="w-20 h-20 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="search-x" class="w-8 h-8 text-[var(--text-muted)]"></i>
                </div>
                <h3 class="font-['Manrope'] text-2xl font-bold mb-3">No products found</h3>
                <p class="text-[var(--text-muted)] mb-8">Try adjusting your filters or search for something else.</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="/shop" class="inline-flex items-center gap-2 border border-[var(--gold)] text-[var(--gold)] font-bold px-6 py-3 rounded-xl hover:bg-[var(--gold)] hover:text-black transition-colors text-sm">
                        Clear All Filters
                    </a>
                    <a href="/" class="inline-flex items-center gap-2 border border-[var(--border)] text-white font-bold px-6 py-3 rounded-xl hover:border-[var(--gold)] transition-colors text-sm">
                        Back to Home
                    </a>
                </div>
            </div>

            <!-- Pagination -->
            <?php
            $totalPages = max(1, (int)ceil($total / $perPage));
            ?>
            <div class="mt-12 flex items-center justify-center gap-2">
                <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="w-10 h-10 rounded-xl border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-[var(--gold)] transition-colors">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </a>
                <?php else: ?>
                <button disabled class="w-10 h-10 rounded-xl border border-[var(--border)] flex items-center justify-center text-[var(--border)] cursor-not-allowed">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?><?= $activeCategory ? '&category=' . urlencode($activeCategory) : '' ?>"
                   class="w-10 h-10 rounded-xl border flex items-center justify-center text-sm font-semibold transition-colors
                          <?= $i === $page ? 'border-[var(--gold)] bg-[var(--gold)] text-black' : 'border-[var(--border)] text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-[var(--gold)]' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="w-10 h-10 rounded-xl border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-[var(--gold)] transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
                <?php else: ?>
                <button disabled class="w-10 h-10 rounded-xl border border-[var(--border)] flex items-center justify-center text-[var(--border)] cursor-not-allowed">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
                <?php endif; ?>
            </div>

        </div><!-- end grid area -->
    </div><!-- end flex -->

    <!-- ============================================================
         MOBILE FILTER DRAWER (slides up from bottom)
         ============================================================ -->
    <div x-show="filterDrawerOpen" class="lg:hidden fixed inset-0 z-[90]">
        <div x-show="filterDrawerOpen" x-transition.opacity
             class="absolute inset-0 bg-black/80 backdrop-blur-sm"
             @click="filterDrawerOpen = false"></div>

        <div x-show="filterDrawerOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="absolute bottom-0 inset-x-0 bg-[var(--surface)] rounded-t-3xl max-h-[85vh] flex flex-col">

            <!-- Handle bar -->
            <div class="flex justify-center pt-4 pb-2">
                <div class="w-10 h-1 bg-[var(--border)] rounded-full"></div>
            </div>

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-[var(--border)]">
                <h2 class="font-['Manrope'] font-bold text-xl">Filters</h2>
                <button @click="filterDrawerOpen = false" class="text-[var(--text-muted)] hover:text-white transition-colors">
                    <i data-lucide="x"></i>
                </button>
            </div>

            <!-- Scrollable filter content -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">

                <!-- Category -->
                <div>
                    <h3 class="font-['Manrope'] font-bold mb-3 text-sm uppercase tracking-wide text-[var(--gold)]">Category</h3>
                    <div class="flex flex-wrap gap-2">
                        <button @click="toggleCategory('')"
                                :class="selectedCategory === '' ? 'bg-[var(--gold)] text-black border-[var(--gold)]' : 'border-[var(--border)] text-[var(--text-secondary)]'"
                                class="px-4 py-2 rounded-full border text-sm font-semibold transition-colors hover:border-[var(--gold)]">
                            All
                        </button>
                        <?php foreach ($categories as $cat): ?>
                        <button @click="toggleCategory('<?= $cat ?>')"
                                :class="selectedCategory === '<?= $cat ?>' ? 'bg-[var(--gold)] text-black border-[var(--gold)]' : 'border-[var(--border)] text-[var(--text-secondary)]'"
                                class="px-4 py-2 rounded-full border text-sm font-semibold transition-colors hover:border-[var(--gold)]">
                            <?= $cat ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>


                <!-- Availability -->
                <div>
                    <h3 class="font-['Manrope'] font-bold mb-3 text-sm uppercase tracking-wide text-[var(--gold)]">Availability</h3>
                    <div class="space-y-3">
                        <?php foreach (['New Arrivals', 'Best Sellers', 'In Stock', 'Pre-order', 'Quote Only'] as $a): ?>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="w-5 h-5 rounded border-[var(--border)] bg-[var(--surface)] accent-[var(--gold)]">
                            <span class="text-sm text-[var(--text-secondary)]"><?= $a ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Apply button -->
            <div class="p-6 border-t border-[var(--border)] flex gap-3">
                <button @click="clearAllFilters()" class="flex-1 border border-[var(--border)] py-4 rounded-xl font-bold text-sm hover:border-[var(--gold)] hover:text-[var(--gold)] transition-colors">
                    Clear All
                </button>
                <button @click="filterDrawerOpen = false" class="flex-1 bg-[var(--gold)] text-black py-4 rounded-xl font-bold text-sm hover:bg-[#D4AF37] transition-colors">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>

</div><!-- end x-data shopPage -->

<script>
function shopPage() {
    return {
        filterDrawerOpen: false,
        selectedCategory: '<?= $activeCategory ?>',
        selectedBrands: [],
        priceMin: <?= $priceMin ?>,
        priceMax: <?= $priceMax ?>,
        sort: '<?= $activeSort ?>',
        sortLabel: <?= json_encode($sorts[$activeSort] ?? 'Newest First') ?>,

        get activeFilters() {
            const f = [];
            if (this.selectedCategory) f.push({ key: 'category', label: this.selectedCategory });
            this.selectedBrands.forEach(b => f.push({ key: 'brand_' + b, label: b }));
            if (this.priceMin > 0)      f.push({ key: 'price_min', label: '₦' + Number(this.priceMin).toLocaleString() + '+ min' });
            if (this.priceMax < 200000) f.push({ key: 'price_max', label: 'Max ₦' + Number(this.priceMax).toLocaleString() });
            return f;
        },

        toggleCategory(cat) {
            this.selectedCategory = this.selectedCategory === cat ? '' : cat;
        },

        toggleBrand(brand) {
            const idx = this.selectedBrands.indexOf(brand);
            if (idx === -1) this.selectedBrands.push(brand);
            else this.selectedBrands.splice(idx, 1);
        },

        removeFilter(key) {
            if (key === 'category')  { this.selectedCategory = ''; return; }
            if (key === 'price_min') { this.priceMin = 0; return; }
            if (key === 'price_max') { this.priceMax = 200000; return; }
            if (key.startsWith('brand_')) this.toggleBrand(key.replace('brand_', ''));
        },

        clearAllFilters() {
            this.selectedCategory = '';
            this.selectedBrands   = [];
            this.priceMin         = 0;
            this.priceMax         = 200000;
        },

        setSort(val, label) {
            this.sort      = val;
            this.sortLabel = label;
        }
    };
}
</script>

<!-- ===== FLOATING PRODUCT IMAGE PREVIEW ===== -->
<div id="img-preview-bubble"
     style="position:fixed;pointer-events:none;z-index:9999;opacity:0;transform:scale(0.85) translateY(8px);transition:opacity 0.18s ease,transform 0.18s ease;width:300px;">
    <div style="background:#191919;border:1px solid rgba(201,162,39,0.35);border-radius:16px;overflow:hidden;box-shadow:0 24px 60px rgba(0,0,0,0.7)">
        <div style="position:relative;aspect-ratio:1/1;">
            <img id="img-preview-main" src="" alt="" style="width:100%;height:100%;object-fit:cover;display:block;transition:opacity 0.3s ease;"
                 onerror="this.onerror=null;this.src='https://placehold.co/300x300/191919/444444?text=No+Image'">
            <img id="img-preview-alt"  src="" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0;transition:opacity 0.3s ease;"
                 onerror="this.onerror=null;this.src='https://placehold.co/300x300/191919/444444?text=No+Image'">
        </div>
        <div style="padding:12px 14px;border-top:1px solid rgba(201,162,39,0.15);">
            <p id="img-preview-label" style="color:#F8F8F8;font-size:13px;font-weight:600;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></p>
        </div>
    </div>
</div>

<script>
(function () {
    const bubble  = document.getElementById('img-preview-bubble');
    const imgMain = document.getElementById('img-preview-main');
    const imgAlt  = document.getElementById('img-preview-alt');
    const label   = document.getElementById('img-preview-label');

    let current   = null;
    let altTimer  = null;
    let mx = 0, my = 0;
    const PAD = 18;           // gap from cursor
    const W   = 300;          // bubble width
    const H   = 340;          // approx bubble height

    function position() {
        const vw = window.innerWidth;
        const vh = window.innerHeight;
        let x = mx + PAD;
        let y = my - H / 2;

        // flip left if too close to right edge
        if (x + W + PAD > vw) x = mx - W - PAD;

        // clamp vertically
        if (y < PAD) y = PAD;
        if (y + H > vh - PAD) y = vh - H - PAD;

        bubble.style.left = x + 'px';
        bubble.style.top  = y + 'px';
    }

    document.addEventListener('mousemove', function (e) {
        mx = e.clientX;
        my = e.clientY;
        if (current) position();
    });

    // Attach hover listeners to every product card
    function attach() {
        document.querySelectorAll('[data-preview-img]').forEach(card => {
            // Only bind once
            if (card._previewBound) return;
            card._previewBound = true;

            card.addEventListener('mouseenter', function () {
                current = card;
                const src1 = card.dataset.previewImg;
                const src2 = card.dataset.previewImg2;
                const name = card.dataset.previewName || '';

                imgMain.src     = src1;
                imgMain.style.opacity = '1';
                imgAlt.src      = src2 || src1;
                imgAlt.style.opacity  = '0';
                label.textContent = name;

                position();
                bubble.style.opacity   = '1';
                bubble.style.transform = 'scale(1) translateY(0)';

                // Crossfade to alt image after 600 ms
                clearTimeout(altTimer);
                if (src2) {
                    altTimer = setTimeout(() => {
                        imgMain.style.opacity = '0';
                        imgAlt.style.opacity  = '1';
                    }, 600);
                }
            });

            card.addEventListener('mouseleave', function () {
                current = null;
                clearTimeout(altTimer);
                bubble.style.opacity   = '0';
                bubble.style.transform = 'scale(0.85) translateY(8px)';
            });
        });
    }

    // Run now and re-run after Alpine settles (in case of dynamic rendering)
    attach();
    document.addEventListener('DOMContentLoaded', attach);
    setTimeout(attach, 600);
})();
</script>
