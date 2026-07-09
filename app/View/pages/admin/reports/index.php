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
<div class="flex items-center gap-6 border-b border-[var(--border)] mb-6 overflow-x-auto hide-scrollbar" x-data="{ tab: 'Overview' }">
    <?php foreach (['Overview', 'Sales & Revenue', 'Customers & Quotes', 'Products & Categories', 'Marketing'] as $t): ?>
    <button @click="tab = '<?= $t ?>'" :class="tab === '<?= $t ?>' ? 'text-white border-[var(--gold)] border-b-2' : 'text-[var(--text-secondary)] hover:text-white'" class="pb-3 text-sm font-medium transition-colors whitespace-nowrap"><?= $t ?></button>
    <?php endforeach; ?>
</div>

<div class="space-y-6">
    
    <!-- Top KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Total Revenue</p>
            <p class="text-2xl font-bold font-manrope text-[var(--gold)]">₦<?= number_format($metrics['total_revenue']) ?></p>
            <p class="text-xs text-green-400 mt-2 flex items-center gap-1"><i data-lucide="trending-up" class="w-3 h-3"></i> +12.5% vs last period</p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Orders</p>
            <p class="text-2xl font-bold font-manrope"><?= number_format($metrics['orders_count']) ?></p>
            <p class="text-xs text-green-400 mt-2 flex items-center gap-1"><i data-lucide="trending-up" class="w-3 h-3"></i> +5.2% vs last period</p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Avg. Order Value</p>
            <p class="text-2xl font-bold font-manrope">₦<?= number_format($metrics['avg_order_value']) ?></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Quotes Converted</p>
            <p class="text-2xl font-bold font-manrope text-[var(--gold)]"><?= $metrics['quotes_converted'] ?> <span class="text-sm font-normal text-[var(--text-muted)]">(68%)</span></p>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
            <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">New Customers</p>
            <p class="text-2xl font-bold font-manrope"><?= $metrics['new_customers'] ?></p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Chart -->
        <div class="lg:col-span-2 bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <div class="flex justify-between items-center mb-6"><h3 class="font-bold font-manrope">Revenue Trend</h3></div>
            <div class="h-64 relative w-full"><canvas id="revenueChart"></canvas></div>
        </div>
        
        <!-- Secondary Chart -->
        <div class="lg:col-span-1 bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="font-bold font-manrope mb-6">Sales by Category</h3>
            <div class="h-48 relative w-full flex justify-center"><canvas id="categoryChart"></canvas></div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Top Products -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)] flex justify-between items-center"><h3 class="font-bold font-manrope">Top Selling Products</h3></div>
            <table class="w-full text-left whitespace-nowrap">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)]">
                    <th class="px-5 py-3">Product</th><th class="px-5 py-3 text-center">Units Sold</th><th class="px-5 py-3 text-right">Revenue generated</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php foreach ($topProducts as $p): ?>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium"><?= htmlspecialchars($p['name']) ?></td>
                        <td class="px-5 py-3 text-center text-sm"><?= number_format($p['sold']) ?></td>
                        <td class="px-5 py-3 text-right text-sm text-[var(--gold)] font-bold">₦<?= number_format($p['revenue']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Recent Customer Acquisition -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)] flex justify-between items-center"><h3 class="font-bold font-manrope">Customer Acquisition (LTV)</h3></div>
            <table class="w-full text-left whitespace-nowrap">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)]">
                    <th class="px-5 py-3">Type</th><th class="px-5 py-3 text-center">Count</th><th class="px-5 py-3 text-right">Avg LTV</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium">Returning Corporate</td>
                        <td class="px-5 py-3 text-center text-sm">45</td>
                        <td class="px-5 py-3 text-right text-sm font-bold">₦4,500,000</td>
                    </tr>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium">New Corporate</td>
                        <td class="px-5 py-3 text-center text-sm">12</td>
                        <td class="px-5 py-3 text-right text-sm font-bold">₦1,200,000</td>
                    </tr>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium">Retail / Individual</td>
                        <td class="px-5 py-3 text-center text-sm">67</td>
                        <td class="px-5 py-3 text-right text-sm font-bold">₦150,000</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Chart.defaults.color = '#888'; Chart.defaults.borderColor = 'rgba(255,255,255,0.05)'; Chart.defaults.font.family = "'Inter', sans-serif";
    
    // Revenue Line Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Revenue (₦)', data: [2500000, 3800000, 3100000, 5100000],
                borderColor: '#c8a96e', backgroundColor: 'rgba(200, 169, 110, 0.1)', borderWidth: 3, tension: 0.4, fill: true, pointBackgroundColor: '#c8a96e', pointBorderColor: '#111', pointBorderWidth: 2, pointRadius: 4
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: v => '₦'+(v/1000000)+'M' } } } }
    });

    // Category Doughnut Chart
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: ['Notebooks', 'Drinkware', 'Pens', 'Apparel'],
            datasets: [{ data: [45, 25, 20, 10], backgroundColor: ['#c8a96e', '#a68c5b', '#806c47', '#594b32'], borderWidth: 0, hoverOffset: 4 }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } } } }
    });
});
</script>
