<div class="mb-8">
    <h1 class="text-3xl font-bold mb-2">Account Settings</h1>
    <p class="text-[var(--text-secondary)]">Manage your personal details, security, and preferences.</p>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8" x-data="{ tab: 'profile' }">

    <!-- Left Nav -->
    <div class="xl:col-span-1">
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-2 space-y-1">
            <button @click="tab = 'profile'" :class="tab === 'profile' ? 'bg-[var(--surface)] text-white border-[var(--gold)]/30 border' : 'text-[var(--text-secondary)] hover:text-white hover:bg-[var(--surface)]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-[8px] transition-colors text-left">
                <i data-lucide="user" class="w-5 h-5 shrink-0"></i> Profile
            </button>
            <button @click="tab = 'password'" :class="tab === 'password' ? 'bg-[var(--surface)] text-white border-[var(--gold)]/30 border' : 'text-[var(--text-secondary)] hover:text-white hover:bg-[var(--surface)]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-[8px] transition-colors text-left">
                <i data-lucide="lock" class="w-5 h-5 shrink-0"></i> Password
            </button>
            <button @click="tab = 'email'" :class="tab === 'email' ? 'bg-[var(--surface)] text-white border-[var(--gold)]/30 border' : 'text-[var(--text-secondary)] hover:text-white hover:bg-[var(--surface)]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-[8px] transition-colors text-left">
                <i data-lucide="mail" class="w-5 h-5 shrink-0"></i> Email
            </button>
            <button @click="tab = 'preferences'" :class="tab === 'preferences' ? 'bg-[var(--surface)] text-white border-[var(--gold)]/30 border' : 'text-[var(--text-secondary)] hover:text-white hover:bg-[var(--surface)]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-[8px] transition-colors text-left">
                <i data-lucide="sliders" class="w-5 h-5 shrink-0"></i> Preferences
            </button>
            <button @click="tab = 'danger'" :class="tab === 'danger' ? 'bg-[var(--surface)] text-[var(--danger)] border-[var(--danger)]/30 border' : 'text-[var(--danger)]/60 hover:text-[var(--danger)] hover:bg-[var(--surface)]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-[8px] transition-colors text-left">
                <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0"></i> Danger Zone
            </button>
        </div>
    </div>

    <!-- Right Content -->
    <div class="xl:col-span-2">

        <!-- Profile Tab -->
        <div x-show="tab === 'profile'" class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-6 border-b border-[var(--border)]">
                <h2 class="text-xl font-bold font-manrope">Profile Information</h2>
                <p class="text-sm text-[var(--text-secondary)] mt-1">Update your personal details and avatar.</p>
            </div>
            <form class="p-6 space-y-6">
                <!-- Avatar -->
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-full bg-[var(--gold)]/20 border-2 border-[var(--gold)] flex items-center justify-center text-[var(--gold)] text-2xl font-bold shrink-0">
                        <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <label class="btn btn-secondary border border-[var(--border)] bg-[var(--surface)] text-sm h-[40px] px-4 cursor-pointer flex items-center gap-2 w-fit">
                            <i data-lucide="upload" class="w-4 h-4"></i> Upload Photo
                            <input type="file" accept="image/*" class="sr-only">
                        </label>
                        <p class="text-xs text-[var(--text-muted)] mt-2">JPG, PNG or WebP. Max 2MB.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">First Name</label>
                        <input type="text" value="<?= htmlspecialchars($user['first_name']) ?>" class="input-field w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Last Name</label>
                        <input type="text" value="<?= htmlspecialchars($user['last_name']) ?>" class="input-field w-full">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Phone Number</label>
                    <input type="tel" value="<?= htmlspecialchars($user['phone']) ?>" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Company Name</label>
                    <input type="text" value="<?= htmlspecialchars($user['company']) ?>" class="input-field w-full">
                </div>
                
                <div class="flex justify-end pt-4 border-t border-[var(--border)]">
                    <button type="submit" class="btn btn-primary h-[44px] px-8">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Password Tab -->
        <div x-show="tab === 'password'" style="display:none" class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-6 border-b border-[var(--border)]">
                <h2 class="text-xl font-bold font-manrope">Change Password</h2>
                <p class="text-sm text-[var(--text-secondary)] mt-1">Ensure your account stays secure.</p>
            </div>
            <form class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Current Password</label>
                    <input type="password" placeholder="••••••••" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">New Password</label>
                    <input type="password" placeholder="••••••••" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Confirm New Password</label>
                    <input type="password" placeholder="••••••••" class="input-field w-full">
                </div>
                <div class="flex justify-end pt-4 border-t border-[var(--border)]">
                    <button type="submit" class="btn btn-primary h-[44px] px-8">Update Password</button>
                </div>
            </form>
        </div>

        <!-- Email Tab -->
        <div x-show="tab === 'email'" style="display:none" class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-6 border-b border-[var(--border)]">
                <h2 class="text-xl font-bold font-manrope">Change Email Address</h2>
                <p class="text-sm text-[var(--text-secondary)] mt-1">A confirmation link will be sent to your new email.</p>
            </div>
            <form class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Current Email</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled class="input-field w-full opacity-50 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">New Email Address</label>
                    <input type="email" placeholder="new@example.com" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Confirm Password</label>
                    <input type="password" placeholder="••••••••" class="input-field w-full">
                </div>
                <div class="flex justify-end pt-4 border-t border-[var(--border)]">
                    <button type="submit" class="btn btn-primary h-[44px] px-8">Send Confirmation</button>
                </div>
            </form>
        </div>

        <!-- Preferences Tab -->
        <div x-show="tab === 'preferences'" style="display:none" class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-6 border-b border-[var(--border)]">
                <h2 class="text-xl font-bold font-manrope">Notification Preferences</h2>
                <p class="text-sm text-[var(--text-secondary)] mt-1">Choose how you receive updates.</p>
            </div>
            <div class="p-6 space-y-6">
                <?php
                $prefs = [
                    ['label' => 'Order Status Updates', 'desc' => 'When your order status changes', 'checked' => true],
                    ['label' => 'Quote Updates', 'desc' => 'When your quote is reviewed or updated', 'checked' => true],
                    ['label' => 'Promotional Emails', 'desc' => 'New collections, deals, and seasonal offers', 'checked' => $user['newsletter']],
                    ['label' => 'Product Announcements', 'desc' => 'New products added to our catalogue', 'checked' => false],
                ];
                foreach($prefs as $pref): ?>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium"><?= $pref['label'] ?></p>
                        <p class="text-sm text-[var(--text-secondary)]"><?= $pref['desc'] ?></p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" <?= $pref['checked'] ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-[var(--surface)] rounded-full peer peer-checked:bg-[var(--gold)] transition-colors border border-[var(--border)]"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform shadow"></div>
                    </label>
                </div>
                <?php endforeach; ?>
                <div class="flex justify-end pt-4 border-t border-[var(--border)]">
                    <button type="submit" class="btn btn-primary h-[44px] px-8">Save Preferences</button>
                </div>
            </div>
        </div>

        <!-- Danger Zone Tab -->
        <div x-show="tab === 'danger'" style="display:none" class="bg-[var(--card)] border border-[var(--danger)]/30 rounded-[16px] overflow-hidden" x-data="{ confirmDelete: false }">
            <div class="p-6 border-b border-[var(--danger)]/20 bg-[var(--danger)]/5">
                <h2 class="text-xl font-bold font-manrope text-[var(--danger)]">Danger Zone</h2>
                <p class="text-sm text-[var(--text-secondary)] mt-1">Irreversible and destructive actions.</p>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between gap-6">
                    <div>
                        <h3 class="font-bold mb-1">Delete Account</h3>
                        <p class="text-sm text-[var(--text-secondary)]">This will permanently remove all your data, including orders, quotes, and personal information. This action cannot be undone.</p>
                    </div>
                    <button @click="confirmDelete = true" class="btn h-[44px] px-6 text-[var(--danger)] border border-[var(--danger)]/50 bg-[var(--danger)]/10 hover:bg-[var(--danger)]/20 transition-colors shrink-0 whitespace-nowrap">
                        Delete Account
                    </button>
                </div>

                <!-- Confirmation Modal -->
                <div x-show="confirmDelete" x-transition.opacity class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-6" style="display:none">
                    <div class="bg-[var(--card)] border border-[var(--danger)]/50 rounded-[24px] w-full max-w-[400px] p-8 text-center" @click.stop>
                        <div class="w-16 h-16 rounded-full bg-[var(--danger)]/10 text-[var(--danger)] flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Are you absolutely sure?</h3>
                        <p class="text-[var(--text-secondary)] mb-8 text-sm">This action is permanent. All your orders, quotes, and account data will be deleted forever.</p>
                        <div class="flex gap-4">
                            <button @click="confirmDelete = false" class="btn btn-secondary border border-[var(--border)] flex-1 h-[52px]">Cancel</button>
                            <button class="btn flex-1 h-[52px] text-white bg-[var(--danger)] hover:bg-red-700 transition-colors">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
