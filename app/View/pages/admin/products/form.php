<?php $isEdit = !empty($product); ?>

<div class="flex items-center gap-4 mb-6">
    <a href="<?= app_url('/admin/products') ?>" class="text-[var(--text-secondary)] hover:text-white transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold font-manrope"><?= $isEdit ? 'Edit: ' . htmlspecialchars($product['name']) : 'Add New Product' ?></h1>
        <p class="text-sm text-[var(--text-secondary)] mt-0.5"><?= $isEdit ? 'SKU: ' . $product['sku'] : 'Fill in the details below to create a new product' ?></p>
    </div>
    <div class="ml-auto flex items-center gap-3">
        <button type="submit" form="product-form" name="save_draft" value="1" class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)]">Save Draft</button>
        <button type="submit" form="product-form" class="btn btn-primary h-9 px-6 text-sm">Publish Product</button>
    </div>
</div>

<div x-data="{ tab: 'general' }" class="grid grid-cols-1 xl:grid-cols-4 gap-6">

    <!-- Tab Navigation (Left Sidebar) -->
    <div class="xl:col-span-1">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-2 space-y-0.5 sticky top-[80px]">
            <?php
            $tabs = [
                ['id' => 'general',   'icon' => 'file-text',    'label' => 'General'],
                ['id' => 'pricing',   'icon' => 'tag',          'label' => 'Pricing'],
                ['id' => 'inventory', 'icon' => 'package',      'label' => 'Inventory'],
                ['id' => 'media',     'icon' => 'image',        'label' => 'Media'],
                ['id' => 'seo',       'icon' => 'search',       'label' => 'SEO'],
                ['id' => 'related',   'icon' => 'git-branch',   'label' => 'Related Products'],
                ['id' => 'publishing','icon' => 'globe',        'label' => 'Publishing'],
            ];
            foreach ($tabs as $t):
            ?>
            <button @click="tab = '<?= $t['id'] ?>'"
                    :class="tab === '<?= $t['id'] ?>' ? 'bg-[var(--gold)]/10 text-[var(--gold)]' : 'text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-[8px] transition-colors text-sm text-left font-medium">
                <i data-lucide="<?= $t['icon'] ?>" class="w-4 h-4 shrink-0"></i>
                <?= $t['label'] ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Form Content Area -->
    <div class="xl:col-span-3">
        <form id="product-form" action="<?= $isEdit ? '/admin/products/' . $product['id'] : '/admin/products' ?>" method="POST" enctype="multipart/form-data">
            <?= \App\Core\CSRF::field() ?>

            <!-- ========== TAB 1: GENERAL ========== -->
            <div x-show="tab === 'general'" class="space-y-6">
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                    <h2 class="text-lg font-bold font-manrope mb-6">General Information</h2>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Product Name <span class="text-[var(--danger)]">*</span></label>
                            <input type="text" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" placeholder="e.g. Executive Leather Notebook" class="input-field w-full" required
                                   x-ref="nameInput" @input="generateSlug($el.value)">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Slug (URL)</label>
                                <div class="relative">
                                    <input type="text" name="slug" x-ref="slugInput" value="<?= isset($product) ? strtolower(str_replace(' ', '-', $product['name'])) : '' ?>" placeholder="auto-generated" class="input-field w-full pr-24 font-mono text-sm">
                                    <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-[var(--gold)] hover:text-white transition-colors px-2 py-1">Regenerate</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">SKU</label>
                                <input type="text" name="sku" value="<?= htmlspecialchars($product['sku'] ?? '') ?>" placeholder="e.g. MS-EXEC-001" class="input-field w-full font-mono text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Barcode / ISBN</label>
                                <input type="text" name="barcode" placeholder="EAN / UPC barcode" class="input-field w-full font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Category</label>
                                <select name="category_id" class="input-field w-full">
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories ?? [] as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (int)($product['category_id'] ?? 0) === (int)$cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Short Description</label>
                            <textarea name="short_description" rows="2" placeholder="A brief summary that appears in product listings…" class="input-field w-full resize-none text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Full Description (Rich Text)</label>
                            <div x-data="richText()" class="border border-[var(--border)] rounded-[10px] overflow-hidden">
                                <div class="bg-[var(--surface)] border-b border-[var(--border)] flex items-center gap-1 p-2 flex-wrap">
                                    <button type="button" data-cmd="bold" @click.prevent="cmd('bold')" title="Bold" class="rte-btn">
                                        <i data-lucide="bold" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" data-cmd="italic" @click.prevent="cmd('italic')" title="Italic" class="rte-btn">
                                        <i data-lucide="italic" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" data-cmd="underline" @click.prevent="cmd('underline')" title="Underline" class="rte-btn">
                                        <i data-lucide="underline" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <span class="w-px h-5 bg-[var(--border)] mx-1"></span>
                                    <button type="button" data-cmd="insertUnorderedList" @click.prevent="cmd('insertUnorderedList')" title="Bullet list" class="rte-btn">
                                        <i data-lucide="list" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" data-cmd="link" @click.prevent="link()" title="Insert link" class="rte-btn">
                                        <i data-lucide="link" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                                <div contenteditable="true" x-ref="editable" @input="sync()" x-init="init()"
                                     class="w-full bg-transparent p-4 text-sm focus:outline-none min-h-[180px] leading-relaxed rte-body">
                                    <?= $product['description'] ?? '' ?>
                                </div>
                                <input type="hidden" name="description" x-ref="descriptionInput">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                    <h2 class="text-lg font-bold font-manrope mb-6">Specifications & Details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div><label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Materials</label><input type="text" name="materials" placeholder="e.g. Genuine Leather" class="input-field w-full text-sm"></div>
                        <div><label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Dimensions</label><input type="text" name="dimensions" placeholder="e.g. 14.8 × 21 cm" class="input-field w-full text-sm"></div>
                        <div><label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Weight</label><input type="text" name="weight" placeholder="e.g. 320g" class="input-field w-full text-sm"></div>
                        <div><label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">MOQ (Min. Order)</label><input type="number" name="moq" value="<?= $product['minimum_order_quantity'] ?? 10 ?>" min="1" class="input-field w-full text-sm"></div>
                        <div><label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Max Quantity</label><input type="number" name="max_quantity" value="<?= $product['maximum_order_quantity'] ?? '' ?>" placeholder="No limit" class="input-field w-full text-sm"></div>
                        <div><label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Brand</label><select name="brand_id" class="input-field w-full text-sm">
                            <option value="">Custom / No Brand</option>
                            <?php foreach ($brands ?? [] as $b): ?>
                            <option value="<?= $b['id'] ?>" <?= (int)($product['brand_id'] ?? 0) === (int)$b['id'] ? 'selected' : '' ?>><?= htmlspecialchars($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select></div>
                        <div><label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Badge (e.g. Bestseller)</label><input type="text" name="badge" value="<?= htmlspecialchars($product['badge'] ?? '') ?>" placeholder="e.g. Bestseller" class="input-field w-full text-sm"></div>
                    </div>
                </div>
            </div>

            <!-- ========== TAB 2: PRICING ========== -->
            <div x-show="tab === 'pricing'" style="display:none" class="space-y-6">
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                    <h2 class="text-lg font-bold font-manrope mb-6">Pricing</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Regular Price (₦) <span class="text-[var(--danger)]">*</span></label>
                            <input type="number" name="price" value="<?= $product['price'] ?? '' ?>" placeholder="0.00" min="0" step="0.01" class="input-field w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Sale Price (₦)</label>
                            <input type="number" name="sale_price" value="<?= $product['sale_price'] ?? '' ?>" placeholder="Leave blank for no sale" min="0" step="0.01" class="input-field w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Tax Class</label>
                            <select name="tax_class" class="input-field w-full"><option>Standard Rate (VAT 7.5%)</option><option>Zero Rate</option><option>Exempt</option></select>
                        </div>
                    </div>
                </div>
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6" x-data="{ tiers: [{min:10,max:49,price:''},{min:50,max:199,price:''},{min:200,max:'',price:''}] }">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-bold font-manrope">Bulk Pricing Tiers</h2>
                            <p class="text-sm text-[var(--text-secondary)] mt-1">Volume discounts applied automatically at checkout.</p>
                        </div>
                        <button type="button" @click="tiers.push({min:'',max:'',price:''})" class="btn btn-secondary border border-[var(--border)] h-8 px-3 text-xs bg-[var(--surface)]">+ Add Tier</button>
                    </div>
                    <div class="space-y-3">
                        <template x-for="(tier, i) in tiers" :key="i">
                            <div class="grid grid-cols-4 gap-3 items-end">
                                <div><label class="block text-xs text-[var(--text-muted)] mb-1">Min Qty</label><input type="number" x-model="tier.min" class="input-field w-full text-sm" placeholder="10"></div>
                                <div><label class="block text-xs text-[var(--text-muted)] mb-1">Max Qty</label><input type="number" x-model="tier.max" class="input-field w-full text-sm" placeholder="49"></div>
                                <div><label class="block text-xs text-[var(--text-muted)] mb-1">Price (₦)</label><input type="number" x-model="tier.price" class="input-field w-full text-sm" placeholder="0.00"></div>
                                <button type="button" @click="tiers.splice(i,1)" class="h-10 w-10 rounded-[8px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--danger)]/60 hover:text-[var(--danger)] hover:border-[var(--danger)]/50 transition-colors mb-0.5">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6 space-y-4">
                    <h2 class="text-lg font-bold font-manrope mb-2">Special Options</h2>
                    <label class="flex items-center justify-between cursor-pointer">
                        <div><p class="font-medium text-sm">Quote-Only Product</p><p class="text-xs text-[var(--text-secondary)]">Hide price, replace Add-to-Cart with Request Quote CTA</p></div>
                        <div class="relative"><input type="checkbox" name="quote_only" class="sr-only peer"><div class="w-11 h-6 bg-[var(--surface)] rounded-full peer-checked:bg-[var(--gold)] transition-colors border border-[var(--border)]"></div><div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform shadow"></div></div>
                    </label>
                    <label class="flex items-center justify-between cursor-pointer">
                        <div><p class="font-medium text-sm">Pre-Order</p><p class="text-xs text-[var(--text-secondary)]">Accept orders before stock is available</p></div>
                        <div class="relative"><input type="checkbox" name="pre_order" class="sr-only peer"><div class="w-11 h-6 bg-[var(--surface)] rounded-full peer-checked:bg-[var(--gold)] transition-colors border border-[var(--border)]"></div><div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform shadow"></div></div>
                    </label>
                </div>
            </div>

            <!-- ========== TAB 3: INVENTORY ========== -->
            <div x-show="tab === 'inventory'" style="display:none" class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-lg font-bold font-manrope mb-6">Inventory Management</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Stock Quantity</label>
                        <input type="number" name="stock" value="<?= $product['stock'] ?? '' ?>" placeholder="0" min="0" class="input-field w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Availability</label>
                        <select name="availability" class="input-field w-full">
                            <?php
                            $avail = $product['availability'] ?? 'in_stock';
                            $opts = [
                                'in_stock'     => 'Available for order',
                                'store_pickup' => 'In-store pickup only',
                                'preorder'     => 'Pre-order only',
                            ];
                            foreach ($opts as $val => $label): ?>
                                <option value="<?= $val ?>" <?= $avail === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ========== TAB 4: MEDIA ========== -->
            <div x-show="tab === 'media'" style="display:none" class="space-y-6"
                 x-data="mediaUpload(<?= htmlspecialchars(json_encode([
                     'featured' => $product['image'] ?? '',
                     'gallery'  => $product['images'] ?? [],
                 ]), ENT_QUOTES, 'UTF-8') ?>)">
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                    <h2 class="text-lg font-bold font-manrope mb-2">Featured Image</h2>
                    <p class="text-sm text-[var(--text-secondary)] mb-6">The main image that appears on listing cards.</p>
                    <div class="flex flex-col sm:flex-row gap-5 items-start">
                        <div class="w-52 aspect-square rounded-[14px] overflow-hidden border border-[var(--border)] bg-[var(--surface)] flex items-center justify-center text-[var(--text-muted)]">
                            <template x-if="featuredPreview">
                                <img :src="featuredPreview" class="w-full h-full object-cover" alt="Featured image preview">
                            </template>
                            <template x-if="!featuredPreview">
                                <div class="text-center p-4">
                                    <i data-lucide="image-plus" class="w-8 h-8 mx-auto mb-2"></i>
                                    <p class="text-xs">No image yet</p>
                                </div>
                            </template>
                        </div>
                        <div class="flex-1">
                            <div class="border-2 border-dashed border-[var(--border)] rounded-[14px] p-8 text-center hover:border-[var(--gold)]/50 transition-colors cursor-pointer group" @click="pickFeatured()">
                                <p class="font-medium text-sm mb-1">Click to upload or replace</p>
                                <p class="text-xs text-[var(--text-muted)]">JPG, PNG, WebP — up to 5MB</p>
                            </div>
                            <button type="button" x-show="featuredPreview" @click="featuredPreview = ''; $refs.featuredInput.value = ''; $refs.imageUrl.value = ''" class="mt-3 text-xs text-[var(--danger)]/70 hover:text-[var(--danger)] transition-colors">Remove featured image</button>
                            <input x-ref="featuredInput" type="file" name="featured_image" accept="image/*" @change="onFeaturedSelected($event)" class="sr-only">
                        </div>
                    </div>
                    <input x-ref="imageUrl" type="hidden" name="image_url" value="<?= htmlspecialchars($product['image'] ?? '') ?>">
                </div>
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                    <h2 class="text-lg font-bold font-manrope mb-2">Image Gallery</h2>
                    <p class="text-sm text-[var(--text-secondary)] mb-6">Add multiple images — a preview appears instantly. The first image is used as the card image.</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                        <template x-for="(g, i) in gallery" :key="i">
                            <div class="aspect-square rounded-[10px] overflow-hidden border border-[var(--border)] relative group">
                                <img :src="g.src" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" @click="removeGallery(i)" class="w-8 h-8 rounded-full bg-[var(--danger)] text-white flex items-center justify-center" title="Remove">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <div class="aspect-square rounded-[10px] border-2 border-dashed border-[var(--border)] flex items-center justify-center text-[var(--text-muted)] hover:border-[var(--gold)]/50 hover:text-[var(--gold)] transition-colors cursor-pointer" @click="pickGallery()">
                            <i data-lucide="plus" class="w-8 h-8"></i>
                        </div>
                    </div>
                    <input x-ref="galleryInput" type="file" name="gallery[]" accept="image/*" multiple @change="onGallerySelected($event)" class="sr-only">
                    <p class="text-xs text-[var(--text-muted)]">New uploads are appended to the gallery when you save. Removing a preview here only hides it for this session.</p>
                </div>
            </div>

            <!-- ========== TAB 5: SEO ========== -->
            <div x-show="tab === 'seo'" style="display:none" x-data="seoFields()" class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-lg font-bold font-manrope mb-6">SEO Settings</h2>
                <p class="text-xs text-[var(--text-muted)] -mt-4 mb-6">Leave a field blank and it will be generated automatically from the product name and description when you save.</p>
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Meta Title</label>
                        <input type="text" name="meta_title" x-model="metaTitle" value="<?= htmlspecialchars($product['meta_title'] ?? '') ?>" placeholder="Auto-generated: Product Name | Marigold Signature" maxlength="80" class="input-field w-full">
                        <p class="text-xs text-[var(--text-muted)] mt-1"><span x-text="metaTitle.length"></span>/80 characters</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Meta Description</label>
                        <textarea name="meta_description" x-model="metaDesc" rows="3" value="<?= htmlspecialchars($product['meta_description'] ?? '') ?>" placeholder="Auto-generated from the short description" maxlength="200" class="input-field w-full resize-none text-sm"></textarea>
                        <p class="text-xs text-[var(--text-muted)] mt-1"><span x-text="metaDesc.length"></span>/200 characters</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Keywords</label>
                        <input type="text" name="keywords" value="<?= htmlspecialchars($product['keywords'] ?? '') ?>" placeholder="corporate gifts, branded notebooks, premium stationery…" class="input-field w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Canonical URL</label>
                        <input type="url" name="canonical_url" value="<?= htmlspecialchars($product['canonical_url'] ?? '') ?>" placeholder="https://marigoldsignatureng.com/shop/product-name" class="input-field w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Open Graph Image</label>
                        <div class="flex items-center gap-3">
                            <input type="url" name="og_image" value="<?= htmlspecialchars($product['og_image'] ?? '') ?>" placeholder="Image URL or upload…" class="input-field flex-grow text-sm">
                            <button type="button" class="btn btn-secondary border border-[var(--border)] h-10 px-4 text-sm bg-[var(--surface)] shrink-0">Browse</button>
                        </div>
                    </div>
                </div>
                <!-- Live preview -->
                <div class="mt-8 p-4 bg-white rounded-[10px]">
                    <p class="text-[10px] text-gray-400 mb-2 font-mono">Search Result Preview</p>
                    <p class="text-blue-700 text-sm font-medium truncate" x-text="metaTitle || '<?= htmlspecialchars(($product['name'] ?? 'Product Name') . ' | Marigold Signature', ENT_QUOTES) ?>'"></p>
                    <p class="text-green-700 text-xs">marigoldsignatureng.com › shop › <?= htmlspecialchars($product['slug'] ?? strtolower(str_replace(' ', '-', $product['name'] ?? 'product-name'))) ?></p>
                    <p class="text-gray-600 text-xs mt-0.5 line-clamp-2" x-text="metaDesc || 'Auto-generated from the product description.'"></p>
                </div>
            </div>

            <!-- ========== TAB 6: RELATED ========== -->
            <div x-show="tab === 'related'" style="display:none" class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-lg font-bold font-manrope mb-2">Related Products</h2>
                <p class="text-sm text-[var(--text-secondary)]">Related products are chosen automatically on the storefront — no manual setup required.</p>
                <div class="mt-6 space-y-5">
                    <?php foreach (['Same category first' => 'Products in the same category are shown first, most relevant first.', 'Same brand next' => 'If fewer than four exist in the category, same-brand items fill the row.', 'Catalogue fill' => 'Any remaining slots are filled from the rest of the catalogue by price similarity.'] as $title => $desc): ?>
                    <div class="flex items-start gap-4 p-4 rounded-[10px] border border-[var(--border)] bg-[var(--surface)]">
                        <div class="w-8 h-8 rounded-full bg-[var(--gold)]/10 text-[var(--gold)] flex items-center justify-center shrink-0"><i data-lucide="sparkles" class="w-4 h-4"></i></div>
                        <div>
                            <p class="font-semibold text-sm"><?= $title ?></p>
                            <p class="text-xs text-[var(--text-secondary)] mt-0.5"><?= $desc ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ========== TAB 7: PUBLISHING ========== -->
            <div x-show="tab === 'publishing'" style="display:none" class="space-y-6">
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                    <h2 class="text-lg font-bold font-manrope mb-6">Publishing Settings</h2>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Status</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" x-data="{ status: '<?= strtolower($product['status'] ?? 'published') ?>' }">
                                <?php foreach (['published' => 'Published', 'draft' => 'Draft', 'scheduled' => 'Scheduled', 'hidden' => 'Hidden', 'archived' => 'Archived'] as $val => $label): ?>
                                <label class="flex items-center gap-3 p-3 rounded-[10px] border cursor-pointer transition-colors"
                                       :class="status === '<?= $val ?>' ? 'border-[var(--gold)]/50 bg-[var(--gold)]/5' : 'border-[var(--border)] bg-[var(--surface)] hover:border-[var(--gold)]/30'">
                                    <input type="radio" name="status" value="<?= $val ?>" x-model="status" class="sr-only">
                                    <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0"
                                         :class="status === '<?= $val ?>' ? 'border-[var(--gold)]' : 'border-[var(--border)]'">
                                        <div x-show="status === '<?= $val ?>'" class="w-2 h-2 rounded-full bg-[var(--gold)]"></div>
                                    </div>
                                    <span class="text-sm font-medium"><?= $label ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                            <p class="text-xs text-[var(--text-muted)] mt-1.5">Scheduled posts are saved as drafts until published from the list.</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <div><p class="font-medium text-sm">Feature on Homepage</p><p class="text-xs text-[var(--text-secondary)]">Show this product in the home "Featured Gifts" grid</p></div>
                                <label class="relative cursor-pointer shrink-0">
                                    <input type="checkbox" name="is_featured" value="1" <?= !empty($product['is_featured']) ? 'checked' : '' ?> class="sr-only peer">
                                    <div class="w-11 h-6 bg-[var(--surface)] rounded-full peer-checked:bg-[var(--gold)] transition-colors border border-[var(--border)]"></div>
                                    <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform shadow"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold">Save & Publish</h3>
                        <p class="text-sm text-[var(--text-secondary)]">Once published, product will be visible in the shop.</p>
                    </div>
                    <button type="submit" class="btn btn-primary h-11 px-8">Publish Now</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
