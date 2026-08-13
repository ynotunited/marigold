<?php // app/View/pages/public/home.php ?>

<!-- ============================================================
     HERO SECTION — CROSSFADE SLIDER
     ============================================================ -->
<section id="hero" class="relative min-h-screen flex items-center overflow-hidden" style="background:#050505;">

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

            <!-- Headline — JS swaps content between slides -->
            <h1 id="hero-headline" class="font-['Manrope'] text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-[1.05] mb-6">
                <span id="hero-line-1" class="block text-white">Elevate Your</span>
                <span id="hero-line-2" class="block text-[var(--gold)]">Corporate</span>
                <span id="hero-line-3" class="block text-white">Identity</span>
            </h1>

            <!-- Subtitle -->
            <p id="hero-sub" class="text-[var(--text-secondary)] text-lg sm:text-xl leading-relaxed mb-10 max-w-xl">
                Bespoke merchandise, luxury corporate gifts, and unforgettable branding experiences crafted exclusively for forward-thinking organizations.
            </p>

            <!-- CTA Buttons -->
            <div id="hero-ctas" class="flex flex-col sm:flex-row gap-4">
                <a href="<?= app_url('/shop') ?>" id="cta-shop"
                   class="inline-flex items-center justify-center gap-2 bg-[var(--gold)] text-black font-bold px-8 py-4 rounded-xl text-base hover:bg-[#D4AF37] transition-all duration-300 hover:-translate-y-1">
                    Shop All Products <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="<?= app_url('/quote-request') ?>" id="cta-quote"
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
<section class="section" style="padding-top:clamp(56px, 7vw, 96px)">
    <div class="container">
        <div class="section-head" style="display:flex;align-items:end;justify-content:space-between;gap:24px;flex-wrap:wrap;max-width:none">
            <div>
                <span class="eyebrow reveal">Signature Line</span>
                <h2 class="display h2 reveal">Iconic Products</h2>
            </div>
            <a class="btn btn-ghost reveal" href="<?= app_url('/shop') ?>">View all <span class="arr">→</span></a>
        </div>

        <div class="iconic-grid">
            <?php
            $iconic_products = [
                ['name' => 'Original Cabin', 'colors' => ['#e2e2e2', '#1a1a1a', '#b5a68c'], 'img' => '1553062407-98eeb64c6a62'],
                ['name' => 'Classic Check-In L', 'colors' => ['#1a1a1a', '#e2e2e2'], 'img' => '1622560480605-d83c853bc5c3'],
                ['name' => 'Essential Cabin', 'colors' => ['#1f4031', '#404040', '#d94c25'], 'img' => '1549465220-1a8b9238cd48'],
                ['name' => 'Original Trunk Plus', 'colors' => ['#e2e2e2', '#d94c25'], 'img' => '1512428813834-c702c7702b78'],
            ];
            foreach ($iconic_products as $i => $prod): ?>
                <a class="iconic-card reveal<?= $i % 4 ? ' d' . $i % 4 : '' ?>" href="<?= app_url('/shop?cat=corporate-gifts') ?>">
                    <div class="ic-img">
                        <span class="ic-idx">0<?= $i + 1 ?></span>
                        <img src="https://images.unsplash.com/photo-<?= $prod['img'] ?>?q=80&w=700&auto=format&fit=crop" alt="<?= htmlspecialchars($prod['name']) ?>" loading="lazy">
                    </div>
                    <h3><?= htmlspecialchars($prod['name']) ?></h3>
                    <div class="ic-swatches">
                        <?php foreach (array_slice($prod['colors'], 0, 3) as $color): ?>
                            <i style="background-color: <?= $color ?>;"></i>
                        <?php endforeach; ?>
                        <small>+<?= count($prod['colors']) ?> colours</small>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     TRUSTED BY LEADING ORGANISATIONS — Client marquee
     ============================================================ -->
