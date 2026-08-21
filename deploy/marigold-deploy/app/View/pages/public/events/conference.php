<?php // app/View/pages/public/events/conference.php ?>

<div style="background: var(--ivory); color: var(--ink);">

    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="<?= app_url('/') ?>">Home</a><span>/</span><a href="<?= app_url('/events') ?>">Events</a><span>/</span><span>Conference</span></div>
            <span class="eyebrow reveal">Events — Conference</span>
            <h1 class="display h1 reveal">Conferences Designed to <span class="gold-text">Inspire Connection</span></h1>
            <p class="lead reveal">We deliver premium promotional merchandise and brand solutions that help organisations maximise the impact of their conferences and corporate events.</p>
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
                <h2 class="display h2" style="margin:18px 0 20px">Maximise Your Conference Impact</h2>
                <p class="lead" style="margin-bottom:18px">Conferences provide a unique opportunity for organisations to showcase their brand, engage stakeholders, share knowledge, and create meaningful connections. At Marigold Signature Nigeria Limited, we deliver premium promotional merchandise and brand solutions that help organisations maximise the impact of their conferences and corporate events.</p>
                <p class="lead" style="margin-bottom:18px">We work closely with businesses, institutions, associations, and event organisers to provide high-quality branded products that enhance the attendee experience while reinforcing brand visibility throughout the event.</p>
                <p class="lead" style="margin-bottom:18px">From industry conferences and leadership summits to annual general meetings, professional workshops, and large-scale corporate gatherings, we provide tailored solutions that align with your event objectives and brand identity.</p>
                <p class="lead" style="margin-bottom:18px">Our attention to detail, commitment to quality, and understanding of corporate branding ensure that every item contributes to a professional, engaging, and memorable conference experience.</p>
                <p class="lead">At Marigold Signature, we believe every conference is an opportunity to strengthen relationships, increase brand visibility, and leave a lasting impression on attendees.</p>
            </div>
            <div class="visual reveal d1">
                <img src="<?= app_url('/pages/conferences/Conference-01.jpg') ?>" alt="Conference delegate branding and merchandise">
                <div class="tick"><span><em>Full-scale</em> conference support</span></div>
            </div>
        </div>
    </section>

    <section class="section" style="padding-top:0">
        <div class="container">
            <div class="section-head center">
                <span class="eyebrow reveal">In Pictures</span>
                <h2 class="display h2 reveal">Conferences, Crafted with Care</h2>
                <p class="lead reveal" style="margin-top:14px">Every detail — from delegate kits to exhibition giveaways — is designed to elevate the attendee experience and keep your brand front of mind.</p>
            </div>
            <div class="collage reveal">
                <div class="collage-grid">
                    <figure class="cg-item cg-main reveal">
                        <img src="<?= app_url('/pages/conferences/Conference-02.jpg') ?>" alt="Branded merchandise for conference delegates">
                        <figcaption class="cg-caption">Delegate Kits &amp; Branded Essentials</figcaption>
                    </figure>
                    <div class="cg-float cg-a reveal d1">
                        <div class="cf-ico"><i data-lucide="users"></i></div>
                        <div><strong>Engage every attendee</strong><span>Purposeful gifts that spark conversations</span></div>
                    </div>
                </div>
                <div class="cg-float cg-b reveal d2">
                    <div class="cf-ico"><i data-lucide="sparkles"></i></div>
                    <div><strong>Lasting impressions</strong><span>Premium, on-brand, memorable</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="padding-top:0">
        <div class="container">
            <div class="section-head center">
                <span class="eyebrow reveal">Our Offerings</span>
                <h2 class="display h2 reveal">Conference Solutions</h2>
            </div>
            <div class="offer-grid">
                <?php
                $offerings = [
                    ['01', 'Delegate Bags & Conference Kits', 'Professionally curated kits that set the tone and carry your brand through the event.'],
                    ['02', 'Branded Notebooks & Journals', 'High-quality notebooks for every delegate to capture ideas and take notes in style.'],
                    ['03', 'Premium Pens & Writing Sets', 'Elegant writing instruments that reinforce professionalism at every session.'],
                    ['04', 'Name Tags & Lanyards', 'Crisp, professional identification that keeps networking seamless.'],
                    ['05', 'Water Bottles & Drinkware', 'Branded hydration solutions that keep delegates refreshed and your brand visible.'],
                    ['06', 'Technology Accessories', 'Useful tech accessories that attendees will appreciate and use beyond the event.'],
                    ['07', 'Exhibition & Promotional Giveaways', 'Eye-catching giveaways that drive traffic to your stand and spark conversations.'],
                    ['08', 'Speaker Appreciation Gifts', 'Distinguished gifts that honour speakers and reflect the prestige of your conference.'],
                    ['09', 'Corporate Gift Sets', 'Premium gift sets curated to delight VIP guests and key stakeholders.'],
                    ['10', 'Event Branding Materials', 'Comprehensive branding solutions for a cohesive, professional conference environment.'],
                    ['11', 'Customised Souvenirs', 'Memorable keepsakes that attendees take home and remember your brand by.'],
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
            <h2 class="display h2 reveal">Let's maximise your next conference</h2>
            <p class="lead reveal">From a leadership summit to a nationwide conference — the Marigold team is ready.</p>
            <div class="cta-actions reveal">
                <a href="<?= app_url('/quote-request') ?>" class="btn btn-gold btn-lg">Get a Quote <span class="arr">&rarr;</span></a>
                <a href="<?= app_url('/contact') ?>" class="btn btn-ghost-light btn-lg">Talk to Us</a>
            </div>
        </div>
    </section>

</div>
