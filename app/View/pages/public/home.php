<?php // app/View/pages/public/home.php ?>

<!-- ============================================================
     HERO SECTION
     ============================================================ -->
<section id="hero" class="relative min-h-screen flex items-center overflow-hidden">
    <!-- Background image with parallax scale -->
    <div id="hero-bg" class="absolute inset-0 scale-110 origin-center">
        <img src="https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?q=80&w=1920&auto=format&fit=crop"
             alt="Premium corporate merchandise" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#050505]/90 via-[#050505]/60 to-transparent"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 sm:px-8 max-w-[1440px] pt-32 pb-24">
        <div class="max-w-2xl">
            <!-- Tag line -->
            <div id="hero-tag" class="inline-flex items-center gap-2 bg-[var(--gold)]/10 border border-[var(--gold)]/30 rounded-full px-4 py-2 mb-6">
                <span class="w-2 h-2 rounded-full bg-[var(--gold)]"></span>
                <span class="text-[var(--gold)] text-sm font-semibold tracking-wide">Premium Corporate Merchandise</span>
            </div>

            <!-- Headline — split for GSAP animation -->
            <h1 id="hero-headline" class="font-['Manrope'] text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-[1.05] mb-6 overflow-hidden">
                <span class="hero-line block">Gifts That</span>
                <span class="hero-line block text-[var(--gold)]">Define</span>
                <span class="hero-line block">Your Brand</span>
            </h1>

            <!-- Subtitle -->
            <p id="hero-sub" class="text-[var(--text-secondary)] text-lg sm:text-xl leading-relaxed mb-10 max-w-xl">
                From executive welcome kits to branded conference merchandise — we craft premium experiences that leave a lasting impression.
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
                    <i data-lucide="truck" class="w-4 h-4 text-[var(--gold)]"></i> Fast Nationwide Delivery
                </div>
                <div class="flex items-center gap-2 text-sm text-[var(--text-muted)]">
                    <i data-lucide="palette" class="w-4 h-4 text-[var(--gold)]"></i> Custom Branding
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-[var(--text-muted)] text-xs animate-bounce">
        <span class="tracking-widest">SCROLL</span>
        <i data-lucide="chevrons-down" class="w-4 h-4"></i>
    </div>
</section>

<!-- ============================================================
     TRUSTED BRANDS — Marquee
     ============================================================ -->
