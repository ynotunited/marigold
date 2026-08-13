<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-bold font-manrope">Roles & Permissions</h1><p class="text-sm text-[var(--text-secondary)] mt-1">Define access levels via permission matrix</p></div>
    <button class="btn btn-primary h-9 px-4 text-sm flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Create Role</button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-140px)] min-h-[500px]">
    
    <!-- Left: Role List -->
    <div class="lg:col-span-1 bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden flex flex-col h-full">
        <div class="p-4 border-b border-[var(--border)] bg-[#0f0f0f] shrink-0"><h3 class="font-bold font-manrope">Available Roles</h3></div>
        <div class="flex-grow overflow-y-auto p-4 space-y-3" x-data="{ activeRole: 2 }">
            <?php foreach ($roles as $r): ?>
            <div @click="activeRole = <?= $r['id'] ?>" :class="activeRole === <?= $r['id'] ?> ? 'border-[var(--gold)] bg-[var(--surface)]/50' : 'border-[var(--border)] hover:border-[var(--gold)]/40 bg-[var(--surface)]'" class="border rounded-[10px] p-4 cursor-pointer transition-colors group">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-sm font-bold text-white"><?= htmlspecialchars($r['name']) ?></p>
                    <span class="text-[10px] uppercase font-bold bg-[#111] border border-[var(--border)] px-2 py-0.5 rounded text-[var(--text-secondary)]"><?= $r['users'] ?> users</span>
                </div>
                <p class="text-xs text-[var(--text-muted)] line-clamp-2"><?= htmlspecialchars($r['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Right: Permission Matrix Editor -->
    <div class="lg:col-span-2 bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden flex flex-col h-full">
        <div class="p-4 border-b border-[var(--border)] bg-[#0f0f0f] flex justify-between items-center shrink-0">
            <h3 class="font-bold font-manrope">Editing: <span class="text-[var(--gold)]">Sales Manager</span></h3>
            <button class="btn btn-secondary border border-[var(--border)] h-8 px-3 text-xs bg-[var(--surface)]">Save Matrix</button>
        </div>
        <div class="flex-grow overflow-y-auto p-0">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="sticky top-0 bg-[#0f0f0f] z-10">
                    <tr class="text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                        <th class="px-5 py-4 font-medium">Module</th><th class="px-5 py-4 font-medium text-center">View</th>
                        <th class="px-5 py-4 font-medium text-center">Create</th><th class="px-5 py-4 font-medium text-center">Edit</th>
                        <th class="px-5 py-4 font-medium text-center">Delete</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php 
                    $modules = ['Dashboard', 'Products', 'Orders', 'Quotes', 'Customers', 'CMS Pages', 'Blog', 'Settings'];
                    foreach ($modules as $m): 
                        // Mock Sales Manager matrix
                        $hasView = in_array($m, ['Dashboard', 'Products', 'Orders', 'Quotes', 'Customers']);
                        $hasEdit = in_array($m, ['Products', 'Orders', 'Quotes', 'Customers']);
                    ?>
                    <tr class="hover:bg-[var(--surface)]/40 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium text-[var(--text-secondary)]"><?= $m ?></td>
                        <td class="px-5 py-3 text-center"><input type="checkbox" <?= $hasView ? 'checked' : '' ?> class="rounded border-[var(--border)] bg-[var(--surface)] accent-[var(--gold)]"></td>
                        <td class="px-5 py-3 text-center"><input type="checkbox" <?= $hasEdit ? 'checked' : '' ?> class="rounded border-[var(--border)] bg-[var(--surface)] accent-[var(--gold)]"></td>
                        <td class="px-5 py-3 text-center"><input type="checkbox" <?= $hasEdit ? 'checked' : '' ?> class="rounded border-[var(--border)] bg-[var(--surface)] accent-[var(--gold)]"></td>
                        <td class="px-5 py-3 text-center"><input type="checkbox" class="rounded border-[var(--border)] bg-[var(--surface)] accent-[var(--gold)]"></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
