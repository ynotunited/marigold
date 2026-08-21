<?php
// app/View/components/newsletter_popup.php
?>
<!-- Newsletter Popup -->
<div x-data="{ open: false, email: '' }"
     x-effect="document.body.classList.toggle('overflow-hidden', open)"
     x-init="setTimeout(() => { if (!sessionStorage.getItem('newsletter_popup_seen')) { open = true; sessionStorage.setItem('newsletter_popup_seen', 'true'); } }, 4000)"
     @keydown.escape.window="open = false"
     class="relative"
     style="z-index: 9999;">

    <!-- Backdrop -->
    <div x-show="open"
         x-cloak
         x-transition.opacity.duration.200ms
         class="fixed inset-0 bg-black/80 backdrop-blur-sm"
         style="z-index: 9998;"
         @click="open = false"></div>

    <!-- Modal -->
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-8"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-8"
         class="fixed inset-0 grid place-items-center p-4 pointer-events-none"
         style="z-index: 9999;">

        <div class="relative w-full max-w-md bg-[var(--card)] rounded-3xl border border-[var(--border)] shadow-2xl overflow-hidden pointer-events-auto">

            <!-- Close button -->
            <button @click="open = false" aria-label="Close newsletter popup"
                    class="absolute top-4 right-4 z-20 text-[var(--text-muted)] hover:text-white transition-colors rounded-full p-2">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <div class="p-8 sm:p-10 text-center">

                <!-- Icon badge -->
                <div class="mx-auto mb-5 w-14 h-14 rounded-2xl bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center">
                    <i data-lucide="mail" class="w-6 h-6 text-[var(--gold)]"></i>
                </div>

                <!-- Eyebrow -->
                <span class="text-[10px] font-bold uppercase tracking-widest text-[var(--gold)] mb-3 block">The Insider List</span>

                <!-- Headline -->
                <h2 class="font-['Manrope'] text-2xl sm:text-3xl font-extrabold text-white mb-3 leading-snug">Corporate gift ideas, delivered.</h2>

                <!-- Pitch -->
                <p class="text-sm text-[var(--text-secondary)] leading-relaxed mb-8">
                    Join our newsletter for exclusive collections, seasonal launches, and insider tips on premium corporate gifting.
                </p>

                <!-- Form -->
                <form @submit.prevent="if (email) { window.Marigold && window.Marigold.subscribe(email, 'Popup'); } sessionStorage.setItem('newsletter_popup_seen', 'true'); open = false;" class="space-y-3">
                    <input x-model="email" type="email" name="email" required autocomplete="email"
                           placeholder="Your email address"
                           class="w-full bg-[var(--bg-primary)] border border-[var(--border)] rounded-xl px-5 py-4 text-sm text-white placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--gold)] focus:ring-2 focus:ring-[var(--gold)] transition-all">
                    <button type="submit"
                            class="w-full bg-[var(--gold)] text-black font-bold px-6 py-4 rounded-xl hover:bg-[#D4AF37] transition-colors duration-300 uppercase tracking-wide text-sm flex items-center justify-center gap-2 group">
                        Subscribe
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <!-- Consent line -->
                <p class="text-[var(--text-muted)] text-[11px] mt-5 leading-relaxed">
                    By subscribing you agree to our
                    <a href="<?= app_url('/terms-and-conditions') ?>" class="text-[var(--text-secondary)] underline hover:text-white transition-colors">Terms</a>
                    and
                    <a href="<?= app_url('/privacy-policy') ?>" class="text-[var(--text-secondary)] underline hover:text-white transition-colors">Privacy Policy</a>.
                    Unsubscribe anytime.
                </p>
            </div>
        </div>
    </div>
</div>
