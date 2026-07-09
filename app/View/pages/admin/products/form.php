<?php $isEdit = !empty($product); ?>

<div class="flex items-center gap-4 mb-6">
    <a href="/admin/products" class="text-[var(--text-secondary)] hover:text-white transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold font-manrope"><?= $isEdit ? 'Edit: ' . htmlspecialchars($product['name']) : 'Add New Product' ?></h1>
        <p class="text-sm text-[var(--text-secondary)] mt-0.5"><?= $isEdit ? 'SKU: ' . $product['sku'] : 'Fill in the details below to create a new product' ?></p>
    </div>
    <div class="ml-auto flex items-center gap-3">
        <button type="button" class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)]">Save Draft</button>
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
                                    <option selected>Stationery</option><option>Drinkware</option><option>Tech</option>
                                    <option>Apparel</option><option>Bags</option><option>Accessories</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Short Description</label>
                            <textarea name="short_description" rows="2" placeholder="A brief summary that appears in product listings…" class="input-field w-full resize-none text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Full Description (Rich Text)</label>
                            <div class="border border-[var(--border)] rounded-[10px] overflow-hidden">
                                <div class="bg-[var(--surface)] border-b border-[var(--border)] flex items-center gap-1 p-2 flex-wrap">
                                    <?php foreach (['bold', 'italic', 'underline', 'list', 'link'] as $btn): ?>
                                    <button type="button" class="w-8 h-7 rounded-[4px] hover:bg-[var(--card)] text-[var(--text-secondary)] hover:text-white transition-colors flex items-center justify-center">
                                        <i data-lucide="<?= $btn ?>" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <?php endforeach; ?>
                                </div>
                                <textarea name="description" rows="8" placeholder="Detailed product description…" class="w-full bg-transparent p-4 text-sm focus:outline-none resize-none"></textarea>
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
                        <div><label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">MOQ (Min. Order)</label><input type="number" name="moq" value="10" min="1" class="input-field w-full text-sm"></div>
                        <div><label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Max Quantity</label><input type="number" name="max_quantity" placeholder="No limit" class="input-field w-full text-sm"></div>
                        <div><label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Brand</label><select name="brand_id" class="input-field w-full text-sm"><option>Custom</option><option>Moleskine</option><option>Thermos</option><option>Anker</option></select></div>
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
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Stock Status</label>
                        <select name="stock_status" class="input-field w-full">
                            <option value="in_stock">In Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                            <option value="on_backorder">On Backorder</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Low Stock Alert Threshold</label>
                        <input type="number" name="low_stock_threshold" value="10" min="0" class="input-field w-full">
                        <p class="text-xs text-[var(--text-muted)] mt-1">You'll receive an alert when stock falls below this number.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Availability</label>
                        <select name="availability" class="input-field w-full">
                            <option>Available for order</option>
                            <option>In-store pickup only</option>
                            <option>Pre-order only</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ========== TAB 4: MEDIA ========== -->
            <div x-show="tab === 'media'" style="display:none" x-data="mediaUpload()" class="space-y-6">
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                    <h2 class="text-lg font-bold font-manrope mb-2">Featured Image</h2>
                    <p class="text-sm text-[var(--text-secondary)] mb-6">The main image that appears on listing cards.</p>
                    <div class="border-2 border-dashed border-[var(--border)] rounded-[14px] p-10 text-center hover:border-[var(--gold)]/50 transition-colors cursor-pointer group" @click="$refs.featuredInput.click()">
                        <i data-lucide="image-plus" class="w-10 h-10 text-[var(--text-muted)] group-hover:text-[var(--gold)] transition-colors mx-auto mb-3"></i>
                        <p class="font-medium text-sm mb-1">Click or drag to upload</p>
                        <p class="text-xs text-[var(--text-muted)]">JPG, PNG, WebP — Min 800×800px recommended</p>
                        <input x-ref="featuredInput" type="file" name="featured_image" accept="image/*" class="sr-only">
                    </div>
                </div>
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                    <h2 class="text-lg font-bold font-manrope mb-2">Image Gallery</h2>
                    <p class="text-sm text-[var(--text-secondary)] mb-6">Add multiple images. Drag to reorder. WebP versions are generated automatically.</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4" id="gallery-grid">
                        <?php if ($isEdit): foreach ($product['images'] ?? [] as $img): ?>
                        <div class="aspect-square rounded-[10px] overflow-hidden border border-[var(--border)] relative group cursor-grab">
                            <img src="<?= $img ?>" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity gap-2">
                                <button type="button" class="w-8 h-8 rounded-full bg-[var(--danger)] text-white flex items-center justify-center"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </div>
                        </div>
                        <?php endforeach; endif; ?>
                        <!-- Upload slot -->
                        <div class="aspect-square rounded-[10px] border-2 border-dashed border-[var(--border)] flex items-center justify-center text-[var(--text-muted)] hover:border-[var(--gold)]/50 hover:text-[var(--gold)] transition-colors cursor-pointer" @click="$refs.galleryInput.click()">
                            <i data-lucide="plus" class="w-8 h-8"></i>
                        </div>
                    </div>
                    <input x-ref="galleryInput" type="file" name="gallery[]" accept="image/*" multiple class="sr-only">
                </div>
            </div>

            <!-- ========== TAB 5: SEO ========== -->
            <div x-show="tab === 'seo'" style="display:none" class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-lg font-bold font-manrope mb-6">SEO Settings</h2>
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Meta Title</label>
                        <input type="text" name="meta_title" placeholder="60 characters recommended" maxlength="80" class="input-field w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Meta Description</label>
                        <textarea name="meta_description" rows="3" placeholder="160 characters recommended" maxlength="200" class="input-field w-full resize-none text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Keywords</label>
                        <input type="text" name="keywords" placeholder="corporate gifts, branded notebooks, premium stationery…" class="input-field w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Canonical URL</label>
                        <input type="url" name="canonical_url" placeholder="https://marigoldsignature.com/shop/product-name" class="input-field w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Open Graph Image</label>
                        <div class="flex items-center gap-3">
                            <input type="url" name="og_image" placeholder="Image URL or upload…" class="input-field flex-grow text-sm">
                            <button type="button" class="btn btn-secondary border border-[var(--border)] h-10 px-4 text-sm bg-[var(--surface)] shrink-0">Browse</button>
                        </div>
                    </div>
                </div>
                <!-- Preview -->
                <div class="mt-8 p-4 bg-white rounded-[10px]">
                    <p class="text-[10px] text-gray-400 mb-2 font-mono">Search Result Preview</p>
                    <p class="text-blue-700 text-sm font-medium truncate">Executive Leather Notebook | Marigold Signature</p>
                    <p class="text-green-700 text-xs">marigoldsignature.com › shop › executive-leather-notebook</p>
                    <p class="text-gray-600 text-xs mt-0.5 line-clamp-2">Premium corporate gifting with this executive leather set. Perfect for onboarding, client appreciation, or board meetings.</p>
                </div>
            </div>

            <!-- ========== TAB 6: RELATED ========== -->
            <div x-show="tab === 'related'" style="display:none" class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-lg font-bold font-manrope mb-6">Related Products</h2>
                <?php foreach (['Cross-sell' => 'Shown at checkout', 'Upsell' => 'Shown on product page', 'Accessories' => 'Complementary items'] as $type => $desc): ?>
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="font-semibold"><?= $type ?></h3>
                            <p class="text-xs text-[var(--text-secondary)]"><?= $desc ?></p>
                        </div>
                    </div>
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[var(--text-muted)]"></i>
                        <input type="text" placeholder="Search and select products…" class="input-field w-full pl-10 text-sm">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ========== TAB 7: PUBLISHING ========== -->
            <div x-show="tab === 'publishing'" style="display:none" class="space-y-6">
                <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                    <h2 class="text-lg font-bold font-manrope mb-6">Publishing Settings</h2>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Status</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" x-data="{ status: 'published' }">
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
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Publish Date (for Scheduled)</label>
                            <input type="datetime-local" name="publish_at" class="input-field w-full text-sm">
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
function mediaUpload() { return {}; }
function generateSlug(v) {
    const slug = v.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    // Would update slug input here
}
</script>
