<?php // app/View/pages/public/product.php

// Mock product data
$product = [
    'id' => 1,
    'name' => 'Executive Leather Notebook & Pen Set',
    'price' => 35000,
    'sale_price' => 28500,
    'sku' => 'MS-EXEC-001',
    'category' => 'Executive Gifts',
    'brand' => 'Moleskine',
    'stock' => 45,
    'moq' => 10,
    'in_stock' => true,
    'badge' => 'Sale',
    'images' => [
        'https://images.unsplash.com/photo-1544816278-ca5e3f4abd8c?q=80&w=1200&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1531346680769-a1d79b57de5c?q=80&w=1200&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=1200&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1553361371-9b22f78e8b1d?q=80&w=1200&auto=format&fit=crop'
    ],
    'description' => '<p class="mb-4">Elevate your corporate gifting with this premium executive set. Features a genuine leather-bound A5 notebook with 192 lined pages of acid-free paper, paired with a weighted metallic ballpoint pen.</p><p>Perfect for onboarding new executives, client appreciation, or executive board meetings. Can be fully debossed with your company logo.</p>',
    'specs' => [
        'Material' => 'Genuine Italian Leather (Notebook), Brass (Pen)',
        'Dimensions' => '14.8 x 21 cm (A5)',
        'Pages' => '192 lined pages, 100gsm',
        'Packaging' => 'Matte black magnetic gift box'
    ]
];

$related = [
    ['id'=>2, 'slug'=>'branded-vacuum-flask-1l',      'name'=>'Branded Vacuum Flask 1L',     'price'=>18000, 'img'=>'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=600&auto=format&fit=crop'],
    ['id'=>3, 'slug'=>'usb-c-hub-organiser',           'name'=>'USB-C Hub & Organiser',       'price'=>35000, 'img'=>'https://images.unsplash.com/photo-1612815292673-ab2ad8a5a95b?q=80&w=600&auto=format&fit=crop'],
    ['id'=>4, 'slug'=>'executive-laptop-backpack',     'name'=>'Executive Laptop Backpack',   'price'=>55000, 'img'=>'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=600&auto=format&fit=crop'],
    ['id'=>5, 'slug'=>'smart-power-bank-20000mah',     'name'=>'Smart Power Bank 20000mAh',   'price'=>42000, 'img'=>'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?q=80&w=600&auto=format&fit=crop'],
];
?>

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "<?= htmlspecialchars($product['name']) ?>",
  "image": <?= json_encode($product['images']) ?>,
  "description": <?= json_encode(strip_tags($product['description'])) ?>,
  "sku": "<?= $product['sku'] ?>",
  "brand": {
    "@type": "Brand",
    "name": "<?= $product['brand'] ?>"
  },
  "offers": {
    "@type": "Offer",
    "url": "<?= 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>",
    "priceCurrency": "NGN",
    "price": "<?= $product['sale_price'] ?? $product['price'] ?>",
    "itemCondition": "https://schema.org/NewCondition",
    "availability": "<?= $product['in_stock'] ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' ?>"
  }
}
</script>

