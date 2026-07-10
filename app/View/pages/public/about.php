<?php // app/View/pages/public/about.php ?>

<div class="pb-0 bg-[var(--bg-primary)] overflow-hidden">

    <!-- Hero Section -->
    <section class="relative min-h-[75vh]">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=1920&auto=format&fit=crop" class="w-full h-full object-cover" alt="Marigold Signature About Us">
            <div class="absolute inset-0 bg-gradient-to-r from-[var(--bg-primary)] via-black/75 to-transparent"></div>
        </div>
        <!-- Spacer that pushes content below the fixed header -->
        <div style="height: 120px;"></div>
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px] relative z-10 py-16">
            <div class="max-w-2xl">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">About Us</span>
                <h1 class="font-['Manrope'] text-5xl sm:text-7xl font-extrabold leading-tight mb-6">
                    The Gift That<br><span class="text-[var(--gold)]">Changes</span> How a Brand Is Remembered.
                </h1>
                <p class="text-[var(--text-secondary)] text-lg leading-relaxed max-w-xl">
                    Premium promotional merchandise and corporate gifts, built on one belief — that the right gift, delivered at the right moment, transforms how a brand is remembered.
                </p>
                <div class="flex gap-4 mt-10">
                    <a href="/solutions" class="inline-flex items-center gap-2 bg-[var(--gold)] text-black font-bold px-8 py-4 rounded-xl hover:brightness-110 transition-all">
                        Our Solutions <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <a href="/contact" class="inline-flex items-center gap-2 border border-[var(--border)] text-white font-semibold px-8 py-4 rounded-xl hover:border-[var(--gold)] transition-all">
                        Get in Touch
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Are -->
    <section class="py-20 sm:py-28 bg-[var(--surface)] border-y border-[var(--border)]">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="flex flex-col lg:flex-row gap-16 items-start">

                <div class="w-full lg:w-1/2">
                    <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">Our Story</span>
                    <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-8 leading-tight">Who We Are</h2>
                    <div class="space-y-5 text-[var(--text-secondary)] leading-relaxed text-base">
                        <p>Marigold Signature Nigeria Limited is a premium promotional merchandise and corporate gifts company, built on one belief: that the right gift, delivered at the right moment, changes how a brand is remembered.</p>
                        <p>We design, source, and deliver curated gift solutions and branded merchandise tailored to each client's objectives, helping organisations strengthen visibility, deepen audience connection, and leave impressions that outlast the moment.</p>
                        <p>We also provide strategic event support, integrating branded products seamlessly into corporate campaigns and activations to ensure consistency and impact at every touchpoint.</p>
                        <p>For over 15 years, Marigold Signature has partnered with some of Nigeria's most recognised organisations, bringing together innovation, precision, and operational excellence to deliver work that reflects the standards of the brands we serve. We do not simply supply products. We build the experiences behind them.</p>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 grid grid-cols-2 gap-4">
                    <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-8 text-center hover:border-[var(--gold)] transition-colors">
                        <div class="font-['Manrope'] text-5xl font-extrabold text-[var(--gold)] mb-2">15+</div>
                        <div class="text-sm font-bold text-white uppercase tracking-wider mb-1">Years</div>
                        <div class="text-[var(--text-secondary)] text-xs">Industry Experience</div>
                    </div>
                    <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-8 text-center hover:border-[var(--gold)] transition-colors">
                        <div class="font-['Manrope'] text-5xl font-extrabold text-[var(--gold)] mb-2">500+</div>
                        <div class="text-sm font-bold text-white uppercase tracking-wider mb-1">Clients</div>
                        <div class="text-[var(--text-secondary)] text-xs">Corporate Partners</div>
                    </div>
                    <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-8 text-center hover:border-[var(--gold)] transition-colors">
                        <div class="font-['Manrope'] text-5xl font-extrabold text-[var(--gold)] mb-2">250k+</div>
                        <div class="text-sm font-bold text-white uppercase tracking-wider mb-1">Items</div>
                        <div class="text-[var(--text-secondary)] text-xs">Delivered Nationwide</div>
                    </div>
                    <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-8 text-center hover:border-[var(--gold)] transition-colors">
                        <div class="font-['Manrope'] text-5xl font-extrabold text-[var(--gold)] mb-2">99%</div>
                        <div class="text-sm font-bold text-white uppercase tracking-wider mb-1">Retention</div>
                        <div class="text-[var(--text-secondary)] text-xs">Client Satisfaction Rate</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Our Philosophy -->
    <section class="py-20 sm:py-28">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">OUR PHILOSOPHY</span>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-4">We get it right the first time — every time</h2>
            </div>

            <!-- Mission & Vision — side by side with clear styling -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- Vision -->
                <div class="bg-[var(--card)] border border-[var(--border)] rounded-3xl p-10 hover:border-[var(--gold)] transition-colors">
                    <div class="w-14 h-14 rounded-2xl bg-[var(--gold)]/10 flex items-center justify-center mb-6">
                        <i data-lucide="eye" class="w-7 h-7 text-[var(--gold)]"></i>
                    </div>
                    <p class="text-[var(--gold)] text-xs font-bold tracking-widest uppercase mb-3">Vision</p>
                    <h3 class="font-['Manrope'] text-2xl font-bold text-white mb-4">Leading the Industry</h3>
                    <p class="text-[var(--text-secondary)] leading-relaxed">
                        To lead the industry in innovative promotional merchandise, delivering quality-driven branding solutions that elevate organisations and strengthen their market presence.
                    </p>
                </div>

                <!-- Mission -->
                <div class="bg-[var(--gold)] rounded-3xl p-10">
                    <div class="w-14 h-14 rounded-2xl bg-black/20 flex items-center justify-center mb-6">
                        <i data-lucide="target" class="w-7 h-7 text-black"></i>
                    </div>
                    <p class="text-black/60 text-xs font-bold tracking-widest uppercase mb-3">Mission</p>
                    <h3 class="font-['Manrope'] text-2xl font-bold text-black mb-4">Premium, Purposeful, Precise</h3>
                    <p class="text-black/80 leading-relaxed">
                        To design, source, and deliver premium promotional merchandise tailored to our clients' brand goals, combining innovation, quality craftsmanship, and reliable execution.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 sm:py-28 bg-[var(--surface)] border-t border-[var(--border)]">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">Why Choose Us</span>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-4">What Sets Us Apart</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $reasons = [
                    ['icon' => 'award',       'title' => '20+ Years of Proven Excellence',  'desc' => 'Two decades of delivering first-class corporate events and merchandise across Nigeria and beyond.'],
                    ['icon' => 'globe',        'title' => 'Global Sourcing Network',          'desc' => 'Direct partnerships with manufacturing facilities in China, Asia, and Europe with strict quality control.'],
                    ['icon' => 'truck',        'title' => 'End-to-End Delivery',              'desc' => 'We own the entire process from concept to delivery, eliminating coordination gaps and ensuring accountability.'],
                    ['icon' => 'shield-check', 'title' => 'Certified Quality Standards',     'desc' => 'RoHS, CE, and OEKO-TEX certified products. We deliver quality you can stake your brand on.'],
                    ['icon' => 'handshake',    'title' => 'Bespoke Client Solutions',        'desc' => 'We listen deeply and design specifically. Every solution is tailored — never off-the-shelf.'],
                    ['icon' => 'building-2',   'title' => 'Multinational Portfolio',         'desc' => 'MTN, Microsoft, MasterCard, Stanbic Bank, Virgin Atlantic, SAP — names that trust us with their brand.'],
                ];
                foreach ($reasons as $r): ?>
                <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-8 hover:border-[var(--gold)] transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-[var(--gold)]/10 flex items-center justify-center mb-6 group-hover:bg-[var(--gold)] transition-colors">
                        <i data-lucide="<?= $r['icon'] ?>" class="w-6 h-6 text-[var(--gold)] group-hover:text-black transition-colors"></i>
                    </div>
                    <h3 class="font-['Manrope'] text-lg font-bold text-white mb-3"><?= $r['title'] ?></h3>
                    <p class="text-[var(--text-secondary)] text-sm leading-relaxed"><?= $r['desc'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- The EPIC Standard (Core Values) -->
    <section class="py-20 sm:py-28">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">CORE VALUES</span>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-4">The EPIC Standard</h2>
                <p class="text-[var(--text-secondary)]">Four pillars that define everything we do.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-0 border border-[var(--gold)]/30 rounded-2xl overflow-hidden max-w-5xl mx-auto">
                <?php
                $epic = [
                    ['letter' => 'E', 'title' => 'Excellence',    'desc' => 'Every brief executed flawlessly, on brief, on budget, on time.'],
                    ['letter' => 'P', 'title' => 'Professionalism','desc' => 'Disciplined process, dependable people, immaculate presentation.'],
                    ['letter' => 'I', 'title' => 'Integrity',      'desc' => 'Honest counsel, transparent pricing, ethical sourcing — always.'],
                    ['letter' => 'C', 'title' => 'Creativity',     'desc' => 'Original thinking that transforms every brief into a signature experience.'],
                ];
                foreach ($epic as $i => $e):
                    $border = $i < count($epic) - 1 ? 'border-r border-[var(--gold)]/30' : '';
                ?>
                <div class="bg-[var(--card)] <?= $border ?> p-10 flex flex-col items-center text-center hover:bg-[var(--surface)] transition-colors group">
                    <div class="text-7xl font-extrabold text-[var(--gold)]/20 group-hover:text-[var(--gold)]/40 transition-colors mb-4 font-['Manrope'] leading-none"><?= $e['letter'] ?></div>
                    <h3 class="font-['Manrope'] font-bold text-lg text-white mb-3"><?= $e['title'] ?></h3>
                    <p class="text-[var(--text-secondary)] text-sm leading-relaxed"><?= $e['desc'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Trusted By -->
    <section class="py-16 bg-[var(--surface)] border-t border-[var(--border)]">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <p class="text-center text-[var(--text-secondary)] text-sm font-semibold tracking-widest uppercase mb-12">Trusted By Nigeria's Most Recognised Brands</p>

            <div class="relative overflow-hidden">
                <!-- Fade edges -->
                <div class="absolute left-0 top-0 h-full w-20 z-10 pointer-events-none" style="background: linear-gradient(to right, var(--surface), transparent);"></div>
                <div class="absolute right-0 top-0 h-full w-20 z-10 pointer-events-none" style="background: linear-gradient(to left, var(--surface), transparent);"></div>

                <div class="clients-track flex items-center gap-16">
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
                        // Duplicate for seamless loop
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
        </div>
    </section>

    <style>
        .clients-track {
            animation: clientsScroll 28s linear infinite;
            width: max-content;
        }
        @keyframes clientsScroll {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }
        .client-logo {
            filter: grayscale(100%) opacity(0.55);
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
    </style>

    <!-- CTA -->
    <section class="py-24 bg-[var(--gold)] text-black text-center">
        <div class="container mx-auto px-4 max-w-3xl">
            <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-6">Ready to elevate your brand?</h2>
            <p class="text-black/80 text-lg mb-10">Let's discuss how Marigold Signature can create a bespoke merchandise program tailored to your organisation's unique needs and goals.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="/quote" class="inline-flex items-center gap-2 bg-black text-white font-bold px-10 py-5 rounded-xl hover:bg-gray-900 transition-transform hover:-translate-y-1">
                    Request a Corporate Quote <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="/contact" class="inline-flex items-center gap-2 border-2 border-black text-black font-bold px-10 py-5 rounded-xl hover:bg-black/10 transition-transform hover:-translate-y-1">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

</div>
