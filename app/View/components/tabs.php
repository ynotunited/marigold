<?php
/**
 * Tabs Component (Requires Alpine.js)
 * 
 * @param array $tabs Associative array of ['id' => 'Label']
 * @param string $defaultActiveId
 * @param array $contents Associative array of ['id' => 'HTML content']
 */
$defaultActiveId = $defaultActiveId ?? array_key_first($tabs);
?>
<div x-data="{ activeTab: '<?= $defaultActiveId ?>' }">
    <div class="border-b border-[var(--border)] overflow-x-auto hide-scrollbar">
        <nav class="flex space-x-8" aria-label="Tabs">
            <?php foreach ($tabs as $id => $label): ?>
                <button 
                    @click="activeTab = '<?= $id ?>'"
                    :class="activeTab === '<?= $id ?>' ? 'border-[var(--gold)] text-[var(--gold)]' : 'border-transparent text-[var(--text-secondary)] hover:border-[var(--border)] hover:text-white'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                >
                    <?= htmlspecialchars($label) ?>
                </button>
            <?php endforeach; ?>
        </nav>
    </div>
    
    <div class="mt-6">
        <?php foreach ($contents as $id => $content): ?>
            <div x-show="activeTab === '<?= $id ?>'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">
                <?= $content ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
