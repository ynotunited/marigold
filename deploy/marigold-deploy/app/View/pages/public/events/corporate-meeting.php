<?php // app/View/pages/public/events/corporate-meeting.php ?>

<?php
$mgImages = [
    ['Corporate Meetings -01.jpg', 'Delegate Welcome Packs'],
    ['Corporate Meetings-02.jpg', 'Boardroom Branding'],
    ['Corporate Meetings-03.jpg', 'Conference Folders'],
    ['Corporate Meetings-04.jpg', 'Executive Pens'],
    ['Corporate Meetings-05.jpg', 'Meeting Collateral'],
    ['Corporate Meetings-06.jpg', 'Branded Notebooks'],
    ['Corporate Meetings-07.jpg', 'Corporate Gift Items'],
    ['Corporate Meetings-08.jpg', 'Name Tags & Lanyards'],
    ['Corporate Meetings-09.jpg', 'Drinkware & Water Bottles'],
    ['Corporate Meetings-10.jpg', 'Delegate Kits'],
    ['Corporate Meetings-11.jpg', 'Welcome Materials'],
    ['Corporate Meetings-12.jpg', 'Event Merchandise'],
    ['Corporate Meetings-13.jpg', 'Technology Accessories'],
    ['Corporate Meetings-14.jpg', 'Custom Souvenirs'],
    ['Corporate Meetings-15.jpg', 'Branded Signage'],
    ['Corporate Meetings-16.jpg', 'Gift Sets'],
    ['Corporate Meetings-17.jpg', 'Workshop Essentials'],
    ['Corporate Meetings-18.jpg', 'Stakeholder Gifts'],
    ['Corporate Meetings-19.jpg', 'Training Kit'],
    ['Corporate Meetings-20.jpg', 'Strategic Session Kit'],
    ['Corporate Meetings-21.jpg', 'Client Meeting Branding'],
];
?>

<div style="background: var(--ivory); color: var(--ink);">

    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="<?= app_url('/') ?>">Home</a><span>/</span><a href="<?= app_url('/events') ?>">Events</a><span>/</span><span>Corporate Meeting</span></div>
            <span class="eyebrow reveal">Events — Corporate Meeting</span>
            <h1 class="display h1 reveal">Professional Meetings, <span class="gold-text">Perfectly Managed</span></h1>
            <p class="lead reveal">Successful corporate meetings require more than just a venue and an agenda — they require thoughtful branding, seamless execution, and memorable experiences.</p>
            <div class="cta-actions reveal" style="margin-top:30px">
                <a href="<?= app_url('/quote-request') ?>" class="btn btn-gold btn-lg">Get a Quote <span class="arr">&rarr;</span></a>
                <a href="<?= app_url('/contact') ?>" class="btn btn-ghost btn-lg">Talk to Us</a>
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
                <img src="<?= app_url('/pages/corporate-meetings/Corporate Meetings-07.jpg') ?>" alt="Corporate gift items and branded merchandise">
                <div class="tick"><span><em>End-to-end</em> meeting solutions</span></div>
            </div>
        </div>
    </section>

    <section class="section" style="padding-top:0">
        <div class="container">
            <div class="section-head center">
                <span class="eyebrow reveal">In Pictures</span>
                <h2 class="display h2 reveal">Meetings, Crafted with Care</h2>
                <p class="lead reveal" style="margin-top:14px">From boardrooms to workshops, every branded detail keeps your organisation professional, cohesive, and memorable.</p>
            </div>

            <div class="slider-shell reveal">
                <div class="swiper meeting-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($mgImages as $img): ?>
                            <div class="swiper-slide">
                                <div class="ms-card">
                                    <img src="<?= app_url('/pages/corporate-meetings/' . rawurlencode($img[0])) ?>" alt="<?= htmlspecialchars($img[1]) ?>" loading="lazy">
                                    <span class="ms-tag"><?= htmlspecialchars($img[1]) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="slider-nav">
                    <button type="button" class="slider-btn ms-prev" aria-label="Previous"><i data-lucide="arrow-left"></i></button>
                    <button type="button" class="slider-btn ms-next" aria-label="Next"><i data-lucide="arrow-right"></i></button>
                </div>
                <div class="ms-pagination"></div>
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
                <a href="<?= app_url('/quote-request') ?>" class="btn btn-gold btn-lg">Get a Quote <span class="arr">&rarr;</span></a>
                <a href="<?= app_url('/contact') ?>" class="btn btn-ghost-light btn-lg">Talk to Us</a>
            </div>
        </div>
    </section>

</div>
