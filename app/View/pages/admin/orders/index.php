<!-- Orders Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div><h1 class="text-2xl font-bold font-manrope">Orders</h1><p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($orders) ?> orders total</p></div>
    <div class="flex items-center gap-3">
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2"><i data-lucide="download" class="w-4 h-4"></i> Export CSV</button>
    </div>
</div>

<!-- Advanced Filters -->
<div class="bg-[#111] border border-[var(--border)] rounded-[14px] p-4 mb-6 flex flex-wrap gap-3">
    <div class="relative flex-grow min-w-[200px] max-w-sm"><i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[var(--text-muted)]"></i><input type="text" placeholder="Order #, customer, email…" class="input-field w-full pl-10 h-9 text-sm bg-[var(--surface)]"></div>
    <input type="date" class="input-field h-9 text-sm bg-[var(--surface)] min-w-[140px]">
    <select class="input-field h-9 text-sm bg-[var(--surface)] pr-8 min-w-[140px]"><option value="">Payment Status</option><option>Paid</option><option>Pending</option><option>Refunded</option></select>
    <select class="input-field h-9 text-sm bg-[var(--surface)] pr-8 min-w-[150px]"><option value="">Delivery Status</option><option>Pending</option><option>Processing</option><option>Shipped</option><option>Completed</option><option>Cancelled</option></select>
</div>

<!-- Orders Table -->
<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                <th class="px-5 py-3 w-10"><input type="checkbox" class="rounded border-[var(--border)]"></th>
                <th class="px-5 py-3 font-medium">Order</th><th class="px-5 py-3 font-medium">Customer</th>
                <th class="px-5 py-3 font-medium">Date</th><th class="px-5 py-3 font-medium">Payment</th>
                <th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3 font-medium text-right">Total</th>
                <th class="px-5 py-3 font-medium text-center">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php
                $statusCls = ['Pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20', 'Processing' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'Shipped' => 'bg-purple-500/10 text-purple-400 border-purple-500/20', 'Completed' => 'bg-green-500/10 text-green-400 border-green-500/20', 'Cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20'];
                foreach ($orders as $o): $sc = $statusCls[$o['status']] ?? '';
                ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group cursor-pointer" onclick="window.location='/admin/orders/<?= $o['id'] ?>'">
                    <td class="px-5 py-4" onclick="event.stopPropagation()"><input type="checkbox" class="rounded border-[var(--border)]"></td>
                    <td class="px-5 py-4 font-medium text-sm group-hover:text-[var(--gold)] transition-colors"><?= $o['id'] ?></td>
                    <td class="px-5 py-4">
                        <div><p class="text-sm font-medium"><?= htmlspecialchars($o['customer']) ?></p><p class="text-xs text-[var(--text-muted)]"><?= $o['email'] ?></p></div>
                    </td>
                    <td class="px-5 py-4 text-sm text-[var(--text-muted)]"><?= date('M j, Y', strtotime($o['date'])) ?></td>
                    <td class="px-5 py-4"><span class="text-xs font-medium <?= $o['payment'] === 'Paid' ? 'text-green-400' : 'text-yellow-400' ?>"><?= $o['payment'] ?></span></td>
                    <td class="px-5 py-4"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $sc ?>"><?= $o['status'] ?></span></td>
                    <td class="px-5 py-4 text-right font-bold text-sm">₦<?= number_format($o['total']) ?></td>
                    <td class="px-5 py-4" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="/admin/orders/<?= $o['id'] ?>" class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--gold)] hover:border-[var(--gold)]/50 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-blue-400 transition-colors"><i data-lucide="printer" class="w-3.5 h-3.5"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
