<div style="background: var(--ivory); color: var(--ink);">

    <section class="section" style="min-height: 72vh; display: flex; align-items: center; padding-top: 60px; padding-bottom: 60px;">
        <div class="container">
            <div class="success-state" style="padding: 30px 10px;">
                <div style="width: 96px; height: 96px; margin: 0 auto 28px; border-radius: 50%; background: rgba(234, 179, 8, 0.1); color: #d97706; display: grid; place-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:42px;height:42px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <span class="eyebrow center" style="justify-content: center;">Too Many Requests</span>
                <h3 style="margin: 18px 0 12px;">Please slow down</h3>
                <p>You've made too many requests in a short period. This is a temporary pause — please wait a moment and try again.</p>

                <div class="cta-actions" style="margin-top: 30px;">
                    <a href="<?= app_url('/') ?>" class="btn btn-gold btn-lg">Return to homepage <span class="arr">&rarr;</span></a>
                    <button onclick="window.location.reload()" class="btn btn-ghost btn-lg">Try again</button>
                </div>
            </div>
        </div>
    </section>

</div>
