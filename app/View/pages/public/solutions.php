<?php // app/View/pages/public/solutions.php ?>

<div style="background: var(--ivory); color: var(--ink);">

    <!-- Hero -->
    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="/">Home</a><span>/</span><span>Corporate Solutions</span></div>
            <span class="eyebrow center reveal">B2B Services</span>
            <h1 class="display h1 reveal">Bespoke corporate <span class="gold-text">gifting solutions</span></h1>
            <p class="lead reveal">Comprehensive, end-to-end merchandise programmes designed to elevate your brand, engage your employees, and impress your clients.</p>
            <div class="cta-actions reveal" style="margin-top: 32px;">
                <a href="/quote-request" class="btn btn-gold btn-lg">Start a project <span class="arr">&rarr;</span></a>
            </div>
        </div>
    </section>

    <!-- Solutions Grid -->
    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow reveal">What We Do</span>
                <h2 class="display h2 reveal">Solutions for every occasion</h2>
            </div>
            <div class="sol-grid">
                <?php
                $solutions = [
                    ['icon' => 'gift', 'title' => 'Employee Welcome Kits', 'desc' => 'Make day one memorable. We curate, brand, and assemble premium onboarding boxes that make new hires feel instantly valued and connected to your company culture.'],
                    ['icon' => 'crown', 'title' => 'Executive & VIP Gifting', 'desc' => 'For those moments when standard won\'t do. Access our exclusive catalogue of luxury items, from Montblanc pens to bespoke leather goods, perfect for C-suite clients.'],
                    ['icon' => 'presentation', 'title' => 'Event & Conference Swag', 'desc' => 'Stand out on the exhibition floor. We provide high-quality, memorable items that delegates actually want to keep, driving long-term brand recall long after the event ends.'],
                    ['icon' => 'trophy', 'title' => 'Awards & Recognition', 'desc' => 'Celebrate milestones and outstanding performance with custom-designed acrylic, crystal, or wood awards, paired with premium congratulatory gift sets.'],
                ];
                foreach ($solutions as $sol): ?>
                    <div class="sol-card-lg reveal">
                        <div class="sc-ico">
                            <?php if ($sol['icon'] === 'gift'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5C11 3 12 8 12 8s1-5 4.5-5a2.5 2.5 0 0 1 0 5"/></svg>
                            <?php elseif ($sol['icon'] === 'crown'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.735H5.81a1 1 0 0 1-.957-.735L2.02 6.02a.5.5 0 0 1 .798-.52l4.276 3.664a1 1 0 0 0 1.516-.294z"/><path d="M5 21h14"/></svg>
                            <?php elseif ($sol['icon'] === 'presentation'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h20"/><path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3"/><path d="m7 21 5-5 5 5"/></svg>
                            <?php elseif ($sol['icon'] === 'trophy'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                            <?php endif; ?>
                        </div>
                        <h2><?= htmlspecialchars($sol['title']) ?></h2>
                        <p><?= htmlspecialchars($sol['desc']) ?></p>
                        <a href="/quote-request" class="ct-link">Request a quote <span class="arr" style="display:inline-block;transition:transform .35s var(--ease);">&rarr;</span></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="section dark">
        <div class="container">
            <div class="section-head center" style="margin-left:auto;margin-right:auto;text-align:center;">
                <span class="eyebrow center reveal" style="justify-content:center;">Process</span>
                <h2 class="display h2 reveal">How it works</h2>
                <p class="lead reveal" style="margin-top:14px">A streamlined, stress-free process from initial concept to final delivery.</p>
            </div>
            <div class="steps">
                <div class="step reveal"><span class="no">01 — Consult</span><h3>Consultation</h3><p>We discuss your goals, budget, audience, and timeline to understand your exact needs.</p></div>
                <div class="step reveal d1"><span class="no">02 — Design</span><h3>Curation &amp; design</h3><p>Our team creates a custom proposal with product recommendations and digital mockups.</p></div>
                <div class="step reveal d2"><span class="no">03 — Produce</span><h3>Production</h3><p>Upon approval, we move to production, ensuring strict quality control during branding.</p></div>
                <div class="step reveal d3"><span class="no">04 — Deliver</span><h3>Delivery</h3><p>Securely packaged and delivered to your office or directly to individual recipients nationwide.</p></div>
            </div>
        </div>
    </section>

    <!-- Industries Served -->
    <section class="section">
        <div class="container">
            <div class="section-head center" style="margin-left:auto;margin-right:auto;text-align:center;">
                <span class="eyebrow center reveal" style="justify-content:center;">Who We Serve</span>
                <h2 class="display h2 reveal">Industries we serve</h2>
            </div>
            <div class="ind-grid">
                <?php
                $industries = [
                    ['name' => 'Banking & Finance', 'icon' => 'landmark'],
                    ['name' => 'Oil & Gas', 'icon' => 'droplet'],
                    ['name' => 'Technology', 'icon' => 'cpu'],
                    ['name' => 'Telecommunications', 'icon' => 'smartphone'],
                    ['name' => 'FMCG', 'icon' => 'shopping-cart'],
                    ['name' => 'Real Estate', 'icon' => 'building-2'],
                ];
                foreach ($industries as $ind): ?>
                    <div class="ind-card reveal">
                        <?php
                        $icons = [
                            'landmark' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>',
                            'droplet' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/></svg>',
                            'cpu' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3"/><path d="M15 1v3"/><path d="M9 20v3"/><path d="M15 20v3"/><path d="M20 9h3"/><path d="M20 14h3"/><path d="M1 9h3"/><path d="M1 14h3"/></svg>',
                            'smartphone' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>',
                            'shopping-cart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>',
                            'building-2' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>',
                        ];
                        echo $icons[$ind['icon']] ?? '';
                        ?>
                        <h3><?= htmlspecialchars($ind['name']) ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section dark cta">
        <div class="container">
            <span class="eyebrow reveal" style="justify-content: center;">Ready When You Are</span>
            <h2 class="display h2 reveal">Let's build your next brand moment</h2>
            <p class="lead reveal">From an intimate executive programme to a nationwide merchandise roll-out — the Marigold team is ready.</p>
            <div class="cta-actions reveal">
                <a href="/quote-request" class="btn btn-gold btn-lg">Start a project <span class="arr">&rarr;</span></a>
                <a href="/contact" class="btn btn-ghost-light btn-lg">Talk to our team</a>
            </div>
        </div>
    </section>

</div>
