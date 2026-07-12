<?php // app/View/pages/public/home.php ?>

<!-- ============================================================
     HERO SECTION — CROSSFADE SLIDER
     ============================================================ -->
<section id="hero" class="relative min-h-screen flex items-center overflow-hidden">

    <!-- ── Slides ─────────────────────────────────────────────── -->
    <!-- Slide 1 — frame-sequence animation -->
    <div class="hero-slide absolute inset-0" data-slide="0" style="isolation:isolate;">
        <div class="absolute inset-0">
            <canvas id="hero-canvas" class="absolute inset-0 w-full h-full" style="display:block; z-index:0;"></canvas>
            <div class="absolute inset-0 bg-gradient-to-r from-[#050505]/90 via-[#050505]/60 to-transparent" style="z-index:1;"></div>
        </div>
    </div>
    <!-- Slide 2 — frame-sequence animation -->
    <div class="hero-slide absolute inset-0 opacity-0" data-slide="1" style="isolation:isolate;">
        <div class="absolute inset-0">
            <canvas id="hero-canvas-2" class="absolute inset-0 w-full h-full" style="display:block; z-index:0;"></canvas>
            <div class="absolute inset-0 bg-gradient-to-r from-[#050505]/90 via-[#050505]/60 to-transparent" style="z-index:1;"></div>
        </div>
    </div>
    <!-- Slide 3 — frame-sequence animation -->
    <div class="hero-slide absolute inset-0 opacity-0" data-slide="2" style="isolation:isolate;">
        <div class="absolute inset-0">
            <canvas id="hero-canvas-3" class="absolute inset-0 w-full h-full" style="display:block; z-index:0;"></canvas>
            <div class="absolute inset-0 bg-gradient-to-r from-[#050505]/90 via-[#050505]/60 to-transparent" style="z-index:1;"></div>
        </div>
    </div>

    <!-- ── Content (cross-fades independently) ───────────────── -->
    <div class="relative z-10 container mx-auto px-4 sm:px-8 max-w-[1440px] pt-32 pb-24">
        <div class="max-w-2xl">

            <!-- Tag line -->
            <div id="hero-tag" class="inline-flex items-center gap-2 bg-[var(--gold)]/10 border border-[var(--gold)]/30 rounded-full px-4 py-2 mb-6">
                <span class="w-2 h-2 rounded-full bg-[var(--gold)]"></span>
                <span id="hero-tag-text" class="text-[var(--gold)] text-sm font-semibold tracking-wide">Premium Corporate Merchandise</span>
            </div>

            <!-- Headline —  JS swaps content between slides -->
            <h1 id="hero-headline" class="font-['Manrope'] text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-[1.05] mb-6">
                <span id="hero-line-1" class="block">Elevate Your</span>
                <span id="hero-line-2" class="block text-[var(--gold)]">Corporate</span>
                <span id="hero-line-3" class="block">Identity</span>
            </h1>

            <!-- Subtitle -->
            <p id="hero-sub" class="text-[var(--text-secondary)] text-lg sm:text-xl leading-relaxed mb-10 max-w-xl">
                Bespoke merchandise, luxury corporate gifts, and unforgettable branding experiences crafted exclusively for forward-thinking organizations.
            </p>

            <!-- CTA Buttons -->
            <div id="hero-ctas" class="flex flex-col sm:flex-row gap-4">
                <a href="/shop" id="cta-shop"
                   class="inline-flex items-center justify-center gap-2 bg-[var(--gold)] text-black font-bold px-8 py-4 rounded-xl text-base hover:bg-[#D4AF37] transition-all duration-300 hover:-translate-y-1">
                    Shop All Products <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="/quote" id="cta-quote"
                   class="inline-flex items-center justify-center gap-2 border border-[var(--border)] text-white font-bold px-8 py-4 rounded-xl text-base hover:border-[var(--gold)] hover:text-[var(--gold)] transition-all duration-300 hover:-translate-y-1">
                    Request a Quote <i data-lucide="file-text" class="w-5 h-5"></i>
                </a>
            </div>

            <!-- Trust signals -->
            <div id="hero-trust" class="mt-12 flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-2 text-sm text-[var(--text-muted)]">
                    <i data-lucide="shield-check" class="w-4 h-4 text-[var(--gold)]"></i> Quality Guaranteed
                </div>
                <div class="flex items-center gap-2 text-sm text-[var(--text-muted)]">
                    <i data-lucide="users" class="w-4 h-4 text-[var(--gold)]"></i> 500+ Corporate Clients
                </div>
                <div class="flex items-center gap-2 text-sm text-[var(--text-muted)]">
                    <i data-lucide="palette" class="w-4 h-4 text-[var(--gold)]"></i> Custom Branding
                </div>
            </div>
        </div>
    </div>

    <!-- ── Dot indicators ─────────────────────────────────────── -->
    <div class="absolute bottom-20 left-1/2 -translate-x-1/2 flex gap-2 z-20">
        <button class="hero-dot w-8 h-1 rounded-full bg-[var(--gold)] transition-all duration-500" data-dot="0"></button>
        <button class="hero-dot w-3 h-1 rounded-full bg-white/40 transition-all duration-500" data-dot="1"></button>
        <button class="hero-dot w-3 h-1 rounded-full bg-white/40 transition-all duration-500" data-dot="2"></button>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-[var(--text-muted)] text-xs animate-bounce">
        <span class="tracking-widest">SCROLL</span>
        <i data-lucide="chevrons-down" class="w-4 h-4"></i>
    </div>
</section>

<!-- ============================================================
     ICONIC PRODUCTS
     ============================================================ -->
