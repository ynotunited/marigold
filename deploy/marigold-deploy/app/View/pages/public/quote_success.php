<div style="background: var(--ivory); color: var(--ink);">

    <section class="section" style="padding-top: clamp(54px, 7vw, 96px);">
        <div class="container">
            <div class="success-state" style="padding: 30px 10px;">

                <div class="ss-ico">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" style="width:38px;height:38px;"><path d="M20 6 9 17l-5-5"/></svg>
                </div>

                <span class="eyebrow center" style="justify-content: center;">Request Received</span>
                <h3 style="margin: 18px 0 12px;">Quote submitted successfully</h3>
                <p>Thank you for your request. Our team has been notified and a dedicated account manager will review your requirements and respond within <strong style="color: var(--ink);">24 hours</strong>.</p>

                <div class="order-sum" style="max-width: 560px; margin: 0 auto 32px; text-align: left; background: var(--paper);">
                    <div class="row"><span>A confirmation email has been sent to your inbox</span></div>
                    <div class="row"><span>An account manager will be assigned to your request</span></div>
                    <div class="row"><span>Track your quote status in your <a href="<?= app_url('/account/quotes') ?>" style="color: var(--gold-deep); font-weight: 700;">customer portal</a></span></div>
                </div>

                <div class="cta-actions">
                    <a href="<?= app_url('/shop') ?>" class="btn btn-gold btn-lg">Continue browsing <span class="arr">&rarr;</span></a>
                    <a href="<?= app_url('/account/quotes') ?>" class="btn btn-ghost btn-lg">View my quotes</a>
                </div>

            </div>
        </div>
    </section>

</div>
