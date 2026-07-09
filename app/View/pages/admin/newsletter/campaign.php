<div class="flex items-center gap-4 mb-6">
    <a href="/admin/newsletter/subscribers" class="text-[var(--text-secondary)] hover:text-white transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
    <div class="flex-grow"><h1 class="text-2xl font-bold font-manrope">Send Campaign</h1></div>
    <div class="flex items-center gap-3">
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)]">Send Test Email</button>
        <button class="btn btn-primary h-9 px-6 text-sm flex items-center gap-2"><i data-lucide="send" class="w-4 h-4"></i> Send to 3,452 Subscribers</button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 h-[calc(100vh-140px)] min-h-[600px]">
    
    <!-- Left: Editor -->
    <div class="xl:col-span-1 space-y-6 flex flex-col h-full">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6 flex flex-col flex-grow">
            <h2 class="font-bold font-manrope mb-4">Campaign Details</h2>
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Email Subject</label>
                    <input type="text" placeholder="e.g. Our new Corporate Gifting Collection is here!" class="input-field w-full text-sm">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-1.5">Target Audience</label>
                    <select class="input-field w-full text-sm"><option>All Active Subscribers (3,452)</option><option>Corporate Accounts Only (450)</option></select>
                </div>
            </div>

            <h2 class="font-bold font-manrope mb-4">Email Content (HTML)</h2>
            <div class="border border-[var(--border)] rounded-[10px] overflow-hidden flex-grow flex flex-col">
                <div class="bg-[var(--surface)] border-b border-[var(--border)] flex items-center gap-1 p-2 flex-wrap shrink-0">
                    <?php foreach (['bold', 'italic', 'image', 'link', 'code'] as $btn): ?>
                    <button type="button" class="w-8 h-7 rounded-[4px] hover:bg-[var(--card)] text-[var(--text-secondary)] hover:text-white transition-colors flex items-center justify-center"><i data-lucide="<?= $btn ?>" class="w-3.5 h-3.5"></i></button>
                    <?php endforeach; ?>
                </div>
                <textarea class="w-full bg-transparent p-4 text-sm leading-relaxed focus:outline-none resize-none flex-grow font-mono text-[var(--text-secondary)]" placeholder="Write your HTML email content here..."></textarea>
            </div>
        </div>
    </div>

    <!-- Right: Live Preview -->
    <div class="xl:col-span-1 bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden flex flex-col h-full">
        <div class="p-4 border-b border-[var(--border)] bg-[#0f0f0f] flex items-center justify-between shrink-0">
            <h3 class="font-bold font-manrope text-sm flex items-center gap-2"><i data-lucide="eye" class="w-4 h-4 text-[var(--text-muted)]"></i> Live Preview</h3>
            <div class="flex gap-2">
                <button class="w-8 h-8 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-white"><i data-lucide="monitor" class="w-4 h-4"></i></button>
                <button class="w-8 h-8 rounded-[6px] bg-transparent flex items-center justify-center text-[var(--text-secondary)] hover:text-white transition-colors"><i data-lucide="smartphone" class="w-4 h-4"></i></button>
            </div>
        </div>
        <div class="flex-grow bg-[#e5e5e5] p-8 overflow-y-auto flex items-start justify-center">
            <!-- Mock Email Preview Container -->
            <div class="w-full max-w-[600px] bg-white rounded shadow-sm text-black p-8 font-sans">
                <div class="text-center mb-8"><span class="font-bold text-2xl tracking-tighter">MARIGOLD</span></div>
                <div class="w-full h-48 bg-gray-200 mb-6 flex items-center justify-center text-gray-400 border border-gray-300">Header Image Placeholder</div>
                <h1 class="text-xl font-bold mb-4">Hello {{ customer.name }},</h1>
                <p class="mb-4 text-gray-700 leading-relaxed">This is a live preview of how your email will look to the recipients. The editor on the left allows you to inject raw HTML or use the rich text toolbar to format your message.</p>
                <p class="mb-6 text-gray-700 leading-relaxed">Don't forget that an unsubscribe link will be automatically appended to the bottom of this email to maintain GDPR compliance.</p>
                <div class="text-center">
                    <a href="#" class="inline-block px-6 py-3 bg-[#111] text-white font-medium text-sm rounded no-underline">Shop Now</a>
                </div>
                <div class="mt-12 pt-6 border-t border-gray-200 text-center text-xs text-gray-500">
                    <p class="mb-2">Marigold Signature Ltd, 14 Adeola Odeku St, Victoria Island, Lagos</p>
                    <p><a href="#" class="underline">Unsubscribe</a> from these emails.</p>
                </div>
            </div>
        </div>
    </div>
</div>
