<?php
// app/View/components/newsletter_popup.php
?>
<!-- Landing Page Newsletter Popup -->
<div x-data="{ open: false, name: '', contact: '' }"
     x-effect="document.body.classList.toggle('overflow-hidden', open)"
     x-init="setTimeout(() => { if (!sessionStorage.getItem('newsletter_popup_seen')) { open = true; sessionStorage.setItem('newsletter_popup_seen', 'true'); } }, 3500)"
     @keydown.escape.window="open = false"
     class="relative"
     style="z-index: 9999;">
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition.opacity
         class="fixed inset-0 bg-black/80 backdrop-blur-sm"
         style="z-index: 9998;"
         @click="open = false"></div>

    <!-- Modal Panel -->
    <div x-show="open"
         x-transition
         class="fixed inset-0 grid place-items-center p-4 sm:p-6 pointer-events-none"
         style="z-index: 9999;">
         
         <div class="bg-[var(--card)] sm:bg-[var(--card)] rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-5xl max-h-[calc(100vh-2rem)] overflow-auto pointer-events-auto flex flex-col sm:flex-row relative border border-[var(--border)]">
             
             <!-- Close button -->
             <button @click="open = false" class="absolute top-4 right-4 z-20 text-[var(--text-secondary)] hover:text-white transition-colors bg-black/40 sm:bg-black/20 rounded-full p-2 backdrop-blur-md">
                 <i data-lucide="x" class="w-5 h-5 sm:w-6 sm:h-6"></i>
             </button>

             <!-- Left Content -->
             <div class="w-full sm:w-1/2 p-8 sm:p-14 flex flex-col justify-center relative">
                 <!-- Subtle glow effect behind text -->
                 <div class="absolute -top-24 -left-24 w-64 h-64 bg-[var(--gold)]/5 rounded-full blur-3xl pointer-events-none"></div>

                 <h2 class="font-['Manrope'] text-3xl sm:text-4xl font-extrabold mb-4 text-white relative z-10">Subscribe to Marigold Signature</h2>
                 
                 <p class="text-[var(--text-secondary)] text-sm mb-6 leading-relaxed relative z-10">
                     Subscribe to our updates and be among the first to know about exclusive corporate gifting collections, new launches, and more. Enjoy complimentary branding consultation on any large order.
                 </p>
                 
                 <p class="text-[var(--text-muted)] text-[11px] mb-8 leading-tight relative z-10">
                     By submitting this form, you agree to receive recurring automated marketing messages (e.g. exclusive insights, new products) from Marigold Signature. Consent is not a condition of purchase. Msg & data rates may apply. By submitting this form, you also agree to our <a href="#" class="underline hover:text-white transition-colors">Terms</a> & <a href="#" class="underline hover:text-white transition-colors">Privacy Policy</a>.
                 </p>
                 
                 <form @submit.prevent="window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'You\'re subscribed! Welcome to Marigold Signature.', type: 'success' }})); open = false;" class="flex flex-col gap-4 relative z-10">
                     <div class="flex flex-col gap-4">
                         <input x-model="name" type="text" placeholder="Full Name" required
                                class="bg-[var(--bg-primary)] border border-[var(--border)] rounded-xl px-5 py-4 text-sm focus:outline-none focus:border-[var(--gold)] transition-colors w-full text-white placeholder-[var(--text-muted)]">
                         <input x-model="contact" type="text" placeholder="Mobile Number or Email" required
                                class="bg-[var(--bg-primary)] border border-[var(--border)] rounded-xl px-5 py-4 text-sm focus:outline-none focus:border-[var(--gold)] transition-colors w-full text-white placeholder-[var(--text-muted)]">
                     </div>
                     
                     <button type="submit"
                             class="bg-[var(--gold)] text-black font-bold px-6 py-4 rounded-xl hover:bg-[#D4AF37] transition-all duration-300 mt-2 uppercase tracking-wide text-sm flex items-center justify-center gap-2 group">
                         Sign Up For Updates
                         <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                     </button>
                 </form>
             </div>
             
             <!-- Right Image -->
             <div class="hidden sm:flex w-1/2 bg-[var(--surface)] relative overflow-hidden items-center justify-center p-12">
                 <!-- Radial gradient background to mimic the rimowa lighting -->
                 <div class="absolute inset-0 bg-gradient-to-br from-[var(--surface)] to-black"></div>
                 
                 <!-- Use a placeholder that fits a premium gift / case to mimic the rimowa vibe -->
                 <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?q=80&w=800&auto=format&fit=crop" 
                      alt="Premium Corporate Gift" 
                      class="relative z-10 w-full h-full object-cover rounded-2xl shadow-2xl border border-[var(--border)]/50 scale-105 transform hover:scale-110 transition-transform duration-1000">
                      
                 <!-- Overlay for blending -->
                 <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg-primary)]/80 via-transparent to-transparent pointer-events-none z-20"></div>
             </div>
         </div>
    </div>
</div>
