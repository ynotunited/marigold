<div style="background: var(--ivory); color: var(--ink);">

    <section class="section" style="min-height: 72vh; display: flex; align-items: center; padding-top: 60px; padding-bottom: 60px;">
        <div class="container">
            <div class="success-state" style="padding: 30px 10px;">
                <div style="width: 96px; height: 96px; margin: 0 auto 28px; border-radius: 50%; background: rgba(181, 52, 34, 0.1); color: var(--danger, #b53422); display: grid; place-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:42px;height:42px;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>
                <span class="eyebrow center" style="justify-content: center;">Internal Error</span>
                <h3 style="margin: 18px 0 12px;">Something went wrong</h3>
                <p>We're experiencing an internal server issue. Our technical team has been notified and is working to resolve the problem.</p>
                <?php if (!empty($error_id)): ?>
                    <p style="font-size: 13px; color: var(--muted); margin-top: 8px;">Reference: <?= htmlspecialchars($error_id, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>

                <div class="cta-actions" style="margin-top: 30px;">
                    <a href="<?= app_url('/') ?>" class="btn btn-gold btn-lg">Return to homepage <span class="arr">&rarr;</span></a>
                    <a href="<?= app_url('/contact') ?>" class="btn btn-ghost btn-lg">Contact support</a>
                </div>
            </div>
        </div>
    </section>

</div>
