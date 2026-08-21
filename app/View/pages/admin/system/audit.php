<?php
/**
 * Admin Audit Log — shows who did what, when, with before/after values.
 * Variables: $logs, $total, $page, $pages, $filters
 */
$q = htmlspecialchars($filters['q'] ?? '', ENT_QUOTES, 'UTF-8');
$entity = htmlspecialchars($filters['entity'] ?? '', ENT_QUOTES, 'UTF-8');
$action = htmlspecialchars($filters['action'] ?? '', ENT_QUOTES, 'UTF-8');
$from = htmlspecialchars($filters['from'] ?? '', ENT_QUOTES, 'UTF-8');
$to = htmlspecialchars($filters['to'] ?? '', ENT_QUOTES, 'UTF-8');
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Audit Log</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1">
            <?= number_format($total) ?> total entries — who did what, when
        </p>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="<?= app_url('/admin/audit') ?>" class="bg-[#111] border border-[var(--border)] rounded-[12px] p-4 mb-4">
    <div class="flex flex-col sm:flex-row gap-3 items-end">
        <div class="flex-1 min-w-0">
            <label class="block text-xs text-[var(--text-muted)] mb-1 font-medium">Search</label>
            <input type="text" name="q" value="<?= $q ?>" placeholder="Action, URI, entity…"
                   class="w-full bg-[var(--surface)] border border-[var(--border)] rounded-[8px] px-3 h-9 text-sm text-white placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--gold)]/50">
        </div>
        <div class="w-full sm:w-44">
            <label class="block text-xs text-[var(--text-muted)] mb-1 font-medium">Entity</label>
            <select name="entity" class="w-full bg-[var(--surface)] border border-[var(--border)] rounded-[8px] px-3 h-9 text-sm text-white focus:outline-none focus:border-[var(--gold)]/50">
                <option value="">All entities</option>
                <option value="users" <?= $entity==='users'?'selected':'' ?>>Users</option>
                <option value="products" <?= $entity==='products'?'selected':'' ?>>Products</option>
                <option value="orders" <?= $entity==='orders'?'selected':'' ?>>Orders</option>
                <option value="customers" <?= $entity==='customers'?'selected':'' ?>>Customers</option>
            </select>
        </div>
        <div class="w-full sm:w-44">
            <label class="block text-xs text-[var(--text-muted)] mb-1 font-medium">Action</label>
            <input type="text" name="action" value="<?= $action ?>" placeholder="e.g. auth.login"
                   class="w-full bg-[var(--surface)] border border-[var(--border)] rounded-[8px] px-3 h-9 text-sm text-white placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--gold)]/50">
        </div>
        <div class="w-full sm:w-36">
            <label class="block text-xs text-[var(--text-muted)] mb-1 font-medium">From</label>
            <input type="date" name="from" value="<?= $from ?>" class="w-full bg-[var(--surface)] border border-[var(--border)] rounded-[8px] px-3 h-9 text-sm text-white focus:outline-none focus:border-[var(--gold)]/50">
        </div>
        <div class="w-full sm:w-36">
            <label class="block text-xs text-[var(--text-muted)] mb-1 font-medium">To</label>
            <input type="date" name="to" value="<?= $to ?>" class="w-full bg-[var(--surface)] border border-[var(--border)] rounded-[8px] px-3 h-9 text-sm text-white focus:outline-none focus:border-[var(--gold)]/50">
        </div>
        <div class="flex gap-2 shrink-0">
            <button type="submit" class="btn btn-gold h-9 px-5 text-sm font-semibold">Filter</button>
            <a href="<?= app_url('/admin/audit') ?>" class="btn btn-secondary h-9 px-4 text-sm border border-[var(--border)] bg-[var(--surface)]">Clear</a>
        </div>
    </div>
</form>

