<?php // app/View/pages/public/events/index.php ?>

<div style="background: var(--ivory); color: var(--ink);">

    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="<?= app_url('/') ?>">Home</a><span>/</span><span>Events</span></div>
            <span class="eyebrow reveal">What We Deliver</span>
            <h1 class="display h1 reveal">Every event, an experience <span class="gold-text">your brand is remembered for</span></h1>
            <p class="lead reveal">Three event practices — corporate meetings, conferences and corporate dinners — delivered with the same Marigold standard: thoughtful branding, seamless execution and memorable experiences.</p>
            <div class="cta-actions reveal" style="margin-top:30px">
                <a href="<?= app_url('/events/corporate-meeting') ?>" class="btn btn-gold btn-lg">Corporate Meeting</a>
                <a href="<?= app_url('/events/conference') ?>" class="btn btn-gold btn-lg">Conference</a>
                <a href="<?= app_url('/events/dinner') ?>" class="btn btn-gold btn-lg">Dinner</a>
            </div>
        </div>
    </section>

    <section class="section" style="padding-top:20px">
        <div class="container">
            <div class="svc-list">
                <a class="svc-card reveal" href="<?= app_url('/events/corporate-meeting') ?>">
                    <div class="sc-ico"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                    <h3>Corporate Meeting</h3>
                    <p>Professional meetings, perfectly managed — with branded products and event solutions that reinforce your organisation's professional image.</p>
                    <ul>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Branded notebooks, pens &amp; delegate kits</li>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Name tags, lanyards &amp; conference folders</li>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Welcome packs &amp; corporate gift items</li>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Custom event merchandise &amp; signage</li>
                    </ul>
                    <span class="ct-link">Explore corporate meetings <span class="arr">&rarr;</span></span>
                </a>
                <a class="svc-card reveal d1" href="<?= app_url('/events/conference') ?>">
                    <div class="sc-ico"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
                    <h3>Conference</h3>
                    <p>Conferences designed to inspire connection — enhancing the attendee experience while reinforcing brand visibility throughout the event.</p>
                    <ul>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Delegate bags &amp; conference kits</li>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Exhibition giveaways &amp; tech accessories</li>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Speaker appreciation gifts</li>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Event branding &amp; customised souvenirs</li>
                    </ul>
                    <span class="ct-link">Explore conferences <span class="arr">&rarr;</span></span>
                </a>
                <a class="svc-card reveal d2" href="<?= app_url('/events/dinner') ?>">
                    <div class="sc-ico"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11h18"/><path d="M12 11v10"/><path d="M6 11c0-4 2-8 6-8s6 4 6 8"/><path d="M6 4c-2 1-3 3-3 7"/><path d="M18 4c2 1 3 3 3 7"/></svg></div>
                    <h3>Dinner</h3>
                    <p>Corporate dinners, elegantly delivered — with premium merchandise and branding solutions that add sophistication and lasting value.</p>
                    <ul>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Executive gift sets &amp; branded gift boxes</li>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Awards &amp; recognition gifts</li>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Table gifts &amp; guest favours</li>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Luxury packaging &amp; event branding</li>
                    </ul>
                    <span class="ct-link">Explore dinners <span class="arr">&rarr;</span></span>
                </a>
            </div>
        </div>
    </section>

    <section class="section dark">
        <div class="container">
            <div class="section-head center">
                <span class="eyebrow reveal">How We Work</span>
                <h2 class="display h2 reveal">A disciplined process, from brief to delivery</h2>
            </div>
            <div class="steps">
                <div class="step reveal"><span class="no">01 — Consult</span><h3>Discovery workshop</h3><p>We map your objectives, audience, budget and deadlines to shape a clear brief.</p></div>
                <div class="step reveal d1"><span class="no">02 — Concept</span><h3>Design &amp; curation</h3><p>Our studio proposes products, artwork and packaging — refined until it's unmistakably you.</p></div>
                <div class="step reveal d2"><span class="no">03 — Produce</span><h3>Sourcing &amp; print</h3><p>Premium materials, rigorous sampling and quality-controlled production in volume.</p></div>
                <div class="step reveal d3"><span class="no">04 — Deliver</span><h3>Fulfilment &amp; support</h3><p>Tracked delivery and event-day presence, so every unit lands exactly as planned.</p></div>
            </div>
        </div>
    </section>

    <section class="section dark cta">
        <div class="container">
            <span class="eyebrow reveal">Ready When You Are</span>
            <h2 class="display h2 reveal">Let's build your next brand moment</h2>
            <p class="lead reveal">From an intimate executive dinner to a nationwide conference — the Marigold team is ready.</p>
            <div class="cta-actions reveal">
                <a href="<?= app_url('/quote-request') ?>" class="btn btn-gold btn-lg">Get a Quote <span class="arr">&rarr;</span></a>
                <a href="<?= app_url('/contact') ?>" class="btn btn-ghost-light btn-lg">Talk to Us</a>
            </div>
        </div>
    </section>

</div>