/* Rich text toolbar — operates on the contenteditable body, syncs to the
   hidden `description` input which is what gets saved. */
function richText() {
    return {
        init() {
            this.$refs.descriptionInput.value = this.$refs.editable.innerHTML;
        },
        sync() {
            this.$refs.descriptionInput.value = this.$refs.editable.innerHTML;
        },
        cmd(cmd) {
            this.$refs.editable.focus();
            document.execCommand(cmd, false, null);
            this.sync();
        },
        link() {
            const url = window.prompt('Enter the link URL (https://...)');
            if (!url) return;
            this.$refs.editable.focus();
            document.execCommand('createLink', false, url);
            this.sync();
        },
    };
}

/* Media tab — live previews for featured + gallery uploads. */
function mediaUpload(config) {
    config = config || {};
    const normalize = (src) => (src && !/^(https?:|data:|blob:)/.test(src) ? appUrl(src) : src);
    return {
        featuredPreview: config.featured ? normalize(config.featured) : '',
        gallery: (config.gallery || []).map((src) => ({ src: normalize(src) })),
        pickFeatured() { this.$refs.featuredInput.click(); },
        onFeaturedSelected(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => { this.featuredPreview = ev.target.result; };
            reader.readAsDataURL(file);
        },
        pickGallery() { this.$refs.galleryInput.click(); },
        onGallerySelected(e) {
            const files = Array.from(e.target.files || []);
            files.forEach((file) => {
                if (!/^image\//.test(file.type)) return;
                const reader = new FileReader();
                reader.onload = (ev) => { this.gallery.push({ src: ev.target.result }); };
                reader.readAsDataURL(file);
            });
            e.target.value = '';
        },
        removeGallery(i) { this.gallery.splice(i, 1); },
    };
}

/* SEO tab — character counts + live search preview. */
function seoFields() {
    return {
        metaTitle: '',
        metaDesc: '',
        init() {
            const t = this.$root.querySelector('input[name="meta_title"]');
            const d = this.$root.querySelector('textarea[name="meta_description"]');
            this.metaTitle = t ? t.value : '';
            this.metaDesc = d ? d.value : '';
        },
    };
}

/* Slug auto-generation from the product name. */
let slugTouched = false;
function generateSlug(v) {
    const slug = v.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    const slugInput = document.querySelector('input[name="slug"]');
    if (slugInput && !slugTouched) {
        slugInput.value = slug;
    }
}
document.addEventListener('DOMContentLoaded', function () {
    const slugInput = document.querySelector('input[name="slug"]');
    if (slugInput) {
        slugInput.addEventListener('input', function () { slugTouched = slugInput.value !== ''; });
        slugInput.addEventListener('blur', function () { if (slugInput.value === '') slugTouched = false; });
    }
});
</script>
