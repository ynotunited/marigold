<div class="mb-8">
    <h1 class="text-3xl font-bold mb-2">Downloads</h1>
    <p class="text-[var(--text-secondary)]">All your invoices, receipts, and documents in one place.</p>
</div>

<!-- Filter Tabs -->
<div class="flex items-center gap-6 border-b border-[var(--border)] mb-8 overflow-x-auto hide-scrollbar">
    <button class="pb-3 text-white border-b-2 border-[var(--gold)] font-medium whitespace-nowrap">All Files</button>
    <button class="pb-3 text-[var(--text-secondary)] hover:text-white transition-colors whitespace-nowrap">Invoices</button>
    <button class="pb-3 text-[var(--text-secondary)] hover:text-white transition-colors whitespace-nowrap">Catalogues</button>
    <button class="pb-3 text-[var(--text-secondary)] hover:text-white transition-colors whitespace-nowrap">Manuals</button>
</div>

<div class="space-y-4">
    <?php foreach($downloads as $download): ?>
    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:border-[var(--gold)]/40 transition-colors group">
        <div class="flex items-center gap-5">
            <!-- Icon -->
            <div class="w-14 h-14 rounded-[12px] bg-[var(--surface)] flex items-center justify-center border border-[var(--border)] text-[var(--gold)] shrink-0 group-hover:border-[var(--gold)]/50 transition-colors">
                <i data-lucide="<?= $download['icon'] ?>" class="w-6 h-6"></i>
            </div>
            
            <div>
                <h3 class="font-bold text-white mb-1 group-hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($download['name']) ?></h3>
                <div class="flex items-center gap-3 text-sm text-[var(--text-secondary)]">
                    <span class="uppercase text-xs tracking-wider font-medium"><?= $download['type'] ?></span>
                    <span class="w-1 h-1 rounded-full bg-[var(--border)]"></span>
                    <span><?= $download['size'] ?></span>
                    <span class="w-1 h-1 rounded-full bg-[var(--border)]"></span>
                    <span><?= date('M j, Y', strtotime($download['date'])) ?></span>
                </div>
            </div>
        </div>
        
        <a href="<?= $download['url'] ?>" download class="btn btn-secondary border border-[var(--border)] h-[40px] px-5 text-sm flex items-center gap-2 bg-[var(--surface)] hover:border-[var(--gold)] hover:text-[var(--gold)] transition-colors shrink-0">
            <i data-lucide="download" class="w-4 h-4"></i> Download
        </a>
    </div>
    <?php endforeach; ?>
</div>