<section class="py-14 border-y border-[var(--border)] bg-[var(--surface)] overflow-hidden">
    <div class="text-center mb-8 text-[var(--text-muted)] text-xs font-semibold tracking-widest uppercase">Trusted by leading organisations</div>
    <div class="relative overflow-hidden"
         x-data="{ paused: false }"
         @mouseenter="paused = true" @mouseleave="paused = false">
        <div class="marquee-track flex gap-16 items-center"
             :style="paused ? 'animation-play-state: paused' : ''">
            <?php
            $brands = [
                'GTBank', 'Dangote', 'Access Bank', 'MTN Nigeria', 'Zenith Bank',
                'First Bank', 'Shell', 'Chevron', 'KPMG', 'PwC',
                'GTBank', 'Dangote', 'Access Bank', 'MTN Nigeria', 'Zenith Bank',
                'First Bank', 'Shell', 'Chevron', 'KPMG', 'PwC',
            ];
            foreach ($brands as $brand): ?>
                <span class="flex-shrink-0 font-['Manrope'] font-bold text-xl text-[var(--text-muted)] hover:text-[var(--gold)] transition-colors cursor-default whitespace-nowrap">
                    <?= htmlspecialchars($brand) ?>
                </span>
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

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 stagger-cards">
            <?php
            $products = [
                ['name' => 'Executive Leather Notebook',  'price' => 24500, 'badge' => 'New',     'img1' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1531346680769-a1d79b57de5c?q=80&w=600&auto=format&fit=crop'],
                ['name' => 'Branded Vacuum Flask 1L',     'price' => 18000, 'badge' => 'Popular', 'img1' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1553361371-9b22f78e8b1d?q=80&w=600&auto=format&fit=crop'],
                ['name' => 'Slim Metal Pen Set (3pcs)',   'price' => 12000, 'badge' => '',         'img1' => 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1631467950437-43d2c40de4e3?q=80&w=600&auto=format&fit=crop'],
                ['name' => 'USB-C Hub & Organiser',      'price' => 35000, 'badge' => 'New',     'img1' => 'https://images.unsplash.com/photo-1612815292673-ab2ad8a5a95b?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1600377695841-1f6dff5c76da?q=80&w=600&auto=format&fit=crop'],
                ['name' => 'Premium Cotton Polo Shirt',   'price' => 9500,  'badge' => '',         'img1' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=600&auto=format&fit=crop'],
                ['name' => 'Wireless Charging Pad',       'price' => 28000, 'badge' => 'Sale',    'img1' => 'https://images.unsplash.com/photo-1583394293253-3f8b5e4d2be6?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1586077220249-a2afebe0a11d?q=80&w=600&auto=format&fit=crop'],
                ['name' => 'Branded Canvas Tote Bag',     'price' => 7500,  'badge' => '',         'img1' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=600&auto=format&fit=crop'],
                ['name' => 'Executive Laptop Backpack',   'price' => 55000, 'badge' => 'Popular', 'img1' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=600&auto=format&fit=crop', 'img2' => 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?q=80&w=600&auto=format&fit=crop'],
            ];

            $badgeColors = ['New' => 'bg-[var(--gold)] text-black', 'Popular' => 'bg-blue-600 text-white', 'Sale' => 'bg-red-600 text-white'];

            foreach ($products as $p): ?>
                <div class="group relative bg-[var(--card)] rounded-2xl overflow-hidden border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-300 hover:-translate-y-1"
                     x-data="{ wishlisted: false }">
                    <!-- Image container with hover swap -->
                    <div class="relative aspect-square overflow-hidden bg-[var(--surface)]">
                        <img src="<?= $p['img1'] ?>" alt="<?= htmlspecialchars($p['name']) ?>"
                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0" loading="lazy">
                        <img src="<?= $p['img2'] ?>" alt="<?= htmlspecialchars($p['name']) ?> alt view"
                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 opacity-0 group-hover:opacity-100" loading="lazy">

                        <!-- Badge -->
                        <?php if ($p['badge']): ?>
                            <span class="absolute top-3 left-3 text-[10px] font-bold px-2 py-1 rounded-full <?= $badgeColors[$p['badge']] ?? 'bg-gray-600 text-white' ?>">
                                <?= $p['badge'] ?>
                            </span>
                        <?php endif; ?>

                        <!-- Wishlist -->
                        <button @click="wishlisted = !wishlisted"
                                class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center text-white hover:bg-[var(--gold)] hover:text-black transition-all opacity-0 group-hover:opacity-100">
                            <i data-lucide="heart" class="w-4 h-4" :class="{ 'fill-current text-red-500': wishlisted }"></i>
                        </button>

                        <!-- Quick action bar -->
                        <div class="absolute bottom-0 left-0 right-0 p-3 flex gap-2 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <button class="flex-1 bg-[var(--gold)] text-black text-xs font-bold py-2.5 rounded-xl hover:bg-[#D4AF37] transition-colors">
                                Add to Cart
                            </button>
                            <button class="px-3 bg-black/70 backdrop-blur-sm text-white text-xs font-bold rounded-xl hover:bg-white hover:text-black transition-colors border border-[var(--border)]">
                                Quote
                            </button>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-4">
                        <h3 class="text-sm font-semibold leading-snug mb-2 line-clamp-2"><?= htmlspecialchars($p['name']) ?></h3>
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
                    ['name' => 'Leatherette Card Holder',    'price' => 6500,  'img' => 'https://images.unsplash.com/photo-1637149937-c2d0bab5d32d?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Bamboo Desk Organiser',      'price' => 15000, 'img' => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Smart Power Bank 20000mAh',  'price' => 42000, 'img' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Branded Cap Collection',      'price' => 5500,  'img' => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Glass Water Bottle 750ml',   'price' => 11000, 'img' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Wireless Earbuds Case',       'price' => 22000, 'img' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?q=80&w=600&auto=format&fit=crop'],
                ];
                foreach ($arrivals as $a): ?>
                    <div class="swiper-slide">
                        <div class="group bg-[var(--card)] border border-[var(--border)] rounded-2xl overflow-hidden hover:border-[var(--gold)]/50 transition-all duration-300 hover:-translate-y-1">
                            <div class="aspect-square overflow-hidden">
                                <img src="<?= $a['img'] ?>" alt="<?= htmlspecialchars($a['name']) ?>"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            </div>
                            <div class="p-4">
                                <span class="text-[10px] font-bold text-[var(--gold)] bg-[var(--gold)]/10 px-2 py-1 rounded-full">New</span>
                                <h3 class="text-sm font-semibold mt-2 mb-1 line-clamp-2"><?= htmlspecialchars($a['name']) ?></h3>
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
                    ['name' => 'Adaeze Okonkwo',  'role' => 'HR Director, GTBank',           'review' => 'Marigold delivered our employee welcome kits with exceptional quality and speed. Every new joiner was genuinely impressed. 10/10 service.',                 'rating' => 5, 'img' => 'https://randomuser.me/api/portraits/women/44.jpg'],
                    ['name' => 'Emeka Nwosu',      'role' => 'CEO, Apex Ventures',            'review' => 'Our executive gifts for partners and clients have never looked better. The leather notebooks and pens were exactly what we envisioned for our brand.',   'rating' => 5, 'img' => 'https://randomuser.me/api/portraits/men/32.jpg'],
                    ['name' => 'Fatima Aliyu',     'role' => 'Brand Manager, MTN Nigeria',    'review' => 'From concept to delivery, Marigold\'s team was professional, responsive, and genuinely creative. Our conference merchandise was a massive hit.',         'rating' => 5, 'img' => 'https://randomuser.me/api/portraits/women/68.jpg'],
                    ['name' => 'Chukwuemeka Bello','role' => 'Admin Manager, Shell Nigeria',  'review' => 'The quality surpassed our expectations at every level. The branded vacuum flasks are still being used daily across our offices months later.',            'rating' => 5, 'img' => 'https://randomuser.me/api/portraits/men/75.jpg'],
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
                                <img src="<?= $t['img'] ?>" alt="<?= htmlspecialchars($t['name']) ?>"
                                     class="w-12 h-12 rounded-full object-cover ring-2 ring-[var(--gold)]" loading="lazy">
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
     HOMEPAGE SCRIPTS
     ============================================================ -->
<style>
    /* Marquee animation */
    .marquee-track {
        animation: marquee 30s linear infinite;
        width: max-content;
    }
    @keyframes marquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    .no-scrollbar { scrollbar-width: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Wait for GSAP to load
    function initGSAP() {
        if (typeof gsap === 'undefined') {
            setTimeout(initGSAP, 100);
            return;
        }

        // ── Hero Animations ──────────────────────────────────────────
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!prefersReduced) {
            const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

            // Parallax bg: scale from 1.1 to 1
            tl.fromTo('#hero-bg', { scale: 1.1 }, { scale: 1, duration: 1.8 }, 0);

            // Tag line
            tl.fromTo('#hero-tag', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6 }, 0.3);

            // Headline lines stagger
            tl.fromTo('.hero-line', { opacity: 0, y: 60 }, { opacity: 1, y: 0, duration: 0.8, stagger: 0.12 }, 0.5);

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
                    { opacity: 0, y: 40 },
                    {
                        opacity: 1, y: 0, duration: 0.6, delay: i * 0.08,
                        scrollTrigger: { trigger: card, start: 'top 88%', toggleActions: 'play none none none' }
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