<!-- Log Table -->
<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                    <th class="px-5 py-3 font-medium">#</th>
                    <th class="px-5 py-3 font-medium">User</th>
                    <th class="px-5 py-3 font-medium">Action</th>
                    <th class="px-5 py-3 font-medium">Entity</th>
                    <th class="px-5 py-3 font-medium">Changes</th>
                    <th class="px-5 py-3 font-medium">IP</th>
                    <th class="px-5 py-3 font-medium text-center">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-[var(--text-muted)] text-sm">
                        No audit log entries found.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($logs as $l): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors cursor-default" x-data="{ open: false }">
                    <td class="px-5 py-4 font-mono text-xs text-[var(--text-muted)]">#<?= $l['id'] ?></td>
                    <td class="px-5 py-4 font-medium text-sm">
                        <?= htmlspecialchars($l['user']) ?>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 text-sm">
                            <?php
                            $actionColor = match(true) {
                                str_starts_with($l['action'], 'auth.login_failed') => 'text-red-400',
                                str_starts_with($l['action'], 'auth.login_rate') => 'text-orange-400',
                                str_starts_with($l['action'], 'auth.login_success') => 'text-green-400',
                                str_starts_with($l['action'], 'product.deleted') => 'text-red-400',
                                str_starts_with($l['action'], 'product.created') => 'text-green-400',
                                str_starts_with($l['action'], 'product.updated') => 'text-blue-400',
                                str_starts_with($l['action'], 'order.') => 'text-purple-400',
                                str_starts_with($l['action'], 'auth.') => 'text-[var(--gold)]',
                                default => 'text-[var(--text-secondary)]',
                            };
                            ?>
                            <span class="<?= $actionColor ?> font-mono text-xs"><?= htmlspecialchars($l['action']) ?></span>
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)]">
                        <?= htmlspecialchars($l['entity'] ?: '—') ?>
                        <?php if ($l['entity_id']): ?><span class="text-[var(--text-muted)]">#<?= $l['entity_id'] ?></span><?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-sm">
                        <?php if ($l['old'] || $l['new']): ?>
                        <button @click="open = !open" class="text-[var(--gold)] hover:underline text-xs font-medium">
                            <span x-text="open ? 'Hide' : 'View'">View</span>
                            changes
                        </button>
                        <div x-show="open" x-transition class="mt-2 text-xs space-y-1 bg-[var(--surface)] rounded-lg p-3 border border-[var(--border)] max-w-xs">
                            <?php foreach ($l['new'] as $k => $v): ?>
                            <div>
                                <span class="text-[var(--text-muted)]"><?= htmlspecialchars($k) ?>:</span>
                                <?php if (isset($l['old'][$k])): ?>
                                    <span class="text-red-400 line-through"><?= htmlspecialchars(is_array($l['old'][$k]) ? json_encode($l['old'][$k]) : (string)$l['old'][$k]) ?></span>
                                    &rarr;
                                <?php endif; ?>
                                <span class="text-green-400"><?= htmlspecialchars(is_array($v) ? json_encode($v) : (string)$v) ?></span>
                            </div>
                            <?php endforeach; ?>
                            <?php foreach ($l['old'] as $k => $v): ?>
                            <?php if (!isset($l['new'][$k])): ?>
                            <div>
                                <span class="text-[var(--text-muted)]"><?= htmlspecialchars($k) ?>:</span>
                                <span class="text-red-400 line-through"><?= htmlspecialchars(is_array($v) ? json_encode($v) : (string)$v) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <span class="text-[var(--text-muted)]">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 font-mono text-xs text-[var(--text-muted)]" title="<?= htmlspecialchars($l['uri'] ?: '') ?>">
                        <?= htmlspecialchars($l['ip'] ?: '—') ?>
                    </td>
                    <td class="px-5 py-4 text-center text-sm text-[var(--text-secondary)]"><?= $l['date'] ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if ($pages > 1): ?>
<nav class="flex justify-center items-center gap-2 mt-6" aria-label="Pagination">
    <?php
    $buildUrl = function(int $p) use ($filters) {
        $params = array_filter(array_merge($filters, ['page' => $p]));
        return app_url('/admin/audit') . '?' . http_build_query($params);
    };
    ?>
    <?php if ($page > 1): ?>
    <a href="<?= $buildUrl($page - 1) ?>" class="btn btn-secondary h-8 px-3 text-xs border border-[var(--border)] bg-[var(--surface)]">&laquo; Prev</a>
    <?php endif; ?>

    <?php
    $start = max(1, $page - 3);
    $end = min($pages, $page + 3);
    if ($start > 1): ?>
        <a href="<?= $buildUrl(1) ?>" class="w-8 h-8 rounded-lg border border-[var(--border)] bg-[var(--surface)] flex items-center justify-center text-xs text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)]/50 transition-colors">1</a>
        <?php if ($start > 2): ?><span class="text-[var(--text-muted)]">…</span><?php endif; ?>
    <?php endif; ?>
    <?php for ($i = $start; $i <= $end; $i++): ?>
    <a href="<?= $buildUrl($i) ?>" class="w-8 h-8 rounded-lg border flex items-center justify-center text-xs font-medium transition-colors
        <?= $i === $page ? 'bg-[var(--gold)] text-black border-[var(--gold)]' : 'border-[var(--border)] bg-[var(--surface)] text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)]/50' ?>">
        <?= $i ?>
    </a>
    <?php endfor; ?>
    <?php if ($end < $pages): ?>
        <?php if ($end < $pages - 1): ?><span class="text-[var(--text-muted)]">…</span><?php endif; ?>
        <a href="<?= $buildUrl($pages) ?>" class="w-8 h-8 rounded-lg border border-[var(--border)] bg-[var(--surface)] flex items-center justify-center text-xs text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)]/50 transition-colors"><?= $pages ?></a>
    <?php endif; ?>

    <?php if ($page < $pages): ?>
    <a href="<?= $buildUrl($page + 1) ?>" class="btn btn-secondary h-8 px-3 text-xs border border-[var(--border)] bg-[var(--surface)]">Next &raquo;</a>
    <?php endif; ?>
</nav>
<?php endif; ?>
