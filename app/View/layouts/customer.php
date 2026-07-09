<?php
// app/View/layouts/customer.php
?>
<?php $csrfToken = \App\Core\CSRF::field(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?? 'Customer Portal | Marigold Signature' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/ms-logo-icon.png">
    <link rel="apple-touch-icon" href="/ms-logo-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Compiled) -->
    <link href="/assets/css/app.css" rel="stylesheet">
    
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest" defer></script>
</head>
<body class="bg-[#0a0a0a] text-[var(--text-primary)] min-h-screen flex flex-col antialiased lg:flex-row pb-20 lg:pb-0">
    
    <!-- Desktop Sidebar Navigation -->
    <aside class="hidden lg:flex flex-col w-[280px] h-screen fixed left-0 top-0 border-r border-[var(--border)] bg-[#111] z-40 pt-8 pb-6 px-6">
        <a href="/" class="mb-12 flex items-center justify-center">
            <span class="font-manrope text-2xl font-bold tracking-wider uppercase text-white">Marigold<span class="text-[var(--gold)]">.</span></span>
        </a>

        <nav class="flex-grow space-y-1">
            <a href="/account/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-[8px] text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/account/dashboard') !== false ? 'bg-[var(--surface)] text-white border border-[var(--gold)]/30' : '' ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5 <?= strpos($_SERVER['REQUEST_URI'], '/account/dashboard') !== false ? 'text-[var(--gold)]' : '' ?>"></i> Dashboard
            </a>
            <a href="/account/orders" class="flex items-center gap-3 px-4 py-3 rounded-[8px] text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/orders') !== false ? 'bg-[var(--surface)] text-white border border-[var(--gold)]/30' : '' ?>">
                <i data-lucide="package" class="w-5 h-5 <?= strpos($_SERVER['REQUEST_URI'], '/orders') !== false ? 'text-[var(--gold)]' : '' ?>"></i> Orders
            </a>
            <a href="/account/quotes" class="flex items-center gap-3 px-4 py-3 rounded-[8px] text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/quotes') !== false ? 'bg-[var(--surface)] text-white border border-[var(--gold)]/30' : '' ?>">
                <i data-lucide="file-text" class="w-5 h-5 <?= strpos($_SERVER['REQUEST_URI'], '/quotes') !== false ? 'text-[var(--gold)]' : '' ?>"></i> Quotes
            </a>
            <a href="/account/wishlist" class="flex items-center gap-3 px-4 py-3 rounded-[8px] text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/wishlist') !== false ? 'bg-[var(--surface)] text-white border border-[var(--gold)]/30' : '' ?>">
                <i data-lucide="heart" class="w-5 h-5 <?= strpos($_SERVER['REQUEST_URI'], '/wishlist') !== false ? 'text-[var(--gold)]' : '' ?>"></i> Wishlist
            </a>
            
            <div class="pt-4 pb-1">
                <p class="text-[9px] uppercase tracking-[0.15em] text-[var(--text-muted)] px-4 font-bold">Account</p>
            </div>
            
            <a href="/account/addresses" class="flex items-center gap-3 px-4 py-3 rounded-[8px] text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/addresses') !== false ? 'bg-[var(--surface)] text-white border border-[var(--gold)]/30' : '' ?>">
                <i data-lucide="map-pin" class="w-5 h-5 <?= strpos($_SERVER['REQUEST_URI'], '/addresses') !== false ? 'text-[var(--gold)]' : '' ?>"></i> Address Book
            </a>
            <a href="/account/downloads" class="flex items-center gap-3 px-4 py-3 rounded-[8px] text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/downloads') !== false ? 'bg-[var(--surface)] text-white border border-[var(--gold)]/30' : '' ?>">
                <i data-lucide="download" class="w-5 h-5 <?= strpos($_SERVER['REQUEST_URI'], '/downloads') !== false ? 'text-[var(--gold)]' : '' ?>"></i> Downloads
            </a>
            <a href="/account/notifications" class="flex items-center justify-between gap-3 px-4 py-3 rounded-[8px] text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/notifications') !== false ? 'bg-[var(--surface)] text-white border border-[var(--gold)]/30' : '' ?>">
                <span class="flex items-center gap-3">
                    <i data-lucide="bell" class="w-5 h-5 <?= strpos($_SERVER['REQUEST_URI'], '/notifications') !== false ? 'text-[var(--gold)]' : '' ?>"></i> Notifications
                </span>
                <span class="text-[10px] font-bold bg-[var(--gold)] text-[#111] rounded-full w-5 h-5 flex items-center justify-center">2</span>
            </a>
        </nav>

        <div class="mt-auto pt-6 border-t border-[var(--border)]">
            <a href="/account/settings" class="flex items-center gap-3 px-4 py-3 rounded-[8px] text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white transition-colors mb-2">
                <i data-lucide="settings" class="w-5 h-5"></i> Account Settings
            </a>
            <form action="/logout" method="POST">
                <?= $csrfToken ?>
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-[8px] text-[var(--danger)] hover:bg-[var(--danger)]/10 transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Top Bar -->
    <header class="lg:hidden flex items-center justify-between h-[72px] px-6 border-b border-[var(--border)] bg-[#111] sticky top-0 z-40">
        <a href="/">
            <span class="font-manrope text-xl font-bold tracking-wider uppercase text-white">Marigold<span class="text-[var(--gold)]">.</span></span>
        </a>
        <div class="flex items-center gap-4">
            <button class="text-[var(--text-primary)] relative">
                <i data-lucide="bell" class="w-6 h-6"></i>
                <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-[var(--gold)]"></span>
            </button>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow lg:ml-[280px] p-6 lg:p-10 relative max-w-[1400px]">
        <?= $content ?? '' ?>
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="lg:hidden fixed bottom-0 left-0 w-full h-[72px] bg-[#111] border-t border-[var(--border)] z-50 flex items-center justify-around px-2 pb-safe">
        <a href="/account/dashboard" class="flex flex-col items-center justify-center w-full h-full text-[var(--gold)]">
            <i data-lucide="layout-dashboard" class="w-6 h-6 mb-1"></i>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="/account/orders" class="flex flex-col items-center justify-center w-full h-full text-[var(--text-secondary)] hover:text-white transition-colors">
            <i data-lucide="package" class="w-6 h-6 mb-1"></i>
            <span class="text-[10px] font-medium">Orders</span>
        </a>
        <a href="/account/quotes" class="flex flex-col items-center justify-center w-full h-full text-[var(--text-secondary)] hover:text-white transition-colors">
            <i data-lucide="file-text" class="w-6 h-6 mb-1"></i>
            <span class="text-[10px] font-medium">Quotes</span>
        </a>
        <a href="/account/settings" class="flex flex-col items-center justify-center w-full h-full text-[var(--text-secondary)] hover:text-white transition-colors">
            <i data-lucide="user" class="w-6 h-6 mb-1"></i>
            <span class="text-[10px] font-medium">Account</span>
        </a>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            
            // Initialize swiper for any carousel elements on mobile
            const swipers = document.querySelectorAll('.mobile-carousel');
            swipers.forEach(el => {
                new Swiper(el, {
                    slidesPerView: 'auto',
                    spaceBetween: 16,
                    freeMode: true,
                });
            });
        });
    </script>
</body>
</html>
