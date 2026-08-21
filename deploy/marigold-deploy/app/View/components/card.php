<?php
/**
 * Standard Card Component
 * 
 * @param string $title (optional)
 * @param string $content HTML content
 * @param string $footer (optional)
 * @param string $class (optional) Additional classes
 */
$class = $class ?? '';
?>
<div class="card <?= $class ?>">
    <?php if (isset($title)): ?>
        <div class="px-6 py-4 border-b border-[var(--border)]">
            <h3 class="text-lg font-bold font-['Manrope'] text-white"><?= htmlspecialchars($title) ?></h3>
        </div>
    <?php endif; ?>
    
    <div class="p-6">
        <?= $content ?>
    </div>
    
    <?php if (isset($footer)): ?>
        <div class="px-6 py-4 bg-black/20 border-t border-[var(--border)]">
            <?= $footer ?>
        </div>
    <?php endif; ?>
</div>
