<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-bold font-manrope">Brands</h1><p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($brands) ?> brands registered</p></div>
    <button x-data @click="$dispatch('open-brand-modal')" class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Add Brand</button>
</div>

<div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden" x-data>
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead><tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                <th class="px-5 py-3 font-medium">Brand</th><th class="px-5 py-3 font-medium text-center">Products</th>
                <th class="px-5 py-3 font-medium text-center">Featured</th><th class="px-5 py-3 font-medium text-center">Status</th>
                <th class="px-5 py-3 font-medium text-center">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-[var(--border)]">
                <?php foreach ($brands as $b): ?>
                <tr class="hover:bg-[var(--surface)]/40 transition-colors group">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-[8px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center overflow-hidden shrink-0">
                                <span class="text-sm font-bold text-[var(--gold)]"><?= strtoupper(substr($b['name'], 0, 2)) ?></span>
                            </div>
                            <span class="font-medium text-sm"><?= htmlspecialchars($b['name']) ?></span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-center"><?= $b['products'] ?></td>
                    <td class="px-5 py-4 text-center">
                        <?php if ($b['featured']): ?>
                        <span class="inline-flex items-center gap-1 text-[var(--gold)] text-xs font-bold"><i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i> Yes</span>
                        <?php else: ?>
                        <span class="text-[var(--text-muted)] text-xs">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-center"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20"><?= $b['status'] ?></span></td>
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

<!-- Add Brand Modal -->
<div x-data="{ open: false }" @open-brand-modal.window="open = true" x-show="open" x-transition.opacity class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-6" style="display:none">
    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[20px] w-full max-w-[480px] p-8" @click.stop>
        <div class="flex items-center justify-between mb-6"><h2 class="text-xl font-bold font-manrope">Add Brand</h2><button @click="open=false" class="text-[var(--text-muted)] hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button></div>
        <form class="space-y-4">
            <div><label class="form-label">Brand Name</label><input type="text" class="input-field w-full mt-1" placeholder="e.g. Moleskine"></div>
            <div><label class="form-label">Logo Image</label><div class="mt-1 border-2 border-dashed border-[var(--border)] rounded-[10px] p-6 text-center cursor-pointer hover:border-[var(--gold)]/50 transition-colors"><i data-lucide="upload-cloud" class="w-8 h-8 text-[var(--text-muted)] mx-auto mb-2"></i><p class="text-xs text-[var(--text-muted)]">Click to upload logo</p></div></div>
            <div><label class="form-label">Website URL</label><input type="url" class="input-field w-full mt-1 text-sm" placeholder="https://moleskine.com"></div>
            <div><label class="form-label">Description</label><textarea rows="3" class="input-field w-full mt-1 resize-none text-sm" placeholder="Brief brand description…"></textarea></div>
            <div class="flex items-center justify-between"><div><p class="font-medium text-sm">Featured Brand</p><p class="text-xs text-[var(--text-secondary)]">Shown on homepage</p></div><label class="relative cursor-pointer"><input type="checkbox" class="sr-only peer"><div class="w-11 h-6 bg-[var(--surface)] rounded-full peer-checked:bg-[var(--gold)] transition-colors border border-[var(--border)]"></div><div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform shadow"></div></label></div>
            <div class="flex gap-3 pt-2"><button type="button" @click="open=false" class="btn btn-secondary border border-[var(--border)] flex-1 h-11">Cancel</button><button type="submit" class="btn btn-primary flex-1 h-11">Save Brand</button></div>
        </form>
    </div>
</div>
<style>.form-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 600; }</style>
