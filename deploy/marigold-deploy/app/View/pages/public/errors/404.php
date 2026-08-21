<div style="background: var(--ivory); color: var(--ink);">

    <section class="section" style="min-height: 72vh; display: flex; align-items: center; padding-top: 60px; padding-bottom: 60px;">
        <div class="container">
            <div class="success-state" style="padding: 30px 10px;">
                <div class="q-no" style="font-size: clamp(5.5rem, 20vw, 11rem); line-height: 1; color: var(--gold); letter-spacing: 0.02em;">404</div>
                <span class="eyebrow center" style="justify-content: center;">Page Not Found</span>
                <h3 style="margin: 18px 0 12px;">This page has wandered off</h3>
                <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>

                <form action="<?= app_url('/search') ?>" method="get" style="max-width: 460px; margin: 0 auto 32px; display: flex; gap: 10px;">
                    <input type="text" name="q" placeholder="Search products or articles..." class="field" style="border-radius: 999px;">
                    <button type="submit" class="btn btn-dark" aria-label="Search">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </button>
                </form>

                <div class="cta-actions">
                    <a href="<?= app_url('/') ?>" class="btn btn-gold btn-lg">Return to homepage <span class="arr">&rarr;</span></a>
                    <a href="<?= app_url('/shop') ?>" class="btn btn-ghost btn-lg">Browse the shop</a>
                </div>
            </div>
        </div>
    </section>

</div>
