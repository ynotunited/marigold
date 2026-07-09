<?php // app/View/pages/public/events/corporate-meeting.php ?>

<div class="pb-0 bg-[var(--bg-primary)]">

    <!-- Hero -->
    <section class="relative min-h-[75vh] overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=1920&auto=format&fit=crop" class="w-full h-full object-cover" alt="Corporate Meeting">
            <div class="absolute inset-0 bg-gradient-to-r from-[var(--bg-primary)] via-black/70 to-transparent"></div>
        </div>
        <div style="height: 120px;"></div>
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px] relative z-10 py-16">
            <div class="max-w-2xl">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">Events — Corporate Meeting</span>
                <h1 class="font-['Manrope'] text-5xl sm:text-6xl font-extrabold leading-tight mb-6 text-white">
                    Professional Meetings,<br><span class="text-[var(--gold)]">Perfectly Managed</span>
                </h1>
                <p class="text-[var(--text-secondary)] text-lg leading-relaxed max-w-xl">
                    Successful corporate meetings require more than just a venue and an agenda — they require thoughtful branding, seamless execution, and memorable experiences.
                </p>
                <div class="flex flex-wrap gap-4 mt-10">
                    <a href="/quote-request" class="inline-flex items-center gap-2 bg-[var(--gold)] text-black font-bold px-8 py-4 rounded-xl hover:brightness-110 transition-all">
                        Get a Quote <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <a href="/contact" class="inline-flex items-center gap-2 border border-[var(--border)] text-white font-semibold px-8 py-4 rounded-xl hover:border-[var(--gold)] transition-all">
                        Talk to Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Intro -->
    <section class="py-20 sm:py-28 bg-[var(--surface)] border-y border-[var(--border)]">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="flex flex-col lg:flex-row gap-16 items-start">
                <div class="w-full lg:w-1/2">
                    <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">What We Do</span>
                    <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-8 leading-tight text-white">Elevate Every Meeting</h2>
                    <div class="space-y-5 text-[var(--text-secondary)] leading-relaxed text-base">
                        <p>At Marigold Signature Nigeria Limited, we provide premium promotional merchandise and brand solutions designed to elevate corporate meetings and reinforce your organisation's professional image.</p>
                        <p>We have partnered with leading corporate organisations to deliver branded products and event support solutions that enhance engagement, strengthen brand visibility, and create lasting impressions among attendees.</p>
                        <p>Whether you are hosting a board meeting, management retreat, stakeholder engagement, annual strategy session, training workshop, or client meeting, our team works closely with you to deliver products that align with your brand identity and event objectives.</p>
                        <p>At Marigold Signature, we understand that every touchpoint matters. Our goal is to help organisations create professional, cohesive, and impactful meeting experiences through carefully curated promotional merchandise and branding solutions.</p>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1200&auto=format&fit=crop" alt="Corporate Meeting" class="w-full h-[480px] object-cover rounded-3xl border border-[var(--border)]">
                </div>
            </div>
        </div>
    </section>

    <!-- Solutions -->
    <section class="py-20 sm:py-28">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">Our Offerings</span>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold text-white">Corporate Meeting Solutions</h2>
            </div>

            <?php
            $items = [
                ['icon' => 'book-open',       'title' => 'Branded Notebooks & Journals',    'desc' => 'Premium notebooks and journals with your corporate branding for every delegate.'],
                ['icon' => 'pen-tool',        'title' => 'Executive Pens',                  'desc' => 'Sophisticated writing instruments that reinforce your brand at every signature.'],
                ['icon' => 'badge',           'title' => 'Name Tags & Lanyards',            'desc' => 'Professional identification solutions to ensure seamless event management.'],
                ['icon' => 'folder',          'title' => 'Conference Folders',              'desc' => 'Elegant branded folders to keep delegates organised and on-brand.'],
                ['icon' => 'coffee',          'title' => 'Drinkware & Water Bottles',       'desc' => 'Custom-branded drinkware that keeps your brand visible throughout the event.'],
                ['icon' => 'cpu',             'title' => 'Technology Accessories',          'desc' => 'Practical tech gifts that delegates will use long after the meeting ends.'],
                ['icon' => 'gift',            'title' => 'Welcome Packs & Delegate Kits',  'desc' => 'Thoughtfully curated kits that make every attendee feel valued from arrival.'],
                ['icon' => 'package',         'title' => 'Corporate Gift Items',            'desc' => 'Premium branded gifts that reinforce relationships and reflect your brand quality.'],
                ['icon' => 'tag',             'title' => 'Custom Event Merchandise',        'desc' => 'Bespoke merchandise crafted specifically for your meeting theme and objectives.'],
                ['icon' => 'layout',          'title' => 'Branded Signage & Event Collateral', 'desc' => 'Comprehensive branding materials to create a cohesive, professional event environment.'],
            ];
            ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                <?php foreach ($items as $item): ?>
                <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-6 hover:border-[var(--gold)] transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-[var(--gold)]/10 flex items-center justify-center mb-4 group-hover:bg-[var(--gold)] transition-colors">
                        <i data-lucide="<?= $item['icon'] ?>" class="w-5 h-5 text-[var(--gold)] group-hover:text-black transition-colors"></i>
                    </div>
                    <h3 class="font-['Manrope'] font-bold text-white mb-2"><?= $item['title'] ?></h3>
                    <p class="text-[var(--text-secondary)] text-sm leading-relaxed"><?= $item['desc'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 bg-[var(--gold)] text-black text-center">
        <div class="container mx-auto px-4 max-w-3xl">
            <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-6">Let us transform your next corporate meeting</h2>
            <p class="text-black/80 text-lg mb-10">Into a memorable brand experience — perfectly planned, professionally delivered.</p>
            <a href="/quote-request" class="inline-flex items-center gap-2 bg-black text-white font-bold px-10 py-5 rounded-xl hover:bg-gray-900 transition-all hover:-translate-y-1">
                Request a Quote <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>
    </section>

</div>
