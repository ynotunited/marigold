<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Dashboard</h1>
        <p class="text-[var(--text-secondary)] text-sm mt-1">Welcome back, Super Admin. Here's what's happening today.</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-sm text-[var(--text-muted)]"><?= date('l, F j, Y') ?></span>
        <a href="/admin/reports" class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)]">
            <i data-lucide="download" class="w-4 h-4 mr-1.5"></i> Export
        </a>
    </div>
</div>

<!-- ========== KPI CARDS ========== -->
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    <?php
    $colorMap = [
        'gold'   => ['bg' => 'bg-[var(--gold)]/10', 'text' => 'text-[var(--gold)]', 'border' => 'border-[var(--gold)]/30'],
        'blue'   => ['bg' => 'bg-blue-500/10',  'text' => 'text-blue-400',  'border' => 'border-blue-500/30'],
        'yellow' => ['bg' => 'bg-yellow-500/10','text' => 'text-yellow-400','border' => 'border-yellow-500/30'],
        'purple' => ['bg' => 'bg-purple-500/10','text' => 'text-purple-400','border' => 'border-purple-500/30'],
        'green'  => ['bg' => 'bg-green-500/10', 'text' => 'text-green-400', 'border' => 'border-green-500/30'],
        'red'    => ['bg' => 'bg-red-500/10',   'text' => 'text-red-400',   'border' => 'border-red-500/30'],
    ];
    foreach ($kpis as $kpi):
        $c = $colorMap[$kpi['color']];
    ?>
    <div class="bg-[#111] border border-[var(--border)] rounded-[14px] p-5 hover:border-[var(--gold)]/30 transition-colors group">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-[10px] <?= $c['bg'] ?> <?= $c['border'] ?> border flex items-center justify-center">
                <i data-lucide="<?= $kpi['icon'] ?>" class="w-5 h-5 <?= $c['text'] ?>"></i>
            </div>
            <span class="text-xs font-medium <?= $kpi['up'] ? 'text-green-400' : 'text-red-400' ?>"><?= $kpi['change'] ?></span>
        </div>
        <p class="text-2xl font-bold font-manrope mb-0.5"><?= $kpi['value'] ?></p>
        <p class="text-xs text-[var(--text-muted)]"><?= $kpi['label'] ?></p>
    </div>
    <?php endforeach; ?>
</div>