<div id="product-page-content" class="pb-12 px-4 sm:px-8 bg-[var(--bg-primary)]">
    <div class="container mx-auto max-w-[1440px]">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm text-[var(--text-muted)] mb-8">
            <a href="/" class="hover:text-[var(--gold)] transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <a href="/shop" class="hover:text-[var(--gold)] transition-colors">Shop</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <a href="/shop?category=<?= urlencode($product['category']) ?>" class="hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($product['category']) ?></a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-white truncate max-w-[200px] sm:max-w-none"><?= htmlspecialchars($product['name']) ?></span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-10 xl:gap-16">
            
            <!-- Gallery (Left) -->
            <div class="w-full lg:w-1/2 xl:w-7/12" x-data="{ activeImage: 0, lightboxOpen: false }">
                <!-- Main Image -->
                <div class="relative aspect-square sm:aspect-[4/3] rounded-3xl overflow-hidden bg-[var(--surface)] mb-4 cursor-zoom-in group" @click="lightboxOpen = true">
                    <template x-for="(img, idx) in <?= htmlspecialchars(json_encode($product['images'])) ?>" :key="idx">
                        <img :src="img" alt="<?= htmlspecialchars($product['name']) ?>" 
                             x-show="activeImage === idx"
                             x-transition.opacity.duration.300ms
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    </template>
                    <?php if ($product['badge']): ?>
                        <span class="absolute top-4 left-4 z-10 bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-widest">
                            <?= $product['badge'] ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Thumbnails -->
                <div class="grid grid-cols-4 gap-3 sm:gap-4">
                    <template x-for="(img, idx) in <?= htmlspecialchars(json_encode($product['images'])) ?>" :key="idx">
                        <button @click="activeImage = idx" 
                                class="relative aspect-square rounded-xl overflow-hidden bg-[var(--surface)] border-2 transition-all duration-300"
                                :class="activeImage === idx ? 'border-[var(--gold)]' : 'border-transparent hover:border-[var(--border)]'">
                            <img :src="img" alt="Thumbnail" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 transition-opacity" :class="activeImage === idx ? 'opacity-0' : 'opacity-100 hover:opacity-0'"></div>
                        </button>
                    </template>
                </div>

                <!-- Lightbox (Alpine) -->
                <div x-show="lightboxOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-sm" style="display: none;">
                    <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-white/70 hover:text-white"><i data-lucide="x" class="w-8 h-8"></i></button>
                    <button @click="activeImage = activeImage > 0 ? activeImage - 1 : <?= count($product['images']) - 1 ?>" class="absolute left-6 text-white/70 hover:text-[var(--gold)]"><i data-lucide="chevron-left" class="w-10 h-10"></i></button>
                    <button @click="activeImage = activeImage < <?= count($product['images']) - 1 ?> ? activeImage + 1 : 0" class="absolute right-6 text-white/70 hover:text-[var(--gold)]"><i data-lucide="chevron-right" class="w-10 h-10"></i></button>
                    
                    <img :src="<?= htmlspecialchars(json_encode($product['images'])) ?>[activeImage]" class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg">
                </div>
            </div>

            <!-- Product Info (Right) -->
            <div class="w-full lg:w-1/2 xl:w-5/12" x-data="{ qty: <?= $product['moq'] ?> }">
                <div class="sticky top-28">
                    <!-- Brand & SKU -->
                    <div class="flex items-center justify-between mb-3">
                        <a href="/shop?brand=<?= urlencode($product['brand']) ?>" class="text-[var(--gold)] text-sm font-bold tracking-widest uppercase hover:underline"><?= htmlspecialchars($product['brand']) ?></a>
                        <span class="text-[var(--text-muted)] text-sm font-mono">SKU: <?= $product['sku'] ?></span>
                    </div>

                    <h1 class="font-['Manrope'] text-3xl sm:text-4xl font-extrabold leading-tight mb-4"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <!-- Reviews snippet -->
                    <div class="flex items-center gap-2 mb-6 cursor-pointer hover:opacity-80 transition-opacity" @click="document.getElementById('reviews').scrollIntoView({behavior: 'smooth'})">
                        <div class="flex text-[var(--gold)]">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current text-white/20"></i>
                        </div>
                        <span class="text-sm text-[var(--text-muted)]">(24 Reviews)</span>
                    </div>

                    <!-- Price -->
                    <div class="flex items-end gap-3 mb-6 pb-6 border-b border-[var(--border)]">
                        <span class="font-['Manrope'] text-4xl font-extrabold text-[var(--gold)]">
                            ₦<?= number_format($product['sale_price'] ?? $product['price']) ?>
                        </span>
                        <?php if ($product['sale_price']): ?>
                            <span class="text-xl text-[var(--text-muted)] line-through mb-1">₦<?= number_format($product['price']) ?></span>
                            <span class="bg-red-600/20 text-red-500 text-xs font-bold px-2 py-1 rounded mb-1.5 ml-2">Save ₦<?= number_format($product['price'] - $product['sale_price']) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Short desc -->
                    <div class="text-[var(--text-secondary)] text-sm leading-relaxed mb-8">
                        <?= $product['description'] ?>
                    </div>

                    <!-- Variants (Mock) -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-bold text-white">Color: <span class="text-[var(--text-secondary)] font-normal" x-text="color">Matte Black</span></span>
                        </div>
                        <div class="flex gap-3" x-data="{ color: 'Matte Black' }">
                            <button @click="color = 'Matte Black'" :class="color === 'Matte Black' ? 'ring-2 ring-[var(--gold)] ring-offset-2 ring-offset-[var(--bg-primary)]' : ''" class="w-8 h-8 rounded-full bg-[#111] border border-white/10 transition-all"></button>
                            <button @click="color = 'Navy Blue'" :class="color === 'Navy Blue' ? 'ring-2 ring-[var(--gold)] ring-offset-2 ring-offset-[var(--bg-primary)]' : ''" class="w-8 h-8 rounded-full bg-[#1e293b] border border-white/10 transition-all"></button>
                            <button @click="color = 'Tan Leather'" :class="color === 'Tan Leather' ? 'ring-2 ring-[var(--gold)] ring-offset-2 ring-offset-[var(--bg-primary)]' : ''" class="w-8 h-8 rounded-full bg-[#8b5a2b] border border-white/10 transition-all"></button>
                        </div>
                    </div>

                    <!-- Add to cart / Action area -->
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 mb-8">
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full <?= $product['in_stock'] ? 'bg-green-500 animate-pulse' : 'bg-red-500' ?>"></div>
                                <span class="text-sm font-bold <?= $product['in_stock'] ? 'text-green-500' : 'text-red-500' ?>"><?= $product['in_stock'] ? 'In Stock' : 'Out of Stock' ?></span>
                            </div>
                            <span class="text-xs text-[var(--text-muted)]">MOQ: <?= $product['moq'] ?> units</span>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <!-- Qty -->
                            <div class="flex items-center border border-[var(--border)] rounded-xl bg-[var(--card)] w-full sm:w-32 h-14">
                                <button @click="qty > <?= $product['moq'] ?> ? qty-- : null" class="flex-1 flex items-center justify-center text-[var(--text-muted)] hover:text-white transition-colors h-full"><i data-lucide="minus" class="w-4 h-4"></i></button>
                                <input type="number" x-model.number="qty" min="<?= $product['moq'] ?>" max="<?= $product['stock'] ?>" class="w-12 text-center bg-transparent text-white font-bold focus:outline-none appearance-none m-0">
                                <button @click="qty < <?= $product['stock'] ?> ? qty++ : null" class="flex-1 flex items-center justify-center text-[var(--text-muted)] hover:text-white transition-colors h-full"><i data-lucide="plus" class="w-4 h-4"></i></button>
                            </div>
                            <!-- CTA -->
                            <button @click="window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Added to cart!', type: 'success' }}))" class="flex-1 bg-[var(--gold)] text-black font-bold h-14 rounded-xl hover:bg-[#D4AF37] transition-all duration-300 flex items-center justify-center gap-2">
                                <i data-lucide="shopping-bag" class="w-5 h-5"></i> Add to Cart
                            </button>
                        </div>
                        
                        <div class="mt-3">
                            <a href="/quote-request?product_id=<?= $product['id'] ?>" class="flex items-center justify-center gap-2 w-full h-14 border border-[var(--border)] rounded-xl text-white font-bold hover:border-[var(--gold)] hover:text-[var(--gold)] transition-all duration-300 bg-[var(--card)]">
                                <i data-lucide="file-text" class="w-5 h-5"></i> Request Bulk Quote
                            </a>
                        </div>
                    </div>

                    <!-- Utilities -->
                    <div class="flex items-center justify-between text-sm text-[var(--text-secondary)] py-4 border-t border-[var(--border)]">
                        <button class="flex items-center gap-2 hover:text-[var(--gold)] transition-colors"><i data-lucide="heart" class="w-4 h-4"></i> Add to Wishlist</button>
                        <button class="flex items-center gap-2 hover:text-[var(--gold)] transition-colors"><i data-lucide="share-2" class="w-4 h-4"></i> Share</button>
                    </div>

                    <!-- Estimate Delivery -->
                    <div class="bg-[var(--surface)] rounded-xl p-4 flex items-start gap-4 border border-[var(--border)]">
                        <div class="w-10 h-10 rounded-full bg-[var(--card)] flex items-center justify-center shrink-0 border border-[var(--border)]">
                            <i data-lucide="truck" class="w-5 h-5 text-[var(--gold)]"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white mb-1">Estimated Delivery</h4>
                            <p class="text-xs text-[var(--text-muted)]">Order within <span class="text-white">5 hrs 30 mins</span> for delivery by <span class="text-[var(--gold)] font-semibold"><?= date('D, M j', strtotime('+3 days')) ?></span> to Lagos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     ACCORDION TABS (Description, Specs, Shipping, Returns)
     ============================================================ -->
