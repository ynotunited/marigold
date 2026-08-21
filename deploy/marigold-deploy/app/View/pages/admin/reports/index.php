<!-- Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Reports & Analytics</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1">Comprehensive business metrics and exports</p>
    </div>
    <div class="flex items-center gap-3">
        <select class="input-field h-9 text-sm bg-[var(--surface)] border-[var(--border)] pr-8"><option>Last 7 Days</option><option selected>Last 30 Days</option><option>This Year</option><option>Custom Range</option></select>
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2"><i data-lucide="download" class="w-4 h-4"></i> Export CSV</button>
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2"><i data-lucide="printer" class="w-4 h-4"></i> PDF Report</button>
    </div>
</div>

<!-- Tabs -->
<div x-data="{ tab: 'Overview' }">
<div class="flex items-center gap-6 border-b border-[var(--border)] mb-6 overflow-x-auto hide-scrollbar">
    <?php foreach (['Overview', 'Sales & Revenue', 'Customers & Quotes', 'Products & Categories', 'Marketing'] as $t): ?>
    <button @click="tab = '<?= $t ?>'" :class="tab === '<?= $t ?>' ? 'text-white border-[var(--gold)] border-b-2' : 'text-[var(--text-secondary)] hover:text-white'" class="pb-3 text-sm font-medium transition-colors whitespace-nowrap"><?= $t ?></button>
    <?php endforeach; ?>
</div>

<!-- ================= OVERVIEW ================= -->
<div class="space-y-6" x-show="tab === 'Overview'">

    <!-- Top KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Total Revenue</p>
            <p class="text-2xl font-bold font-manrope text-[var(--gold)]"><?= money_format($metrics['total_revenue'], 'NGN') ?></p>
            <p class="text-xs text-green-400 mt-2 flex items-center gap-1"><i data-lucide="trending-up" class="w-3 h-3"></i> paid orders</p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Orders</p>
            <p class="text-2xl font-bold font-manrope"><?= number_format($metrics['orders_count']) ?></p>
            <p class="text-xs text-green-400 mt-2 flex items-center gap-1"><i data-lucide="trending-up" class="w-3 h-3"></i> all time</p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Avg. Order Value</p>
            <p class="text-2xl font-bold font-manrope"><?= money_format($metrics['avg_order_value'], 'NGN') ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Quotes Converted</p>
            <p class="text-2xl font-bold font-manrope text-[var(--gold)]"><?= $metrics['quotes_converted'] ?> <span class="text-sm font-normal text-[var(--text-muted)]">(<?= $metrics['quotes_rate'] ?>%)</span></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">New Customers</p>
            <p class="text-2xl font-bold font-manrope"><?= $metrics['new_customers'] ?></p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <div class="flex justify-between items-center mb-6"><h3 class="font-bold font-manrope">Revenue Trend</h3></div>
            <div class="h-64 relative w-full"><canvas id="revenueChart"></canvas></div>
        </div>
        <div class="lg:col-span-1 bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="font-bold font-manrope mb-6">Sales by Category</h3>
            <div class="h-48 relative w-full flex justify-center"><canvas id="categoryChart"></canvas></div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)] flex justify-between items-center"><h3 class="font-bold font-manrope">Top Selling Products</h3></div>
            <table class="w-full text-left whitespace-nowrap">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)]">
                    <th class="px-5 py-3">Product</th><th class="px-5 py-3 text-center">Units Sold</th><th class="px-5 py-3 text-right">Revenue generated</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php if (empty($topProducts)): ?>
                    <tr><td colspan="3" class="px-5 py-8 text-center text-sm text-[var(--text-muted)]">No sales data yet</td></tr>
                    <?php else: ?>
                    <?php foreach ($topProducts as $p): ?>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium"><?= htmlspecialchars($p['name']) ?></td>
                        <td class="px-5 py-3 text-center text-sm"><?= number_format($p['sold']) ?></td>
                        <td class="px-5 py-3 text-right text-sm text-[var(--gold)] font-bold"><?= money_format($p['revenue'], 'NGN') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)] flex justify-between items-center"><h3 class="font-bold font-manrope">Customer Acquisition</h3></div>
            <table class="w-full text-left whitespace-nowrap">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)]">
                    <th class="px-5 py-3">Customer</th><th class="px-5 py-3 text-center">Orders</th><th class="px-5 py-3 text-right">Total Spent</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php if (empty($customers)): ?>
                    <tr><td colspan="3" class="px-5 py-8 text-center text-sm text-[var(--text-muted)]">No registered customers yet</td></tr>
                    <?php else: ?>
                    <?php foreach ($customers as $c): ?>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium"><?= htmlspecialchars($c['name']) ?><span class="block text-xs text-[var(--text-muted)]"><?= htmlspecialchars($c['email']) ?></span></td>
                        <td class="px-5 py-3 text-center text-sm"><?= $c['orders'] ?></td>
                        <td class="px-5 py-3 text-right text-sm font-bold"><?= money_format($c['spent'], 'NGN') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= SALES & REVENUE ================= -->
