<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-bold font-manrope">Audit Logs</h1><p class="text-sm text-[var(--text-secondary)] mt-1">System activity and modifications history</p></div>
    <div class="flex items-center gap-3">
        <select class="input-field h-9 text-sm bg-[var(--surface)] border-[var(--border)] pr-8"><option>All Users</option><option>Sarah Jenkins</option><option>System</option></select>
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2"><i data-lucide="download" class="w-4 h-4"></i> Export CSV</button>
    </div>
</div>

<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                <th class="px-5 py-3 font-medium">ID</th><th class="px-5 py-3 font-medium">User</th>
                <th class="px-5 py-3 font-medium">Action</th><th class="px-5 py-3 font-medium">Module / Record</th>
                <th class="px-5 py-3 font-medium text-center">Date & Time</th>
            </tr></thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($logs as $l): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                    <td class="px-5 py-4 font-mono text-xs text-[var(--text-muted)]">#<?= $l['id'] ?></td>
                    <td class="px-5 py-4 font-medium text-sm flex items-center gap-2">
                        <?php if($l['user']==='System'): ?><i data-lucide="cpu" class="w-4 h-4 text-purple-400"></i><?php else: ?><i data-lucide="user" class="w-4 h-4 text-[var(--text-secondary)]"></i><?php endif; ?>
                        <?= htmlspecialchars($l['user']) ?>
                    </td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= $l['action'] ?></td>
                    <td class="px-5 py-4">
                        <span class="text-xs uppercase font-bold text-[var(--text-muted)] mr-2"><?= $l['module'] ?></span>
                        <span class="text-sm font-medium"><?= htmlspecialchars($l['record']) ?></span>
                    </td>
                    <td class="px-5 py-4 text-center text-sm text-[var(--text-secondary)]"><?= $l['date'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