<section class="py-16 bg-[var(--surface)] border-y border-[var(--border)]">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <div class="max-w-4xl mx-auto" x-data="{ activeTab: 'desc' }">
            
            <!-- Desktop Tabs -->
            <div class="hidden sm:flex border-b border-[var(--border)] gap-8 mb-8">
                <button @click="activeTab = 'desc'" :class="activeTab === 'desc' ? 'text-[var(--gold)] border-[var(--gold)]' : 'text-[var(--text-secondary)] border-transparent hover:text-white'" class="pb-4 font-['Manrope'] font-bold text-lg border-b-2 transition-colors">Description</button>
                <button @click="activeTab = 'specs'" :class="activeTab === 'specs' ? 'text-[var(--gold)] border-[var(--gold)]' : 'text-[var(--text-secondary)] border-transparent hover:text-white'" class="pb-4 font-['Manrope'] font-bold text-lg border-b-2 transition-colors">Specifications</button>
                <button @click="activeTab = 'shipping'" :class="activeTab === 'shipping' ? 'text-[var(--gold)] border-[var(--gold)]' : 'text-[var(--text-secondary)] border-transparent hover:text-white'" class="pb-4 font-['Manrope'] font-bold text-lg border-b-2 transition-colors">Shipping & Returns</button>
            </div>

            <!-- Tab Content -->
            <div class="hidden sm:block">
                <div x-show="activeTab === 'desc'" class="text-[var(--text-secondary)] leading-relaxed prose prose-invert max-w-none">
                    <?= $product['description'] ?>
                    <h3 class="text-white font-bold mt-6 mb-3">Custom Branding Options</h3>
                    <ul class="list-disc pl-5 mb-4 space-y-2">
                        <li>Debossing / Embossing on leather notebook</li>
                        <li>Laser engraving on pen</li>
                        <li>Custom foil stamping on presentation box</li>
                    </ul>
                </div>
                <div x-show="activeTab === 'specs'" class="text-[var(--text-secondary)]" style="display: none;">
                    <table class="w-full text-sm">
                        <tbody>
                            <?php foreach ($product['specs'] as $key => $val): ?>
                            <tr class="border-b border-[var(--border)]">
                                <td class="py-4 font-bold text-white w-1/3"><?= htmlspecialchars($key) ?></td>
                                <td class="py-4"><?= htmlspecialchars($val) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div x-show="activeTab === 'shipping'" class="text-[var(--text-secondary)] leading-relaxed" style="display: none;">
                    <p class="mb-4">We offer nationwide delivery across Nigeria via our trusted logistics partners (DHL, GIG Logistics, RedStar).</p>
                    <ul class="list-disc pl-5 mb-6 space-y-2">
                        <li><strong class="text-white">Lagos:</strong> 1-2 Business Days</li>
                        <li><strong class="text-white">Abuja & Port Harcourt:</strong> 2-3 Business Days</li>
                        <li><strong class="text-white">Other States:</strong> 3-5 Business Days</li>
                    </ul>
                    <p>For custom branded orders, please add 7-10 business days to the estimated delivery time for production.</p>
                </div>
            </div>

            <!-- Mobile Accordion -->
            <div class="sm:hidden space-y-4">
                <div x-data="{ open: true }" class="border border-[var(--border)] rounded-2xl bg-[var(--card)] overflow-hidden">
                    <button @click="open = !open" class="flex items-center justify-between w-full p-5 font-['Manrope'] font-bold text-lg text-white">
                        Description <i data-lucide="chevron-down" class="w-5 h-5 transition-transform" :class="open ? 'rotate-180 text-[var(--gold)]' : ''"></i>
                    </button>
                    <div x-show="open" class="px-5 pb-5 text-[var(--text-secondary)] text-sm leading-relaxed prose prose-invert">
                        <?= $product['description'] ?>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="border border-[var(--border)] rounded-2xl bg-[var(--card)] overflow-hidden">
                    <button @click="open = !open" class="flex items-center justify-between w-full p-5 font-['Manrope'] font-bold text-lg text-white">
                        Specifications <i data-lucide="chevron-down" class="w-5 h-5 transition-transform" :class="open ? 'rotate-180 text-[var(--gold)]' : ''"></i>
                    </button>
                    <div x-show="open" class="px-5 pb-5" style="display: none;">
                        <table class="w-full text-sm text-[var(--text-secondary)]">
                            <tbody>
                                <?php foreach ($product['specs'] as $key => $val): ?>
                                <tr class="border-t border-[var(--border)]">
                                    <td class="py-3 font-bold text-white"><?= htmlspecialchars($key) ?></td>
                                    <td class="py-3"><?= htmlspecialchars($val) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div x-data="{ open: false }" class="border border-[var(--border)] rounded-2xl bg-[var(--card)] overflow-hidden">
                    <button @click="open = !open" class="flex items-center justify-between w-full p-5 font-['Manrope'] font-bold text-lg text-white">
                        Shipping & Returns <i data-lucide="chevron-down" class="w-5 h-5 transition-transform" :class="open ? 'rotate-180 text-[var(--gold)]' : ''"></i>
                    </button>
                    <div x-show="open" class="px-5 pb-5 text-[var(--text-secondary)] text-sm leading-relaxed" style="display: none;">
                        <p class="mb-4">We offer nationwide delivery across Nigeria via our trusted logistics partners (DHL, GIG Logistics, RedStar).</p>
                        <ul class="list-disc pl-5 mb-4 space-y-2">
                            <li><strong class="text-white">Lagos:</strong> 1-2 Business Days</li>
                            <li><strong class="text-white">Abuja & PH:</strong> 2-3 Business Days</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     RELATED PRODUCTS
     ============================================================ -->
