<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Email Templates</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1">Preview branded email notifications (Phase 1)</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-[calc(100vh-140px)] min-h-[600px]" x-data="{ active: 'welcome' }">
    
    <!-- Left: Template List -->
    <div class="lg:col-span-1 bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden flex flex-col h-full">
        <div class="p-4 border-b border-[var(--border)] bg-[#0f0f0f] shrink-0"><h3 class="font-bold font-manrope">Available Templates</h3></div>
        <div class="flex-grow overflow-y-auto p-2 space-y-1">
            <?php foreach ($templates as $key => $name): ?>
            <button @click="active = '<?= $key ?>'" :class="active === '<?= $key ?>' ? 'bg-[var(--gold)]/10 text-[var(--gold)] border-l-2 border-[var(--gold)]' : 'text-[var(--text-secondary)] hover:text-white border-l-2 border-transparent'" class="w-full text-left px-4 py-3 text-sm font-medium transition-colors flex items-center gap-3"><i data-lucide="mail" class="w-4 h-4"></i> <?= htmlspecialchars($name) ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Right: Browser Preview Pane -->
    <div class="lg:col-span-3 bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden flex flex-col h-full">
        <div class="p-4 border-b border-[var(--border)] bg-[#0f0f0f] flex justify-between items-center shrink-0">
            <h3 class="font-bold font-manrope text-sm flex items-center gap-2"><i data-lucide="eye" class="w-4 h-4 text-[var(--text-muted)]"></i> Browser Preview</h3>
            <div class="flex gap-2">
                <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-white"><i data-lucide="monitor" class="w-4 h-4"></i></button>
            </div>
        </div>
        
        <!-- Iframe wrapper for isolated CSS rendering -->
        <div class="flex-grow bg-[#222] p-4 flex items-center justify-center relative overflow-hidden">
            <template x-for="(name, key) in <?= htmlspecialchars(json_encode($templates)) ?>">
                <iframe x-show="active === key" :src="'/admin/email-previews/' + key" class="w-full max-w-[800px] h-full bg-white rounded shadow-xl border-none"></iframe>
            </template>
        </div>
    </div>
</div>
