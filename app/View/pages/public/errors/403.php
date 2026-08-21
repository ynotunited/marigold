<div style="background: var(--ivory); color: var(--ink);">

    <section class="section" style="min-height: 72vh; display: flex; align-items: center; padding-top: 60px; padding-bottom: 60px;">
        <div class="container">
            <div class="success-state" style="padding: 30px 10px;">
                <div style="width: 96px; height: 96px; margin: 0 auto 28px; border-radius: 50%; background: rgba(181, 52, 34, 0.1); color: var(--danger, #b53422); display: grid; place-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:42px;height:42px;"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <span class="eyebrow center" style="justify-content: center;">Access Denied</span>
                <h3 style="margin: 18px 0 12px;">You don't have permission to view this page</h3>
                <p>You may not be signed in, or your account may not have the required access level. If you believe this is a mistake, please contact your account administrator.</p>

                <div class="cta-actions" style="margin-top: 30px;">
                    <a href="<?= app_url('/') ?>" class="btn btn-gold btn-lg">Return to homepage <span class="arr">&rarr;</span></a>
                    <a href="<?= app_url('/login') ?>" class="btn btn-ghost btn-lg">Sign in</a>
                </div>
            </div>
        </div>
    </section>

</div>
