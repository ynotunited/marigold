<?php
/**
 * Breadcrumbs Component
 * 
 * @param array $links Array of ['url' => '/path', 'label' => 'Label']
 */
?>
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="/" class="inline-flex items-center text-sm font-medium text-[var(--text-secondary)] hover:text-white transition-colors">
                <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                Home
            </a>
        </li>
        <?php foreach ($links as $index => $link): ?>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 text-[var(--border)]"></i>
                    <?php if ($index === count($links) - 1): ?>
                        <span class="ml-1 text-sm font-medium text-[var(--gold)] md:ml-2"><?= htmlspecialchars($link['label']) ?></span>
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($link['url']) ?>" class="ml-1 text-sm font-medium text-[var(--text-secondary)] hover:text-white md:ml-2 transition-colors"><?= htmlspecialchars($link['label']) ?></a>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
