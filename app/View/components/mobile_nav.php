<?php
// app/View/components/mobile_nav.php
?>
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-[var(--surface)] border-t border-[var(--border)] z-40 pb-safe">
    <nav class="flex justify-around items-center h-16">
        <a href="/" class="flex flex-col items-center justify-center w-full h-full text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors <?php echo ($_SERVER['REQUEST_URI'] == '/') ? 'text-[var(--gold)]' : ''; ?>">
            <i data-lucide="home" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-semibold">Home</span>
        </a>
        <a href="/shop" class="flex flex-col items-center justify-center w-full h-full text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors <?php echo (strpos($_SERVER['REQUEST_URI'], '/shop') !== false) ? 'text-[var(--gold)]' : ''; ?>">
            <i data-lucide="layout-grid" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-semibold">Sales</span>
        </a>
        <a href="/wishlist" class="flex flex-col items-center justify-center w-full h-full text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors relative <?php echo (strpos($_SERVER['REQUEST_URI'], '/wishlist') !== false) ? 'text-[var(--gold)]' : ''; ?>">
            <i data-lucide="heart" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-semibold">Wishlist</span>
            <span class="absolute top-2 right-4 bg-[var(--gold)] text-black text-[8px] font-bold w-3 h-3 rounded-full flex items-center justify-center">2</span>
        </a>
        <a href="/account" class="flex flex-col items-center justify-center w-full h-full text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors <?php echo (strpos($_SERVER['REQUEST_URI'], '/account') !== false) ? 'text-[var(--gold)]' : ''; ?>">
            <i data-lucide="user" class="w-5 h-5 mb-1"></i>
            <span class="text-[10px] font-semibold">Account</span>
        </a>
    </nav>
</div>
