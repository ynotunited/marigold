<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-bold font-manrope">Global Settings</h1><p class="text-sm text-[var(--text-secondary)] mt-1">Configure store information and integrations</p></div>
    <button class="btn btn-primary h-9 px-6 text-sm flex items-center gap-2"><i data-lucide="save" class="w-4 h-4"></i> Save Settings</button>
</div>

<div class="grid grid-cols-1 xl:grid-cols-4 gap-6" x-data="{ tab: 'general' }">
    <!-- Sidebar Navigation -->
    <div class="xl:col-span-1 space-y-2">
        <button @click="tab = 'general'" :class="tab === 'general' ? 'bg-[var(--gold)]/10 text-[var(--gold)] border-[var(--gold)]/30' : 'bg-[var(--surface)] text-[var(--text-secondary)] border-[var(--border)]'" class="w-full text-left px-4 py-3 border rounded-[10px] text-sm font-medium transition-colors flex items-center gap-3"><i data-lucide="settings" class="w-4 h-4"></i> General</button>
        <button @click="tab = 'smtp'" :class="tab === 'smtp' ? 'bg-[var(--gold)]/10 text-[var(--gold)] border-[var(--gold)]/30' : 'bg-[var(--surface)] text-[var(--text-secondary)] border-[var(--border)]'" class="w-full text-left px-4 py-3 border rounded-[10px] text-sm font-medium transition-colors flex items-center gap-3"><i data-lucide="mail" class="w-4 h-4"></i> SMTP Settings</button>
        <button @click="tab = 'payment'" :class="tab === 'payment' ? 'bg-[var(--gold)]/10 text-[var(--gold)] border-[var(--gold)]/30' : 'bg-[var(--surface)] text-[var(--text-secondary)] border-[var(--border)]'" class="w-full text-left px-4 py-3 border rounded-[10px] text-sm font-medium transition-colors flex items-center gap-3"><i data-lucide="credit-card" class="w-4 h-4"></i> Payment & Tax</button>
        <button @click="tab = 'shipping'" :class="tab === 'shipping' ? 'bg-[var(--gold)]/10 text-[var(--gold)] border-[var(--gold)]/30' : 'bg-[var(--surface)] text-[var(--text-secondary)] border-[var(--border)]'" class="w-full text-left px-4 py-3 border rounded-[10px] text-sm font-medium transition-colors flex items-center gap-3"><i data-lucide="truck" class="w-4 h-4"></i> Shipping Methods</button>
        <button @click="tab = 'integrations'" :class="tab === 'integrations' ? 'bg-[var(--gold)]/10 text-[var(--gold)] border-[var(--gold)]/30' : 'bg-[var(--surface)] text-[var(--text-secondary)] border-[var(--border)]'" class="w-full text-left px-4 py-3 border rounded-[10px] text-sm font-medium transition-colors flex items-center gap-3"><i data-lucide="link" class="w-4 h-4"></i> Integrations</button>
    </div>

    <!-- Forms Area -->
    <div class="xl:col-span-3 bg-[#111] border border-[var(--border)] rounded-[16px] p-6 min-h-[500px]">
        
        <!-- General -->
        <div x-show="tab === 'general'" class="space-y-6">
            <h2 class="font-bold font-manrope text-lg mb-4 pb-2 border-b border-[var(--border)]">Store Identity</h2>
            <div class="grid grid-cols-2 gap-5">
                <div><label class="form-label">Store Name</label><input type="text" value="Marigold Signature" class="input-field w-full mt-1 text-sm"></div>
                <div><label class="form-label">Support Email</label><input type="email" value="support@marigoldsignature.com" class="input-field w-full mt-1 text-sm"></div>
                <div><label class="form-label">Phone Number</label><input type="text" value="+234 800 MARIGOLD" class="input-field w-full mt-1 text-sm"></div>
                <div><label class="form-label">Currency Symbol</label><input type="text" value="₦" class="input-field w-full mt-1 text-sm"></div>
                <div class="col-span-2"><label class="form-label">Physical Address</label><textarea rows="2" class="input-field w-full mt-1 text-sm resize-none">14 Adeola Odeku St, Victoria Island, Lagos</textarea></div>
                <div class="col-span-2"><label class="form-label">Footer Copyright Text</label><input type="text" value="© 2026 Marigold Signature Ltd. All rights reserved." class="input-field w-full mt-1 text-sm"></div>
            </div>
            
            <h2 class="font-bold font-manrope text-lg mb-4 mt-8 pb-2 border-b border-[var(--border)]">System Status</h2>
            <div class="flex items-center justify-between bg-[var(--surface)] p-4 rounded-[10px] border border-[var(--border)]">
                <div><p class="font-medium text-sm">Maintenance Mode</p><p class="text-xs text-[var(--text-secondary)]">Hide store from public. Admins can still log in.</p></div>
                <label class="relative cursor-pointer"><input type="checkbox" class="sr-only peer"><div class="w-11 h-6 bg-[#111] rounded-full peer-checked:bg-[var(--danger)] transition-colors border border-[var(--border)]"></div><div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform shadow"></div></label>
            </div>
        </div>

        <!-- Payment & Tax -->
        <div x-show="tab === 'payment'" class="space-y-6" style="display:none">
            <h2 class="font-bold font-manrope text-lg mb-4 pb-2 border-b border-[var(--border)]">Paystack Integration</h2>
            <div class="grid grid-cols-1 gap-5">
                <div><label class="form-label">Public Key</label><input type="text" value="pk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxx" class="input-field w-full mt-1 text-sm font-mono text-[var(--text-secondary)]"></div>
                <div><label class="form-label">Secret Key</label><input type="password" value="sk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxx" class="input-field w-full mt-1 text-sm font-mono text-[var(--text-secondary)]"></div>
            </div>
            <h2 class="font-bold font-manrope text-lg mb-4 mt-8 pb-2 border-b border-[var(--border)]">Tax Settings</h2>
            <div class="grid grid-cols-2 gap-5">
                <div><label class="form-label">Tax Rate (%)</label><input type="number" value="7.5" class="input-field w-full mt-1 text-sm"></div>
                <div><label class="form-label">Price Display</label><select class="input-field w-full mt-1 text-sm"><option>Exclusive of Tax</option><option>Inclusive of Tax</option></select></div>
            </div>
        </div>

        <!-- Integrations -->
        <div x-show="tab === 'integrations'" class="space-y-6" style="display:none">
            <h2 class="font-bold font-manrope text-lg mb-4 pb-2 border-b border-[var(--border)]">Marketing & Analytics</h2>
            <div class="grid grid-cols-1 gap-5">
                <div><label class="form-label">Google Analytics Measurement ID</label><input type="text" placeholder="G-XXXXXXXXXX" class="input-field w-full mt-1 text-sm font-mono"></div>
                <div><label class="form-label">Meta Pixel ID</label><input type="text" placeholder="XXXXXXXXXXXXXXXX" class="input-field w-full mt-1 text-sm font-mono"></div>
                <div><label class="form-label">WhatsApp Business Number</label><input type="text" value="+2348000000000" class="input-field w-full mt-1 text-sm"></div>
            </div>
        </div>

    </div>
</div>

<style>.form-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 600; }</style>
