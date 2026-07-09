<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold mb-2">My Orders</h1>
        <p class="text-[var(--text-secondary)]">Track, manage, and view history of your corporate orders.</p>
    </div>
    
    <!-- Filters / Search -->
    <div class="flex items-center gap-4 w-full md:w-auto">
        <div class="relative w-full md:w-64">
            <input type="text" placeholder="Search orders..." class="input-field w-full pl-10 h-11 bg-[var(--surface)] border-[var(--border)]">
            <i data-lucide="search" class="w-4 h-4 text-[var(--text-muted)] absolute left-4 top-1/2 -translate-y-1/2"></i>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="flex items-center gap-6 border-b border-[var(--border)] mb-8 overflow-x-auto hide-scrollbar">
    <button class="pb-3 text-white border-b-2 border-[var(--gold)] font-medium whitespace-nowrap">All Orders</button>
    <button class="pb-3 text-[var(--text-secondary)] hover:text-white transition-colors whitespace-nowrap">Processing</button>
    <button class="pb-3 text-[var(--text-secondary)] hover:text-white transition-colors whitespace-nowrap">Completed</button>
    <button class="pb-3 text-[var(--text-secondary)] hover:text-white transition-colors whitespace-nowrap">Cancelled</button>
</div>

<!-- Order List -->
<div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="bg-[var(--surface)] text-[var(--text-muted)] text-sm">
                    <th class="px-6 py-4 font-medium">Order #</th>
                    <th class="px-6 py-4 font-medium">Date</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Total</th>
                    <th class="px-6 py-4 font-medium text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach($orders as $order): ?>
                <tr class="hover:bg-[var(--surface)]/50 transition-colors group cursor-pointer" onclick="window.location='/account/orders/<?= $order['id'] ?>'">
                    <td class="px-6 py-4">
                        <span class="font-medium group-hover:text-[var(--gold)] transition-colors"><?= $order['id'] ?></span>
                    </td>
                    <td class="px-6 py-4 text-[var(--text-secondary)]"><?= date('M j, Y', strtotime($order['date'])) ?></td>
                    <td class="px-6 py-4">
                        <?php if($order['status'] === 'Completed'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">
                                Completed
                            </span>
                        <?php elseif($order['status'] === 'Cancelled'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                                Cancelled
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                <?= $order['status'] ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right font-medium"><?= $order['total'] ?></td>
                    <td class="px-6 py-4 text-center">
                        <a href="/account/orders/<?= $order['id'] ?>" class="text-[var(--text-secondary)] hover:text-white transition-colors" onclick="event.stopPropagation()">
                            <i data-lucide="chevron-right" class="w-5 h-5 mx-auto"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
