<?php
// ─── Shared CRUD table renderer helper ───
function adminTableHeader(array $cols): void {
    echo '<thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">';
    foreach ($cols as $col) echo "<th class=\"px-5 py-3 font-medium\">$col</th>";
    echo '</tr></thead>';
}
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Categories</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($categories) ?> categories in total</p>
    </div>
    <button x-data @click="$dispatch('open-cat-modal')" class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Add Category
    </button>
</div>

<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden" x-data>

    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <?php adminTableHeader(['Name', 'Slug', 'Parent', 'Products', 'Sort', 'Status', 'Actions']); ?>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($categories as $cat): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group">
                    <td class="px-5 py-4">
                        <span class="font-medium text-sm <?= $cat['parent'] ? 'pl-4 border-l-2 border-[var(--gold)]/30' : '' ?>"><?= htmlspecialchars($cat['name']) ?></span>
                    </td>
                    <td class="px-5 py-4 text-sm font-mono text-[var(--text-muted)]"><?= $cat['slug'] ?></td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= $cat['parent'] ?? '—' ?></td>
                    <td class="px-5 py-4 text-sm text-center"><?= $cat['products'] ?></td>
                    <td class="px-5 py-4 text-sm text-center text-[var(--text-secondary)]"><?= $cat['sort'] ?></td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                            <?= $cat['status'] === 'Active' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' ?>">
                            <?= $cat['status'] ?>
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
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

<!-- Add/Edit Modal -->
<div x-data="{ open: false }" @open-cat-modal.window="open = true" x-show="open" x-transition.opacity
     class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-6" style="display:none">
    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[20px] w-full max-w-[520px] p-8" @click.stop>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold font-manrope">Add Category</h2>
            <button @click="open = false" class="text-[var(--text-muted)] hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form class="space-y-4">
            <div><label class="form-label">Category Name</label><input type="text" class="input-field w-full mt-1" placeholder="e.g. Stationery"></div>
            <div><label class="form-label">Slug</label><input type="text" class="input-field w-full mt-1 font-mono text-sm" placeholder="auto-generated"></div>
            <div><label class="form-label">Parent Category</label><select class="input-field w-full mt-1"><option value="">None (Top Level)</option><?php foreach(array_filter($categories, fn($c) => !$c['parent']) as $c): ?><option><?= $c['name'] ?></option><?php endforeach; ?></select></div>
            <div><label class="form-label">Sort Order</label><input type="number" value="1" class="input-field w-full mt-1 text-sm"></div>
            <div><label class="form-label">Status</label><select class="input-field w-full mt-1"><option>Active</option><option>Hidden</option></select></div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="open=false" class="btn btn-secondary border border-[var(--border)] flex-1 h-11">Cancel</button>
                <button type="submit" class="btn btn-primary flex-1 h-11">Save Category</button>
            </div>
        </form>
    </div>
</div>

<style>.form-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 600; }</style>
