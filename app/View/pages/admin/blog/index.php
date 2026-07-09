<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Blog Posts</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($posts) ?> published articles</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="/admin/blog/create" class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2">
            <i data-lucide="pen-tool" class="w-4 h-4"></i> Write Post
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-[#111] border border-[var(--border)] rounded-[14px] p-4 mb-6 flex flex-wrap gap-3">
    <div class="relative flex-grow min-w-[200px] max-w-sm"><i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[var(--text-muted)]"></i><input type="text" placeholder="Search titles, authors…" class="input-field w-full pl-10 h-9 text-sm bg-[var(--surface)]"></div>
    <select class="input-field h-9 text-sm bg-[var(--surface)] pr-8 min-w-[140px]"><option value="">Category</option><option>Gift Guides</option><option>Marketing</option><option>Sustainability</option></select>
    <select class="input-field h-9 text-sm bg-[var(--surface)] pr-8 min-w-[140px]"><option value="">Status</option><option>Published</option><option>Draft</option><option>Scheduled</option></select>
</div>

<!-- Table -->
<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                <th class="px-5 py-3 w-10"><input type="checkbox" class="rounded border-[var(--border)] bg-[var(--surface)]"></th>
                <th class="px-5 py-3 font-medium">Title</th><th class="px-5 py-3 font-medium">Category</th>
                <th class="px-5 py-3 font-medium">Author</th><th class="px-5 py-3 font-medium text-center">Views</th>
                <th class="px-5 py-3 font-medium text-center">Date</th><th class="px-5 py-3 font-medium text-center">Status</th>
                <th class="px-5 py-3 font-medium text-center">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php
                $statusCls = ['Published' => 'bg-green-500/10 text-green-400 border-green-500/20', 'Draft' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20', 'Scheduled' => 'bg-blue-500/10 text-blue-400 border-blue-500/20'];
                foreach ($posts as $p): $sc = $statusCls[$p['status']] ?? '';
                ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group cursor-pointer" onclick="window.location='/admin/blog/<?= $p['id'] ?>/edit'">
                    <td class="px-5 py-4" onclick="event.stopPropagation()"><input type="checkbox" class="rounded border-[var(--border)] bg-[var(--surface)]"></td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <?php if ($p['featured']): ?><i data-lucide="star" class="w-3.5 h-3.5 text-[var(--gold)] fill-[var(--gold)]" title="Featured Article"></i><?php endif; ?>
                            <a href="/admin/blog/<?= $p['id'] ?>/edit" class="font-medium text-sm group-hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($p['title']) ?></a>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= $p['category'] ?></td>
                    <td class="px-5 py-4 text-sm text-[var(--text-secondary)]"><?= $p['author'] ?></td>
                    <td class="px-5 py-4 text-sm text-center text-[var(--text-muted)]"><?= number_format($p['views']) ?></td>
                    <td class="px-5 py-4 text-center text-sm text-[var(--text-muted)]"><?= date('M j, Y', strtotime($p['date'])) ?></td>
                    <td class="px-5 py-4 text-center"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $sc ?>"><?= $p['status'] ?></span></td>
                    <td class="px-5 py-4" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="/admin/blog/<?= $p['id'] ?>/edit" class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--gold)] hover:border-[var(--gold)]/50 transition-colors"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--danger)] hover:border-[var(--danger)]/50 transition-colors"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
