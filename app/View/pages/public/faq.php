<div style="background: var(--ivory); color: var(--ink);">

    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="/">Home</a><span>/</span><span>FAQ</span></div>
            <span class="eyebrow center reveal">Support</span>
            <h1 class="display h1 reveal">How can we help?</h1>
            <p class="lead reveal">Find answers to commonly asked questions about our products, ordering process, customization, and shipping.</p>
        </div>
    </section>

    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div x-data="{ q: '', active: null }">

                <div class="faq-search">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input type="text" x-model="q" placeholder="Search FAQs..." class="field">
                </div>

                <div class="faq-list" x-ref="faqList">
                    <?php foreach ($faqs as $index => $faq): ?>
                    <?php $haystack = strtolower(($faq['question'] ?? '') . ' ' . ($faq['answer'] ?? '')); ?>
                    <div class="faq-item" x-ref="item-<?= $index ?>"
                         :class="{ 'open': active === <?= $index ?>, 'is-hidden': q.trim() !== '' && !'<?= addslashes(htmlspecialchars($haystack, ENT_QUOTES)) ?>'.includes(q.trim().toLowerCase()) }">
                        <button type="button" @click="active = active === <?= $index ?> ? null : <?= $index ?>" class="faq-q" :aria-expanded="active === <?= $index ?> ? 'true' : 'false'">
                            <h3><?= htmlspecialchars($faq['question']) ?></h3>
                            <span class="faq-ico">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            </span>
                        </button>
                        <div class="faq-a">
                            <div class="faq-a-inner">
                                <div class="faq-a-body">
                                    <span class="faq-tag"><?= htmlspecialchars($faq['category']) ?></span>
                                    <p><?= nl2br(htmlspecialchars($faq['answer'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <p class="faq-none" x-show="q.trim() !== '' && $refs.faqList.querySelectorAll('.faq-item:not(.is-hidden)').length === 0">
                    No questions match your search. Try a different keyword or <a href="/contact">contact our team</a>.
                </p>
            </div>
        </div>
    </section>

    <section class="section dark cta">
        <div class="container">
            <span class="eyebrow reveal" style="justify-content: center;">Still have questions?</span>
            <h2 class="display h2 reveal">Can't find the answer you're looking for?</h2>
            <p class="lead reveal">Our dedicated corporate sales team is here to help you.</p>
            <div class="cta-actions reveal">
                <a href="/contact" class="btn btn-gold btn-lg">Contact support <span class="arr">&rarr;</span></a>
            </div>
        </div>
    </section>

</div>
