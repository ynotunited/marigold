<?php // app/View/pages/public/events/conference.php ?>

<div class="pb-0 bg-[var(--bg-primary)]">

    <!-- Hero -->
    <section class="relative min-h-[75vh] overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=1920&auto=format&fit=crop" class="w-full h-full object-cover" alt="Conference">
            <div class="absolute inset-0 bg-gradient-to-r from-[var(--bg-primary)] via-black/70 to-transparent"></div>
        </div>
        <div style="height: 120px;"></div>
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px] relative z-10 py-16">
            <div class="max-w-2xl">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">Events — Conference</span>
                <h1 class="font-['Manrope'] text-5xl sm:text-6xl font-extrabold leading-tight mb-6 text-white">
                    Conferences Designed to<br><span class="text-[var(--gold)]">Inspire Connection</span>
                </h1>
                <p class="text-[var(--text-secondary)] text-lg leading-relaxed max-w-xl">
                    We deliver premium promotional merchandise and brand solutions that help organisations maximise the impact of their conferences and corporate events.
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
                    <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-8 leading-tight text-white">Maximise Your Conference Impact</h2>
                    <div class="space-y-5 text-[var(--text-secondary)] leading-relaxed text-base">
                        <p>Conferences provide a unique opportunity for organisations to showcase their brand, engage stakeholders, share knowledge, and create meaningful connections. At Marigold Signature Nigeria Limited, we deliver premium promotional merchandise and brand solutions that help organisations maximise the impact of their conferences and corporate events.</p>
                        <p>We work closely with businesses, institutions, associations, and event organisers to provide high-quality branded products that enhance the attendee experience while reinforcing brand visibility throughout the event.</p>
                        <p>From industry conferences and leadership summits to annual general meetings, professional workshops, and large-scale corporate gatherings, we provide tailored solutions that align with your event objectives and brand identity.</p>
                        <p>At Marigold Signature, we believe every conference is an opportunity to strengthen relationships, increase brand visibility, and leave a lasting impression on attendees.</p>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <img src="https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=1200&auto=format&fit=crop" alt="Conference" class="w-full h-[480px] object-cover rounded-3xl border border-[var(--border)]">
                </div>
            </div>
        </div>
    </section>

    <!-- Solutions -->
    <section class="py-20 sm:py-28">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">Our Offerings</span>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold text-white">Conference Solutions</h2>
            </div>

            <?php
            $items = [
                ['icon' => 'briefcase',       'title' => 'Delegate Bags & Conference Kits',  'desc' => 'Professionally curated kits that set the tone and carry your brand through the event.'],
                ['icon' => 'book-open',       'title' => 'Branded Notebooks & Journals',     'desc' => 'High-quality notebooks for every delegate to capture ideas and take notes in style.'],
                ['icon' => 'pen-tool',        'title' => 'Premium Pens & Writing Sets',      'desc' => 'Elegant writing instruments that reinforce professionalism at every session.'],
                ['icon' => 'badge',           'title' => 'Name Tags & Lanyards',             'desc' => 'Crisp, professional identification that keeps networking seamless.'],
                ['icon' => 'coffee',          'title' => 'Water Bottles & Drinkware',        'desc' => 'Branded hydration solutions that keep delegates refreshed and your brand visible.'],
                ['icon' => 'cpu',             'title' => 'Technology Accessories',           'desc' => 'Useful tech accessories that attendees will appreciate and use beyond the event.'],
                ['icon' => 'gift',            'title' => 'Exhibition & Promotional Giveaways', 'desc' => 'Eye-catching giveaways that drive traffic to your stand and spark conversations.'],
                ['icon' => 'award',           'title' => 'Speaker Appreciation Gifts',       'desc' => 'Distinguished gifts that honour speakers and reflect the prestige of your conference.'],
                ['icon' => 'package',         'title' => 'Corporate Gift Sets',              'desc' => 'Premium gift sets curated to delight VIP guests and key stakeholders.'],
                ['icon' => 'layout',          'title' => 'Event Branding Materials',         'desc' => 'Comprehensive branding solutions for a cohesive, professional conference environment.'],
                ['icon' => 'tag',             'title' => 'Customised Souvenirs',             'desc' => 'Memorable keepsakes that attendees take home and remember your brand by.'],
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
            <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-6">Partner with us for your next conference</h2>
            <p class="text-black/80 text-lg mb-10">Create experiences that inspire, connect, and deliver lasting value for every attendee.</p>
            <a href="/quote-request" class="inline-flex items-center gap-2 bg-black text-white font-bold px-10 py-5 rounded-xl hover:bg-gray-900 transition-all hover:-translate-y-1">
                Request a Quote <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>
    </section>

</div>
