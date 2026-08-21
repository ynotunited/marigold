<?php
/**
 * Customer account deletion page.
 * Variables: $is_pending, $days_remaining, $retention_days, $csrf_token
 */
?>

<section class="section" style="padding-top: 40px; padding-bottom: 60px;">
    <div class="container" style="max-width: 640px;">

        <?php if ($is_pending): ?>
        <!-- Pending Deletion State -->
        <div style="background: rgba(234, 179, 8, 0.08); border: 1px solid rgba(234, 179, 8, 0.2); border-radius: 16px; padding: 32px; text-align: center;">
            <div style="width: 64px; height: 64px; margin: 0 auto 20px; border-radius: 50%; background: rgba(234, 179, 8, 0.15); display: grid; place-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:32px;height:32px;color:#d97706;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <h2 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 12px;">Your account is scheduled for deletion</h2>
            <p style="color: var(--text-secondary); margin-bottom: 8px;">
                Your account will be permanently deleted in <strong><?= $days_remaining ?> day<?= $days_remaining !== 1 ? 's' : '' ?></strong>.
            </p>
            <p style="color: var(--text-secondary); margin-bottom: 24px;">
                After this period, all your personal data will be permanently removed from our systems. Order financial records will be anonymized for tax compliance but stripped of your personal information.
            </p>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 28px;">
                You can cancel this deletion at any time before the retention period expires by clicking the button below.
            </p>
            <form method="POST" action="<?= app_url('/account/delete/cancel') ?>" style="display: inline;">
                <?= \App\Core\CSRF::field() ?>
                <button type="submit" class="btn btn-gold btn-lg" style="min-width: 220px;">
                    Cancel Deletion <span class="arr">&rarr;</span>
                </button>
            </form>
        </div>

        <?php else: ?>
        <!-- Deletion Request Form -->
        <div style="text-align: center; margin-bottom: 36px;">
            <h1 style="font-family: var(--font-display); font-size: 2rem; margin-bottom: 12px;">Delete My Account</h1>
            <p style="color: var(--text-secondary); max-width: 480px; margin: 0 auto;">
                Permanently delete your account and all associated personal data. This action is irreversible after the <?= $retention_days ?>-day retention period.
            </p>
        </div>

        <!-- Data overview -->
        <div style="background: var(--surface, #1a1a1a); border: 1px solid var(--border, #333); border-radius: 16px; padding: 28px; margin-bottom: 24px;">
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 16px;">What will happen:</h3>
            <ul style="list-style: none; padding: 0; margin: 0; display: grid; gap: 12px;">
                <li style="display: flex; align-items: flex-start; gap: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;color:#ef4444;flex-shrink:0;margin-top:2px;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m9 9 6 6m0-6-6 6"/></svg>
                    <span style="font-size: 0.9rem; color: var(--text-secondary);">Your profile, wishlist, reviews, and notifications will be permanently deleted</span>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;color:#d97706;flex-shrink:0;margin-top:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span style="font-size: 0.9rem; color: var(--text-secondary);">Order records will be anonymized — your name, email, phone, and address removed, but financial data retained for tax compliance (6-7 years)</span>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;color:#d97706;flex-shrink:0;margin-top:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span style="font-size: 0.9rem; color: var(--text-secondary);">You will be logged out immediately and unable to access your account</span>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;color:#22c55e;flex-shrink:0;margin-top:2px;"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    <span style="font-size: 0.9rem; color: var(--text-secondary);">You can cancel the deletion within <?= $retention_days ?> days by logging back in</span>
                </li>
            </ul>
        </div>

        <!-- Confirmation form -->
        <form method="POST" action="<?= app_url('/account/delete/request') ?>" style="background: rgba(181, 52, 34, 0.06); border: 1px solid rgba(181, 52, 34, 0.2); border-radius: 16px; padding: 28px;">
            <?= \App\Core\CSRF::field() ?>

            <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; margin-bottom: 24px;">
                <input type="checkbox" name="confirm_deletion" value="1" required style="margin-top: 3px; accent-color: #b53422;">
                <span style="font-size: 0.9rem; color: var(--text-secondary);">
                    I understand that after the <?= $retention_days ?>-day retention period, my account and personal data will be permanently and irreversibly deleted. I have downloaded any data I wish to keep.
                </span>
            </label>

            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <button type="submit" class="btn btn-lg" style="background: #b53422; color: white; min-width: 200px;">
                    Request Account Deletion
                </button>
                <a href="<?= app_url('/account/settings') ?>" class="btn btn-ghost btn-lg">Cancel</a>
            </div>
        </form>

        <!-- Data export links -->
        <div style="margin-top: 28px; padding: 20px; border: 1px solid var(--border, #333); border-radius: 12px; text-align: center;">
            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 12px;">Before deleting, you can download a copy of all data we hold on you (GDPR Article 20):</p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="<?= app_url('/account/delete/export') ?>" class="btn btn-secondary btn-sm" style="border: 1px solid var(--border);">Download JSON</a>
                <a href="<?= app_url('/account/delete/view') ?>" class="btn btn-secondary btn-sm" style="border: 1px solid var(--border);">View Online</a>
            </div>
        </div>

        <?php endif; ?>
    </div>
</section>