<div class="space-y-6" x-show="tab === 'Sales & Revenue'" x-cloak>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Total Revenue</p>
            <p class="text-2xl font-bold font-manrope text-[var(--gold)]"><?= money_format($metrics['total_revenue'], 'NGN') ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Total Orders</p>
            <p class="text-2xl font-bold font-manrope"><?= number_format($metrics['orders_count']) ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Avg. Order Value</p>
            <p class="text-2xl font-bold font-manrope"><?= money_format($metrics['avg_order_value'], 'NGN') ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Payment Methods</p>
            <p class="text-2xl font-bold font-manrope"><?= count($paymentMethods) ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <div class="flex justify-between items-center mb-6"><h3 class="font-bold font-manrope">Revenue Trend (Paid)</h3></div>
            <div class="h-64 relative w-full"><canvas id="revenueChartSales"></canvas></div>
        </div>
        <div class="lg:col-span-1 bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)]"><h3 class="font-bold font-manrope">Payment Methods</h3></div>
            <table class="w-full text-left whitespace-nowrap">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)]">
                    <th class="px-5 py-3">Method</th><th class="px-5 py-3 text-center">Orders</th><th class="px-5 py-3 text-right">Total</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php if (empty($paymentMethods)): ?>
                    <tr><td colspan="3" class="px-5 py-8 text-center text-sm text-[var(--text-muted)]">No orders yet</td></tr>
                    <?php else: ?>
                    <?php foreach ($paymentMethods as $m): ?>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium"><?= htmlspecialchars($m['method']) ?></td>
                        <td class="px-5 py-3 text-center text-sm"><?= $m['count'] ?></td>
                        <td class="px-5 py-3 text-right text-sm text-[var(--gold)] font-bold"><?= money_format($m['total'], 'NGN') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
        <div class="p-5 border-b border-[var(--border)]"><h3 class="font-bold font-manrope">Orders by Status</h3></div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 p-5">
            <?php $statusColors = ['Pending'=>'bg-yellow-500/10 text-yellow-400', 'Processing'=>'bg-blue-500/10 text-blue-400', 'Completed'=>'bg-green-500/10 text-green-400', 'Cancelled'=>'bg-red-500/10 text-red-400', 'Refunded'=>'bg-purple-500/10 text-purple-400']; ?>
            <?php if (empty($orderStatuses)): ?>
            <p class="text-sm text-[var(--text-muted)] col-span-full">No orders yet</p>
            <?php else: ?>
            <?php foreach ($orderStatuses as $os): ?>
            <div class="rounded-[12px] <?= $statusColors[$os['status']] ?? 'bg-[var(--surface)] text-[var(--text-secondary)]' ?> p-4 text-center">
                <p class="text-2xl font-bold font-manrope"><?= $os['count'] ?></p>
                <p class="text-xs mt-1"><?= $os['status'] ?></p>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ================= CUSTOMERS & QUOTES ================= -->
