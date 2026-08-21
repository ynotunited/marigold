<?php
/**
 * Accordion Item Component (Requires Alpine.js)
 * 
 * @param string $title
 * @param string $content
 * @param bool $isOpen Default open state
 */
$isOpen = $isOpen ?? false;
$openStr = $isOpen ? 'true' : 'false';
?>
<div x-data="{ open: <?= $openStr ?> }" class="border-b border-[var(--border)] last:border-0">
    <button @click="open = !open" class="flex justify-between items-center w-full py-4 text-left focus:outline-none group">
        <span class="font-medium text-[var(--text-primary)] group-hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($title) ?></span>
        <span class="ml-6 flex items-center justify-center w-6 h-6 rounded-full border border-[var(--border)] group-hover:border-[var(--gold)] transition-colors">
            <i data-lucide="chevron-down" 
               class="w-4 h-4 text-[var(--text-secondary)] transition-transform duration-300"
               :class="{'rotate-180 text-[var(--gold)]': open}"></i>
        </span>
    </button>
    <div x-show="open" 
         x-collapse 
         class="overflow-hidden">
        <div class="pb-4 pt-1 text-[var(--text-secondary)] text-sm leading-relaxed">
            <?= $content ?>
        </div>
    </div>
</div>
