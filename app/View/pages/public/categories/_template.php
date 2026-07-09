<?php // app/View/pages/public/categories/_template.php
// Expects $cat array with keys: label, tagline, intro, hero_img, side_img, body[], items[]
?>

<div class="pb-0 bg-[var(--bg-primary)]">

    <!-- Hero -->
    <section class="relative min-h-[70vh] overflow-hidden">
        <div class="absolute inset-0">
            <img src="<?= $cat['hero_img'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($cat['label']) ?>">
            <div class="absolute inset-0 bg-gradient-to-r from-[var(--bg-primary)] via-black/70 to-transparent"></div>
        </div>
        <div style="height: 120px;"></div>
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px] relative z-10 py-16">
            <div class="max-w-2xl">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">
                    Categories &rsaquo; <?= htmlspecialchars($cat['label']) ?>
                </span>
                <h1 class="font-['Manrope'] text-5xl sm:text-6xl font-extrabold leading-tight mb-6 text-white">
                    <?= htmlspecialchars($cat['tagline']) ?>
                </h1>
                <p class="text-[var(--text-secondary)] text-lg leading-relaxed max-w-xl">
                    <?= htmlspecialchars($cat['intro']) ?>
                </p>
                <div class="flex flex-wrap gap-4 mt-10">
                    <a href="/shop" class="inline-flex items-center gap-2 bg-[var(--gold)] text-black font-bold px-8 py-4 rounded-xl hover:brightness-110 transition-all">
                        Shop Now <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <a href="/quote-request" class="inline-flex items-center gap-2 border border-[var(--border)] text-white font-semibold px-8 py-4 rounded-xl hover:border-[var(--gold)] transition-all">
                        Request a Quote
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About This Category -->
    <section class="py-20 sm:py-28 bg-[var(--surface)] border-y border-[var(--border)]">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="flex flex-col lg:flex-row gap-16 items-center">
                <div class="w-full lg:w-1/2">
                    <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">About This Category</span>
                    <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-8 leading-tight text-white">
                        <?= htmlspecialchars($cat['label']) ?>
                    </h2>
                    <div class="space-y-5 text-[var(--text-secondary)] leading-relaxed text-base">
                        <?php foreach ($cat['body'] as $para): ?>
                        <p><?= htmlspecialchars($para) ?></p>
                        <?php endforeach; ?>
                    </div>
                    <a href="/contact" class="inline-flex items-center gap-2 mt-8 text-[var(--gold)] font-semibold hover:underline">
                        Talk to our team <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
                <div class="w-full lg:w-1/2">
                    <img src="<?= $cat['side_img'] ?>" alt="<?= htmlspecialchars($cat['label']) ?>"
                         class="w-full h-[460px] object-cover rounded-3xl border border-[var(--border)]">
                </div>
            </div>
        </div>
    </section>

    <!-- Product Types -->
    <section class="py-20 sm:py-28">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-[var(--gold)] text-xs font-bold tracking-[0.3em] uppercase mb-4 block">What We Offer</span>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold text-white">
                    Our <?= htmlspecialchars($cat['label']) ?> Range
                </h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($cat['items'] as $item): ?>
                <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-8 hover:border-[var(--gold)] transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-[var(--gold)]/10 flex items-center justify-center mb-5 group-hover:bg-[var(--gold)] transition-colors">
                        <i data-lucide="<?= $item['icon'] ?>" class="w-6 h-6 text-[var(--gold)] group-hover:text-black transition-colors"></i>
                    </div>
                    <h3 class="font-['Manrope'] font-bold text-lg text-white mb-3"><?= htmlspecialchars($item['title']) ?></h3>
                    <p class="text-[var(--text-secondary)] text-sm leading-relaxed"><?= htmlspecialchars($item['desc']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Other Categories -->
    <section class="py-16 bg-[var(--surface)] border-t border-[var(--border)]">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <p class="text-center text-[var(--text-secondary)] text-sm font-semibold tracking-widest uppercase mb-8">Explore Other Categories</p>
            <div class="flex flex-wrap justify-center gap-3">
                <?php
                $allCats = [
                    ['label' => 'Drinkware',                'slug' => 'drinkware',                'icon' => 'coffee'],
                    ['label' => 'Technology & Accessories', 'slug' => 'technology-accessories',   'icon' => 'cpu'],
                    ['label' => 'Bags & Travel',            'slug' => 'bags-travel',              'icon' => 'briefcase'],
                    ['label' => 'Apparels',                 'slug' => 'apparels',                 'icon' => 'shirt'],
                    ['label' => 'Corporate Gifts',          'slug' => 'corporate-gifts',          'icon' => 'gift'],
                    ['label' => 'Souvenirs',                'slug' => 'souvenirs',                'icon' => 'package'],
                    ['label' => 'Seasonal Gifts',           'slug' => 'seasonal-gifts',           'icon' => 'star'],
                ];
                foreach ($allCats as $c):
                    if ($c['label'] === $cat['label']) continue; // skip current
                ?>
                <a href="/categories/<?= $c['slug'] ?>"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[var(--card)] border border-[var(--border)] text-sm text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)] transition-colors">
                    <i data-lucide="<?= $c['icon'] ?>" class="w-4 h-4 text-[var(--gold)]"></i>
                    <?= htmlspecialchars($c['label']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 bg-[var(--gold)] text-black text-center">
        <div class="container mx-auto px-4 max-w-3xl">
            <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-6">
                Ready to brand your <?= htmlspecialchars(strtolower($cat['label'])) ?>?
            </h2>
            <p class="text-black/80 text-lg mb-10">
                Let us design, source, and deliver exactly what your brand needs — on time and to spec.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="/quote-request" class="inline-flex items-center gap-2 bg-black text-white font-bold px-10 py-5 rounded-xl hover:bg-gray-900 transition-all hover:-translate-y-1">
                    Request a Quote <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="/contact" class="inline-flex items-center gap-2 border-2 border-black text-black font-bold px-10 py-5 rounded-xl hover:bg-black/10 transition-all hover:-translate-y-1">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

</div>