<div class="space-y-6" x-show="tab === 'Customers & Quotes'" x-cloak>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Registered Customers</p>
            <p class="text-2xl font-bold font-manrope"><?= $metrics['new_customers'] ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Total Quotes</p>
            <p class="text-2xl font-bold font-manrope"><?= $metrics['quotes_total'] ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Quotes Converted</p>
            <p class="text-2xl font-bold font-manrope text-[var(--gold)]"><?= $metrics['quotes_converted'] ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Conversion Rate</p>
            <p class="text-2xl font-bold font-manrope"><?= $metrics['quotes_rate'] ?>%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)]"><h3 class="font-bold font-manrope">Top Customers</h3></div>
            <table class="w-full text-left whitespace-nowrap">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)]">
                    <th class="px-5 py-3">Customer</th><th class="px-5 py-3 text-center">Orders</th><th class="px-5 py-3 text-right">Spent</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php if (empty($customers)): ?>
                    <tr><td colspan="3" class="px-5 py-8 text-center text-sm text-[var(--text-muted)]">No registered customers yet</td></tr>
                    <?php else: ?>
                    <?php foreach ($customers as $c): ?>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium"><?= htmlspecialchars($c['name']) ?><span class="block text-xs text-[var(--text-muted)]"><?= htmlspecialchars($c['email']) ?></span></td>
                        <td class="px-5 py-3 text-center text-sm"><?= $c['orders'] ?></td>
                        <td class="px-5 py-3 text-right text-sm font-bold"><?= money_format($c['spent'], 'NGN') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)]"><h3 class="font-bold font-manrope">Quotes by Status</h3></div>
            <div class="p-5 space-y-3">
                <?php if (empty($quoteStatuses)): ?>
                <p class="text-sm text-[var(--text-muted)]">No quotes yet</p>
                <?php else: ?>
                <?php foreach ($quoteStatuses as $qs): ?>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--text-secondary)]"><?= $qs['status'] ?></span>
                    <span class="text-sm font-bold"><?= $qs['count'] ?></span>
                </div>
                <div class="h-2 bg-[var(--surface)] rounded-full overflow-hidden">
                    <div class="h-full bg-[var(--gold)] rounded-full" style="width: <?= $metrics['quotes_total'] > 0 ? round(($qs['count'] / $metrics['quotes_total']) * 100) : 0 ?>%"></div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ================= PRODUCTS & CATEGORIES ================= -->
<div class="space-y-6" x-show="tab === 'Products & Categories'" x-cloak>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Products</p>
            <p class="text-2xl font-bold font-manrope"><?= count($products) ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Categories</p>
            <p class="text-2xl font-bold font-manrope"><?= count($categories) ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Stock Value</p>
            <p class="text-2xl font-bold font-manrope text-[var(--gold)]"><?= money_format($totalStockValue, 'NGN') ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Units Sold</p>
            <p class="text-2xl font-bold font-manrope"><?= array_sum(array_column($topProducts, 'sold')) ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)]"><h3 class="font-bold font-manrope">Product Inventory</h3></div>
            <table class="w-full text-left whitespace-nowrap">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)]">
                    <th class="px-5 py-3">Product</th><th class="px-5 py-3">Category</th><th class="px-5 py-3 text-center">Stock</th><th class="px-5 py-3 text-right">Price</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php if (empty($products)): ?>
                    <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-[var(--text-muted)]">No products yet</td></tr>
                    <?php else: ?>
                    <?php foreach ($products as $p): ?>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium"><?= htmlspecialchars($p['name']) ?><span class="block text-xs text-[var(--text-muted)]"><?= htmlspecialchars($p['sku']) ?></span></td>
                        <td class="px-5 py-3 text-sm"><?= htmlspecialchars($p['category']) ?></td>
                        <td class="px-5 py-3 text-center text-sm"><?= $p['stock'] ?></td>
                        <td class="px-5 py-3 text-right text-sm font-bold"><?= money_format($p['price'], 'NGN') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)]"><h3 class="font-bold font-manrope">Categories</h3></div>
            <table class="w-full text-left whitespace-nowrap">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)]">
                    <th class="px-5 py-3">Category</th><th class="px-5 py-3 text-center">Products</th><th class="px-5 py-3 text-right">Stock Value</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php if (empty($categories)): ?>
                    <tr><td colspan="3" class="px-5 py-8 text-center text-sm text-[var(--text-muted)]">No categories yet</td></tr>
                    <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium"><?= htmlspecialchars($cat['name']) ?></td>
                        <td class="px-5 py-3 text-center text-sm"><?= $cat['products'] ?></td>
                        <td class="px-5 py-3 text-right text-sm font-bold"><?= money_format($cat['stock_value'], 'NGN') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MARKETING ================= -->
