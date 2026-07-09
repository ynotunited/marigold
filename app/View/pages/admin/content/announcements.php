<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-bold font-manrope">Announcements</h1><p class="text-sm text-[var(--text-secondary)] mt-1">Manage global site banners</p></div>
    <button class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Add Banner</button>
</div>

<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                <th class="px-5 py-3 font-medium">Message</th><th class="px-5 py-3 font-medium">Schedule</th>
                <th class="px-5 py-3 font-medium text-center">Priority</th><th class="px-5 py-3 font-medium text-center">Status</th>
                <th class="px-5 py-3 font-medium text-center">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($announcements as $a): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group">
                    <td class="px-5 py-4 font-medium text-sm truncate max-w-sm"><?= htmlspecialchars($a['message']) ?></td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= $a['schedule'] ?></td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase border <?= $a['priority'] === 'Urgent' ? 'bg-red-500/10 text-red-400 border-red-500/20' : ($a['priority'] === 'High' ? 'bg-orange-500/10 text-orange-400 border-orange-500/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20') ?>"><?= $a['priority'] ?></span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $a['status'] === 'Active' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-[var(--surface)] text-[var(--text-muted)] border-[var(--border)]' ?>"><?= $a['status'] ?></span>
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
