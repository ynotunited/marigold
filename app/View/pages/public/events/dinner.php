<?php // app/View/pages/public/events/dinner.php ?>

<div style="background: var(--ivory); color: var(--ink);">

    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="/">Home</a><span>/</span><a href="/events">Events</a><span>/</span><span>Dinner</span></div>
            <span class="eyebrow reveal">Events — Corporate Dinner</span>
            <h1 class="display h1 reveal">Corporate Dinners, <span class="gold-text">Elegantly Delivered</span></h1>
            <p class="lead reveal">We provide premium promotional merchandise and brand solutions that add sophistication and lasting value to corporate dinner events.</p>
            <div class="cta-actions reveal" style="margin-top:30px">
                <a href="/quote-request" class="btn btn-gold btn-lg">Get a Quote <span class="arr">&rarr;</span></a>
                <a href="/contact" class="btn btn-ghost btn-lg">Talk to Us</a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container split">
            <div class="reveal">
                <span class="eyebrow">What We Do</span>
                <h2 class="display h2" style="margin:18px 0 20px">Memorable Occasions, Delivered with Excellence</h2>
                <p class="lead" style="margin-bottom:18px">Corporate dinners offer a valuable opportunity to celebrate achievements, strengthen relationships, recognise excellence, and create memorable experiences for employees, clients, partners, and stakeholders. At Marigold Signature Nigeria Limited, we provide premium promotional merchandise and brand solutions that add sophistication and lasting value to corporate dinner events.</p>
                <p class="lead" style="margin-bottom:18px">Whether it is an executive dinner, gala night, awards ceremony, client appreciation event, end-of-year celebration, fundraising dinner, or leadership gathering, we help organisations create memorable occasions through carefully curated branded products and event solutions.</p>
                <p class="lead" style="margin-bottom:18px">We understand that corporate dinners are more than social gatherings — they are opportunities to reinforce relationships, communicate appreciation, and leave a lasting impression on guests.</p>
                <p class="lead">From intimate executive dinners to large-scale gala events, Marigold Signature delivers premium merchandise and branding solutions that elevate the guest experience and showcase your brand with excellence.</p>
            </div>
            <div class="visual reveal d1">
                <img src="https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?w=900&q=80&auto=format&fit=crop" alt="Elegant corporate dinner table setting">
                <div class="tick"><span><em>Prestige-level</em> dinner solutions</span></div>
            </div>
        </div>
    </section>

    <section class="section" style="padding-top:0">
        <div class="container">
            <div class="section-head center">
                <span class="eyebrow reveal">Our Offerings</span>
                <h2 class="display h2 reveal">Corporate Dinner Solutions</h2>
            </div>
            <div class="offer-grid">
                <?php
                $offerings = [
                    ['01', 'Executive Gift Sets', 'Curated luxury gift sets that reflect the prestige of the occasion and your brand.'],
                    ['02', 'Premium Drinkware & Glassware', 'Elegant branded drinkware that elevates the table experience for every guest.'],
                    ['03', 'Customised Souvenirs', 'Personalised keepsakes that guests cherish long after the evening ends.'],
                    ['04', 'Awards & Recognition Gifts', 'Distinguished awards and gifts that honour achievement and inspire excellence.'],
                    ['05', 'Table Gifts & Guest Favors', 'Thoughtful place gifts that delight guests and set the tone for a memorable evening.'],
                    ['06', 'Branded Gift Boxes', 'Beautifully packaged branded boxes that make a lasting first impression.'],
                    ['07', 'Personalised Event Merchandise', 'Bespoke merchandise designed specifically around your dinner theme and brand.'],
                    ['08', 'Luxury Packaging Solutions', 'Premium packaging that communicates quality before a single gift is unwrapped.'],
                    ['09', 'VIP & Speaker Appreciation Gifts', 'Exclusive gifts that honour VIPs and speakers with the recognition they deserve.'],
                    ['10', 'Event Branding Materials', 'Comprehensive branding collateral for a sophisticated, cohesive event atmosphere.'],
                ];
                ?>
                <?php foreach ($offerings as $i => $o): ?>
                    <div class="offer-card reveal<?= $i % 3 === 1 ? ' d1' : ($i % 3 === 2 ? ' d2' : '') ?>"><span class="oc-num"><?= $o[0] ?></span><h3><?= $o[1] ?></h3><p><?= $o[2] ?></p></div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section dark cta">
        <div class="container">
            <span class="eyebrow reveal">Ready When You Are</span>
            <h2 class="display h2 reveal">Let's deliver your next corporate dinner</h2>
            <p class="lead reveal">From an intimate executive dinner to a full gala — the Marigold team is ready.</p>
            <div class="cta-actions reveal">
                <a href="/quote-request" class="btn btn-gold btn-lg">Get a Quote <span class="arr">&rarr;</span></a>
                <a href="/contact" class="btn btn-ghost-light btn-lg">Talk to Us</a>
            </div>
        </div>
    </section>

</div>
