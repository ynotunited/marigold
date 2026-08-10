<?php // app/View/pages/public/categories/_template.php
// Expects $cat array with keys: label, tagline, intro, hero_img, side_img, body[], items[]

$ICONS = [
    'battery-charging' => '<path d="M15 7h1a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-1"/><path d="M6 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="m11 7-3 5h4l-3 5"/><line x1="22" x2="22" y1="11" y2="13"/>',
    'wifi' => '<path d="M12 20h.01"/><path d="M2 8.82a15 15 0 0 1 20 0"/><path d="M5 12.859a10 10 0 0 1 14 0"/><path d="M8.5 16.429a5 5 0 0 1 7 0"/>',
    'book-open' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
    'monitor' => '<rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>',
    'wind' => '<path d="M17.7 7.7a2.5 2.5 0 1 1 1.8 4.3H2"/><path d="M9.6 4.6A2 2 0 1 1 11 8H2"/><path d="M12.6 19.4A2 2 0 1 0 14 16H2"/>',
    'headphones' => '<path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Z"/><path d="M21 14h-3a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-5Z"/><path d="M3 14v-3a9 9 0 0 1 18 0v3"/>',
    'gift' => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',
    'star' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
    'moon' => '<path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>',
    'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>',
    'package' => '<path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="M3.3 7 12 12l8.7-5"/><path d="M12 22V12"/>',
    'sparkles' => '<path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/>',
    'coffee' => '<path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/>',
    'droplets' => '<path d="M7 16.3c2.2 0 4-1.83 4-4.05 0-1.16-.57-2.26-1.71-3.19S7.29 6.75 7 5.3c-.29 1.45-1.14 2.84-2.29 3.76S3 11.1 3 12.25c0 2.22 1.8 4.05 4 4.05z"/><path d="M12.56 6.6A10.97 10.97 0 0 0 14 3.02c.5 2.5 2 4.9 4 6.5s3 3.5 3 5.5a6.98 6.98 0 0 1-11.91 4.97"/>',
    'cup-soda' => '<path d="m6 8 1.75 12.28a2 2 0 0 0 2 1.72h4.54a2 2 0 0 0 2-1.72L18 8"/><path d="M5 8h14"/><path d="M7 15a6.47 6.47 0 0 1 5 0 6.47 6.47 0 0 0 5 0"/><path d="m12 8 1-6h2"/>',
    'wine' => '<path d="M8 22h8"/><path d="M7 10h10"/><path d="M12 15v7"/><path d="M12 15a5 5 0 0 0 5-5c0-2-.5-4-2-8H9c-1.5 4-2 6-2 8a5 5 0 0 0 5 5Z"/>',
    'briefcase' => '<rect width="20" height="14" x="2" y="7" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
    'shopping-bag' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',
    'luggage' => '<path d="M6 20a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2"/><path d="M8 18V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v14"/><path d="M10 20h4"/><circle cx="16" cy="20" r="2"/><circle cx="8" cy="20" r="2"/>',
    'folder' => '<path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>',
    'scissors' => '<circle cx="6" cy="6" r="3"/><path d="M8.12 8.12 12 12"/><path d="M20 4 8.12 15.88"/><circle cx="6" cy="18" r="3"/><path d="M14.8 14.8 20 20"/>',
    'key' => '<path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/>',
    'image' => '<rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
    'tag' => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>',
    'cpu' => '<rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/>',
    'award' => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
    'shirt' => '<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>',
    'user' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    'circle' => '<circle cx="12" cy="12" r="10"/>',
    'layers' => '<path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>',
];
function cat_icon(string $name, int $size = 20): string
{
    global $ICONS;
    $paths = $ICONS[$name] ?? $ICONS['gift'];
    return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $paths . '</svg>';
}
?>

<div style="background: var(--ivory); color: var(--ink);">
    <!-- Hero -->
    <section class="page-hero">
        <div class="container">
            <div class="crumbs">Home <span>/</span> Categories <span>/</span> <?= htmlspecialchars($cat['label']) ?></div>
            <div class="eyebrow center reveal">Categories &rsaquo; <?= htmlspecialchars($cat['label']) ?></div>
            <h1 class="display reveal"><?= htmlspecialchars($cat['tagline']) ?></h1>
            <p class="lead reveal"><?= htmlspecialchars($cat['intro']) ?></p>
            <div class="cta-actions reveal">
                <a href="/shop" class="btn btn-gold btn-lg">Shop Now</a>
                <a href="/quote-request" class="btn btn-ghost btn-lg">Request a Quote</a>
            </div>
        </div>
    </section>

    <!-- About This Category -->
    <section class="section" style="background: var(--cream);">
        <div class="container split">
            <div>
                <div class="eyebrow reveal">About This Category</div>
                <h2 class="h2 reveal"><?= htmlspecialchars($cat['label']) ?></h2>
                <div class="body-text reveal" style="display: grid; gap: 16px; margin: 20px 0 30px;">
                    <?php foreach ($cat['body'] as $para): ?>
                        <p><?= htmlspecialchars($para) ?></p>
                    <?php endforeach; ?>
                </div>
                <a href="/contact" class="btn btn-ghost reveal">Talk to our Team</a>
            </div>
            <div class="visual reveal">
                <img src="<?= $cat['side_img'] ?>" alt="<?= htmlspecialchars($cat['label']) ?>">
            </div>
        </div>
    </section>

    <!-- Product Types -->
    <section class="section">
        <div class="container">
            <div class="section-head center">
                <div class="eyebrow reveal">What We Offer</div>
                <h2 class="h2 reveal">Our <?= htmlspecialchars($cat['label']) ?> <span class="gold-text">Range</span></h2>
            </div>
            <div class="range-grid">
                <?php foreach ($cat['items'] as $item): ?>
                    <div class="svc-card reveal">
                        <div class="sc-ico"><?= cat_icon($item['icon'], 24) ?></div>
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                        <p><?= htmlspecialchars($item['desc']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Other Categories -->
    <section class="section" style="background: var(--cream); border-top: 1px solid var(--line);">
        <div class="container">
            <div class="section-head center">
                <div class="eyebrow reveal">Keep Exploring</div>
                <h2 class="h2 reveal">Other <span class="gold-text">Categories</span></h2>
            </div>
            <div class="tag-list reveal" style="justify-content: center;">
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
                    <a href="/categories/<?= $c['slug'] ?>" style="display: inline-flex; align-items: center; gap: 8px;">
                        <?= cat_icon($c['icon'], 15) ?>
                        <?= htmlspecialchars($c['label']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta section">
        <div class="container">
            <div class="eyebrow center reveal">Marigold Signature</div>
            <h2 class="h2 reveal">Ready to brand your <?= htmlspecialchars(strtolower($cat['label'])) ?>?</h2>
            <p class="lead reveal">Let us design, source, and deliver exactly what your brand needs — on time and to spec.</p>
            <div class="cta-actions reveal">
                <a href="/quote-request" class="btn btn-gold btn-lg">Request a Quote</a>
                <a href="/contact" class="btn btn-ghost-light btn-lg">Contact Us</a>
            </div>
        </div>
    </section>
</div>
