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
        <button @click="tab = 'gdpr'" :class="tab === 'gdpr' ? 'bg-[var(--gold)]/10 text-[var(--gold)] border-[var(--gold)]/30' : 'bg-[var(--surface)] text-[var(--text-secondary)] border-[var(--border)]'" class="w-full text-left px-4 py-3 border rounded-[10px] text-sm font-medium transition-colors flex items-center gap-3"><i data-lucide="shield-check" class="w-4 h-4"></i> GDPR & Retention</button>
    </div>

    <!-- Forms Area -->
    <div class="xl:col-span-3 bg-[#111] border border-[var(--border)] rounded-[16px] p-6 min-h-[500px]">
        
        <!-- General -->
        <div x-show="tab === 'general'" class="space-y-6">
            <h2 class="font-bold font-manrope text-lg mb-4 pb-2 border-b border-[var(--border)]">Store Identity</h2>
            <div class="grid grid-cols-2 gap-5">
                <div><label class="form-label">Store Name</label><input type="text" value="Marigold Signature" class="input-field w-full mt-1 text-sm"></div>
                <div><label class="form-label">Support Email</label><input type="email" value="support@marigoldsignatureng.com" class="input-field w-full mt-1 text-sm"></div>
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
                <div><label class="form-label">Public Key</label><input type="text" value="" placeholder="pk_test_..." class="input-field w-full mt-1 text-sm font-mono text-[var(--text-secondary)]"></div>
                <div><label class="form-label">Secret Key</label><input type="password" value="" placeholder="sk_test_..." autocomplete="new-password" class="input-field w-full mt-1 text-sm font-mono text-[var(--text-secondary)]"></div>
            </div>
            <h2 class="font-bold font-manrope text-lg mb-4 mt-8 pb-2 border-b border-[var(--border)]">Flutterwave Integration</h2>
            <div class="grid grid-cols-1 gap-5">
                <div><label class="form-label">Secret Key</label><input type="password" value="" placeholder="FLWSECK_TEST-..." autocomplete="new-password" class="input-field w-full mt-1 text-sm font-mono text-[var(--text-secondary)]"></div>
                <div><label class="form-label">Webhook Secret Hash</label><input type="password" value="" placeholder="verif-hash secret" autocomplete="new-password" class="input-field w-full mt-1 text-sm font-mono text-[var(--text-secondary)]"></div>
            </div>
            <h2 class="font-bold font-manrope text-lg mb-4 mt-8 pb-2 border-b border-[var(--border)]">Tax Settings</h2>
            <div class="grid grid-cols-2 gap-5">
                <div><label class="form-label">Tax Rate (%)</label><input type="number" value="7.5" class="input-field w-full mt-1 text-sm"></div>
                <div><label class="form-label">Price Display</label><select class="input-field w-full mt-1 text-sm"><option>Exclusive of Tax</option><option>Inclusive of Tax</option></select></div>
            </div>
        </div>

        <!-- SMTP Settings -->
        <div x-show="tab === 'smtp'" class="space-y-6" style="display:none">
            <h2 class="font-bold font-manrope text-lg mb-4 pb-2 border-b border-[var(--border)]">SMTP Server</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><label class="form-label">Host</label><input type="text" value="<?= htmlspecialchars($smtp['host']) ?>" placeholder="smtp.gmail.com" class="input-field w-full mt-1 text-sm font-mono"></div>
                <div><label class="form-label">Port</label><input type="number" value="<?= htmlspecialchars($smtp['port']) ?>" placeholder="587" class="input-field w-full mt-1 text-sm"></div>
                <div><label class="form-label">Encryption</label><select class="input-field w-full mt-1 text-sm"><option value="tls" <?= $smtp['encryption'] === 'tls' ? 'selected' : '' ?>>TLS</option><option value="ssl" <?= $smtp['encryption'] === 'ssl' ? 'selected' : '' ?>>SSL</option><option value="none" <?= !in_array($smtp['encryption'], ['tls', 'ssl'], true) ? 'selected' : '' ?>>None</option></select></div>
                <div><label class="form-label">Username</label><input type="text" value="<?= htmlspecialchars($smtp['username']) ?>" placeholder="your@email.com" autocomplete="off" class="input-field w-full mt-1 text-sm font-mono"></div>
                <div><label class="form-label">Password / App Password</label><input type="password" value="<?= htmlspecialchars($smtp['password']) ?>" autocomplete="new-password" class="input-field w-full mt-1 text-sm font-mono text-[var(--text-secondary)]"></div>
            </div>

            <h2 class="font-bold font-manrope text-lg mb-4 mt-8 pb-2 border-b border-[var(--border)]">Sender Identity</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><label class="form-label">From Name</label><input type="text" value="<?= htmlspecialchars($smtp['from_name']) ?>" placeholder="Marigold Signature" class="input-field w-full mt-1 text-sm"></div>
                <div><label class="form-label">From Email</label><input type="email" value="<?= htmlspecialchars($smtp['from_email']) ?>" placeholder="no-reply@marigoldsignatureng.com" class="input-field w-full mt-1 text-sm"></div>
            </div>

            <div class="flex items-center justify-between bg-[var(--surface)] p-4 rounded-[10px] border border-[var(--border)]">
                <div><p class="font-medium text-sm">Send Test Email</p><p class="text-xs text-[var(--text-secondary)]">Verifies the SMTP connection before saving.</p></div>
                <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2"><i data-lucide="send" class="w-4 h-4"></i> Send Test</button>
            </div>
        </div>

        <!-- Shipping Methods -->
        <div x-show="tab === 'shipping'" class="space-y-6" style="display:none">
            <h2 class="font-bold font-manrope text-lg mb-4 pb-2 border-b border-[var(--border)]">ShipBubble Logistics</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><label class="form-label">API Key</label><input type="password" value="<?= htmlspecialchars($shipping['api_key']) ?>" autocomplete="new-password" class="input-field w-full mt-1 text-sm font-mono text-[var(--text-secondary)]"></div>
                <div><label class="form-label">Webhook Secret</label><input type="password" value="<?= htmlspecialchars($shipping['webhook_secret']) ?>" autocomplete="new-password" class="input-field w-full mt-1 text-sm font-mono text-[var(--text-secondary)]"></div>
                <div><label class="form-label">Sender Address Code</label><input type="text" value="<?= htmlspecialchars($shipping['sender_code']) ?>" placeholder="e.g. 98794022" class="input-field w-full mt-1 text-sm"></div>
                <div><label class="form-label">Default Category ID</label><input type="text" value="<?= htmlspecialchars($shipping['category_id']) ?>" placeholder="e.g. 90097994" class="input-field w-full mt-1 text-sm"></div>
            </div>

            <h2 class="font-bold font-manrope text-lg mb-4 mt-8 pb-2 border-b border-[var(--border)]">Sender Address</h2>
            <div>
                <label class="form-label">Pickup Address</label>
                <textarea rows="2" class="input-field w-full mt-1 text-sm resize-none" placeholder="6 Oluwole Omole Street, Opebi, Lagos, Nigeria"><?= htmlspecialchars($shipping['sender_address']) ?></textarea>
            </div>

            <h2 class="font-bold font-manrope text-lg mb-4 mt-8 pb-2 border-b border-[var(--border)]">Default Package Dimensions (cm)</h2>
            <div class="grid grid-cols-3 gap-5">
                <div><label class="form-label">Length</label><input type="number" value="<?= htmlspecialchars($shipping['package_length']) ?>" placeholder="20" class="input-field w-full mt-1 text-sm"></div>
                <div><label class="form-label">Width</label><input type="number" value="<?= htmlspecialchars($shipping['package_width']) ?>" placeholder="15" class="input-field w-full mt-1 text-sm"></div>
                <div><label class="form-label">Height</label><input type="number" value="<?= htmlspecialchars($shipping['package_height']) ?>" placeholder="10" class="input-field w-full mt-1 text-sm"></div>
            </div>

            <h2 class="font-bold font-manrope text-lg mb-4 mt-8 pb-2 border-b border-[var(--border)]">Delivery Methods</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between bg-[var(--surface)] p-4 rounded-[10px] border border-[var(--border)]">
                    <div><p class="font-medium text-sm">Delivery (Courier)</p><p class="text-xs text-[var(--text-secondary)]">Real-time courier rates via ShipBubble.</p></div>
                    <label class="relative cursor-pointer"><input type="checkbox" checked class="sr-only peer"><div class="w-11 h-6 bg-[#111] rounded-full peer-checked:bg-[var(--gold)] transition-colors border border-[var(--border)]"></div><div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform shadow"></div></label>
                </div>
                <div class="flex items-center justify-between bg-[var(--surface)] p-4 rounded-[10px] border border-[var(--border)]">
                    <div><p class="font-medium text-sm">Store Pickup</p><p class="text-xs text-[var(--text-secondary)]">Customer collects from your Lagos atelier.</p></div>
                    <label class="relative cursor-pointer"><input type="checkbox" checked class="sr-only peer"><div class="w-11 h-6 bg-[#111] rounded-full peer-checked:bg-[var(--gold)] transition-colors border border-[var(--border)]"></div><div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform shadow"></div></label>
                </div>
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

        <!-- GDPR & Data Retention -->
        <div x-show="tab === 'gdpr'" class="space-y-6" style="display:none">
            <h2 class="font-bold font-manrope text-lg mb-4 pb-2 border-b border-[var(--border)]">Data Retention Policy</h2>
            <div class="grid grid-cols-1 gap-5">
                <div class="max-w-md">
                    <label class="form-label">Retention Window (days)</label>
                    <input type="number" value="<?= htmlspecialchars(\App\Service\Settings::get('gdpr_retention_days', '30')) ?>" min="1" max="365" class="input-field w-full mt-1 text-sm">
                    <p class="text-xs text-[var(--text-muted)] mt-1">Number of days after a user requests deletion before their data is permanently hard-deleted. During this window, the user can cancel the deletion.</p>
                </div>
                <div class="flex items-center justify-between bg-[var(--surface)] p-4 rounded-[10px] border border-[var(--border)] max-w-md">
                    <div>
                        <p class="font-medium text-sm">Anonymize Orders</p>
                        <p class="text-xs text-[var(--text-secondary)]">When enabled, order records have PII stripped (name, email, phone, address) but financial data is retained for statutory tax compliance (6-7 years). When disabled, orders are hard-deleted with the user.</p>
                    </div>
                    <label class="relative cursor-pointer">
                        <input type="checkbox" name="gdpr_anonymize_orders" value="1" <?= \App\Service\Settings::get('gdpr_anonymize_orders', '1') === '1' ? 'checked' : '' ?> class="sr-only peer">
                        <div class="w-11 h-6 bg-[#111] rounded-full peer-checked:bg-[var(--gold)] transition-colors border border-[var(--border)]"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform shadow"></div>
                    </label>
                </div>
            </div>
            <div class="mt-6 p-4 bg-[var(--surface)] rounded-[10px] border border-[var(--border)]">
                <p class="text-sm font-medium mb-2">Quick Links</p>
                <div class="flex gap-3">
                    <a href="<?= app_url('/admin/gdpr') ?>" class="btn btn-secondary h-8 px-4 text-xs border border-[var(--border)]">GDPR Dashboard</a>
                    <a href="<?= app_url('/admin/audit') ?>" class="btn btn-secondary h-8 px-4 text-xs border border-[var(--border)]">Audit Log</a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>.form-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 600; }</style>
