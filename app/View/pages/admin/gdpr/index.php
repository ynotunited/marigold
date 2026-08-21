<?php
/**
 * Admin GDPR Dashboard — pending deletions + compliance receipts.
 * Variables: $pending, $receipts, $retention_days
 */
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold font-manrope">GDPR & Data Retention</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1">
            Manage account deletions, data exports, and compliance receipts.
            Retention window: <strong><?= $retention_days ?> days</strong>.
        </p>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-[#111] border border-[var(--border)] rounded-[12px] p-5">
        <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-medium mb-1">Pending Deletions</p>
        <p class="text-2xl font-bold font-manrope"><?= count($pending) ?></p>
    </div>
    <div class="bg-[#111] border border-[var(--border)] rounded-[12px] p-5">
        <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-medium mb-1">Total Compliance Receipts</p>
        <p class="text-2xl font-bold font-manrope"><?= count($receipts) ?></p>
    </div>
    <div class="bg-[#111] border border-[var(--border)] rounded-[12px] p-5">
        <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-medium mb-1">Retention Window</p>
        <p class="text-2xl font-bold font-manrope"><?= $retention_days ?>d</p>
    </div>
</div>

<!-- Pending Deletions -->
<h2 class="text-lg font-bold font-manrope mb-4">Pending Deletions</h2>

<?php if (empty($pending)): ?>
<div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-8 text-center mb-8">
    <p class="text-[var(--text-muted)] text-sm">No accounts pending deletion.</p>
</div>
<?php else: ?>
<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                    <th class="px-5 py-3 font-medium">User</th>
                    <th class="px-5 py-3 font-medium">Email</th>
                    <th class="px-5 py-3 font-medium">Requested</th>
                    <th class="px-5 py-3 font-medium text-center">Days Left</th>
                    <th class="px-5 py-3 font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($pending as $p): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                    <td class="px-5 py-4 font-medium text-sm"><?= htmlspecialchars($p['name']) ?></td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= htmlspecialchars($p['email']) ?></td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= $p['deleted_at'] ?></td>
                    <td class="px-5 py-4 text-center">
                        <?php if ($p['days_left'] <= 3): ?>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                            <?= $p['days_left'] ?>d left
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                            <?= $p['days_left'] ?>d left
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?= app_url('/admin/gdpr/export/' . $p['id']) ?>" class="text-xs text-[var(--gold)] hover:underline" target="_blank">Export</a>
                            <span class="text-[var(--text-muted)]">|</span>
                            <form method="POST" action="<?= app_url('/admin/gdpr/restore/' . $p['id']) ?>" style="display:inline;" onsubmit="return confirm('Restore this user account?')">
                                <?= \App\Core\CSRF::field() ?>
                                <button type="submit" class="text-xs text-green-400 hover:underline">Restore</button>
                            </form>
                            <span class="text-[var(--text-muted)]">|</span>
                            <form method="POST" action="<?= app_url('/admin/gdpr/force-delete/' . $p['id']) ?>" style="display:inline;" onsubmit="return confirm('PERMANENTLY DELETE this user and all their data? This cannot be undone.')">
                                <?= \App\Core\CSRF::field() ?>
                                <button type="submit" class="text-xs text-red-400 hover:underline font-semibold">Force Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Compliance Receipts -->
<h2 class="text-lg font-bold font-manrope mb-4">Compliance Receipts</h2>

<?php if (empty($receipts)): ?>
<div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-8 text-center">
    <p class="text-[var(--text-muted)] text-sm">No compliance receipts yet.</p>
</div>
<?php else: ?>
<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                    <th class="px-5 py-3 font-medium">ID</th>
                    <th class="px-5 py-3 font-medium">User ID</th>
                    <th class="px-5 py-3 font-medium">Action</th>
                    <th class="px-5 py-3 font-medium">Tables Affected</th>
                    <th class="px-5 py-3 font-medium">Row Counts</th>
                    <th class="px-5 py-3 font-medium">Anonymized</th>
                    <th class="px-5 py-3 font-medium">Initiated By</th>
                    <th class="px-5 py-3 font-medium text-center">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($receipts as $r): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                    <td class="px-5 py-4 font-mono text-xs text-[var(--text-muted)]">#<?= $r['id'] ?></td>
                    <td class="px-5 py-4 font-mono text-xs">#<?= $r['user_id'] ?></td>
                    <td class="px-5 py-4">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold
                            <?= $r['action'] === 'delete' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' ?>
                            <?= $r['action'] === 'anonymize' ? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' : '' ?>
                            <?= $r['action'] === 'export' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' ?>
                        ">
                            <?= ucfirst($r['action']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-4 text-xs text-[var(--text-secondary)]">
                        <?= implode(', ', array_slice($r['tables'], 0, 4)) ?>
                        <?= count($r['tables']) > 4 ? ' +' . (count($r['tables']) - 4) . ' more' : '' ?>
                    </td>
                    <td class="px-5 py-4 text-xs text-[var(--text-secondary)]">
                        <?= array_sum($r['row_counts']) ?> total
                    </td>
                    <td class="px-5 py-4 text-xs">
                        <?php if ($r['anonymized']): ?>
                        <span class="text-yellow-400"><?= implode(', ', $r['anonymized']) ?></span>
                        <?php else: ?>
                        <span class="text-[var(--text-muted)]">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-sm">
                        <span class="inline-flex items-center gap-1">
                            <?php if ($r['initiated_by'] === 'customer'): ?>
                            <span class="text-blue-400 text-xs">Customer</span>
                            <?php elseif ($r['initiated_by'] === 'admin'): ?>
                            <span class="text-[var(--gold)] text-xs">Admin: <?= htmlspecialchars($r['initiator']) ?></span>
                            <?php else: ?>
                            <span class="text-purple-400 text-xs">System (cron)</span>
                            <?php endif; ?>
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center text-sm text-[var(--text-secondary)]"><?= $r['date'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
