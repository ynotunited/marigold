<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold mb-2">My Quotes</h1>
        <p class="text-[var(--text-secondary)]">Review bespoke pricing requests and approvals.</p>
    </div>
    
    <div class="flex items-center gap-4">
        <a href="/quotes/new" class="btn btn-primary h-[44px] px-6">Request New Quote</a>
    </div>
</div>

<!-- Tabs -->
<div class="flex items-center gap-6 border-b border-[var(--border)] mb-8 overflow-x-auto hide-scrollbar">
    <button class="pb-3 text-white border-b-2 border-[var(--gold)] font-medium whitespace-nowrap">All Quotes</button>
    <button class="pb-3 text-[var(--text-secondary)] hover:text-white transition-colors whitespace-nowrap">Pending</button>
    <button class="pb-3 text-[var(--text-secondary)] hover:text-white transition-colors whitespace-nowrap">Approved</button>
    <button class="pb-3 text-[var(--text-secondary)] hover:text-white transition-colors whitespace-nowrap">Expired</button>
</div>

<!-- Quotes List -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php foreach($quotes as $quote): ?>
    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6 hover:border-[var(--gold)]/50 transition-colors group flex flex-col">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-xl font-bold font-manrope group-hover:text-[var(--gold)] transition-colors mb-1"><?= $quote['id'] ?></h3>
                <p class="text-sm text-[var(--text-secondary)]"><?= date('M j, Y', strtotime($quote['date'])) ?></p>
            </div>
            <?php if($quote['status'] === 'Approved'): ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>
            <?php elseif($quote['status'] === 'Expired'): ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">Expired</span>
            <?php else: ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20"><?= $quote['status'] ?></span>
            <?php endif; ?>
        </div>
        
        <div class="flex-grow">
            <p class="text-[var(--text-secondary)] text-sm mb-6"><span class="text-white font-medium"><?= $quote['items'] ?></span> custom items requested.</p>
        </div>
        
        <div class="border-t border-[var(--border)] pt-4 flex items-center justify-between mt-auto">
            <a href="/account/quotes/<?= $quote['id'] ?>" class="text-[var(--gold)] text-sm font-medium hover:text-white transition-colors flex items-center">
                Review Details <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
