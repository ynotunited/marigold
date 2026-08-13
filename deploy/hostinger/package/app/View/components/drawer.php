<?php
/**
 * Drawer Component (Requires Alpine.js)
 * 
 * @param string $id Drawer identifier
 * @param string $title
 * @param string $content HTML content
 * @param string $position right|bottom|left
 */
$position = $position ?? 'right';
$isBottom = $position === 'bottom';
$isLeft = $position === 'left';
?>
<div x-data="{ open: false }" 
     @open-drawer-<?= $id ?>.window="open = true" 
     @keydown.escape.window="open = false"
     class="relative z-50">
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm"
         @click="open = false"></div>

    <!-- Drawer Panel -->
    <div x-show="open"
         <?php if($isBottom): ?>
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed inset-x-0 bottom-0 max-h-[90vh] bg-[var(--surface)] border-t border-[var(--border)] rounded-t-[20px] shadow-2xl flex flex-col"
         <?php elseif($isLeft): ?>
         x-transition:enter="ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 w-full max-w-sm bg-[var(--surface)] border-r border-[var(--border)] shadow-2xl flex flex-col"
         <?php else: ?>
         x-transition:enter="ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 w-full max-w-sm bg-[var(--surface)] border-l border-[var(--border)] shadow-2xl flex flex-col"
         <?php endif; ?>>
         
         <!-- Header -->
         <div class="px-6 py-4 border-b border-[var(--border)] flex justify-between items-center">
             <h3 class="text-xl font-bold font-['Manrope']"><?= $title ?></h3>
             <button @click="open = false" class="text-[var(--text-secondary)] hover:text-white transition-colors">
                 <i data-lucide="x" class="w-6 h-6"></i>
             </button>
         </div>
         
         <!-- Body -->
         <div class="flex-1 overflow-y-auto p-6">
             <?= $content ?>
         </div>
    </div>
</div>
