<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold mb-2">Address Book</h1>
        <p class="text-[var(--text-secondary)]">Manage your shipping and billing addresses for faster checkout.</p>
    </div>
    <button x-data @click="$dispatch('open-address-modal')" class="btn btn-primary h-[44px] px-6 flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Add New Address
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" x-data>

    <?php foreach($addresses as $address): ?>
    <div class="relative bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6 hover:border-[var(--gold)]/50 transition-colors flex flex-col gap-4
        <?= $address['is_default'] ? 'border-[var(--gold)]/40' : '' ?>">
        
        <?php if($address['is_default']): ?>
        <div class="absolute top-4 right-4 text-[10px] uppercase font-bold bg-[var(--gold)] text-[#111] px-2 py-1 rounded-[4px]">Default</div>
        <?php endif; ?>

        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-[var(--surface)] flex items-center justify-center text-[var(--gold)]">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg"><?= htmlspecialchars($address['label']) ?></h3>
                <span class="text-xs text-[var(--text-muted)] uppercase tracking-wider"><?= $address['type'] ?></span>
            </div>
        </div>
        
        <address class="not-italic text-[var(--text-secondary)] leading-relaxed text-sm flex-grow">
            <span class="block text-white font-medium"><?= htmlspecialchars($address['name']) ?></span>
            <?php if($address['company']): ?>
                <span class="block"><?= htmlspecialchars($address['company']) ?></span>
            <?php endif; ?>
            <span class="block mt-2"><?= htmlspecialchars($address['street']) ?></span>
            <span class="block"><?= htmlspecialchars($address['city']) ?>, <?= htmlspecialchars($address['state']) ?></span>
            <span class="block"><?= htmlspecialchars($address['country']) ?></span>
            <span class="block mt-2"><?= htmlspecialchars($address['phone']) ?></span>
        </address>
        
        <div class="pt-4 border-t border-[var(--border)] flex items-center gap-3">
            <button class="text-sm text-[var(--text-secondary)] hover:text-white transition-colors flex items-center gap-1.5">
                <i data-lucide="pencil" class="w-4 h-4"></i> Edit
            </button>
            <?php if(!$address['is_default']): ?>
            <button class="text-sm text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors flex items-center gap-1.5 ml-2">
                <i data-lucide="star" class="w-4 h-4"></i> Set Default
            </button>
            <button class="ml-auto text-sm text-[var(--danger)]/70 hover:text-[var(--danger)] transition-colors flex items-center gap-1.5">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    
    <!-- Add New Card Placeholder -->
    <button @click="$dispatch('open-address-modal')" class="border-2 border-dashed border-[var(--border)] rounded-[16px] p-6 flex flex-col items-center justify-center gap-3 text-[var(--text-muted)] hover:border-[var(--gold)]/50 hover:text-[var(--gold)] transition-colors min-h-[220px] group">
        <div class="w-12 h-12 rounded-full bg-[var(--surface)] flex items-center justify-center group-hover:bg-[var(--gold)]/10 transition-colors">
            <i data-lucide="plus" class="w-6 h-6"></i>
        </div>
        <span class="font-medium">Add New Address</span>
    </button>
</div>

<!-- Add/Edit Address Modal (Alpine.js) -->
<div x-data="{ open: false }" @open-address-modal.window="open = true" x-show="open" x-transition.opacity class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-6" style="display:none">
    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[24px] w-full max-w-[560px] p-8" @click.stop x-transition>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold font-manrope">Add New Address</h2>
            <button @click="open = false" class="text-[var(--text-muted)] hover:text-white transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <form class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Address Label</label>
                <input type="text" placeholder="e.g. Head Office, Warehouse..." class="input-field w-full">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">First Name</label>
                    <input type="text" placeholder="David" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Last Name</label>
                    <input type="text" placeholder="Okon" class="input-field w-full">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Street Address</label>
                <input type="text" placeholder="14 Adeola Odeku St" class="input-field w-full">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">City</label>
                    <input type="text" placeholder="Victoria Island" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">State</label>
                    <input type="text" placeholder="Lagos" class="input-field w-full">
                </div>
            </div>
            <div>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-10 h-6 bg-[var(--surface)] rounded-full peer-checked:bg-[var(--gold)] transition-colors border border-[var(--border)]"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-4 transition-transform shadow"></div>
                    </div>
                    <span class="text-sm text-[var(--text-secondary)]">Set as default shipping address</span>
                </label>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" @click="open = false" class="btn btn-secondary border border-[var(--border)] flex-1 h-[52px]">Cancel</button>
                <button type="submit" class="btn btn-primary flex-1 h-[52px]">Save Address</button>
            </div>
        </form>
    </div>
</div>
