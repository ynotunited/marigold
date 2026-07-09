<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Pages (CMS)</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($pages) ?> custom pages</p>
    </div>
    <div class="flex items-center gap-3">
        <button class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Create Page
        </button>
    </div>
</div>

<!-- Table -->
<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                <th class="px-5 py-3 font-medium">Page Title</th><th class="px-5 py-3 font-medium">URL Path</th>
                <th class="px-5 py-3 font-medium text-center">Sections</th><th class="px-5 py-3 font-medium">Last Updated</th>
                <th class="px-5 py-3 font-medium text-center">Status</th><th class="px-5 py-3 font-medium text-center">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($pages as $p): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group cursor-pointer" onclick="window.location='/admin/cms/<?= $p['id'] ?>/builder'">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="layout" class="w-4 h-4 text-[var(--text-muted)]"></i>
                            <a href="/admin/cms/<?= $p['id'] ?>/builder" class="font-medium text-sm group-hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($p['title']) ?></a>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm font-mono text-[var(--text-secondary)]"><?= $p['slug'] ?></td>
                    <td class="px-5 py-4 text-sm text-center text-[var(--text-secondary)]"><?= $p['sections'] ?></td>
                    <td class="px-5 py-4 text-sm text-[var(--text-muted)]"><?= $p['updated'] ?></td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $p['status'] === 'Published' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' ?>"><?= $p['status'] ?></span>
                    </td>
                    <td class="px-5 py-4" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="/admin/cms/<?= $p['id'] ?>/builder" class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--gold)] hover:border-[var(--gold)]/50 transition-colors" title="Open Builder"><i data-lucide="edit" class="w-3.5 h-3.5"></i></a>
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-blue-400 transition-colors" title="Duplicate Page"><i data-lucide="copy" class="w-3.5 h-3.5"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
