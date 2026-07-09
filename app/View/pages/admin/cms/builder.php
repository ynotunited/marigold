<div class="flex items-center justify-between mb-4 pb-4 border-b border-[var(--border)]">
    <div class="flex items-center gap-4">
        <a href="/admin/cms" class="w-8 h-8 flex items-center justify-center rounded-full bg-[var(--surface)] text-[var(--text-secondary)] hover:text-white transition-colors"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
        <div>
            <h1 class="text-xl font-bold font-manrope flex items-center gap-2">
                <?= htmlspecialchars($page['title']) ?> 
                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase border bg-green-500/10 text-green-400 border-green-500/20"><?= $page['status'] ?></span>
            </h1>
            <p class="text-xs text-[var(--text-muted)] font-mono mt-0.5"><?= $page['slug'] ?></p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-3 text-xs bg-[var(--surface)]"><i data-lucide="history" class="w-3.5 h-3.5 mr-1.5"></i> History</button>
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-3 text-xs bg-[var(--surface)]"><i data-lucide="settings" class="w-3.5 h-3.5 mr-1.5"></i> Page Settings</button>
        <div class="w-px h-6 bg-[var(--border)] mx-1"></div>
        <button class="btn btn-primary h-9 px-5 text-sm">Save & Publish</button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-[calc(100vh-180px)] min-h-[600px]">

    <!-- Left: Section List (Draggable) -->
    <div class="lg:col-span-1 flex flex-col h-full overflow-hidden">
        <div class="flex items-center justify-between mb-3 shrink-0">
            <h2 class="font-bold font-manrope">Page Layout</h2>
            <button class="text-xs text-[var(--gold)] hover:text-white">+ Add Section</button>
        </div>
        
        <div class="flex-grow overflow-y-auto space-y-2 pr-2 hide-scrollbar" x-data="{ active: 's1' }">
            <?php foreach ($sections as $index => $s): ?>
            <div class="bg-[var(--surface)] border <?= $index === 0 ? 'border-[var(--gold)]' : 'border-[var(--border)] hover:border-[var(--gold)]/30' ?> rounded-[10px] p-3 cursor-pointer group flex items-center gap-3 transition-colors">
                <!-- Drag Handle -->
                <i data-lucide="grip-vertical" class="w-4 h-4 text-[var(--text-muted)] cursor-grab shrink-0 opacity-50 group-hover:opacity-100"></i>
                
                <div class="flex-grow min-w-0" @click="active = '<?= $s['id'] ?>'">
                    <p class="text-sm font-medium truncate <?= $s['hidden'] ? 'text-[var(--text-muted)] line-through' : '' ?>"><?= htmlspecialchars($s['title']) ?></p>
                    <p class="text-[10px] text-[var(--text-secondary)] uppercase tracking-wider mt-0.5"><?= $s['type'] ?></p>
                </div>
                
                <!-- Visibility Toggle -->
                <button class="shrink-0 text-[var(--text-muted)] hover:text-white" title="<?= $s['hidden'] ? 'Show Section' : 'Hide Section' ?>">
                    <i data-lucide="<?= $s['hidden'] ? 'eye-off' : 'eye' ?>" class="w-4 h-4"></i>
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Right: Section Editor Preview/Form -->
    <div class="lg:col-span-3 flex flex-col h-full bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden relative">
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03]">
            <i data-lucide="layout" class="w-64 h-64"></i>
        </div>
        
        <div class="p-4 border-b border-[var(--border)] bg-[#0f0f0f] flex items-center justify-between shrink-0 relative z-10">
            <h3 class="font-bold font-manrope">Editing: <span class="text-[var(--gold)]">Main Hero</span> <span class="text-xs text-[var(--text-muted)] font-normal ml-2 uppercase tracking-wider">(Hero Banner)</span></h3>
            <button class="text-[var(--danger)] hover:text-red-400 text-xs font-medium flex items-center gap-1"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Remove Section</button>
        </div>

        <!-- Editor Form (Mock) -->
        <div class="p-6 flex-grow overflow-y-auto relative z-10 space-y-6">
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2"><label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Section Title (Internal)</label><input type="text" value="Main Hero" class="input-field w-full text-sm"></div>
                
                <div class="col-span-2 border-t border-[var(--border)] my-2"></div>
                <h4 class="col-span-2 font-bold text-sm text-[var(--text-secondary)]">Content</h4>
                
                <div class="col-span-2"><label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Headline</label><input type="text" value="Premium Corporate Gifting" class="input-field w-full text-sm"></div>
                <div class="col-span-2"><label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Subheadline</label><textarea rows="2" class="input-field w-full text-sm resize-none">Elevate your brand with bespoke merchandise designed to impress.</textarea></div>
                
                <div><label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Primary Button Text</label><input type="text" value="Explore Catalog" class="input-field w-full text-sm"></div>
                <div><label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Primary Button Link</label><input type="text" value="/shop" class="input-field w-full text-sm font-mono"></div>

                <div class="col-span-2"><label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Background Image</label>
                    <div class="flex items-center gap-3">
                        <div class="w-16 h-16 rounded-[8px] border border-[var(--border)] bg-[var(--surface)] overflow-hidden"><img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=150" class="w-full h-full object-cover"></div>
                        <button class="btn btn-secondary border border-[var(--border)] h-9 px-3 text-xs bg-[var(--surface)]">Replace Image</button>
                    </div>
                </div>

                <div class="col-span-2 border-t border-[var(--border)] my-2"></div>
                <h4 class="col-span-2 font-bold text-sm text-[var(--text-secondary)]">Design Settings</h4>

                <div><label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Overlay Opacity</label><input type="range" min="0" max="100" value="60" class="w-full accent-[var(--gold)]"></div>
                <div><label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Text Alignment</label>
                    <div class="flex gap-1 bg-[var(--surface)] p-1 rounded-[8px] border border-[var(--border)] w-max">
                        <button class="w-8 h-7 rounded-[6px] flex items-center justify-center text-[var(--text-secondary)] hover:text-white"><i data-lucide="align-left" class="w-3.5 h-3.5"></i></button>
                        <button class="w-8 h-7 rounded-[6px] flex items-center justify-center bg-[var(--card)] text-white shadow"><i data-lucide="align-center" class="w-3.5 h-3.5"></i></button>
                        <button class="w-8 h-7 rounded-[6px] flex items-center justify-center text-[var(--text-secondary)] hover:text-white"><i data-lucide="align-right" class="w-3.5 h-3.5"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
