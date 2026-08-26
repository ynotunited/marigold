<?php
// System Status page — embeds Better Stack status page.
$statusUrl = $status_url ?? '#';
?>
<section class="status-page" style="min-height:80vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:4rem 1.5rem;text-align:center;">
    <div style="max-width:640px;width:100%;">
        <div style="margin-bottom:2rem;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--gold,#C89B3C)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 1rem;display:block;">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
            </svg>
            <h1 style="font-family:var(--font-display);font-size:2rem;color:var(--text-primary,#1B1A15);margin-bottom:0.5rem;">System Status</h1>
            <p style="color:var(--text-secondary,#666);font-size:1rem;line-height:1.6;">
                Real-time uptime and incident status for all Marigold Signature services.
            </p>
        </div>

        <!-- Better Stack badge embed -->
        <div style="margin-bottom:2rem;">
            <a href="<?= $statusUrl ?>" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:var(--ink,#1B1A15);color:#fff;border-radius:12px;text-decoration:none;font-weight:600;font-size:0.95rem;transition:transform 0.2s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
                View Live Status Page
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7 17L17 7M17 7H7M17 7v10"/>
                </svg>
            </a>
        </div>

        <!-- Service grid (static fallback — Better Stack embed replaces this) -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;max-width:560px;margin:0 auto 2.5rem;text-align:left;">
            <div style="padding:1rem 1.25rem;border:1px solid var(--border,rgba(0,0,0,.08));border-radius:12px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                    <span style="font-weight:600;font-size:0.9rem;">Website</span>
                </div>
                <span style="font-size:0.8rem;color:var(--text-secondary,#888);">marigoldsignatureng.com</span>
            </div>
            <div style="padding:1rem 1.25rem;border:1px solid var(--border,rgba(0,0,0,.08));border-radius:12px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                    <span style="font-weight:600;font-size:0.9rem;">Payment Gateway</span>
                </div>
                <span style="font-size:0.8rem;color:var(--text-secondary,#888);">Paystack &amp; Flutterwave</span>
            </div>
            <div style="padding:1rem 1.25rem;border:1px solid var(--border,rgba(0,0,0,.08));border-radius:12px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                    <span style="font-weight:600;font-size:0.9rem;">Email Delivery</span>
                </div>
                <span style="font-size:0.8rem;color:var(--text-secondary,#888);">SMTP service</span>
            </div>
            <div style="padding:1rem 1.25rem;border:1px solid var(--border,rgba(0,0,0,.08));border-radius:12px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                    <span style="font-weight:600;font-size:0.9rem;">Logistics</span>
                </div>
                <span style="font-size:0.8rem;color:var(--text-secondary,#888);">ShipBubble integration</span>
            </div>
        </div>

        <p style="font-size:0.85rem;color:var(--text-secondary,#999);">
            For real-time updates, subscribe to notifications on our
            <a href="<?= $statusUrl ?>" target="_blank" rel="noopener" style="color:var(--gold,#C89B3C);text-decoration:underline;">status page</a>.
        </p>
    </div>
</section>
