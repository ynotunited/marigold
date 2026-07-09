<?php
/**
 * Modal Component (Requires Alpine.js)
 * 
 * @param string $id Modal identifier
 * @param string $title
 * @param string $content HTML content
 * @param string $footer HTML footer (optional)
 */
?>
<div x-data="{ open: false }" 
     @open-modal-<?= $id ?>.window="open = true" 
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

    <!-- Modal Panel -->
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
         
         <div class="bg-[var(--card)] border border-[var(--border)] rounded-[20px] shadow-2xl w-full max-w-lg pointer-events-auto overflow-hidden">
             
             <!-- Header -->
             <div class="px-6 py-4 border-b border-[var(--border)] flex justify-between items-center">
                 <h3 class="text-xl font-bold font-['Manrope']"><?= $title ?></h3>
                 <button @click="open = false" class="text-[var(--text-secondary)] hover:text-white transition-colors">
                     <i data-lucide="x" class="w-6 h-6"></i>
                 </button>
             </div>
             
             <!-- Body -->
             <div class="px-6 py-4">
                 <?= $content ?>
             </div>
             
             <!-- Footer -->
             <?php if (isset($footer)): ?>
                 <div class="px-6 py-4 bg-black/20 border-t border-[var(--border)] flex justify-end gap-3">
                     <?= $footer ?>
                 </div>
             <?php endif; ?>
         </div>
    </div>
</div>