<section class="py-20 bg-[var(--bg-primary)]">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <h2 class="font-['Manrope'] text-3xl font-extrabold mb-10">You May Also Like</h2>
        
        <div class="flex sm:grid sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 overflow-x-auto pb-4 snap-x snap-mandatory no-scrollbar -mx-4 sm:mx-0 px-4 sm:px-0">
            <?php foreach ($related as $p): ?>
                <a href="/product/<?= $p['slug'] ?>"
                   class="group relative bg-[var(--card)] rounded-2xl overflow-hidden border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-300 hover:-translate-y-1 w-[280px] sm:w-auto flex-shrink-0 snap-start block">
                    <div class="aspect-square overflow-hidden bg-[var(--surface)] relative">
                        <img src="<?= $p['img'] ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <!-- Quick Actions -->
                        <div class="absolute bottom-0 left-0 right-0 p-3 flex gap-2 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <button class="flex-1 bg-[var(--gold)] text-black text-xs font-bold py-2.5 rounded-xl hover:bg-[#D4AF37]" onclick="event.preventDefault()">Add</button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold mb-2 line-clamp-1 group-hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($p['name']) ?></h3>
                        <p class="font-['Manrope'] font-bold text-[var(--gold)]">₦<?= number_format($p['price']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Mobile Sticky Action Area -->
<div class="sm:hidden fixed bottom-0 left-0 right-0 bg-[var(--surface)] border-t border-[var(--border)] p-4 z-40 pb-safe flex gap-3 shadow-[0_-10px_20px_rgba(0,0,0,0.5)]">
    <button class="flex-1 bg-[var(--gold)] text-black font-bold h-12 rounded-xl flex items-center justify-center gap-2">
        <i data-lucide="shopping-bag" class="w-4 h-4"></i> Add
    </button>
    <a href="/quote?product=<?= $product['id'] ?>" class="flex-1 bg-[var(--card)] text-white font-bold h-12 rounded-xl border border-[var(--border)] flex items-center justify-center gap-2">
        Quote
    </a>
</div>

<style>
    .no-scrollbar { scrollbar-width: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>

<script>
    // Dynamically offset page content below the fixed header
    function setProductPageOffset() {
        const header = document.querySelector('header');
        const content = document.getElementById('product-page-content');
        if (header && content) {
            content.style.paddingTop = (header.offsetHeight + 32) + 'px';
        }
    }

    // Run immediately, after fonts/images load, and on resize
    setProductPageOffset();
    window.addEventListener('load', setProductPageOffset);
    window.addEventListener('resize', setProductPageOffset);

    // Also re-run after Alpine has had a tick to render the announcement bar
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(setProductPageOffset, 50);
        setTimeout(setProductPageOffset, 200);
    });
</script>