<div class="space-y-6" x-show="tab === 'Marketing'" x-cloak>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Total Subscribers</p>
            <p class="text-2xl font-bold font-manrope"><?= $marketing['subscribers_total'] ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Active</p>
            <p class="text-2xl font-bold font-manrope text-[var(--gold)]"><?= $marketing['subscribers_active'] ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">New (30 days)</p>
            <p class="text-2xl font-bold font-manrope"><?= $marketing['subscribers_new'] ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Coupons Active</p>
            <p class="text-2xl font-bold font-manrope"><?= $marketing['coupons_active'] ?> <span class="text-sm font-normal text-[var(--text-muted)]">/ <?= $marketing['coupons_total'] ?></span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)]"><h3 class="font-bold font-manrope">Subscribers by Source</h3></div>
            <div class="p-5 space-y-3">
                <?php if (empty($marketing['subscriber_sources'])): ?>
                <p class="text-sm text-[var(--text-muted)]">No subscribers yet</p>
                <?php else: ?>
                <?php foreach ($marketing['subscriber_sources'] as $s): ?>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--text-secondary)]"><?= htmlspecialchars($s['source']) ?></span>
                    <span class="text-sm font-bold"><?= $s['count'] ?></span>
                </div>
                <div class="h-2 bg-[var(--surface)] rounded-full overflow-hidden">
                    <div class="h-full bg-[var(--gold)] rounded-full" style="width: <?= $marketing['subscribers_total'] > 0 ? round(($s['count'] / $marketing['subscribers_total']) * 100) : 0 ?>%"></div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)]"><h3 class="font-bold font-manrope">Contact Messages</h3></div>
            <div class="p-5">
                <div class="flex items-center gap-4">
                    <div class="flex-1 rounded-[12px] bg-[var(--surface)] border border-[var(--border)] p-4 text-center">
                        <p class="text-2xl font-bold font-manrope"><?= $marketing['messages_total'] ?></p>
                        <p class="text-xs text-[var(--text-muted)] mt-1">Total</p>
                    </div>
                    <div class="flex-1 rounded-[12px] bg-yellow-500/10 border border-yellow-500/20 p-4 text-center">
                        <p class="text-2xl font-bold font-manrope text-yellow-400"><?= $marketing['messages_unread'] ?></p>
                        <p class="text-xs text-[var(--text-muted)] mt-1">Unread</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /Marketing -->

</div><!-- /x-data wrapper -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Chart.defaults.color = '#888'; Chart.defaults.borderColor = 'rgba(255,255,255,0.05)'; Chart.defaults.font.family = "'Inter', sans-serif";

    const labels = <?= json_encode($chartLabels) ?>;
    const revenue = <?= json_encode($revenueData) ?>;

    // Overview Revenue Line Chart
    const revCtx = document.getElementById('revenueChart');
    if (revCtx) {
        new Chart(revCtx, {
            type: 'line',
            data: {
                labels,
                datasets: [{ label: 'Revenue (₦)', data: revenue, borderColor: '#c8a96e', backgroundColor: 'rgba(200, 169, 110, 0.1)', borderWidth: 3, tension: 0.4, fill: true, pointBackgroundColor: '#c8a96e', pointBorderColor: '#111', pointBorderWidth: 2, pointRadius: 4 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: v => '₦' + (v / 1000000).toFixed(1) + 'M' } } } }
        });
    }

    // Overview Category Doughnut Chart
    const catCtx = document.getElementById('categoryChart');
    if (catCtx) {
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($categories, 'name')) ?: "['Uncategorised']" ?>,
                datasets: [{ data: <?= json_encode(array_map(fn($c) => $c['products'], $categories)) ?: '[0]' ?>, backgroundColor: ['#c8a96e', '#a68c5b', '#806c47', '#594b32', '#3d3a2f', '#2a2822'], borderWidth: 0, hoverOffset: 4 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } } } }
        });
    }

    // Sales & Revenue tab line chart
    const salesCtx = document.getElementById('revenueChartSales');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels,
                datasets: [{ label: 'Revenue (₦)', data: revenue, borderColor: '#c8a96e', backgroundColor: 'rgba(200, 169, 110, 0.1)', borderWidth: 3, tension: 0.4, fill: true, pointBackgroundColor: '#c8a96e', pointBorderColor: '#111', pointBorderWidth: 2, pointRadius: 4 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: v => '₦' + (v / 1000000).toFixed(1) + 'M' } } } }
        });
    }

    lucide.createIcons();
});
</script>
