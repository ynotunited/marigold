<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold font-manrope">Media Library</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1"><?= count($files) ?> files · <?= $storage_used ?> of <?= $storage_total ?> used</p>
    </div>
    <div class="flex items-center gap-3">
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2 text-[var(--danger)]/80 hover:text-[var(--danger)] hover:border-[var(--danger)]/50 transition-colors">
            <i data-lucide="trash-2" class="w-4 h-4"></i> Clean Unused
        </button>
        <button class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2">
            <i data-lucide="upload-cloud" class="w-4 h-4"></i> Upload Files
        </button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-4 gap-6 h-[calc(100vh-160px)] min-h-[600px]">

    <!-- Left: Folders & Storage -->
    <div class="xl:col-span-1 space-y-6 flex flex-col h-full">
        <!-- Storage Usage -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5 shrink-0">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold">Storage Usage</span>
                <span class="text-xs text-[var(--text-muted)]"><?= $storage_percent ?>%</span>
            </div>
            <div class="w-full h-2 bg-[var(--surface)] rounded-full overflow-hidden mb-2">
                <div class="h-full bg-[var(--gold)]" style="width: <?= $storage_percent ?>%"></div>
            </div>
            <p class="text-xs text-[var(--text-secondary)]"><?= $storage_used ?> used out of <?= $storage_total ?></p>
        </div>

        <!-- Folders -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-5 flex-grow flex flex-col min-h-0">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold font-manrope">Folders</h3>
                <button class="text-[var(--text-muted)] hover:text-white transition-colors" title="New Folder"><i data-lucide="folder-plus" class="w-4 h-4"></i></button>
            </div>
            <div class="space-y-1 overflow-y-auto hide-scrollbar flex-grow" x-data="{ active: 'All Files' }">
                <button @click="active = 'All Files'" :class="active === 'All Files' ? 'bg-[var(--gold)]/10 text-[var(--gold)]' : 'text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white'" class="w-full flex items-center justify-between px-3 py-2 rounded-[8px] transition-colors text-sm font-medium">
                    <div class="flex items-center gap-2"><i data-lucide="folder" class="w-4 h-4"></i> All Files</div>
                    <span class="text-xs">403</span>
                </button>
                <?php foreach ($folders as $f): ?>
                <button @click="active = '<?= $f['name'] ?>'" :class="active === '<?= $f['name'] ?>' ? 'bg-[var(--gold)]/10 text-[var(--gold)]' : 'text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white'" class="w-full flex items-center justify-between px-3 py-2 rounded-[8px] transition-colors text-sm font-medium group">
                    <div class="flex items-center gap-2">
                        <i data-lucide="folder" class="w-4 h-4 text-[var(--text-muted)] group-hover:text-current"></i>
                        <?= htmlspecialchars($f['name']) ?>
                    </div>
                    <span class="text-xs opacity-60"><?= $f['files'] ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Right: Files Grid -->
    <div class="xl:col-span-3 bg-[#111] border border-[var(--border)] rounded-[16px] flex flex-col h-full min-h-0 overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-[var(--border)] flex flex-wrap gap-4 items-center justify-between shrink-0">
            <div class="relative flex-grow max-w-sm">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[var(--text-muted)]"></i>
                <input type="text" placeholder="Search by filename or alt text…" class="input-field w-full pl-10 h-9 text-sm bg-[var(--surface)]">
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-[var(--text-secondary)] flex items-center gap-1"><i data-lucide="check-circle" class="w-3.5 h-3.5 text-green-400"></i> WebP Auto-compression ON</span>
            </div>
        </div>

        <!-- Dropzone / Grid -->
        <div class="flex-grow overflow-y-auto p-5 relative" x-data="{ dragOver: false }"
             @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false" @drop.prevent="dragOver = false">
            
            <div x-show="dragOver" class="absolute inset-4 border-2 border-dashed border-[var(--gold)] bg-[var(--gold)]/5 rounded-[12px] flex flex-col items-center justify-center z-10 transition-colors" style="display:none">
                <i data-lucide="upload-cloud" class="w-12 h-12 text-[var(--gold)] mb-3"></i>
                <p class="text-lg font-bold font-manrope text-[var(--gold)]">Drop files to upload</p>
                <p class="text-sm text-[var(--text-secondary)] mt-1">Images will be compressed to WebP automatically</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <?php foreach ($files as $f): ?>
                <div class="group relative bg-[var(--surface)] border border-[var(--border)] rounded-[12px] overflow-hidden hover:border-[var(--gold)]/50 transition-all cursor-pointer">
                    <!-- Image -->
                    <div class="aspect-square bg-[#0f0f0f] relative flex items-center justify-center overflow-hidden">
                        <?php if (strpos($f['type'], 'svg') !== false): ?>
                        <img src="<?= $f['thumbnail'] ?>" class="w-12 h-12">
                        <?php else: ?>
                        <img src="<?= $f['thumbnail'] ?>" class="w-full h-full object-cover">
                        <?php endif; ?>
                        
                        <!-- Checkbox -->
                        <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <input type="checkbox" class="rounded border-[var(--border)] bg-black/50">
                        </div>
                        
                        <!-- Format Badge -->
                        <div class="absolute bottom-2 right-2 px-1.5 py-0.5 rounded bg-black/60 backdrop-blur-sm text-[9px] font-bold uppercase tracking-wider text-white border border-white/10">
                            <?= explode('/', $f['type'])[1] ?>
                        </div>

                        <!-- Unused Badge -->
                        <?php if (!empty($f['unused'])): ?>
                        <div class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-[var(--danger)]" title="Unused file"></div>
                        <?php endif; ?>
                    </div>
                    <!-- Details -->
                    <div class="p-3">
                        <p class="text-xs font-medium truncate mb-1 group-hover:text-[var(--gold)] transition-colors" title="<?= $f['name'] ?>"><?= $f['name'] ?></p>
                        <div class="flex items-center justify-between text-[10px] text-[var(--text-muted)]">
                            <span><?= $f['size'] ?></span>
                            <span><?= $f['dimensions'] ?></span>
                        </div>
                    </div>
                    
                    <!-- Quick Actions overlay on hover -->
                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-[1px]">
                        <button class="w-8 h-8 rounded-full bg-[var(--surface)] text-white flex items-center justify-center hover:bg-[var(--gold)] hover:text-black transition-colors" title="Edit details"><i data-lucide="edit-2" class="w-4 h-4"></i></button>
                        <button class="w-8 h-8 rounded-full bg-[var(--surface)] text-white flex items-center justify-center hover:bg-red-500 transition-colors" title="Delete"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
