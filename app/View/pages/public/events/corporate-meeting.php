<?php // app/View/pages/public/events/corporate-meeting.php ?>

<div style="background: var(--ivory); color: var(--ink);">

    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="/">Home</a><span>/</span><a href="/events">Events</a><span>/</span><span>Corporate Meeting</span></div>
            <span class="eyebrow reveal">Events — Corporate Meeting</span>
            <h1 class="display h1 reveal">Professional Meetings, <span class="gold-text">Perfectly Managed</span></h1>
            <p class="lead reveal">Successful corporate meetings require more than just a venue and an agenda — they require thoughtful branding, seamless execution, and memorable experiences.</p>
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
                <h2 class="display h2" style="margin:18px 0 20px">Elevate Every Meeting</h2>
                <p class="lead" style="margin-bottom:18px">At Marigold Signature Nigeria Limited, we provide premium promotional merchandise and brand solutions designed to elevate corporate meetings and reinforce your organisation's professional image.</p>
                <p class="lead" style="margin-bottom:18px">We have partnered with leading corporate organisations to deliver branded products and event support solutions that enhance engagement, strengthen brand visibility, and create lasting impressions among attendees.</p>
                <p class="lead" style="margin-bottom:18px">Whether you are hosting a board meeting, management retreat, stakeholder engagement, annual strategy session, training workshop, or client meeting, our team works closely with you to deliver products that align with your brand identity and event objectives.</p>
                <p class="lead">At Marigold Signature, we understand that every touchpoint matters. Our goal is to help organisations create professional, cohesive, and impactful meeting experiences through carefully curated promotional merchandise and branding solutions.</p>
            </div>
            <div class="visual reveal d1">
                <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=900&q=80&auto=format&fit=crop" alt="Professional corporate meeting room">
                <div class="tick"><span><em>End-to-end</em> meeting solutions</span></div>
            </div>
        </div>
    </section>

    <section class="section" style="padding-top:0">
        <div class="container">
            <div class="section-head center">
                <span class="eyebrow reveal">Our Offerings</span>
                <h2 class="display h2 reveal">Corporate Meeting Solutions</h2>
            </div>
            <div class="offer-grid">
                <?php
                $offerings = [
                    ['01', 'Branded Notebooks & Journals', 'Premium notebooks and journals with your corporate branding for every delegate.'],
                    ['02', 'Executive Pens', 'Sophisticated writing instruments that reinforce your brand at every signature.'],
                    ['03', 'Name Tags & Lanyards', 'Professional identification solutions to ensure seamless event management.'],
                    ['04', 'Conference Folders', 'Elegant branded folders to keep delegates organised and on-brand.'],
                    ['05', 'Drinkware & Water Bottles', 'Custom-branded drinkware that keeps your brand visible throughout the event.'],
                    ['06', 'Technology Accessories', 'Practical tech gifts that delegates will use long after the meeting ends.'],
                    ['07', 'Welcome Packs & Delegate Kits', 'Thoughtfully curated kits that make every attendee feel valued from arrival.'],
                    ['08', 'Corporate Gift Items', 'Premium branded gifts that reinforce relationships and reflect your brand quality.'],
                    ['09', 'Custom Event Merchandise', 'Bespoke merchandise crafted specifically for your meeting theme and objectives.'],
                    ['10', 'Branded Signage & Event Collateral', 'Comprehensive branding materials to create a cohesive, professional event environment.'],
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
            <h2 class="display h2 reveal">Let's elevate your next corporate meeting</h2>
            <p class="lead reveal">From a board meeting to a full management retreat — the Marigold team is ready.</p>
            <div class="cta-actions reveal">
                <a href="/quote-request" class="btn btn-gold btn-lg">Get a Quote <span class="arr">&rarr;</span></a>
                <a href="/contact" class="btn btn-ghost-light btn-lg">Talk to Us</a>
            </div>
        </div>
    </section>

</div>
