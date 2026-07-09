<?php
/**
 * Product Card Component
 * 
 * @param array $product ['id', 'name', 'price', 'sale_price', 'image', 'category', 'badge']
 */
$badge = $product['badge'] ?? null;
$isOnSale = isset($product['sale_price']) && $product['sale_price'] > 0;
?>
<div class="product-card group flex flex-col h-full">
    <div class="relative aspect-square overflow-hidden bg-black/20">
        <?php if ($badge): ?>
            <div class="absolute top-4 left-4 z-10">
                <?php \App\Core\View::render('components/badge', ['type' => $badge['type'], 'label' => $badge['label']]) ?>
            </div>
        <?php endif; ?>
        
        <button class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white hover:text-[var(--gold)] transition-colors">
            <i data-lucide="heart" class="w-5 h-5"></i>
        </button>

        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110" loading="lazy">
        
        <!-- Quick Actions Overlay -->
        <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 bg-gradient-to-t from-black/80 to-transparent flex gap-2">
            <?php \App\Core\View::render('components/button', ['type' => 'primary', 'label' => 'Add to Cart', 'class' => 'w-full py-2 text-sm', 'icon' => 'shopping-cart']) ?>
        </div>
    </div>
    
    <div class="p-5 flex flex-col grow">
        <span class="text-xs text-[var(--text-secondary)] uppercase tracking-wider mb-2 font-semibold"><?= htmlspecialchars($product['category']) ?></span>
        <h3 class="text-lg font-bold text-white mb-2 line-clamp-2"><?= htmlspecialchars($product['name']) ?></h3>
        
        <div class="mt-auto flex items-center gap-3">
            <?php if ($isOnSale): ?>
                <span class="text-[var(--gold)] font-bold text-xl">₦<?= number_format($product['sale_price'], 2) ?></span>
                <span class="text-[var(--text-secondary)] line-through text-sm">₦<?= number_format($product['price'], 2) ?></span>
            <?php else: ?>
                <span class="text-[var(--gold)] font-bold text-xl">₦<?= number_format($product['price'], 2) ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