<section class="section" style="padding:60px 0 88px">
    <div class="container">
        <div class="section-head center" style="margin-bottom:36px">
            <span class="eyebrow center reveal">Trusted by leading organisations</span>
            <h2 class="display h3 reveal">The brands we help remember well</h2>
        </div>

        <div class="clients-wrap reveal">
            <div class="clients-edge left"></div>
            <div class="clients-edge right"></div>
            <div class="clients-track">
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
                    // Duplicate for a seamless infinite loop
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
                    <span class="client-logo" title="<?= htmlspecialchars($c['name']) ?>">
                        <img src="<?= app_url('/clients/') ?><?= $c['file'] ?>" alt="<?= htmlspecialchars($c['name']) ?>" loading="lazy">
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     CORPORATE SOLUTIONS
     ============================================================ -->
<section class="section dark">
    <div class="container">
        <div class="section-head center">
            <span class="eyebrow reveal">For Business</span>
            <h2 class="display h2 reveal">Corporate Solutions</h2>
            <p class="lead reveal" style="margin-top:14px">End-to-end merchandise and gifting programmes built for forward-thinking organisations.</p>
        </div>

        <div class="steps three">
            <?php
            $solutions = [
                ['icon' => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',           'title' => 'Employee Welcome Kits',   'desc' => 'Make every new hire feel valued from day one with curated, branded onboarding packs.'],
                ['icon' => '<path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.735H5.81a1 1 0 0 1-.957-.735L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z"/><path d="M5 21h14"/>',                                                        'title' => 'Executive Gifts',             'desc' => 'Impress C-suite clients and partners with luxury items that reflect your brand premium.'],
                ['icon' => '<path d="M2 3h20"/><path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3"/><path d="m7 21 5-5 5 5"/>',                                                                               'title' => 'Conference Merchandise',    'desc' => 'Stand out at events with high-quality branded items delegates will actually keep.'],
                ['icon' => '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',                                                                              'title' => 'Promotional Campaigns',      'desc' => 'Drive awareness and recall with creative, on-brand promotional merchandise.'],
                ['icon' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',                                                         'title' => 'Client Appreciation',         'desc' => 'Strengthen relationships with thoughtful, premium gifts for your most valued clients.'],
                ['icon' => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>',                'title' => 'Awards & Recognition',       'desc' => 'Celebrate outstanding performance with bespoke trophies, plaques, and gift sets.'],
            ];
            foreach ($solutions as $i => $sol): ?>
                <div class="sol-card reveal<?= $i % 4 ? ' d' . $i % 4 : '' ?>">
                    <span class="sc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?= $sol['icon'] ?></svg></span>
                    <h3><?= htmlspecialchars($sol['title']) ?></h3>
                    <p><?= htmlspecialchars($sol['desc']) ?></p>
                    <a class="ct-link" href="<?= app_url('/quote-request') ?>">Start a programme <span class="arr">→</span></a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     FEATURED PRODUCTS
     ============================================================ -->
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="section-head" style="display:flex;align-items:end;justify-content:space-between;gap:24px;flex-wrap:wrap;max-width:none">
            <div>
                <span class="eyebrow reveal">Handpicked</span>
                <h2 class="display h2 reveal">Featured Products</h2>
            </div>
            <a class="btn btn-ghost reveal" href="<?= app_url('/shop') ?>">View all products <span class="arr">→</span></a>
        </div>
        <div class="pgrid" id="featuredGrid"></div>
    </div>
</section>

<!-- ============================================================
     NEW ARRIVALS
     ============================================================ -->
<section class="section" style="background:var(--paper)">
    <div class="container">
        <div class="section-head" style="display:flex;align-items:end;justify-content:space-between;gap:24px;flex-wrap:wrap;max-width:none">
            <div>
                <span class="eyebrow reveal">Just In</span>
                <h2 class="display h2 reveal">New Arrivals</h2>
            </div>
            <a class="btn btn-ghost reveal" href="<?= app_url('/shop') ?>">View all <span class="arr">→</span></a>
        </div>
        <div class="pgrid" id="newGrid"></div>
    </div>
</section>

<!-- ============================================================
     SOCIAL PROOF — Testimonials
     ============================================================ -->
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="section-head center">
            <span class="eyebrow reveal">Social Proof</span>
            <h2 class="display h2 reveal">What Our Clients Say</h2>
        </div>
        <div class="testi" id="testiGrid"></div>
    </div>
</section>

<!-- ============================================================
     LATEST INSIGHTS — Blog preview
     ============================================================ -->
<section class="section" style="background:var(--paper)">
    <div class="container">
        <div class="section-head" style="display:flex;align-items:end;justify-content:space-between;gap:24px;flex-wrap:wrap;max-width:none">
            <div>
                <span class="eyebrow reveal">Knowledge</span>
                <h2 class="display h2 reveal">Latest Insights</h2>
            </div>
            <a class="btn btn-ghost reveal" href="<?= app_url('/blog') ?>">View all articles <span class="arr">→</span></a>
        </div>

        <div class="blog-grid">
            <?php
            $posts = [
                ['id' => 'psychology-of-corporate-gifting',   'cat' => 'Strategy',     'read' => '6 min read', 'img' => '1503602642458-232111445657', 'title' => 'The Psychology of Corporate Gifting: Why Thoughtful Gifts Build Loyalty',    'excerpt' => 'A gift is never just an object. Chosen with intention, it becomes a signal of value, memory and trust.'],
                ['id' => 'corporate-gifting-trends-2026',     'cat' => 'Trends',       'read' => '7 min read', 'img' => '1554224155-6726b3ff858f',     'title' => '2026 Corporate Gifting Trends: What Nigeria\'s Leading Brands Are Doing',       'excerpt' => 'From sustainable sourcing to personalisation at scale, the trends reshaping corporate gifting programmes.'],
                ['id' => 'sustainable-branded-merchandise',   'cat' => 'Sustainability','read' => '5 min read', 'img' => '1542601906990-b4d3fb778b09', 'title' => 'Sustainable Branded Merchandise: Eco-Friendly Gifting Done Right',             'excerpt' => 'Eco-friendly branded merchandise no longer means boring. Materials, manufacturing and messaging that let your brand do good.'],
            ];
            foreach ($posts as $i => $post): ?>
                <a class="blog-card reveal<?= $i % 4 ? ' d' . $i % 4 : '' ?>" href="<?= app_url('/blog/') ?><?= $post['id'] ?>">
                    <div class="bc-img"><img src="https://images.unsplash.com/photo-<?= $post['img'] ?>?q=80&w=800&auto=format&fit=crop" alt="<?= htmlspecialchars($post['title']) ?>" loading="lazy"></div>
                    <div class="bc-body">
                        <div class="bc-meta"><span class="cat"><?= htmlspecialchars($post['cat']) ?></span><span class="dot"></span><span><?= $post['read'] ?></span></div>
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <p><?= htmlspecialchars($post['excerpt']) ?></p>
                        <span class="ct-link">Read article <span class="arr">→</span></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     CTA
     ============================================================ -->
<section class="section dark cta">
    <div class="container">
        <span class="eyebrow reveal">Start a Programme</span>
        <h2 class="display h2 reveal">Plan your next corporate gifting campaign</h2>
        <p class="lead reveal">Tell us your objectives and audience — we'll curate a proposal that makes your brand unforgettable.</p>
        <div class="cta-actions reveal">
            <a href="<?= app_url('/quote-request') ?>" class="btn btn-gold btn-lg">Request a Proposal <span class="arr">→</span></a>
            <a href="<?= app_url('/shop') ?>" class="btn btn-ghost-light btn-lg">Browse Products</a>
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

<!-- ── Frame-sequence animation engine for Hero Slides 1, 2 & 3 ─────── -->
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
                // Auto-start slide 1 immediately; slides 2/3 start on first switch
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

<!-- ── Hero Slider (crossfade + content swap, powered by GSAP) ──────── -->
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
        }
    }

    initGSAP();
});
</script>

<!-- ── New Arrivals grid (rendered from the canonical product data) ── -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var grid = document.getElementById('newGrid');
    if (!grid || !window.Marigold || typeof PRODUCTS === 'undefined') return;
    var M = window.Marigold;
    var ids = ['corporate-hamper', 'heritage-timepiece', 'smart-band', 'wireless-headset'];
    var items = ids.map(function (id) {
        return PRODUCTS.find(function (p) { return p.id === id; });
    }).filter(Boolean);
    if (!items.length) return;
    grid.innerHTML = items.map(function (p) { return M.productCard(p); }).join('');
});
</script>
