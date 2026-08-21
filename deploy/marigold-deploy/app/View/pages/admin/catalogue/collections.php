<!-- Collections -->
<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-bold font-manrope">Collections</h1><p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($collections) ?> active collections</p></div>
    <button class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> New Collection</button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-10">
    <?php foreach ($collections as $c):
        $scls = match($c['status']) { 'Active' => 'bg-green-500/10 text-green-400 border-green-500/20', 'Draft' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20', default => 'bg-blue-500/10 text-blue-400 border-blue-500/20' };
    ?>
    <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5 hover:border-[var(--gold)]/40 transition-colors group flex flex-col gap-4">
        <div class="flex items-start justify-between">
            <h3 class="font-semibold text-sm group-hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($c['name']) ?></h3>
            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold border uppercase <?= $scls ?>"><?= $c['status'] ?></span>
        </div>
        <p class="text-2xl font-bold font-manrope text-[var(--gold)]"><?= $c['products'] ?> <span class="text-sm text-[var(--text-muted)] font-normal">products</span></p>
        <div class="flex items-center gap-2 mt-auto pt-3 border-t border-[var(--border)]">
            <button class="flex-1 btn btn-secondary border border-[var(--border)] h-8 text-xs bg-[var(--surface)]">Edit</button>
            <button class="w-8 h-8 rounded-[8px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--danger)]/60 hover:text-[var(--danger)] hover:border-[var(--danger)]/50 transition-colors"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
        </div>
    </div>
    <?php endforeach; ?>
    <!-- Add new card -->
    <div class="border-2 border-dashed border-[var(--border)] rounded-[16px] p-5 flex flex-col items-center justify-center gap-3 text-[var(--text-muted)] hover:border-[var(--gold)]/50 hover:text-[var(--gold)] transition-colors cursor-pointer min-h-[180px] group">
        <div class="w-10 h-10 rounded-full bg-[var(--surface)] flex items-center justify-center group-hover:bg-[var(--gold)]/10 transition-colors"><i data-lucide="plus" class="w-5 h-5"></i></div>
        <span class="text-sm font-medium">New Collection</span>
    </div>
</div>

<!-- Corporate Solutions -->
<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-bold font-manrope">Corporate Solutions</h1><p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($solutions) ?> solution packages</p></div>
    <button class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> New Solution</button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <?php foreach ($solutions as $s): ?>
    <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6 hover:border-[var(--gold)]/40 transition-colors group flex items-start gap-5">
        <div class="w-12 h-12 rounded-[12px] bg-[var(--gold)]/10 border border-[var(--gold)]/30 flex items-center justify-center text-[var(--gold)] shrink-0">
            <i data-lucide="<?= $s['icon'] ?>" class="w-6 h-6"></i>
        </div>
        <div class="flex-grow">
            <div class="flex items-center justify-between mb-1">
                <h3 class="font-semibold group-hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($s['name']) ?></h3>
                <span class="text-xs <?= $s['status'] === 'Active' ? 'text-green-400' : 'text-yellow-400' ?>"><?= $s['status'] ?></span>
            </div>
            <p class="text-sm text-[var(--text-secondary)]"><?= $s['products'] ?> linked products</p>
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--gold)] hover:border-[var(--gold)]/50 transition-colors"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></button>
            <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-[var(--danger)] hover:border-[var(--danger)]/50 transition-colors"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
        </div>
    </div>
    <?php endforeach; ?>
</div>
