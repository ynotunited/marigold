<?php
/**
 * Toast Component (Requires Alpine.js)
 * 
 * Includes the global toast container and listener.
 * Dispatch from JS: window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Success!', type: 'success' }}));
 */
?>
<div x-data="{ 
        toasts: [],
        add(toast) {
            toast.id = Date.now();
            this.toasts.push(toast);
            setTimeout(() => { this.remove(toast.id) }, 3000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
     }"
     @toast.window="add($event.detail)"
     class="fixed bottom-0 right-0 sm:bottom-6 sm:right-6 z-50 p-4 space-y-3 w-full sm:w-96 flex flex-col items-center sm:items-end pointer-events-none">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true"
             x-transition:enter="ease-out duration-300 transition"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="ease-in duration-200 transition"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             class="w-full pointer-events-auto bg-[var(--card)] border border-[var(--border)] rounded-[12px] shadow-lg overflow-hidden"
             :class="{
                'border-[var(--success)]': toast.type === 'success',
                'border-[var(--danger)]': toast.type === 'error',
                'border-[var(--info)]': toast.type === 'info',
                'border-[var(--warning)]': toast.type === 'warning'
             }">
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    <template x-if="toast.type === 'success'">
                        <i data-lucide="check-circle" class="w-5 h-5 text-[var(--success)]"></i>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <i data-lucide="x-circle" class="w-5 h-5 text-[var(--danger)]"></i>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <i data-lucide="info" class="w-5 h-5 text-[var(--info)]"></i>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-[var(--warning)]"></i>
                    </template>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p x-text="toast.message" class="text-sm font-medium text-white"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="remove(toast.id)" class="bg-transparent rounded-md inline-flex text-[var(--text-secondary)] hover:text-white focus:outline-none">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
