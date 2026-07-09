<?php
// app/View/components/header.php
?>
<header 
    x-data="{ scrolled: false, mobileMenuOpen: false, searchOpen: false, cartOpen: false }" 
    @scroll.window="scrolled = (window.pageYOffset > 50)"
    :class="{ 'bg-[var(--bg-primary)]/90 backdrop-blur-md shadow-md py-4': scrolled, 'bg-transparent py-6': !scrolled }"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 border-b border-transparent"
    :style="scrolled ? 'border-bottom-color: var(--border)' : ''"
>
    <!-- Announcement Bar -->
    <div class="bg-[var(--gold)] text-black text-xs font-semibold py-2 px-4 text-center absolute top-0 w-full transition-transform duration-300 origin-top"
         :class="{ 'scale-y-0': scrolled, 'scale-y-100': !scrolled }"
         style="height: 40px; line-height: 24px;">
        Complimentary shipping on corporate orders over ₦500,000
    </div>

    <!-- Main Header -->
    <div class="container mx-auto px-4 sm:px-8 max-w-[1440px] flex items-center justify-between"
         :class="{ 'mt-0': scrolled, 'mt-[40px]': !scrolled }">
         
        <!-- Mobile Left: Hamburger -->
        <button @click="mobileMenuOpen = true" class="lg:hidden text-[var(--text-primary)] hover:text-[var(--gold)] transition-colors">
            <i data-lucide="menu"></i>
        </button>

        <!-- Logo -->
        <a href="/" class="flex items-center">
            <img src="/ms-logo-removebg-preview.png" alt="Marigold Signature" class="h-8 w-auto max-w-[160px] object-contain">
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-8 font-medium text-sm text-[var(--text-secondary)]">
            <a href="/shop" class="hover:text-[var(--gold)] transition-colors">Shop</a>
            
            <!-- Events Dropdown -->
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="flex items-center gap-1 hover:text-[var(--gold)] transition-colors py-2">
                    Events <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-64 bg-[var(--surface)] border border-[var(--border)] rounded-2xl shadow-2xl overflow-hidden py-3">
                    <a href="/events/corporate-meeting" class="flex items-center gap-3 px-5 py-3 text-[13px] text-[var(--text-secondary)] hover:text-white hover:bg-[var(--card)] transition-colors">
                        <i data-lucide="users" class="w-[18px] h-[18px] text-[var(--gold)] shrink-0"></i> Corporate Meeting
                    </a>
                    <a href="/events/conference" class="flex items-center gap-3 px-5 py-3 text-[13px] text-[var(--text-secondary)] hover:text-white hover:bg-[var(--card)] transition-colors">
                        <i data-lucide="presentation" class="w-[18px] h-[18px] text-[var(--gold)] shrink-0"></i> Conference
                    </a>
                    <a href="/events/dinner" class="flex items-center gap-3 px-5 py-3 text-[13px] text-[var(--text-secondary)] hover:text-white hover:bg-[var(--card)] transition-colors">
                        <i data-lucide="utensils" class="w-[18px] h-[18px] text-[var(--gold)] shrink-0"></i> Dinner
                    </a>
                </div>
            </div>

            <a href="/about" class="hover:text-[var(--gold)] transition-colors">About</a>
            <a href="/blog" class="hover:text-[var(--gold)] transition-colors">Blog</a>
            <a href="/contact" class="hover:text-[var(--gold)] transition-colors">Contact</a>
        </nav>

        <!-- Right Icons -->
        <div class="flex items-center gap-5">
            <button @click="searchOpen = true" class="text-[var(--text-primary)] hover:text-[var(--gold)] transition-colors">
                <i data-lucide="search" class="w-5 h-5"></i>
            </button>
            <a href="/wishlist" class="hidden lg:block relative text-[var(--text-primary)] hover:text-[var(--gold)] transition-colors">
                <i data-lucide="heart" class="w-5 h-5"></i>
                <span class="absolute -top-2 -right-2 bg-[var(--gold)] text-black text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">2</span>
            </a>
            <a href="/account" class="hidden lg:block text-[var(--text-primary)] hover:text-[var(--gold)] transition-colors">
                <i data-lucide="user" class="w-5 h-5"></i>
            </a>
            <button @click="cartOpen = true" class="relative text-[var(--text-primary)] hover:text-[var(--gold)] transition-colors">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                <span class="absolute -top-2 -right-2 bg-[var(--gold)] text-black text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">0</span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Drawer -->
    <template x-teleport="body">
        <div x-show="mobileMenuOpen" class="fixed inset-0 z-[100] lg:hidden">
            <div x-show="mobileMenuOpen" x-transition.opacity class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="absolute inset-y-0 left-0 w-[300px] bg-[var(--surface)] border-r border-[var(--border)] p-6 flex flex-col">
                
                <div class="flex items-center justify-between mb-8">
                    <a href="/">
                        <img src="/ms-logo-removebg-preview.png" alt="Marigold Signature" class="h-9 w-auto object-contain">
                    </a>
                    <button @click="mobileMenuOpen = false" class="text-[var(--text-muted)] hover:text-white transition-colors">
                        <i data-lucide="x"></i>
                    </button>
                </div>

                <nav class="flex flex-col gap-1 text-lg font-['Manrope'] flex-grow overflow-y-auto">
                    <a href="/shop" class="px-2 py-3 hover:text-[var(--gold)] transition-colors">Shop</a>

                    <!-- Events accordion -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-2 py-3 hover:text-[var(--gold)] transition-colors">
                            <span>Events</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition class="pl-4 flex flex-col gap-1 border-l border-[var(--border)] ml-2 mb-2">
                            <a href="/events/corporate-meeting" class="py-2 text-base text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors flex items-center gap-2">
                                <i data-lucide="users" class="w-4 h-4 text-[var(--gold)]"></i> Corporate Meeting
                            </a>
                            <a href="/events/conference" class="py-2 text-base text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors flex items-center gap-2">
                                <i data-lucide="presentation" class="w-4 h-4 text-[var(--gold)]"></i> Conference
                            </a>
                            <a href="/events/dinner" class="py-2 text-base text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors flex items-center gap-2">
                                <i data-lucide="utensils" class="w-4 h-4 text-[var(--gold)]"></i> Dinner
                            </a>
                        </div>
                    </div>

                    <a href="/about" class="px-2 py-3 hover:text-[var(--gold)] transition-colors">About</a>
                    <a href="/blog" class="px-2 py-3 hover:text-[var(--gold)] transition-colors">Blog</a>
                    <a href="/contact" class="px-2 py-3 hover:text-[var(--gold)] transition-colors">Contact</a>
                </nav>

                <div class="mt-8 pt-8 border-t border-[var(--border)] flex flex-col gap-4">
                    <a href="/account" class="flex items-center gap-3 text-[var(--text-secondary)] hover:text-white transition-colors">
                        <i data-lucide="user" class="w-5 h-5"></i> Account
                    </a>
                    <a href="/wishlist" class="flex items-center gap-3 text-[var(--text-secondary)] hover:text-white transition-colors">
                        <i data-lucide="heart" class="w-5 h-5"></i> Wishlist (2)
                    </a>
                </div>
            </div>
        </div>
    </template>

    <!-- Search Overlay -->
    <template x-teleport="body">
        <div x-show="searchOpen" class="fixed inset-0 z-[100]">
            <div x-show="searchOpen" x-transition.opacity class="absolute inset-0 bg-black/90 backdrop-blur-md" @click="searchOpen = false"></div>
            <div x-show="searchOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-8"
                 class="absolute top-0 inset-x-0 bg-[var(--surface)] border-b border-[var(--border)] p-6 md:p-12">
                 
                 <div class="max-w-4xl mx-auto relative">
                     <button @click="searchOpen = false" class="absolute right-0 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-white transition-colors">
                         <i data-lucide="x"></i>
                     </button>
                     <input type="text" placeholder="Search products, categories..." class="w-full bg-transparent text-3xl font-['Manrope'] text-white border-b-2 border-[var(--border)] focus:border-[var(--gold)] outline-none py-4 pr-12 transition-colors">
                 </div>
            </div>
        </div>
    </template>

    <!-- Cart Mini-Drawer -->
    <template x-teleport="body">
        <div x-show="cartOpen" class="fixed inset-0 z-[100]">
            <div x-show="cartOpen" x-transition.opacity class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="cartOpen = false"></div>
            <div x-show="cartOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="absolute inset-y-0 right-0 w-full sm:w-[400px] bg-[var(--surface)] border-l border-[var(--border)] flex flex-col shadow-2xl">
                 
                 <div class="p-6 border-b border-[var(--border)] flex items-center justify-between">
                     <h2 class="font-['Manrope'] text-xl font-bold">Your Cart</h2>
                     <button @click="cartOpen = false" class="text-[var(--text-muted)] hover:text-white transition-colors">
                         <i data-lucide="x"></i>
                     </button>
                 </div>

                 <div class="flex-grow p-6 flex items-center justify-center text-[var(--text-muted)]">
                     <div class="text-center">
                         <i data-lucide="shopping-bag" class="w-12 h-12 mx-auto mb-4 opacity-50"></i>
                         <p>Your cart is empty.</p>
                         <button @click="cartOpen = false" class="mt-4 text-[var(--gold)] hover:underline">Continue Shopping</button>
                     </div>
                 </div>

                 <div class="p-6 border-t border-[var(--border)] bg-[var(--card)]">
                     <div class="flex justify-between mb-4">
                         <span class="text-[var(--text-secondary)]">Subtotal</span>
                         <span class="font-bold font-['Manrope'] text-white">₦0.00</span>
                     </div>
                     <p class="text-xs text-[var(--text-muted)] mb-4">Shipping and taxes calculated at checkout.</p>
                     <a href="/checkout" class="block w-full bg-[var(--gold)] text-black text-center font-bold py-4 rounded-xl hover:bg-[#D4AF37] transition-colors">
                         Checkout
                     </a>
                 </div>
            </div>
        </div>
    </template>
</header>
