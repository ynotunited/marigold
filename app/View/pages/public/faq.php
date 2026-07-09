<!-- FAQ Hero -->
<section class="pt-32 pb-16 px-6 lg:px-20 border-b border-[var(--border)]">
    <div class="max-w-[1280px] mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">How can we help?</h1>
        <p class="text-[var(--text-secondary)] text-lg mb-8 max-w-2xl mx-auto">Find answers to commonly asked questions about our products, ordering process, customization, and shipping.</p>
        
        <div class="max-w-xl mx-auto relative">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)] w-5 h-5"></i>
            <input type="text" placeholder="Search FAQs..." class="input-field pl-12">
        </div>
    </div>
</section>

<!-- FAQ Accordion List -->
<section class="py-20 px-6 lg:px-20">
    <div class="max-w-3xl mx-auto" x-data="{ active: null }">
        <?php foreach ($faqs as $index => $faq): ?>
            <div class="border-b border-[var(--border)]">
                <button @click="active = active === <?= $index ?> ? null : <?= $index ?>" 
                        class="w-full py-6 flex items-center justify-between text-left focus:outline-none group">
                    <h3 class="text-xl font-semibold group-hover:text-[var(--gold)] transition-colors pr-8">
                        <?= htmlspecialchars($faq['question']) ?>
                    </h3>
                    <div class="relative w-6 h-6 flex-shrink-0 flex items-center justify-center text-[var(--gold)]">
                        <i data-lucide="plus" class="absolute transition-transform duration-300" 
                           :class="{'rotate-90 opacity-0': active === <?= $index ?>}"></i>
                        <i data-lucide="minus" class="absolute transition-transform duration-300 opacity-0" 
                           :class="{'opacity-100': active === <?= $index ?>}"></i>
                    </div>
                </button>
                <div x-show="active === <?= $index ?>" 
                     x-collapse 
                     class="overflow-hidden"
                     style="display: none;">
                    <div class="pb-6 text-[var(--text-secondary)] leading-relaxed">
                        <span class="inline-block px-3 py-1 bg-[var(--surface)] border border-[var(--border)] rounded-full text-xs text-[var(--gold)] mb-3">
                            <?= htmlspecialchars($faq['category']) ?>
                        </span>
                        <p><?= nl2br(htmlspecialchars($faq['answer'])) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- CTA -->
<section class="py-20 px-6 lg:px-20 bg-[var(--surface)] text-center">
    <div class="max-w-[1280px] mx-auto">
        <h2 class="text-3xl font-bold mb-4">Still have questions?</h2>
        <p class="text-[var(--text-secondary)] mb-8 max-w-xl mx-auto">Can't find the answer you're looking for? Our dedicated corporate sales team is here to help you.</p>
        <a href="/contact" class="btn btn-primary px-8 h-[52px]">Contact Support</a>
    </div>
</section>
