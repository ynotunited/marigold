<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold mb-2">My Wishlist</h1>
        <p class="text-[var(--text-secondary)]">Items you've saved for future corporate gifting campaigns.</p>
    </div>
    <div class="flex items-center gap-4 mt-4 md:mt-0">
        <button class="btn btn-secondary border border-[var(--border)] h-[44px] px-6 flex items-center gap-2">
            <i data-lucide="share-2" class="w-4 h-4"></i> Share Wishlist
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php foreach($products as $product): ?>
    <div class="card group flex flex-col h-full relative border border-[var(--border)] rounded-[16px] overflow-hidden">
        
        <!-- Delete Button -->
        <button class="absolute top-4 right-4 w-8 h-8 rounded-full bg-black/50 backdrop-blur text-white flex items-center justify-center z-10 hover:bg-[var(--danger)] transition-colors opacity-0 group-hover:opacity-100">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>

        <a href="/shop/product-<?= $product['id'] ?>" class="block overflow-hidden relative aspect-[4/3] bg-[var(--surface)]">
            <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            <?php if(!$product['in_stock']): ?>
                <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                    <span class="bg-[#111] text-white px-4 py-1.5 rounded-full text-sm font-bold tracking-wider uppercase">Out of Stock</span>
                </div>
            <?php endif; ?>
        </a>
        <div class="p-5 flex-grow flex flex-col bg-[var(--card)]">
            <h3 class="text-lg font-bold mb-1 group-hover:text-[var(--gold)] transition-colors line-clamp-2">
                <a href="/shop/product-<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></a>
            </h3>
            <p class="text-[var(--text-secondary)] font-medium mb-6"><?= $product['price'] ?></p>
            
            <div class="mt-auto pt-4 border-t border-[var(--border)] flex gap-3">
                <button class="btn <?= $product['in_stock'] ? 'btn-primary' : 'btn-secondary border border-[var(--border)] bg-[var(--surface)] opacity-50 cursor-not-allowed' ?> w-full h-[40px] text-sm" <?= !$product['in_stock'] ? 'disabled' : '' ?>>
                    <?= $product['in_stock'] ? 'Move to Cart' : 'Unavailable' ?>
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
