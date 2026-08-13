<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold mb-2">Notifications</h1>
        <p class="text-[var(--text-secondary)]">Stay up to date with your orders, quotes, and news.</p>
    </div>
    <button class="text-sm text-[var(--gold)] hover:text-white transition-colors font-medium">Mark all as read</button>
</div>

<div class="space-y-3">
    <?php foreach($notifications as $n): ?>
    <a href="<?= $n['link'] ?>" class="flex items-start gap-5 p-5 rounded-[16px] border transition-colors group
        <?= !$n['is_read'] ? 'bg-[var(--card)] border-[var(--gold)]/30 hover:border-[var(--gold)]/60' : 'bg-[var(--surface)]/40 border-[var(--border)] hover:border-[var(--border)] opacity-70 hover:opacity-100' ?>">
        
        <!-- Icon with Unread dot -->
        <div class="relative shrink-0">
            <div class="w-12 h-12 rounded-full flex items-center justify-center
                <?= $n['type'] === 'order' ? 'bg-blue-500/10 text-blue-400' : ($n['type'] === 'quote' ? 'bg-[var(--gold)]/10 text-[var(--gold)]' : 'bg-purple-500/10 text-purple-400') ?>">
                <i data-lucide="<?= $n['icon'] ?>" class="w-5 h-5"></i>
            </div>
            <?php if(!$n['is_read']): ?>
                <span class="absolute -top-0.5 -right-0.5 w-3 h-3 rounded-full bg-[var(--gold)] border-2 border-[#0a0a0a]"></span>
            <?php endif; ?>
        </div>
        
        <div class="flex-grow min-w-0">
            <div class="flex items-start justify-between gap-4">
                <h4 class="font-semibold group-hover:text-[var(--gold)] transition-colors <?= !$n['is_read'] ? 'text-white' : 'text-[var(--text-secondary)]' ?>">
                    <?= htmlspecialchars($n['title']) ?>
                </h4>
                <span class="text-xs text-[var(--text-muted)] shrink-0 mt-0.5">
                    <?= date('M j', strtotime($n['time'])) ?>
                </span>
            </div>
            <p class="text-sm text-[var(--text-secondary)] mt-1 line-clamp-1"><?= htmlspecialchars($n['message']) ?></p>
        </div>
    </a>
    <?php endforeach; ?>
</div>