<!-- ========== CHARTS ROW ========== -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

    <!-- Revenue Chart (2 cols wide) -->
    <div class="xl:col-span-2 bg-[#111] border border-[var(--border)] rounded-[16px] p-6" x-data="{ period: 'monthly' }">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold font-manrope">Sales Overview</h2>
                <p class="text-sm text-[var(--text-muted)]">Revenue and orders over time</p>
            </div>
            <div class="flex items-center gap-1 bg-[var(--surface)] border border-[var(--border)] rounded-[8px] p-1">
                <button @click="period = 'daily'"   :class="period === 'daily'   ? 'bg-[var(--gold)] text-[#111]' : 'text-[var(--text-secondary)]'" class="px-3 py-1 rounded-[6px] text-xs font-medium transition-colors">Daily</button>
                <button @click="period = 'weekly'"  :class="period === 'weekly'  ? 'bg-[var(--gold)] text-[#111]' : 'text-[var(--text-secondary)]'" class="px-3 py-1 rounded-[6px] text-xs font-medium transition-colors">Weekly</button>
                <button @click="period = 'monthly'" :class="period === 'monthly' ? 'bg-[var(--gold)] text-[#111]' : 'text-[var(--text-secondary)]'" class="px-3 py-1 rounded-[6px] text-xs font-medium transition-colors">Monthly</button>
            </div>
        </div>
        <div class="relative h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Category Breakdown (1 col) -->
    <div class="xl:col-span-1 bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
        <div class="mb-6">
            <h2 class="text-lg font-bold font-manrope">Top Categories</h2>
            <p class="text-sm text-[var(--text-muted)]">By sales volume this month</p>
        </div>
        <div class="relative h-48 mb-6">
            <canvas id="categoryChart"></canvas>
        </div>
        <div class="space-y-3">
            <?php foreach ($categories as $cat): ?>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full" style="background:<?= $cat['color'] ?>"></div>
                    <span class="text-sm text-[var(--text-secondary)]"><?= $cat['name'] ?></span>
                </div>
                <span class="text-sm font-bold"><?= $cat['sales'] ?>%</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ========== BOTTOM ROW ========== -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Recent Orders (2 cols) -->
    <div class="xl:col-span-2 bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
        <div class="p-6 border-b border-[var(--border)] flex items-center justify-between">
            <h2 class="text-lg font-bold font-manrope">Recent Orders</h2>
            <a href="/admin/orders" class="text-sm text-[var(--gold)] hover:text-white transition-colors">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-[var(--surface)] text-[var(--text-muted)] text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Order</th>
                        <th class="px-6 py-3 font-medium">Customer</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php
                    $statusColors = [
                        'Pending'    => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                        'Processing' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                        'Shipped'    => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                        'Completed'  => 'bg-green-500/10 text-green-400 border-green-500/20',
                        'Cancelled'  => 'bg-red-500/10 text-red-400 border-red-500/20',
                    ];
                    foreach ($recent_orders as $order):
                        $sc = $statusColors[$order['status']] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20';
                    ?>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors cursor-pointer group" onclick="window.location='/admin/orders/<?= $order['id'] ?>'">
                        <td class="px-6 py-4 text-sm font-medium group-hover:text-[var(--gold)] transition-colors"><?= $order['id'] ?></td>
                        <td class="px-6 py-4 text-sm text-[var(--text-secondary)]"><?= htmlspecialchars($order['customer']) ?></td>
                        <td class="px-6 py-4 text-sm text-[var(--text-muted)]"><?= $order['date'] ?></td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $sc ?>"><?= $order['status'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-right"><?= $order['total'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column -->
    <div class="xl:col-span-1 space-y-6">

        <!-- Quick Actions -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="text-lg font-bold font-manrope mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-3">
                <?php
                $quickActions = [
                    ['label' => 'Add Product',    'icon' => 'plus-square',   'href' => '/admin/products/create'],
                    ['label' => 'New Category',   'icon' => 'folder-plus',   'href' => '/admin/categories/create'],
                    ['label' => 'View Orders',    'icon' => 'package',       'href' => '/admin/orders'],
                    ['label' => 'New Blog Post',  'icon' => 'pen-tool',      'href' => '/admin/blog/create'],
                    ['label' => 'Upload Media',   'icon' => 'upload-cloud',  'href' => '/admin/media'],
                    ['label' => 'View Quotes',    'icon' => 'file-text',     'href' => '/admin/quotes'],
                ];
                foreach ($quickActions as $qa):
                ?>
                <a href="<?= $qa['href'] ?>" class="flex flex-col items-center gap-2 p-3 rounded-[10px] bg-[var(--surface)] border border-[var(--border)] hover:border-[var(--gold)]/50 hover:bg-[var(--gold)]/5 transition-colors group text-center">
                    <i data-lucide="<?= $qa['icon'] ?>" class="w-5 h-5 text-[var(--text-secondary)] group-hover:text-[var(--gold)] transition-colors"></i>
                    <span class="text-xs text-[var(--text-secondary)] group-hover:text-white transition-colors leading-tight"><?= $qa['label'] ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-6 border-b border-[var(--border)]">
                <h2 class="text-lg font-bold font-manrope">Recent Activity</h2>
            </div>
            <div class="divide-y divide-[var(--border)] max-h-[360px] overflow-y-auto hide-scrollbar">
                <?php
                $activityColors = [
                    'blue'   => 'bg-blue-500/10 text-blue-400',
                    'yellow' => 'bg-yellow-500/10 text-yellow-400',
                    'green'  => 'bg-green-500/10 text-green-400',
                    'red'    => 'bg-red-500/10 text-red-400',
                    'purple' => 'bg-purple-500/10 text-purple-400',
                ];
                foreach ($activity as $a):
                    $ac = $activityColors[$a['color']] ?? 'bg-gray-500/10 text-gray-400';
                ?>
                <div class="flex items-start gap-4 px-5 py-4 hover:bg-[var(--surface)]/40 transition-colors">
                    <div class="w-8 h-8 rounded-full <?= $ac ?> flex items-center justify-center shrink-0 mt-0.5">
                        <i data-lucide="<?= $a['icon'] ?>" class="w-4 h-4"></i>
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-sm text-[var(--text-secondary)] leading-relaxed"><?= $a['message'] ?></p>
                        <p class="text-xs text-[var(--text-muted)] mt-1"><?= $a['time'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- ========== CHARTS JS ========== -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chartDefaults = {
        color: '#9ca3af',
        borderColor: '#1f2937',
    };

    // Sales Overview Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const gradient = salesCtx.createLinearGradient(0, 0, 0, 256);
    gradient.addColorStop(0, 'rgba(200, 169, 110, 0.3)');
    gradient.addColorStop(1, 'rgba(200, 169, 110, 0)');

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'Revenue (₦)',
                data: <?= json_encode($revenueData) ?>,
                borderColor: '#C8A96E',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#C8A96E',
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2.5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111',
                    borderColor: '#2a2a2a',
                    borderWidth: 1,
                    titleColor: '#fff',
                    bodyColor: '#9ca3af',
                    callbacks: {
                        label: (ctx) => ' ₦' + ctx.raw.toLocaleString()
                    }
                }
            },
            scales: {
                x: { grid: { color: '#1a1a1a' }, ticks: { color: '#6b7280', font: { size: 11 } } },
                y: { grid: { color: '#1a1a1a' }, ticks: { color: '#6b7280', font: { size: 11 }, callback: (v) => '₦' + (v/1000000).toFixed(1) + 'M' } }
            }
        }
    });

    // Category Donut Chart
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($categories, 'name')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($categories, 'sales')) ?>,
                backgroundColor: <?= json_encode(array_column($categories, 'color')) ?>,
                borderWidth: 3,
                borderColor: '#0f0f0f',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111',
                    borderColor: '#2a2a2a',
                    borderWidth: 1,
                    titleColor: '#fff',
                    bodyColor: '#9ca3af',
                    callbacks: { label: (ctx) => ' ' + ctx.label + ': ' + ctx.raw + '%' }
                }
            }
        }
    });

    lucide.createIcons();
});
</script>
