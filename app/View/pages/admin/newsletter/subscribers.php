<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Subscribers</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($subscribers) ?> total subscribers</p>
    </div>
    <div class="flex items-center gap-3">
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2"><i data-lucide="download" class="w-4 h-4"></i> Export CSV</button>
        <button class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Add Subscriber</button>
    </div>
</div>

<!-- Filters -->
<div class="bg-[#111] border border-[var(--border)] rounded-[14px] p-4 mb-6 flex flex-wrap gap-3">
    <div class="relative flex-grow min-w-[200px] max-w-sm"><i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[var(--text-muted)]"></i><input type="text" placeholder="Search by email…" class="input-field w-full pl-10 h-9 text-sm bg-[var(--surface)]"></div>
    <select class="input-field h-9 text-sm bg-[var(--surface)] pr-8 min-w-[140px]"><option value="">Status</option><option>Subscribed</option><option>Unsubscribed</option></select>
    <select class="input-field h-9 text-sm bg-[var(--surface)] pr-8 min-w-[140px]"><option value="">Source</option><option>Checkout</option><option>Footer Form</option><option>Popup</option></select>
</div>

<!-- Table -->
<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                <th class="px-5 py-3 w-10"><input type="checkbox" class="rounded border-[var(--border)] bg-[var(--surface)]"></th>
                <th class="px-5 py-3 font-medium">Email Address</th><th class="px-5 py-3 font-medium">Source</th>
                <th class="px-5 py-3 font-medium text-center">Subscription Date</th><th class="px-5 py-3 font-medium text-center">GDPR Consent</th>
                <th class="px-5 py-3 font-medium text-center">Status</th><th class="px-5 py-3 font-medium text-center">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($subscribers as $s): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group">
                    <td class="px-5 py-4"><input type="checkbox" class="rounded border-[var(--border)] bg-[var(--surface)]"></td>
                    <td class="px-5 py-4 font-medium text-sm"><?= htmlspecialchars($s['email']) ?></td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= $s['source'] ?></td>
                    <td class="px-5 py-4 text-center text-sm text-[var(--text-muted)]"><?= date('M j, Y', strtotime($s['date'])) ?></td>
                    <td class="px-5 py-4 text-center">
                        <?php if($s['consent']): ?><i data-lucide="check-circle" class="w-4 h-4 text-green-400 mx-auto" title="Consent recorded"></i><?php else: ?><span class="text-[var(--text-muted)]">—</span><?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $s['status'] === 'Subscribed' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-[var(--surface)] text-[var(--text-muted)] border-[var(--border)]' ?>"><?= $s['status'] ?></span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--danger)] hover:border-[var(--danger)]/50 transition-colors" title="<?= $s['status'] === 'Subscribed' ? 'Unsubscribe User' : 'Delete Record' ?>"><i data-lucide="<?= $s['status'] === 'Subscribed' ? 'user-x' : 'trash-2' ?>" class="w-3.5 h-3.5"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
