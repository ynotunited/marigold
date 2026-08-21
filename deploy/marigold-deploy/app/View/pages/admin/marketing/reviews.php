<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-bold font-manrope">Product Reviews</h1><p class="text-sm text-[var(--text-secondary)] mt-1">Manage and moderate customer reviews</p></div>
</div>

<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                <th class="px-5 py-3 font-medium">Product / Customer</th><th class="px-5 py-3 font-medium">Review</th>
                <th class="px-5 py-3 font-medium text-center">Rating</th><th class="px-5 py-3 font-medium text-center">Date</th>
                <th class="px-5 py-3 font-medium text-center">Status</th><th class="px-5 py-3 font-medium text-center">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($reviews as $r): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group">
                    <td class="px-5 py-4">
                        <p class="text-sm font-medium"><?= htmlspecialchars($r['product']) ?></p>
                        <p class="text-xs text-[var(--text-muted)]">by <?= htmlspecialchars($r['customer']) ?></p>
                    </td>
                    <td class="px-5 py-4"><p class="text-sm text-[var(--text-secondary)] whitespace-normal min-w-[200px] max-w-sm line-clamp-2" title="<?= htmlspecialchars($r['comment']) ?>"><?= htmlspecialchars($r['comment']) ?></p></td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-0.5 text-[var(--gold)]">
                            <?php for($i=0; $i<$r['rating']; $i++): ?><i data-lucide="star" class="w-3 h-3 fill-current"></i><?php endfor; ?>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center text-sm text-[var(--text-muted)]"><?= $r['date'] ?></td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $r['status'] === 'Approved' ? 'bg-green-500/10 text-green-400 border-green-500/20' : ($r['status'] === 'Rejected' ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20') ?>"><?= $r['status'] ?></span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <?php if ($r['status'] === 'Pending'): ?>
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-green-400 hover:bg-green-500/20 transition-colors" title="Approve"><i data-lucide="check" class="w-4 h-4"></i></button>
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-red-400 hover:bg-red-500/20 transition-colors" title="Reject"><i data-lucide="x" class="w-4 h-4"></i></button>
                            <?php endif; ?>
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-white transition-colors" title="Reply"><i data-lucide="message-square" class="w-4 h-4"></i></button>
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--danger)] hover:border-[var(--danger)]/50 transition-colors" title="Delete"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
