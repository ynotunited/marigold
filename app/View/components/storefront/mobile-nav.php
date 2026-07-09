<!-- Mobile Bottom Navigation (Persistent, App-like) -->
<nav class="md:hidden fixed bottom-0 left-0 w-full bg-[#111]/90 backdrop-blur-md border-t border-[var(--border)] z-50 pb-safe">
    <div class="flex justify-around items-center h-[60px]">
        <a href="/" class="flex flex-col items-center justify-center w-full h-full text-[var(--gold)]">
            <i data-lucide="home" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-medium tracking-wide">Home</span>
        </a>
        <a href="/shop" class="flex flex-col items-center justify-center w-full h-full text-[var(--text-secondary)] hover:text-white transition-colors">
            <i data-lucide="grid" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-medium tracking-wide">Shop</span>
        </a>
        <button @click="$dispatch('open-cart')" class="flex flex-col items-center justify-center w-full h-full text-[var(--text-secondary)] hover:text-white transition-colors relative">
            <i data-lucide="shopping-bag" class="w-5 h-5 mb-1"></i>
            <span class="absolute top-1 right-1/4 translate-x-2 -translate-y-1 w-4 h-4 bg-[var(--gold)] text-[#111] font-bold text-[9px] flex items-center justify-center rounded-full border-2 border-[#111]">2</span>
            <span class="text-[10px] font-medium tracking-wide">Cart</span>
        </button>
        <a href="/wishlist" class="flex flex-col items-center justify-center w-full h-full text-[var(--text-secondary)] hover:text-white transition-colors">
            <i data-lucide="heart" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-medium tracking-wide">Wishlist</span>
        </a>
        <a href="/account/dashboard" class="flex flex-col items-center justify-center w-full h-full text-[var(--text-secondary)] hover:text-white transition-colors">
            <i data-lucide="user" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-medium tracking-wide">Account</span>
        </a>
    </div>
</nav>

<!-- Global CSS Overrides for Mobile Experience -->
<style>
    /* Prevent iOS zoom on inputs */
    input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="tel"], textarea, select {
        font-size: 16px !important;
    }
    
    /* Ensure minimum tap targets (44x44) */
    button, a.btn, .nav-item {
        min-height: 44px;
        min-width: 44px;
    }

    /* Prevent unintentional horizontal scroll */
    html, body {
        max-width: 100vw;
        overflow-x: hidden;
    }

    /* Safe area padding for iPhones with notch/home indicator */
    .pb-safe {
        padding-bottom: env(safe-area-inset-bottom);
    }
</style>
