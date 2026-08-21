<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Navigation Manager</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1">Manage header, footer, and mobile menus</p>
    </div>
    <div class="flex items-center gap-3">
        <button class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Save Menus
        </button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 h-[calc(100vh-160px)] min-h-[600px]">

    <!-- Left: Menu Selector & Editor Form -->
    <div class="xl:col-span-1 space-y-6 flex flex-col h-full overflow-hidden">
        
        <!-- Menu Selector -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5 shrink-0" x-data="{ activeMenu: 'Header' }">
            <h3 class="font-bold font-manrope mb-3">Select Menu</h3>
            <div class="flex gap-2">
                <button @click="activeMenu = 'Header'" :class="activeMenu === 'Header' ? 'bg-[var(--gold)]/10 text-[var(--gold)] border-[var(--gold)]/30' : 'bg-[var(--surface)] text-[var(--text-secondary)] border-[var(--border)]'" class="flex-1 py-2 text-sm font-medium border rounded-[8px] transition-colors">Header Menu</button>
                <button @click="activeMenu = 'Footer'" :class="activeMenu === 'Footer' ? 'bg-[var(--gold)]/10 text-[var(--gold)] border-[var(--gold)]/30' : 'bg-[var(--surface)] text-[var(--text-secondary)] border-[var(--border)]'" class="flex-1 py-2 text-sm font-medium border rounded-[8px] transition-colors">Footer Menu</button>
            </div>
            <div class="mt-4 pt-4 border-t border-[var(--border)]">
                <button class="w-full btn border border-dashed border-[var(--border)] bg-transparent text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)]/50 transition-colors h-9 text-sm flex items-center justify-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Menu Item
                </button>
            </div>
        </div>

        <!-- Add/Edit Item Form -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5 flex-grow overflow-y-auto hide-scrollbar">
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-[var(--border)]">
                <h3 class="font-bold font-manrope">Edit Menu Item</h3>
                <button class="text-xs text-[var(--danger)] hover:text-red-400">Remove</button>
            </div>
            
            <form class="space-y-4">
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Navigation Label</label>
                    <input type="text" value="Corporate Solutions" class="input-field w-full text-sm">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Link Type</label>
                    <select class="input-field w-full text-sm"><option>Page</option><option>Category</option><option>Custom URL</option></select>
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Target</label>
                    <select class="input-field w-full text-sm"><option>Corporate Solutions (Page)</option><option>About Us (Page)</option></select>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Open in</label>
                        <select class="input-field w-full text-sm"><option>Same Tab</option><option>New Tab</option></select>
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Icon (Optional)</label>
                        <input type="text" placeholder="lucide-icon-name" class="input-field w-full text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Badge Text (Optional)</label>
                    <input type="text" value="New" placeholder="e.g. Sale, New" class="input-field w-full text-sm">
                </div>

                <div class="pt-2">
                    <button type="button" class="btn btn-secondary border border-[var(--border)] bg-[var(--surface)] w-full h-10 text-sm">Update Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right: Menu Structure (Draggable) -->
    <div class="xl:col-span-2 bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden flex flex-col h-full">
        <div class="p-4 border-b border-[var(--border)] bg-[#0f0f0f] shrink-0">
            <h3 class="font-bold font-manrope">Header Menu Structure</h3>
            <p class="text-xs text-[var(--text-secondary)] mt-1">Drag and drop items to reorder them or create dropdowns (nesting).</p>
        </div>
        
        <div class="p-6 flex-grow overflow-y-auto bg-[var(--surface)]/20" x-data="{ activeId: 3 }">
            <div class="space-y-2 max-w-2xl">
                <?php foreach ($menus['Header'] as $item): ?>
                
                <!-- Parent Item -->
                <div @click="activeId = <?= $item['id'] ?>" :class="activeId === <?= $item['id'] ?> ? 'border-[var(--gold)] ring-1 ring-[var(--gold)]' : 'border-[var(--border)] hover:border-[var(--gold)]/40'" class="bg-[var(--surface)] border rounded-[10px] p-3 flex items-center gap-4 cursor-pointer transition-all">
                    <i data-lucide="grip-vertical" class="w-5 h-5 text-[var(--text-muted)] cursor-grab shrink-0"></i>
                    <div class="flex-grow min-w-0 flex items-center gap-3">
                        <p class="text-sm font-medium"><?= htmlspecialchars($item['title']) ?></p>
                        <span class="text-[10px] text-[var(--text-secondary)] bg-black/40 px-2 py-0.5 rounded border border-white/5"><?= $item['type'] ?></span>
                        <?php if (isset($item['badge'])): ?><span class="text-[10px] text-[#111] font-bold uppercase bg-[var(--gold)] px-1.5 py-0.5 rounded"><?= $item['badge'] ?></span><?php endif; ?>
                    </div>
                    <div class="text-xs text-[var(--text-muted)] font-mono truncate max-w-[150px]"><?= $item['url'] ?></div>
                    <button class="shrink-0 text-[var(--text-muted)] hover:text-white px-2"><i data-lucide="eye" class="w-4 h-4"></i></button>
                </div>

                <!-- Children -->
                <?php if (!empty($item['children'])): ?>
                    <div class="pl-12 space-y-2 pt-1 pb-2 relative before:content-[''] before:absolute before:left-6 before:top-0 before:bottom-4 before:w-px before:bg-[var(--border)]">
                        <?php foreach ($item['children'] as $child): ?>
                        <div @click="activeId = <?= $child['id'] ?>" :class="activeId === <?= $child['id'] ?> ? 'border-[var(--gold)] ring-1 ring-[var(--gold)]' : 'border-[var(--border)] hover:border-[var(--gold)]/40'" class="relative bg-[var(--surface)] border rounded-[10px] p-3 flex items-center gap-4 cursor-pointer transition-all before:content-[''] before:absolute before:-left-6 before:top-1/2 before:-translate-y-1/2 before:w-6 before:h-px before:bg-[var(--border)]">
                            <i data-lucide="grip-vertical" class="w-4 h-4 text-[var(--text-muted)] cursor-grab shrink-0"></i>
                            <div class="flex-grow min-w-0 flex items-center gap-3">
                                <p class="text-sm font-medium"><?= htmlspecialchars($child['title']) ?></p>
                                <span class="text-[10px] text-[var(--text-secondary)] bg-black/40 px-2 py-0.5 rounded border border-white/5"><?= $child['type'] ?></span>
                            </div>
                            <div class="text-xs text-[var(--text-muted)] font-mono truncate max-w-[150px]"><?= $child['url'] ?></div>
                            <button class="shrink-0 text-[var(--text-muted)] hover:text-white px-2"><i data-lucide="eye" class="w-4 h-4"></i></button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
