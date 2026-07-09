<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Products</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($products) ?> total products in catalogue</p>
    </div>
    <div class="flex items-center gap-3">
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2">
            <i data-lucide="upload" class="w-4 h-4"></i> Import CSV
        </button>
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2">
            <i data-lucide="download" class="w-4 h-4"></i> Export CSV
        </button>
        <a href="/admin/products/create" class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Product
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="bg-[#111] border border-[var(--border)] rounded-[14px] p-4 mb-6 flex flex-col md:flex-row gap-3" x-data="{ bulkMode: false, selected: [] }">
    <div class="relative flex-grow max-w-sm">
        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[var(--text-muted)]"></i>
        <input type="text" placeholder="Search by name, SKU…" class="input-field w-full pl-10 h-9 text-sm bg-[var(--surface)]">
    </div>
    <select class="input-field h-9 text-sm bg-[var(--surface)] pr-8 min-w-[140px]">
        <option value="">All Categories</option>
        <option>Stationery</option><option>Drinkware</option><option>Tech</option>
        <option>Apparel</option><option>Bags</option><option>Accessories</option>
    </select>
    <select class="input-field h-9 text-sm bg-[var(--surface)] pr-8 min-w-[130px]">
        <option value="">All Statuses</option>
        <option>Published</option><option>Draft</option><option>Archived</option>
    </select>
    <select class="input-field h-9 text-sm bg-[var(--surface)] pr-8 min-w-[130px]">
        <option value="">All Brands</option>
        <option>Moleskine</option><option>Thermos</option><option>Anker</option><option>Custom</option>
    </select>
</div>

<!-- Product Table -->
<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden" x-data="{ selected: [], allSelected: false }">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                    <th class="px-4 py-3 w-10">
                        <input type="checkbox" @click="allSelected = !allSelected" class="rounded border-[var(--border)] bg-[var(--surface)]">
                    </th>
                    <th class="px-4 py-3 font-medium">Product</th>
                    <th class="px-4 py-3 font-medium">SKU</th>
                    <th class="px-4 py-3 font-medium">Category</th>
                    <th class="px-4 py-3 font-medium text-right">Price</th>
                    <th class="px-4 py-3 font-medium text-center">Stock</th>
                    <th class="px-4 py-3 font-medium text-center">Status</th>
                    <th class="px-4 py-3 font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($products as $p): ?>
                <?php
                $statusCls = match($p['status']) {
                    'Published' => 'bg-green-500/10 text-green-400 border-green-500/20',
                    'Draft'     => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                    'Archived'  => 'bg-[var(--surface)] text-[var(--text-muted)] border-[var(--border)]',
                    default     => ''
                };
                $stockCls = $p['stock'] === 0 ? 'text-red-400' : ($p['stock'] <= 5 ? 'text-yellow-400' : 'text-green-400');
                ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group">
                    <td class="px-4 py-3">
                        <input type="checkbox" :checked="allSelected" class="rounded border-[var(--border)] bg-[var(--surface)]">
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-[8px] overflow-hidden bg-[var(--surface)] border border-[var(--border)] shrink-0">
                                <img src="<?= $p['image'] ?>" alt="" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <a href="/admin/products/<?= $p['id'] ?>/edit" class="font-medium text-sm hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($p['name']) ?></a>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-[var(--text-muted)] font-mono"><?= $p['sku'] ?></td>
                    <td class="px-4 py-3 text-sm text-[var(--text-secondary)]"><?= $p['category'] ?></td>
                    <td class="px-4 py-3 text-sm text-right">
                        <?php if ($p['sale_price']): ?>
                            <span class="font-bold text-[var(--gold)]">₦<?= number_format($p['sale_price']) ?></span>
                            <span class="text-[var(--text-muted)] line-through text-xs ml-1">₦<?= number_format($p['price']) ?></span>
                        <?php else: ?>
                            <span class="font-bold">₦<?= number_format($p['price']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-sm text-center font-bold <?= $stockCls ?>">
                        <?= $p['stock'] === 0 ? 'Out' : $p['stock'] ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $statusCls ?>"><?= $p['status'] ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="/admin/products/<?= $p['id'] ?>/edit" class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--gold)] hover:border-[var(--gold)]/50 transition-colors">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                            </a>
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-blue-400 hover:border-blue-400/50 transition-colors" title="Duplicate">
                                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                            </button>
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--danger)] hover:border-[var(--danger)]/50 transition-colors" title="Delete">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-[var(--border)] flex items-center justify-between text-sm text-[var(--text-muted)]">
        <span>Showing <?= count($products) ?> of <?= count($products) ?> products</span>
        <div class="flex items-center gap-1">
            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center hover:border-[var(--gold)]/50 transition-colors opacity-50 cursor-not-allowed">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>
            <button class="w-8 h-8 rounded-[6px] bg-[var(--gold)] text-[#111] font-bold flex items-center justify-center text-xs">1</button>
            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center hover:border-[var(--gold)]/50 transition-colors opacity-50 cursor-not-allowed">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</div>
