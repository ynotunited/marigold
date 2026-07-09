<div class="flex items-center gap-4 mb-6">
    <a href="/admin/customers" class="text-[var(--text-secondary)] hover:text-white transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
    <div class="flex-grow">
        <h1 class="text-2xl font-bold font-manrope">Customer Profile</h1>
    </div>
    <div class="flex items-center gap-3">
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)]">Reset Password</button>
        <button class="btn h-9 px-4 text-sm text-red-400 border border-red-500/40 bg-red-500/10 hover:bg-red-500/20 transition-colors">Deactivate Account</button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Left: Core Profile & Notes -->
    <div class="xl:col-span-1 space-y-6">
        <!-- Profile Card -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6 text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-[var(--gold)]/20 border-2 border-[var(--gold)]/40 flex items-center justify-center text-[var(--gold)] font-bold text-2xl mb-4"><?= strtoupper(substr($customer['name'], 0, 1)) ?></div>
            <h2 class="text-xl font-bold font-manrope"><?= htmlspecialchars($customer['name']) ?></h2>
            <p class="text-[var(--text-secondary)] text-sm mb-4"><?= $customer['email'] ?></p>
            <div class="flex items-center justify-center gap-2 mb-6">
                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase border bg-purple-500/10 text-purple-400 border-purple-500/20"><?= $customer['type'] ?></span>
                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase border bg-green-500/10 text-green-400 border-green-500/20"><?= $customer['status'] ?></span>
            </div>
            <div class="grid grid-cols-2 gap-3 text-left bg-[var(--surface)] p-4 rounded-[12px] border border-[var(--border)]">
                <div><p class="text-xs text-[var(--text-muted)] mb-0.5">Phone</p><p class="text-sm font-medium"><?= htmlspecialchars($customer['phone']) ?></p></div>
                <div><p class="text-xs text-[var(--text-muted)] mb-0.5">Company</p><p class="text-sm font-medium"><?= htmlspecialchars($customer['company']) ?></p></div>
                <div><p class="text-xs text-[var(--text-muted)] mb-0.5">Registered</p><p class="text-sm font-medium"><?= $customer['registered'] ?></p></div>
                <div><p class="text-xs text-[var(--text-muted)] mb-0.5">Last Login</p><p class="text-sm font-medium"><?= $customer['last_login'] ?></p></div>
            </div>
        </div>

        <!-- Internal Notes -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="font-bold font-manrope mb-4">Internal Notes</h3>
            <textarea rows="4" class="input-field w-full resize-none text-sm mb-3" placeholder="Add notes about this customer…"><?= htmlspecialchars($customer['internal_notes']) ?></textarea>
            <button class="btn btn-secondary border border-[var(--border)] w-full h-9 text-sm bg-[var(--surface)]">Save Notes</button>
        </div>

        <!-- Address Book -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="font-bold font-manrope mb-4">Saved Addresses</h3>
            <div class="space-y-3">
                <?php foreach ($customer['addresses'] as $addr): ?>
                <div class="p-3 bg-[var(--surface)] border border-[var(--border)] rounded-[10px] text-sm">
                    <p class="font-bold text-[var(--gold)] text-xs mb-1 uppercase tracking-wider"><?= $addr['label'] ?></p>
                    <p class="text-[var(--text-secondary)]"><?= $addr['street'] ?>, <?= $addr['city'] ?>, <?= $addr['state'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Right: Stats & History -->
    <div class="xl:col-span-2 space-y-6">
        
        <!-- Lifetime Value Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
                <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Lifetime Value</p>
                <p class="text-2xl font-bold font-manrope text-[var(--gold)]">₦<?= number_format($customer['lifetime_value']) ?></p>
            </div>
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
                <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Total Orders</p>
                <p class="text-2xl font-bold font-manrope"><?= $customer['total_orders'] ?></p>
            </div>
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5">
                <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1 font-semibold">Avg. Order Value</p>
                <p class="text-2xl font-bold font-manrope">₦<?= number_format($customer['avg_order_value']) ?></p>
            </div>
        </div>

        <!-- Order History -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)] flex items-center justify-between"><h2 class="font-bold font-manrope">Recent Orders</h2><a href="/admin/orders" class="text-sm text-[var(--gold)]">View All</a></div>
            <table class="w-full text-left">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase text-[var(--text-muted)]">
                    <th class="px-5 py-3">Order ID</th><th class="px-5 py-3">Date</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Total</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php foreach ($customer['recent_orders'] as $ro): ?>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors group cursor-pointer" onclick="window.location='/admin/orders/<?= $ro['id'] ?>'">
                        <td class="px-5 py-4 font-medium text-sm group-hover:text-[var(--gold)] transition-colors"><?= $ro['id'] ?></td>
                        <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= $ro['date'] ?></td>
                        <td class="px-5 py-4"><span class="text-xs"><?= $ro['status'] ?></span></td>
                        <td class="px-5 py-4 text-right font-bold text-sm text-[var(--gold)]">₦<?= number_format($ro['total']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Quote History -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)] flex items-center justify-between"><h2 class="font-bold font-manrope">Recent Quotes</h2><a href="/admin/quotes" class="text-sm text-[var(--gold)]">View All</a></div>
            <table class="w-full text-left">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase text-[var(--text-muted)]">
                    <th class="px-5 py-3">Quote ID</th><th class="px-5 py-3">Date</th><th class="px-5 py-3">Items</th><th class="px-5 py-3">Status</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php foreach ($customer['recent_quotes'] as $rq): ?>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors group cursor-pointer" onclick="window.location='/admin/quotes/<?= $rq['id'] ?>'">
                        <td class="px-5 py-4 font-medium text-sm group-hover:text-[var(--gold)] transition-colors"><?= $rq['id'] ?></td>
                        <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= $rq['date'] ?></td>
                        <td class="px-5 py-4 text-sm"><?= $rq['items'] ?></td>
                        <td class="px-5 py-4"><span class="text-xs"><?= $rq['status'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
