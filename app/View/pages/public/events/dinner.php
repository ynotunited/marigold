<?php // app/View/pages/public/events/dinner.php ?>

<div class="pb-0 bg-[var(--bg-primary)]">

    <!-- Hero -->
    <section class="relative min-h-[75vh] overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=1920&auto=format&fit=crop" class="w-full h-full object-cover" alt="Corporate Dinner">
            <div class="absolute inset-0 bg-gradient-to-r from-[var(--bg-primary)] via-black/70 to-transparent"></div>
        </div>
        <div style="height: 120px;"></div>
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px] relative z-10 py-16">
            <div class="max-w-2xl">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">Events — Dinner</span>
                <h1 class="font-['Manrope'] text-5xl sm:text-6xl font-extrabold leading-tight mb-6 text-white">
                    Corporate Dinners,<br><span class="text-[var(--gold)]">Elegantly Delivered</span>
                </h1>
                <p class="text-[var(--text-secondary)] text-lg leading-relaxed max-w-xl">
                    We provide premium promotional merchandise and brand solutions that add sophistication and lasting value to corporate dinner events.
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
                    <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-8 leading-tight text-white">Memorable Occasions, Delivered with Excellence</h2>
                    <div class="space-y-5 text-[var(--text-secondary)] leading-relaxed text-base">
                        <p>Corporate dinners offer a valuable opportunity to celebrate achievements, strengthen relationships, recognise excellence, and create memorable experiences for employees, clients, partners, and stakeholders. At Marigold Signature Nigeria Limited, we provide premium promotional merchandise and brand solutions that add sophistication and lasting value to corporate dinner events.</p>
                        <p>Whether it is an executive dinner, gala night, awards ceremony, client appreciation event, end-of-year celebration, fundraising dinner, or leadership gathering, we help organisations create memorable occasions through carefully curated branded products and event solutions.</p>
                        <p>We understand that corporate dinners are more than social gatherings — they are opportunities to reinforce relationships, communicate appreciation, and leave a lasting impression on guests.</p>
                        <p>From intimate executive dinners to large-scale gala events, Marigold Signature delivers premium merchandise and branding solutions that elevate the guest experience and showcase your brand with excellence.</p>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <img src="https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?q=80&w=1200&auto=format&fit=crop" alt="Corporate Dinner" class="w-full h-[480px] object-cover rounded-3xl border border-[var(--border)]">
                </div>
            </div>
        </div>
    </section>

    <!-- Solutions -->
    <section class="py-20 sm:py-28">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">Our Offerings</span>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold text-white">Corporate Dinner Solutions</h2>
            </div>

            <?php
            $items = [
                ['icon' => 'gift',        'title' => 'Executive Gift Sets',              'desc' => 'Curated luxury gift sets that reflect the prestige of the occasion and your brand.'],
                ['icon' => 'coffee',      'title' => 'Premium Drinkware & Glassware',    'desc' => 'Elegant branded drinkware that elevates the table experience for every guest.'],
                ['icon' => 'tag',         'title' => 'Customised Souvenirs',             'desc' => 'Personalised keepsakes that guests cherish long after the evening ends.'],
                ['icon' => 'award',       'title' => 'Awards & Recognition Gifts',       'desc' => 'Distinguished awards and gifts that honour achievement and inspire excellence.'],
                ['icon' => 'star',        'title' => 'Table Gifts & Guest Favors',       'desc' => 'Thoughtful place gifts that delight guests and set the tone for a memorable evening.'],
                ['icon' => 'package',     'title' => 'Branded Gift Boxes',              'desc' => 'Beautifully packaged branded boxes that make a lasting first impression.'],
                ['icon' => 'shopping-bag','title' => 'Personalised Event Merchandise',  'desc' => 'Bespoke merchandise designed specifically around your dinner theme and brand.'],
                ['icon' => 'box',         'title' => 'Luxury Packaging Solutions',      'desc' => 'Premium packaging that communicates quality before a single gift is unwrapped.'],
                ['icon' => 'crown',       'title' => 'VIP & Speaker Appreciation Gifts','desc' => 'Exclusive gifts that honour VIPs and speakers with the recognition they deserve.'],
                ['icon' => 'layout',      'title' => 'Event Branding Materials',        'desc' => 'Comprehensive branding collateral for a sophisticated, cohesive event atmosphere.'],
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
            <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-6">Create a dinner experience worthy of your brand</h2>
            <p class="text-black/80 text-lg mb-10">Elegant, memorable, and precisely delivered — from concept to the last guest favour.</p>
            <a href="/quote-request" class="inline-flex items-center gap-2 bg-black text-white font-bold px-10 py-5 rounded-xl hover:bg-gray-900 transition-all hover:-translate-y-1">
                Request a Quote <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>
    </section>

</div>
