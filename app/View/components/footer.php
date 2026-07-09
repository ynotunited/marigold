<?php
// app/View/components/footer.php
?>
<footer class="bg-[var(--surface)] pt-16 pb-8 border-t border-[var(--border)] mt-auto">
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- Company Info -->
            <div>
                <a href="/" class="flex items-center gap-3 mb-6">
                    <img src="/ms-logo-removebg-preview.png" alt="Marigold Signature" class="h-10 w-auto object-contain">
                </a>
                <p class="text-[var(--text-secondary)] text-sm mb-6">
                    Premium corporate merchandise and executive gifting solutions for modern businesses.
                </p>
                <div class="flex gap-4">
                    <a href="https://www.instagram.com/marigoldsignature_ng" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--gold)] hover:border-[var(--gold)] transition-colors">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="https://www.facebook.com/marigoldsignature_ng" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--gold)] hover:border-[var(--gold)] transition-colors">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/marigold-signature" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--gold)] hover:border-[var(--gold)] transition-colors">
                        <i data-lucide="linkedin" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-['Manrope'] font-bold text-white mb-6">Quick Links</h4>
                <ul class="space-y-3 text-sm text-[var(--text-secondary)]">
                    <li><a href="/shop" class="hover:text-[var(--gold)] transition-colors">Shop All</a></li>
                    <li><a href="/solutions" class="hover:text-[var(--gold)] transition-colors">Corporate Solutions</a></li>
                    <li><a href="/about" class="hover:text-[var(--gold)] transition-colors">About Us</a></li>
                    <li><a href="/contact" class="hover:text-[var(--gold)] transition-colors">Contact</a></li>
                    <li><a href="/faq" class="hover:text-[var(--gold)] transition-colors">FAQs</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div>
                <h4 class="font-['Manrope'] font-bold text-white mb-6">Categories</h4>
                <ul class="space-y-3 text-sm text-[var(--text-secondary)]">
                    <li><a href="#" class="hover:text-[var(--gold)] transition-colors">Executive Pens</a></li>
                    <li><a href="#" class="hover:text-[var(--gold)] transition-colors">Drinkware</a></li>
                    <li><a href="#" class="hover:text-[var(--gold)] transition-colors">Leather Goods</a></li>
                    <li><a href="#" class="hover:text-[var(--gold)] transition-colors">Tech Accessories</a></li>
                    <li><a href="#" class="hover:text-[var(--gold)] transition-colors">Apparel</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="font-['Manrope'] font-bold text-white mb-6">Newsletter</h4>
                <p class="text-[var(--text-secondary)] text-sm mb-4">
                    Subscribe to receive updates, access to exclusive deals, and more.
                </p>
                <form class="flex flex-col gap-3" onsubmit="event.preventDefault(); window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Subscribed successfully!', type: 'success' }}))">
                    <input type="email" placeholder="Enter your email address" required class="bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-[var(--gold)] transition-colors">
                    <button type="submit" class="bg-[var(--gold)] text-black font-bold text-sm py-3 rounded-xl hover:bg-[#D4AF37] transition-colors">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        <div class="pt-8 border-t border-[var(--border)] flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-[var(--text-muted)] text-sm text-center md:text-left">
                &copy; <?= date('Y') ?> Marigold Signature Nigeria Limited. All rights reserved.
            </p>
            <div class="flex flex-wrap justify-center gap-4 text-sm text-[var(--text-muted)]">
                <a href="/privacy" class="hover:text-[var(--gold)] transition-colors">Privacy Policy</a>
                <a href="/terms" class="hover:text-[var(--gold)] transition-colors">Terms of Service</a>
                <a href="/shipping" class="hover:text-[var(--gold)] transition-colors">Shipping Policy</a>
            </div>
        </div>
    </div>
</footer>
