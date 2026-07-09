<section class="min-h-[70vh] flex items-center justify-center py-20 px-6">
    <div class="max-w-[600px] w-full text-center">
        <!-- Animated Check -->
        <div class="w-24 h-24 rounded-full bg-[var(--gold)]/10 border-2 border-[var(--gold)] flex items-center justify-center mx-auto mb-8">
            <i data-lucide="check" class="w-12 h-12 text-[var(--gold)]"></i>
        </div>
        
        <h1 class="text-3xl md:text-4xl font-bold font-manrope mb-4">Quote Submitted Successfully!</h1>
        <p class="text-lg text-[var(--text-secondary)] mb-8">
            Thank you for your request. Our team has been notified and a dedicated account manager will review your requirements and respond within <strong class="text-white">24 hours</strong>.
        </p>
        
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6 text-left mb-10 space-y-3">
            <div class="flex items-center gap-3 text-sm text-[var(--text-secondary)]">
                <i data-lucide="mail" class="w-5 h-5 text-[var(--gold)] shrink-0"></i>
                A confirmation email has been sent to your inbox.
            </div>
            <div class="flex items-center gap-3 text-sm text-[var(--text-secondary)]">
                <i data-lucide="user-check" class="w-5 h-5 text-[var(--gold)] shrink-0"></i>
                An account manager will be assigned to your request.
            </div>
            <div class="flex items-center gap-3 text-sm text-[var(--text-secondary)]">
                <i data-lucide="file-text" class="w-5 h-5 text-[var(--gold)] shrink-0"></i>
                Track your quote status in your <a href="/account/quotes" class="text-[var(--gold)] hover:underline">customer portal</a>.
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/shop" class="btn btn-primary h-[52px] px-8 w-full sm:w-auto">Continue Browsing</a>
            <a href="/account/quotes" class="btn btn-secondary border border-[var(--border)] h-[52px] px-8 w-full sm:w-auto">View My Quotes</a>
        </div>
    </div>
</section>
