<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-bold font-manrope">Popups</h1><p class="text-sm text-[var(--text-secondary)] mt-1">Manage modals and exit-intent popups</p></div>
    <button class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Create Popup</button>
</div>

<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                <th class="px-5 py-3 font-medium">Title</th><th class="px-5 py-3 font-medium">Trigger</th>
                <th class="px-5 py-3 font-medium text-center">Devices</th><th class="px-5 py-3 font-medium text-center">Views</th>
                <th class="px-5 py-3 font-medium text-center">Conversions</th><th class="px-5 py-3 font-medium text-center">Status</th>
                <th class="px-5 py-3 font-medium text-center">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($popups as $p): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group">
                    <td class="px-5 py-4 font-medium text-sm"><?= htmlspecialchars($p['title']) ?></td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= htmlspecialchars($p['trigger']) ?></td>
                    <td class="px-5 py-4 text-center text-sm text-[var(--text-secondary)]"><?= $p['devices'] ?></td>
                    <td class="px-5 py-4 text-center text-sm font-medium"><?= number_format($p['views']) ?></td>
                    <td class="px-5 py-4 text-center text-sm text-[var(--gold)] font-bold"><?= number_format($p['conversions']) ?></td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $p['status'] === 'Active' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' ?>"><?= $p['status'] ?></span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--gold)] hover:border-[var(--gold)]/50 transition-colors"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></button>
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--danger)] hover:border-[var(--danger)]/50 transition-colors"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
