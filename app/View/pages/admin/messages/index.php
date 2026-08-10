<?php
// app/View/pages/admin/messages/index.php
$statusBadge = function (string $status): string {
    switch ($status) {
        case 'replied': return 'bg-[var(--gold)]/10 text-[var(--gold)] border-[var(--gold)]/20';
        case 'archived': return 'bg-[var(--surface)] text-[var(--text-muted)] border-[var(--border)]';
        case 'read': return 'bg-[var(--surface)] text-[var(--text-secondary)] border-[var(--border)]';
        default: return 'bg-blue-500/10 text-blue-400 border-blue-500/20';
    }
};
?>
<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Contact Messages</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($messages) ?> total messages<?= $unread > 0 ? ' · ' . $unread . ' unread' : '' ?></p>
    </div>
    <div class="flex items-center gap-3">
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2"><i data-lucide="download" class="w-4 h-4"></i> Export CSV</button>
        <button class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2"><i data-lucide="mail-check" class="w-4 h-4"></i> Mark All Read</button>
    </div>
</div>

<!-- Filters -->
<div class="bg-[#111] border border-[var(--border)] rounded-[14px] p-4 mb-6 flex flex-wrap gap-3">
    <div class="relative flex-grow min-w-[200px] max-w-sm"><i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[var(--text-muted)]"></i><input type="text" placeholder="Search by name, email or subject…" class="input-field w-full pl-10 h-9 text-sm bg-[var(--surface)]"></div>
    <select class="input-field h-9 text-sm bg-[var(--surface)] pr-8 min-w-[140px]"><option value="">Status</option><option>New</option><option>Read</option><option>Replied</option><option>Archived</option></select>
</div>

<!-- Table -->
<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                <th class="px-5 py-3 w-10"><input type="checkbox" class="rounded border-[var(--border)] bg-[var(--surface)]"></th>
                <th class="px-5 py-3 font-medium">Sender</th><th class="px-5 py-3 font-medium">Company</th>
                <th class="px-5 py-3 font-medium">Subject</th><th class="px-5 py-3 font-medium">Message</th>
                <th class="px-5 py-3 font-medium text-center">Date</th><th class="px-5 py-3 font-medium text-center">Status</th>
                <th class="px-5 py-3 font-medium text-center">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($messages as $m): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group">
                    <td class="px-5 py-4"><input type="checkbox" class="rounded border-[var(--border)] bg-[var(--surface)]"></td>
                    <td class="px-5 py-4">
                        <div class="font-medium text-sm"><?= htmlspecialchars($m['name']) ?></div>
                        <div class="text-xs text-[var(--text-muted)]"><?= htmlspecialchars($m['email']) ?><?= !empty($m['phone']) ? ' · ' . htmlspecialchars($m['phone']) : '' ?></div>
                    </td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= htmlspecialchars($m['company'] ?: '—') ?></td>
                    <td class="px-5 py-4 text-sm font-medium"><?= htmlspecialchars($m['subject'] ?: '—') ?></td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)] max-w-[280px]">
                        <span class="block truncate" title="<?= htmlspecialchars($m['message']) ?>"><?= htmlspecialchars(mb_strimwidth($m['message'], 0, 90, '…')) ?></span>
                    </td>
                    <td class="px-5 py-4 text-center text-sm text-[var(--text-muted)]"><?= date('M j, Y g:ia', strtotime($m['created_at'])) ?></td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border capitalize <?= $statusBadge($m['status']) ?>"><?= htmlspecialchars($m['status']) ?></span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--gold)] hover:border-[var(--gold)]/50 transition-colors" title="Mark as read"><i data-lucide="mail-open" class="w-3.5 h-3.5"></i></button>
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--danger)] hover:border-[var(--danger)]/50 transition-colors" title="Delete"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($messages)): ?>
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center">
                        <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-[var(--text-muted)]"></i>
                        <p class="text-sm text-[var(--text-secondary)]">No messages yet. Contact form submissions will appear here.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