<section class="pt-24 pb-12 overflow-hidden relative" style="background-color: var(--bg-primary);">
    <!-- Title -->
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px] mb-8">
        <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold">Iconic Products</h2>
    </div>

    <!-- Carousel Container -->
    <div class="relative w-full">
        <!-- The background band -->
        <div class="absolute top-0 left-0 right-0 h-[320px] sm:h-[400px] bg-[var(--surface)] z-0"></div>

        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px] relative z-10">
            
            <!-- Navigation Buttons perfectly centered to the image band height -->
            <div class="absolute top-0 left-0 right-0 h-[320px] sm:h-[400px] flex items-center justify-between pointer-events-none z-[100] px-2 sm:px-4">
                <button class="swiper-prev-iconic pointer-events-auto w-12 h-12 sm:w-14 sm:h-14 bg-[var(--card)] border border-[var(--border)] rounded-full shadow-2xl flex items-center justify-center text-white hover:text-[var(--gold)] hover:border-[var(--gold)] hover:scale-110 transition-all cursor-pointer">
                    <i data-lucide="arrow-left" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </button>
                <button class="swiper-next-iconic pointer-events-auto w-12 h-12 sm:w-14 sm:h-14 bg-[var(--card)] border border-[var(--border)] rounded-full shadow-2xl flex items-center justify-center text-white hover:text-[var(--gold)] hover:border-[var(--gold)] hover:scale-110 transition-all cursor-pointer">
                    <i data-lucide="arrow-right" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </button>
            </div>

            <div class="swiper swiper-iconic overflow-visible">
                <div class="swiper-wrapper">
                    <?php
                    $iconic_products = [
                        ['name' => 'Original Cabin', 'colors' => ['#e2e2e2', '#1a1a1a', '#b5a68c'], 'img' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=400&auto=format&fit=crop'],
                        ['name' => 'Classic Check-In L', 'colors' => ['#1a1a1a', '#e2e2e2'], 'img' => 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?q=80&w=400&auto=format&fit=crop'],
                        ['name' => 'Essential Cabin', 'colors' => ['#1f4031', '#404040', '#d94c25'], 'img' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?q=80&w=400&auto=format&fit=crop'],
                        ['name' => 'Original Trunk Plus', 'colors' => ['#e2e2e2', '#d94c25'], 'img' => 'https://images.unsplash.com/photo-1512428813834-c702c7702b78?q=80&w=400&auto=format&fit=crop'],
                        ['name' => 'Corporate Briefcase', 'colors' => ['#1a1a1a'], 'img' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=400&auto=format&fit=crop'],
                    ];
                    foreach ($iconic_products as $prod): ?>
                        <div class="swiper-slide cursor-pointer group">
                            <!-- Image container (fits inside the band) -->
                            <div class="h-[320px] sm:h-[400px] w-full flex items-center justify-center p-8">
                                <!-- Using object-cover rounded-xl to handle arbitrary unsplash images nicely on dark mode without ugly white squares -->
                                <img src="<?= $prod['img'] ?>" alt="<?= htmlspecialchars($prod['name']) ?>"
                                     class="h-full w-auto max-h-[85%] object-cover rounded-2xl transition-transform duration-700 group-hover:scale-105" style="filter: drop-shadow(0 20px 15px rgba(0,0,0,0.4));">
                            </div>
                            <!-- Text container -->
                            <div class="pt-6 pb-2 px-2">
                                <h3 class="text-[14px] text-white mb-2 font-medium"><?= htmlspecialchars($prod['name']) ?></h3>
                                <?php if (!empty($prod['colors'])): ?>
                                    <div class="flex items-center gap-1.5">
                                        <?php foreach (array_slice($prod['colors'], 0, 3) as $color): ?>
                                            <div class="w-3.5 h-3.5 rounded-full border border-[var(--border)] shadow-sm" style="background-color: <?= $color ?>;"></div>
                                        <?php endforeach; ?>
                                        <span class="text-[10px] text-[var(--text-muted)] font-semibold ml-1">+<?= count($prod['colors']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     TRUSTED BRANDS — Marquee
     ============================================================ -->
<section class="py-16 border-y border-[var(--border)] bg-[var(--surface)] overflow-hidden">
    <div class="text-center mb-10 text-[var(--text-muted)] text-xs font-semibold tracking-widest uppercase">Trusted by leading organisations</div>

    <div class="relative"
         x-data="{ paused: false }"
         @mouseenter="paused = true"
         @mouseleave="paused = false">

        <!-- Left fade -->
        <div class="absolute left-0 top-0 h-full w-28 z-10 pointer-events-none" style="background: linear-gradient(to right, var(--surface), transparent);"></div>
        <!-- Right fade -->
        <div class="absolute right-0 top-0 h-full w-28 z-10 pointer-events-none" style="background: linear-gradient(to left, var(--surface), transparent);"></div>

        <div class="clients-track flex items-center gap-16"
             :class="paused ? 'clients-paused' : ''">
            <?php
            $clients = [
                ['file' => 'Afren-Nigeria.png',          'name' => 'Afren Nigeria'],
                ['file' => 'Airtel.jpg',                 'name' => 'Airtel'],
                ['file' => 'FM-97.1.png',                'name' => 'FM 97.1'],
                ['file' => 'Napims.png',                 'name' => 'Napims'],
                ['file' => 'nigerian-stock-exchange.jpg', 'name' => 'Nigerian Stock Exchange'],
                ['file' => 'Sahcol.png',                 'name' => 'Sahcol'],
                ['file' => 'SAP_logo.png',               'name' => 'SAP'],
                ['file' => 'sprite.png',                 'name' => 'Sprite'],
                ['file' => 'virgin-atlantic.jpeg',       'name' => 'Virgin Atlantic'],
                // Duplicate for seamless infinite loop
                ['file' => 'Afren-Nigeria.png',          'name' => 'Afren Nigeria'],
                ['file' => 'Airtel.jpg',                 'name' => 'Airtel'],
                ['file' => 'FM-97.1.png',                'name' => 'FM 97.1'],
                ['file' => 'Napims.png',                 'name' => 'Napims'],
                ['file' => 'nigerian-stock-exchange.jpg', 'name' => 'Nigerian Stock Exchange'],
                ['file' => 'Sahcol.png',                 'name' => 'Sahcol'],
                ['file' => 'SAP_logo.png',               'name' => 'SAP'],
                ['file' => 'sprite.png',                 'name' => 'Sprite'],
                ['file' => 'virgin-atlantic.jpeg',       'name' => 'Virgin Atlantic'],
            ];
            foreach ($clients as $c): ?>
                <div class="client-logo flex-shrink-0 flex items-center justify-center">
                    <img src="/clients/<?= $c['file'] ?>"
                         alt="<?= htmlspecialchars($c['name']) ?>"
                         loading="lazy">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     FEATURED CATEGORIES
     ============================================================ -->
<section class="py-20 sm:py-28">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <div class="flex items-end justify-between mb-12">
            <div>
                <p class="text-[var(--gold)] text-sm font-semibold tracking-widest uppercase mb-2">Browse</p>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold">Featured Categories</h2>
            </div>
            <a href="/shop" class="hidden sm:flex items-center gap-2 text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors text-sm font-semibold">
                View All <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <!-- Mobile: horizontal scroll, Desktop: grid -->
        <div class="flex sm:grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 overflow-x-auto pb-4 sm:pb-0 -mx-4 sm:mx-0 px-4 sm:px-0 scroll-smooth snap-x snap-mandatory no-scrollbar">
            <?php
            $categories = [
                ['name' => 'Executive Gifts',       'count' => 48, 'img' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?q=80&w=600&auto=format&fit=crop'],
                ['name' => 'Branded Apparel',        'count' => 32, 'img' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?q=80&w=600&auto=format&fit=crop'],
                ['name' => 'Tech Accessories',       'count' => 55, 'img' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=600&auto=format&fit=crop'],
                ['name' => 'Premium Drinkware',      'count' => 27, 'img' => 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?q=80&w=600&auto=format&fit=crop'],
            ];
            foreach ($categories as $cat): ?>
                <a href="/shop?category=<?= urlencode(strtolower($cat['name'])) ?>"
                   class="group flex-shrink-0 w-64 sm:w-auto snap-start relative rounded-2xl overflow-hidden aspect-[3/4] block">
                    <img src="<?= $cat['img'] ?>" alt="<?= htmlspecialchars($cat['name']) ?>"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute inset-0 border-2 border-transparent group-hover:border-[var(--gold)] rounded-2xl transition-colors duration-300"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="font-['Manrope'] font-bold text-xl text-white mb-1"><?= htmlspecialchars($cat['name']) ?></h3>
                        <p class="text-[var(--text-muted)] text-sm"><?= $cat['count'] ?> products</p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-8 sm:hidden">
            <a href="/shop" class="text-[var(--gold)] text-sm font-semibold">View All Categories →</a>
        </div>
    </div>
</section>

<!-- ============================================================
     CORPORATE SOLUTIONS
     ============================================================ -->
<section class="py-20 sm:py-28 bg-[var(--surface)]">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <p class="text-[var(--gold)] text-sm font-semibold tracking-widest uppercase mb-3">For Business</p>
            <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-4">Corporate Solutions</h2>
            <p class="text-[var(--text-secondary)]">End-to-end merchandise and gifting programmes built for forward-thinking organisations.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 stagger-cards">
            <?php
            $solutions = [
                ['icon' => 'gift',         'title' => 'Employee Welcome Kits',        'desc' => 'Make every new hire feel valued from day one with curated, branded onboarding packs.'],
                ['icon' => 'crown',        'title' => 'Executive Gifts',               'desc' => 'Impress C-suite clients and partners with luxury items that reflect your brand premium.'],
                ['icon' => 'presentation', 'title' => 'Conference Merchandise',        'desc' => 'Stand out at events with high-quality branded items delegates will actually keep.'],
                ['icon' => 'megaphone',    'title' => 'Promotional Campaigns',         'desc' => 'Drive awareness and recall with creative, on-brand promotional merchandise.'],
                ['icon' => 'heart-handshake','title'=> 'Client Appreciation',          'desc' => 'Strengthen relationships with thoughtful, premium gifts for your most valued clients.'],
                ['icon' => 'trophy',       'title' => 'Awards & Recognition',          'desc' => 'Celebrate outstanding performance with bespoke trophies, plaques, and gift sets.'],
            ];
            foreach ($solutions as $sol): ?>
                <div class="group bg-[var(--card)] border border-[var(--border)] rounded-2xl p-8 hover:border-[var(--gold)] transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-[var(--gold)]/10 border border-[var(--gold)]/20 flex items-center justify-center mb-6 group-hover:bg-[var(--gold)]/20 transition-colors">
                        <i data-lucide="<?= $sol['icon'] ?>" class="w-6 h-6 text-[var(--gold)]"></i>
                    </div>
                    <h3 class="font-['Manrope'] font-bold text-xl mb-3"><?= htmlspecialchars($sol['title']) ?></h3>
                    <p class="text-[var(--text-secondary)] text-sm leading-relaxed mb-6"><?= htmlspecialchars($sol['desc']) ?></p>
                    <a href="/solutions" class="text-[var(--gold)] text-sm font-semibold flex items-center gap-1 group-hover:gap-2 transition-all">
                        Learn More <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-12">
            <a href="/quote" class="inline-flex items-center gap-2 border border-[var(--gold)] text-[var(--gold)] font-bold px-8 py-4 rounded-xl hover:bg-[var(--gold)] hover:text-black transition-all duration-300">
                Request a Corporate Quote <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     FEATURED PRODUCTS
     ============================================================ -->
<section class="py-20 sm:py-28">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <div class="flex items-end justify-between mb-12">
            <div>
                <p class="text-[var(--gold)] text-sm font-semibold tracking-widest uppercase mb-2">Handpicked</p>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold">Featured Products</h2>
            </div>
            <a href="/shop?featured=1" class="hidden sm:flex items-center gap-2 text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors text-sm font-semibold">
                View All <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 stagger-cards" x-data>
            <?php
            $products = [
                ['slug' => 'executive-leather-notebook',  'name' => 'Executive Leather Notebook',  'price' => 24500, 'badge' => 'New',     'img1' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1531346680769-a1d79b57de5c?q=80&w=600&auto=format&fit=crop'],
                ['slug' => 'branded-vacuum-flask-1l',     'name' => 'Branded Vacuum Flask 1L',     'price' => 18000, 'badge' => 'Popular', 'img1' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?q=80&w=600&auto=format&fit=crop'],
                ['slug' => 'slim-metal-pen-set',          'name' => 'Slim Metal Pen Set (3pcs)',   'price' => 12000, 'badge' => '',         'img1' => 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1583147247680-2f9c3c41d491?q=80&w=600&auto=format&fit=crop'],
                ['slug' => 'usb-c-hub-organiser',         'name' => 'USB-C Hub & Organiser',       'price' => 35000, 'badge' => 'New',     'img1' => 'https://images.unsplash.com/photo-1612815292673-ab2ad8a5a95b?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1593640408182-31c228f6e2e2?q=80&w=600&auto=format&fit=crop'],
                ['slug' => 'premium-cotton-polo-shirt',   'name' => 'Premium Cotton Polo Shirt',   'price' => 9500,  'badge' => '',         'img1' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=600&auto=format&fit=crop'],
                ['slug' => 'wireless-charging-pad',       'name' => 'Wireless Charging Pad',       'price' => 28000, 'badge' => 'Sale',    'img1' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?q=80&w=600&auto=format&fit=crop'],
                ['slug' => 'branded-canvas-tote-bag',     'name' => 'Branded Canvas Tote Bag',     'price' => 7500,  'badge' => '',         'img1' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?q=80&w=600&auto=format&fit=crop'],
                ['slug' => 'executive-laptop-backpack',   'name' => 'Executive Laptop Backpack',   'price' => 55000, 'badge' => 'Popular', 'img1' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?q=80&w=600&auto=format&fit=crop'],
            ];

            $badgeColors = ['New' => 'bg-[var(--gold)] text-black', 'Popular' => 'bg-blue-600 text-white', 'Sale' => 'bg-red-600 text-white'];

            foreach ($products as $p): ?>
                <div class="group relative bg-[var(--card)] rounded-2xl border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-300 hover:-translate-y-1"
                     x-data="{ wishlisted: false }">
                    <!-- Image container — overflow hidden ONLY here -->
                    <div class="relative aspect-square overflow-hidden rounded-t-2xl bg-[var(--surface)]">
                        <a href="/product/<?= $p['slug'] ?>" class="block absolute inset-0">
                        <img src="<?= $p['img1'] ?>" alt="<?= htmlspecialchars($p['name']) ?>"
                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0" loading="lazy">
                        <img src="<?= $p['img2'] ?>" alt="<?= htmlspecialchars($p['name']) ?> alt view"
                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 opacity-0 group-hover:opacity-100" loading="lazy">
                        </a>

                        <!-- Badge -->
                        <?php if ($p['badge']): ?>
                            <span class="absolute top-3 left-3 text-[10px] font-bold px-2 py-1 rounded-full <?= $badgeColors[$p['badge']] ?? 'bg-gray-600 text-white' ?>">
                                <?= $p['badge'] ?>
                            </span>
                        <?php endif; ?>

                        <!-- Wishlist -->
                        <button @click.prevent="wishlisted = !wishlisted"
                                class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center text-white hover:bg-[var(--gold)] hover:text-black transition-all opacity-0 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :style="wishlisted ? 'fill:currentColor;color:#ef4444;stroke:#ef4444' : ''"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                    </div>

                    <!-- Quick action bar — sits OUTSIDE the overflow-hidden image div -->
                    <div class="product-action-bar-wrap">
                        <div class="p-3 flex gap-2 bg-[var(--card)]">
                            <button class="flex-1 bg-[var(--gold)] text-black text-xs font-bold py-2.5 rounded-xl hover:bg-[#D4AF37] transition-colors">
                                Add to Cart
                            </button>
                            <a href="/quote-request?product=<?= $p['slug'] ?>" class="px-3 bg-[var(--surface)] border border-[var(--border)] text-white text-xs font-bold rounded-xl hover:bg-[var(--gold)] hover:text-black hover:border-[var(--gold)] transition-colors flex items-center">
                                Quote
                            </a>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-4">
                        <a href="/product/<?= $p['slug'] ?>" class="block">
                            <h3 class="text-sm font-semibold leading-snug mb-2 line-clamp-2 hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($p['name']) ?></h3>
                        </a>
                        <p class="font-['Manrope'] font-bold text-[var(--gold)]">₦<?= number_format($p['price']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-10 sm:hidden">
            <a href="/shop" class="text-[var(--gold)] text-sm font-semibold">View All Products →</a>
        </div>
    </div>
</section>

<!-- ============================================================
     NEW ARRIVALS — Swiper Carousel
     ============================================================ -->
<section class="py-20 sm:py-28 bg-[var(--surface)]">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <div class="flex items-end justify-between mb-12">
            <div>
                <p class="text-[var(--gold)] text-sm font-semibold tracking-widest uppercase mb-2">Just In</p>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold">New Arrivals</h2>
            </div>
            <div class="flex gap-3">
                <button class="swiper-prev-arrivals w-10 h-10 rounded-full border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-[var(--gold)] transition-colors">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>
                <button class="swiper-next-arrivals w-10 h-10 rounded-full border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-[var(--gold)] transition-colors">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <div class="swiper swiper-arrivals">
            <div class="swiper-wrapper">
                <?php
                $arrivals = [
                    ['slug' => 'leatherette-card-holder',    'name' => 'Leatherette Card Holder',    'price' => 6500,  'img1' => 'https://images.unsplash.com/photo-1637149937-c2d0bab5d32d?q=80&w=600&auto=format&fit=crop',  'img2' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=600&auto=format&fit=crop'],
                    ['slug' => 'bamboo-desk-organiser',      'name' => 'Bamboo Desk Organiser',      'price' => 15000, 'img1' => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1531346680769-a1d79b57de5c?q=80&w=600&auto=format&fit=crop'],
                    ['slug' => 'smart-power-bank-20000mah',  'name' => 'Smart Power Bank 20000mAh',  'price' => 42000, 'img1' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1612815292673-ab2ad8a5a95b?q=80&w=600&auto=format&fit=crop'],
                    ['slug' => 'branded-cap-collection',      'name' => 'Branded Cap Collection',      'price' => 5500,  'img1' => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=600&auto=format&fit=crop'],
                    ['slug' => 'branded-vacuum-flask-1l',    'name' => 'Branded Vacuum Flask',        'price' => 11000, 'img1' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?q=80&w=600&auto=format&fit=crop'],
                    ['slug' => 'executive-laptop-backpack',  'name' => 'Executive Laptop Backpack',   'price' => 55000, 'img1' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?q=80&w=600&auto=format&fit=crop'],
                ];
                foreach ($arrivals as $a): ?>
                    <div class="swiper-slide">
                        <div class="group relative bg-[var(--card)] border border-[var(--border)] rounded-2xl overflow-hidden hover:border-[var(--gold)]/50 transition-all duration-300 hover:-translate-y-1">
                            <!-- Image with dual swap -->
                            <div class="relative aspect-square overflow-hidden bg-[var(--surface)]">
                                <a href="/product/<?= $a['slug'] ?>" class="block absolute inset-0">
                                <img src="<?= $a['img1'] ?>" alt="<?= htmlspecialchars($a['name']) ?>"
                                     class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0" loading="lazy">
                                <img src="<?= $a['img2'] ?>" alt="<?= htmlspecialchars($a['name']) ?> alt"
                                     class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 opacity-0 group-hover:opacity-100" loading="lazy">
                                </a>

                                <!-- Wishlist -->
                                <button onclick="this.classList.toggle('wishlisted')"
                                        class="arrival-wish absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 backdrop-blur-sm flex items-center justify-center text-white hover:bg-[var(--gold)] hover:text-black transition-all opacity-0 group-hover:opacity-100">
                                    <i data-lucide="heart" class="w-4 h-4"></i>
                                </button>

                                <!-- Slide-up action bar -->
                                <div class="absolute bottom-0 left-0 right-0 p-3 flex gap-2 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                    <button class="flex-1 bg-[var(--gold)] text-black text-xs font-bold py-2.5 rounded-xl hover:bg-[#D4AF37] transition-colors">
                                        Add to Cart
                                    </button>
                                    <a href="/quote-request?product=<?= $a['slug'] ?>" class="px-3 bg-black/70 backdrop-blur-sm border border-[var(--border)] text-white text-xs font-bold rounded-xl hover:bg-[var(--gold)] hover:text-black hover:border-[var(--gold)] transition-colors flex items-center">
                                        Quote
                                    </a>
                                </div>
                            </div>
                            <div class="p-4">
                                <span class="text-[10px] font-bold text-[var(--gold)] bg-[var(--gold)]/10 px-2 py-1 rounded-full">New</span>
                                <a href="/product/<?= $a['slug'] ?>" class="block mt-2 mb-1">
                                    <h3 class="text-sm font-semibold line-clamp-2 hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($a['name']) ?></h3>
                                </a>
                                <p class="font-['Manrope'] font-bold text-[var(--gold)]">₦<?= number_format($a['price']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     WHY CHOOSE MARIGOLD — Animated Counters
     ============================================================ -->
<section class="py-20 sm:py-28">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <div class="text-center max-w-xl mx-auto mb-16">
            <p class="text-[var(--gold)] text-sm font-semibold tracking-widest uppercase mb-3">Our Promise</p>
            <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold">Why Choose Marigold?</h2>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 stagger-cards">
            <?php
            $stats = [
                ['icon' => 'gem',          'counter' => 500,  'suffix' => '+',  'label' => 'Premium Products',       'desc' => 'Curated catalogue of luxury corporate merchandise'],
                ['icon' => 'truck',        'counter' => 48,   'suffix' => 'hrs','label' => 'Fast Delivery',          'desc' => 'Express nationwide delivery to any address'],
                ['icon' => 'palette',      'counter' => 100,  'suffix' => '%',  'label' => 'Custom Branding',        'desc' => 'Full-colour customisation on every single item'],
                ['icon' => 'headphones',   'counter' => 24,   'suffix' => '/7', 'label' => 'Dedicated Support',      'desc' => 'Our team is always here to help you succeed'],
            ];
            foreach ($stats as $stat): ?>
                <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-8 text-center hover:border-[var(--gold)]/50 transition-colors">
                    <div class="w-14 h-14 rounded-2xl bg-[var(--gold)]/10 border border-[var(--gold)]/20 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="<?= $stat['icon'] ?>" class="w-6 h-6 text-[var(--gold)]"></i>
                    </div>
                    <div class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-2">
                        <span class="counter text-[var(--gold)]" data-target="<?= $stat['counter'] ?>">0</span><span class="text-[var(--gold)]"><?= $stat['suffix'] ?></span>
                    </div>
                    <h3 class="font-['Manrope'] font-bold text-lg mb-2"><?= htmlspecialchars($stat['label']) ?></h3>
                    <p class="text-[var(--text-muted)] text-sm leading-relaxed"><?= htmlspecialchars($stat['desc']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     FEATURED COLLECTION BANNER
     ============================================================ -->
<section class="py-8 sm:py-12 px-4 sm:px-8">
    <div class="container mx-auto max-w-[1440px]">
        <div class="relative rounded-3xl overflow-hidden min-h-[400px] flex items-center">
            <img src="https://images.unsplash.com/photo-1444653389962-8149286c578a?q=80&w=1920&auto=format&fit=crop"
                 class="absolute inset-0 w-full h-full object-cover" alt="Featured Collection" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
            <div class="relative z-10 p-10 sm:p-16 max-w-xl">
                <span class="text-[var(--gold)] text-xs font-bold tracking-widest uppercase mb-4 block">Exclusive Collection</span>
                <h2 class="font-['Manrope'] text-3xl sm:text-5xl font-extrabold leading-tight mb-4">The Executive Essentials Bundle</h2>
                <p class="text-[var(--text-secondary)] mb-8">Everything a top executive needs — curated, branded, and delivered in a premium gift box.</p>
                <a href="/shop" class="inline-flex items-center gap-2 bg-[var(--gold)] text-black font-bold px-8 py-4 rounded-xl hover:bg-[#D4AF37] transition-colors">
                    Shop the Collection <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     TESTIMONIALS — Swiper Slider
     ============================================================ -->
<section class="py-20 sm:py-28 bg-[var(--surface)]">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <div class="text-center mb-16">
            <p class="text-[var(--gold)] text-sm font-semibold tracking-widest uppercase mb-3">Social Proof</p>
            <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold">What Our Clients Say</h2>
        </div>

        <div class="swiper swiper-testimonials">
            <div class="swiper-wrapper pb-12">
                <?php
                $testimonials = [
                    ['name' => 'Adaeze Okonkwo',  'role' => 'HR Director, GTBank',           'review' => 'Marigold delivered our employee welcome kits with exceptional quality and speed. Every new joiner was genuinely impressed. 10/10 service.',               'rating' => 5, 'initials' => 'AO'],
                    ['name' => 'Emeka Nwosu',      'role' => 'CEO, Apex Ventures',            'review' => 'Our executive gifts for partners and clients have never looked better. The leather notebooks and pens were exactly what we envisioned for our brand.',   'rating' => 5, 'initials' => 'EN'],
                    ['name' => 'Fatima Aliyu',     'role' => 'Brand Manager, MTN Nigeria',    'review' => 'From concept to delivery, Marigold\'s team was professional, responsive, and genuinely creative. Our conference merchandise was a massive hit.',         'rating' => 5, 'initials' => 'FA'],
                    ['name' => 'Chukwuemeka Bello','role' => 'Admin Manager, Shell Nigeria',  'review' => 'The quality surpassed our expectations at every level. The branded vacuum flasks are still being used daily across our offices months later.',            'rating' => 5, 'initials' => 'CB'],
                ];
                foreach ($testimonials as $t): ?>
                    <div class="swiper-slide">
                        <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-8">
                            <div class="flex gap-1 mb-6">
                                <?php for ($i = 0; $i < $t['rating']; $i++): ?>
                                    <i data-lucide="star" class="w-5 h-5 text-[var(--gold)] fill-current"></i>
                                <?php endfor; ?>
                            </div>
                            <blockquote class="text-[var(--text-secondary)] leading-relaxed mb-8 text-base">
                                "<?= htmlspecialchars($t['review']) ?>"
                            </blockquote>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-[var(--gold)]/20 border-2 border-[var(--gold)] flex items-center justify-center shrink-0">
                                    <span class="font-['Manrope'] font-bold text-[var(--gold)] text-sm"><?= htmlspecialchars($t['initials']) ?></span>
                                </div>
                                <div>
                                    <p class="font-['Manrope'] font-bold text-white"><?= htmlspecialchars($t['name']) ?></p>
                                    <p class="text-[var(--text-muted)] text-sm"><?= htmlspecialchars($t['role']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination-testimonials flex justify-center"></div>
        </div>
    </div>
</section>

<!-- ============================================================
     INSTAGRAM GALLERY
     ============================================================ -->
<section class="py-20 sm:py-28">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <div class="text-center mb-12">
            <p class="text-[var(--gold)] text-sm font-semibold tracking-widest uppercase mb-3">@marigoldsignature</p>
            <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold">Follow Our Work</h2>
        </div>

        <div class="grid grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-4">
            <?php
            $gallery = [
                'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?q=80&w=400&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1586077220249-a2afebe0a11d?q=80&w=400&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=400&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1612815292673-ab2ad8a5a95b?q=80&w=400&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1553361371-9b22f78e8b1d?q=80&w=400&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=400&auto=format&fit=crop',
            ];
            foreach ($gallery as $img): ?>
                <a href="#" class="group relative aspect-square overflow-hidden rounded-xl sm:rounded-2xl">
                    <img src="<?= $img ?>" alt="Marigold gallery" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-colors duration-300 flex items-center justify-center">
                        <i data-lucide="instagram" class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     LATEST INSIGHTS — Blog Preview
     ============================================================ -->
<section class="py-20 sm:py-28 bg-[var(--surface)]">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <div class="flex items-end justify-between mb-12">
            <div>
                <p class="text-[var(--gold)] text-sm font-semibold tracking-widest uppercase mb-2">Knowledge</p>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold">Latest Insights</h2>
            </div>
            <a href="/blog" class="hidden sm:flex items-center gap-2 text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors text-sm font-semibold">
                View All Articles <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 stagger-cards">
            <?php
            $posts = [
                ['cat' => 'Corporate Gifting', 'read' => '5 min read', 'title' => '7 Corporate Gift Ideas That Actually Impress Executives in 2026',    'img' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?q=80&w=600&auto=format&fit=crop'],
                ['cat' => 'Branding',           'read' => '4 min read', 'title' => 'How Branded Merchandise Boosts Employee Retention and Morale',       'img' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?q=80&w=600&auto=format&fit=crop'],
                ['cat' => 'Trends',             'read' => '6 min read', 'title' => 'The Rise of Sustainable Corporate Merchandise: What Brands Need to Know', 'img' => 'https://images.unsplash.com/photo-1512428813834-c702c7702b78?q=80&w=600&auto=format&fit=crop'],
            ];
            foreach ($posts as $post): ?>
                <a href="/blog" class="group bg-[var(--card)] border border-[var(--border)] rounded-2xl overflow-hidden hover:border-[var(--gold)]/50 transition-all duration-300 hover:-translate-y-1 block">
                    <div class="aspect-[16/10] overflow-hidden">
                        <img src="<?= $post['img'] ?>" alt="<?= htmlspecialchars($post['title']) ?>"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-[var(--gold)] text-xs font-bold uppercase tracking-wide"><?= htmlspecialchars($post['cat']) ?></span>
                            <span class="text-[var(--text-muted)] text-xs">·</span>
                            <span class="text-[var(--text-muted)] text-xs"><?= $post['read'] ?></span>
                        </div>
                        <h3 class="font-['Manrope'] font-bold text-lg leading-snug group-hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($post['title']) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     NEWSLETTER
     ============================================================ -->
<section class="py-20 sm:py-28">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <div class="bg-gradient-to-br from-[var(--surface)] to-[var(--card)] border border-[var(--border)] rounded-3xl p-10 sm:p-16 text-center max-w-3xl mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-[var(--gold)]/10 border border-[var(--gold)]/20 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="mail" class="w-7 h-7 text-[var(--gold)]"></i>
            </div>
            <h2 class="font-['Manrope'] text-3xl sm:text-4xl font-extrabold mb-4">Stay in the Loop</h2>
            <p class="text-[var(--text-secondary)] mb-8 max-w-md mx-auto">Get exclusive access to new arrivals, promotions, and corporate gifting inspiration — straight to your inbox.</p>
            <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto"
                  x-data="{ email: '' }"
                  @submit.prevent="window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'You\'re subscribed! Welcome aboard.', type: 'success' }})); email = ''">
                <input x-model="email" type="email" placeholder="Your email address" required
                       class="flex-1 bg-[var(--bg-primary)] border border-[var(--border)] rounded-xl px-5 py-4 text-sm focus:outline-none focus:border-[var(--gold)] transition-colors">
                <button type="submit"
                        class="bg-[var(--gold)] text-black font-bold px-6 py-4 rounded-xl hover:bg-[#D4AF37] transition-colors whitespace-nowrap">
                    Subscribe Now
                </button>
            </form>
            <p class="text-[var(--text-muted)] text-xs mt-4">No spam. Unsubscribe at any time.</p>
        </div>
    </div>
</section>

<!-- ============================================================
     NEWSLETTER POPUP
     ============================================================ -->
<?php \App\Core\View::render('components/newsletter_popup') ?>

<!-- ============================================================
     HOMEPAGE SCRIPTS
     ============================================================ -->
<style>
    /* ── Client logo marquee ─────────────────────────────────── */
    .clients-track {
        animation: clientsScroll 28s linear infinite;
        width: max-content;
    }
    .clients-paused {
        animation-play-state: paused !important;
    }
    @keyframes clientsScroll {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    /* Each logo: greyscale by default, full colour + lift on hover */
    .client-logo {
        filter: grayscale(100%) opacity(0.5);
        transition: filter 0.35s ease, transform 0.3s ease;
    }
    .client-logo:hover {
        filter: grayscale(0%) opacity(1);
        transform: scale(1.06);
    }
    .client-logo img {
        height: 36px;
        width: auto;
        max-width: 120px;
        object-fit: contain;
        display: block;
    }
    .no-scrollbar { scrollbar-width: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }

    /* ── Product card hover action bar ──────────────────────── */
    .group:hover .product-action-bar {
        display: flex;
    }
    .group .product-action-bar-wrap {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.28s ease;
    }
    .group:hover .product-action-bar-wrap {
        max-height: 60px;
    }
    /* Allow cards to lift vertically without clipping, but clip horizontal bleed */
    .swiper-arrivals {
        overflow: hidden !important;
    }
    .swiper-arrivals .swiper-wrapper,
    .swiper-arrivals .swiper-slide {
        overflow: visible !important;
    }
    /* Keep the product card image area clipped */
    .swiper-arrivals .swiper-slide .aspect-square {
        overflow: hidden;
    }

    /* Wishlist toggled state */
    .arrival-wish.wishlisted {
        background-color: var(--gold);
        color: black;
    }
    .arrival-wish.wishlisted i {
        fill: currentColor;
    }

</style>

<!-- ── Frame-sequence animation engine for Hero Slides 1 & 2 ────────── -->
<script>
(function () {
    const FPS = 24;
    const interval = 1000 / FPS;

    function pad(n, len) { return String(n).padStart(len, '0'); }

    function drawFrame(ctx, canvas, img) {
        const cw = canvas.width, ch = canvas.height;
        const iw = img.naturalWidth, ih = img.naturalHeight;
        const scale = Math.max(cw / iw, ch / ih);
        const dx = (cw - iw * scale) / 2;
        const dy = (ch - ih * scale) / 2;
        ctx.clearRect(0, 0, cw, ch);
        ctx.drawImage(img, dx, dy, iw * scale, ih * scale);
    }

    /**
     * makeSequence(canvasId, base, total, pad)
     * Loads frames from `base + padded(i) + '.jpg'` and plays them
     * on the given canvas at FPS, looping forever.
     * Playback only runs while the slide is visible (opacity > 0).
     */
    function makeSequence(canvasId, base, total, padLen) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        const ctx    = canvas.getContext('2d');
        const frames = [];
        let loaded   = 0;
        let current  = 0;
        let lastTime = 0;
        let rafId    = null;
        let running  = false;

        function resize() {
            canvas.width  = canvas.offsetWidth  || window.innerWidth;
            canvas.height = canvas.offsetHeight || window.innerHeight;
            if (frames[current] && frames[current].complete) {
                drawFrame(ctx, canvas, frames[current]);
            }
        }
        window.addEventListener('resize', resize);

        function tick(ts) {
            if (ts - lastTime >= interval) {
                if (frames[current] && frames[current].complete) {
                    drawFrame(ctx, canvas, frames[current]);
                }
                current  = (current + 1) % total;
                lastTime = ts;
            }
            rafId = requestAnimationFrame(tick);
        }

        function start() {
            if (running) return;
            running = true;
            resize();
            rafId = requestAnimationFrame(tick);
        }

        // Expose start so the hero slider can activate on slide change
        canvas._startSequence = start;

        // Preload all frames
        for (let i = 1; i <= total; i++) {
            const img = new Image();
            img.src = base + pad(i, padLen) + '.jpg';
            img.onload = function () {
                loaded++;
                // Auto-start slide 1 immediately; slide 2 starts on first switch
                if (loaded === 1 && canvasId === 'hero-canvas') {
                    start();
                }
            };
            frames.push(img);
        }
    }

    // Initialise all three sequences
    makeSequence('hero-canvas',   '/100/frame-',        51, 3);
    makeSequence('hero-canvas-2', '/101/frame-',        51, 3);
    makeSequence('hero-canvas-3', '/102/ezgif-frame-',  51, 3);

    // When the hero slider switches slides, start that canvas
    document.addEventListener('hero:slideChange', function (e) {
        const idx = e.detail.index;
        const map = { 0: 'hero-canvas', 1: 'hero-canvas-2', 2: 'hero-canvas-3' };
        const id  = map[idx];
        if (id) {
            const c = document.getElementById(id);
            if (c && c._startSequence) c._startSequence();
        }
    });
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Wait for GSAP to load
    function initGSAP() {
        if (typeof gsap === 'undefined') {
            setTimeout(initGSAP, 100);
            return;
        }

        // ── Hero Slider ───────────────────────────────────────────────
        const heroSlides = [
            {
                tag:  'Premium Corporate Merchandise',
                l1:   'Elevate Your',
                l2:   'Corporate',
                l3:   'Identity',
                sub:  'Bespoke merchandise, luxury corporate gifts, and unforgettable branding experiences crafted exclusively for forward-thinking organizations.'
            },
            {
                tag:  'Where Luxury Meets Purpose',
                l1:   'Gifts That',
                l2:   'Inspire &',
                l3:   'Endure',
                sub:  'From bespoke executive hampers to precision-branded stationery — every piece we create tells your brand\'s story with quiet confidence.'
            },
            {
                tag:  'Crafted for the Remarkable',
                l1:   'Your Brand,',
                l2:   'Beautifully',
                l3:   'Remembered',
                sub:  'We transform everyday corporate touchpoints into extraordinary experiences — because the details are what distinguish great brands from iconic ones.'
            }
        ];

        let heroCurrentSlide = 0;
        const slideDuration  = 6000; // ms between auto-advance
        const fadeDuration   = '1.2s';
        const slideEls  = document.querySelectorAll('.hero-slide');
        const dotEls    = document.querySelectorAll('.hero-dot');

        function heroGoTo(idx, animate = true) {
            const prev = heroCurrentSlide;
            heroCurrentSlide = (idx + slideEls.length) % slideEls.length;
            const data = heroSlides[heroCurrentSlide];

            // Crossfade background slides
            slideEls[prev].style.transition  = animate ? `opacity ${fadeDuration} ease` : 'none';
            slideEls[heroCurrentSlide].style.transition = animate ? `opacity ${fadeDuration} ease` : 'none';
            slideEls[prev].style.opacity = '0';
            slideEls[heroCurrentSlide].style.opacity = '1';

            // Notify canvas animation engine which slide is now active
            document.dispatchEvent(new CustomEvent('hero:slideChange', { detail: { index: heroCurrentSlide } }));

            // Fade content out, swap text, fade back in
            const contentEls = ['#hero-tag-text','#hero-line-1','#hero-line-2','#hero-line-3','#hero-sub'];
            contentEls.forEach(sel => {
                const el = document.querySelector(sel);
                if (el) {
                    el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    el.style.opacity    = '0';
                    el.style.transform  = 'translateY(20px)';
                }
            });

            setTimeout(() => {
                document.querySelector('#hero-tag-text').textContent = data.tag;
                document.querySelector('#hero-line-1').textContent   = data.l1;
                document.querySelector('#hero-line-2').textContent   = data.l2;
                document.querySelector('#hero-line-3').textContent   = data.l3;
                document.querySelector('#hero-sub').textContent      = data.sub;
                contentEls.forEach(sel => {
                    const el = document.querySelector(sel);
                    if (el) {
                        el.style.opacity   = '1';
                        el.style.transform = 'translateY(0)';
                    }
                });
            }, 420);

            // Update dots
            dotEls.forEach((d, i) => {
                d.classList.toggle('bg-[var(--gold)]', i === heroCurrentSlide);
                d.classList.toggle('w-8', i === heroCurrentSlide);
                d.classList.toggle('bg-white/40', i !== heroCurrentSlide);
                d.classList.toggle('w-3', i !== heroCurrentSlide);
            });
        }

        // Dot click
        dotEls.forEach(d => d.addEventListener('click', () => {
            clearInterval(heroTimer);
            heroGoTo(parseInt(d.dataset.dot));
            heroTimer = setInterval(() => heroGoTo(heroCurrentSlide + 1), slideDuration);
        }));

        // Auto-advance
        let heroTimer = setInterval(() => heroGoTo(heroCurrentSlide + 1), slideDuration);

        // ── Hero entrance Animations ─────────────────────────────────
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!prefersReduced) {
            const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

            // Tag line
            tl.fromTo('#hero-tag', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6 }, 0.3);

            // Headline lines stagger
            tl.fromTo('#hero-line-1, #hero-line-2, #hero-line-3', { opacity: 0, y: 60 }, { opacity: 1, y: 0, duration: 0.8, stagger: 0.12 }, 0.5);

            // Subtitle
            tl.fromTo('#hero-sub', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.7 }, 1.0);

            // CTAs stagger
            tl.fromTo('#hero-ctas > *', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6, stagger: 0.15 }, 1.2);

            // Trust signals
            tl.fromTo('#hero-trust > *', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.5, stagger: 0.1 }, 1.4);

            // ── Scroll-triggered stagger cards ───────────────────────
            gsap.registerPlugin(ScrollTrigger);

            document.querySelectorAll('.stagger-cards > *').forEach((card, i) => {
                gsap.fromTo(card,
                    { opacity: 0, y: 30 },
                    {
                        opacity: 1, y: 0, duration: 0.5, delay: i * 0.07,
                        scrollTrigger: {
                            trigger: card,
                            start: 'top 95%',
                            toggleActions: 'play none none none',
                            once: true,
                            onLeaveBack: () => {} // prevent hiding on scroll up
                        }
                    }
                );
            });

            // ── Animated Counters ─────────────────────────────────────
            document.querySelectorAll('.counter').forEach(el => {
                const target = parseInt(el.dataset.target, 10);
                ScrollTrigger.create({
                    trigger: el,
                    start: 'top 85%',
                    once: true,
                    onEnter: () => {
                        gsap.fromTo({ val: 0 }, { val: target, duration: 2, ease: 'power2.out',
                            onUpdate: function () { el.textContent = Math.round(this.targets()[0].val); }
                        });
                    }
                });
            });
        }

        // ── Iconic Products Swiper ────────────────────────────────────
        new Swiper('.swiper-iconic', {
            slidesPerView: 1.2,
            spaceBetween: 16,
            navigation: { prevEl: '.swiper-prev-iconic', nextEl: '.swiper-next-iconic' },
            breakpoints: {
                640:  { slidesPerView: 2.2, spaceBetween: 24 },
                1024: { slidesPerView: 3.2, spaceBetween: 32 },
                1280: { slidesPerView: 4, spaceBetween: 32 },
            }
        });

        // ── New Arrivals Swiper ───────────────────────────────────────
        new Swiper('.swiper-arrivals', {
            slidesPerView: 1.3,
            spaceBetween: 16,
            loop: true,
            navigation: { prevEl: '.swiper-prev-arrivals', nextEl: '.swiper-next-arrivals' },
            breakpoints: {
                480:  { slidesPerView: 2.2 },
                768:  { slidesPerView: 3.2 },
                1024: { slidesPerView: 4, spaceBetween: 24 },
            },
            on: {
                init: function () {
                    // Re-init lucide icons on cloned slides
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                },
                slideChange: function () {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            }
        });

        // ── Testimonials Swiper ───────────────────────────────────────
        new Swiper('.swiper-testimonials', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination-testimonials', clickable: true },
            breakpoints: {
                768:  { slidesPerView: 2 },
                1200: { slidesPerView: 3 },
            }
        });
    }

    initGSAP();
});
</script>
