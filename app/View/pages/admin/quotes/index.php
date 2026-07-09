<!-- Quotes Header -->
<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-bold font-manrope">Quotes</h1><p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($quotes) ?> total quote requests</p></div>
</div>

<!-- Status Tabs -->
<div class="flex items-center gap-6 border-b border-[var(--border)] mb-6 overflow-x-auto hide-scrollbar">
    <?php foreach (['All', 'Pending', 'Approved', 'Rejected', 'Converted', 'Expired'] as $tab): ?>
    <button class="pb-3 whitespace-nowrap text-sm font-medium transition-colors <?= $tab === 'All' ? 'text-white border-b-2 border-[var(--gold)]' : 'text-[var(--text-secondary)] hover:text-white' ?>"><?= $tab ?></button>
    <?php endforeach; ?>
</div>

<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                <th class="px-5 py-3 font-medium">Quote ID</th><th class="px-5 py-3 font-medium">Customer</th>
                <th class="px-5 py-3 font-medium text-center">Items</th><th class="px-5 py-3 font-medium">Date</th>
                <th class="px-5 py-3 font-medium text-center">Status</th><th class="px-5 py-3 font-medium text-center">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php
                $qStatus = ['Pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20', 'Approved' => 'bg-green-500/10 text-green-400 border-green-500/20', 'Rejected' => 'bg-red-500/10 text-red-400 border-red-500/20', 'Converted' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'Expired' => 'bg-[var(--surface)] text-[var(--text-muted)] border-[var(--border)]'];
                foreach ($quotes as $q): $sc = $qStatus[$q['status']] ?? '';
                ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group cursor-pointer" onclick="window.location='/admin/quotes/<?= $q['id'] ?>'">
                    <td class="px-5 py-4 font-medium text-sm group-hover:text-[var(--gold)] transition-colors"><?= $q['id'] ?></td>
                    <td class="px-5 py-4">
                        <div><p class="text-sm font-medium"><?= htmlspecialchars($q['customer']) ?></p><p class="text-xs text-[var(--text-muted)]"><?= $q['email'] ?></p></div>
                    </td>
                    <td class="px-5 py-4 text-sm text-center"><?= $q['items'] ?></td>
                    <td class="px-5 py-4 text-sm text-[var(--text-muted)]"><?= date('M j, Y', strtotime($q['date'])) ?></td>
                    <td class="px-5 py-4 text-center"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $sc ?>"><?= $q['status'] ?></span></td>
                    <td class="px-5 py-4" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="/admin/quotes/<?= $q['id'] ?>" class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--gold)] hover:border-[var(--gold)]/50 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
