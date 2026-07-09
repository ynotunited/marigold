<?php
// app/View/layouts/admin.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin | Marigold Signature' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/ms-logo-icon.png">
    <link rel="apple-touch-icon" href="/ms-logo-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link href="/assets/css/app.css" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Scrollbar hide utility */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Sidebar transition */
        .sidebar-collapsed { width: 72px; }
        .sidebar-collapsed .nav-label { display: none; }
        .sidebar-collapsed .nav-section-label { display: none; }
        .sidebar-collapsed .logo-text { display: none; }
        .sidebar-collapsed .logo-dot { display: none; }
        .sidebar-collapsed .sidebar-footer-text { display: none; }
    </style>
</head>
<body class="bg-[#080808] text-[var(--text-primary)] antialiased" x-data="adminLayout()">

    <!-- ===================== SIDEBAR ===================== -->
    <aside :class="sidebarOpen ? 'w-[260px]' : 'w-[72px] sidebar-collapsed'"
           class="fixed left-0 top-0 h-screen bg-[#0f0f0f] border-r border-[var(--border)] z-40 flex flex-col transition-all duration-300 overflow-hidden hidden lg:flex">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-5 h-[64px] border-b border-[var(--border)] shrink-0">
            <img src="/ms-logo-icon.png" alt="Marigold Signature" class="w-8 h-8 object-contain shrink-0">
            <span class="logo-text font-manrope font-bold text-lg tracking-wider uppercase whitespace-nowrap">Marigold<span class="logo-dot text-[var(--gold)]">.</span></span>
        </div>

        <!-- Collapse Toggle -->
        <button @click="sidebarOpen = !sidebarOpen" class="absolute top-[18px] -right-3 w-6 h-6 rounded-full bg-[var(--border)] border border-[var(--surface)] flex items-center justify-center text-[var(--text-secondary)] hover:text-white transition-colors z-50">
            <i :data-lucide="sidebarOpen ? 'chevron-left' : 'chevron-right'" class="w-3 h-3"></i>
        </button>

        <!-- Navigation -->
        <nav class="flex-grow overflow-y-auto py-4 space-y-0.5 px-2 hide-scrollbar">

            <?php
            $currentUri = $_SERVER['REQUEST_URI'];
            $navItems = [
                ['section' => null, 'href' => '/admin', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
                ['section' => 'Catalogue', 'href' => '/admin/products', 'icon' => 'box', 'label' => 'Products'],
                ['section' => null, 'href' => '/admin/categories', 'icon' => 'folder-tree', 'label' => 'Categories'],
                ['section' => null, 'href' => '/admin/brands', 'icon' => 'award', 'label' => 'Brands'],
                ['section' => null, 'href' => '/admin/collections', 'icon' => 'layers', 'label' => 'Collections'],
                ['section' => null, 'href' => '/admin/solutions', 'icon' => 'briefcase', 'label' => 'Corp. Solutions'],
                ['section' => 'Commerce', 'href' => '/admin/orders', 'icon' => 'package', 'label' => 'Orders'],
                ['section' => null, 'href' => '/admin/quotes', 'icon' => 'file-text', 'label' => 'Quotes'],
                ['section' => null, 'href' => '/admin/customers', 'icon' => 'users', 'label' => 'Customers'],
                ['section' => 'Content', 'href' => '/admin/blog', 'icon' => 'pen-tool', 'label' => 'Blog'],
                ['section' => null, 'href' => '/admin/media', 'icon' => 'image', 'label' => 'Media'],
                ['section' => 'Insights', 'href' => '/admin/reports', 'icon' => 'bar-chart-2', 'label' => 'Reports'],
                ['section' => 'System', 'href' => '/admin/settings', 'icon' => 'settings', 'label' => 'Settings'],
                ['section' => null, 'href' => '/admin/users', 'icon' => 'shield', 'label' => 'Administration'],
            ];
            $lastSection = null;
            foreach ($navItems as $item):
                $isActive = $item['href'] === '/admin'
                    ? ($currentUri === '/admin' || $currentUri === '/admin/')
                    : strpos($currentUri, $item['href']) === 0;
                if ($item['section'] && $item['section'] !== $lastSection):
                    $lastSection = $item['section'];
            ?>
                <div class="nav-section-label pt-4 pb-1 px-3">
                    <p class="text-[9px] uppercase tracking-[0.15em] text-[var(--text-muted)] font-bold"><?= $item['section'] ?></p>
                </div>
            <?php endif; ?>
            <a href="<?= $item['href'] ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-[8px] transition-colors group relative
               <?= $isActive ? 'bg-[var(--gold)]/10 text-[var(--gold)]' : 'text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white' ?>">
                <i data-lucide="<?= $item['icon'] ?>" class="w-5 h-5 shrink-0"></i>
                <span class="nav-label text-sm font-medium whitespace-nowrap"><?= $item['label'] ?></span>
                <?php if ($isActive): ?>
                    <span class="absolute right-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-[var(--gold)] rounded-l-full"></span>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </nav>

        <!-- Footer -->
        <div class="border-t border-[var(--border)] p-3 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[var(--gold)]/20 border border-[var(--gold)]/40 flex items-center justify-center text-[var(--gold)] font-bold text-sm shrink-0">SA</div>
                <div class="sidebar-footer-text min-w-0">
                    <p class="text-sm font-medium truncate">Super Admin</p>
                    <p class="text-xs text-[var(--text-muted)] truncate">admin@marigold.ng</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- ===================== MOBILE OVERLAY ===================== -->
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false"
         class="lg:hidden fixed inset-0 bg-black/60 z-30 backdrop-blur-sm" style="display:none"></div>

    <!-- Mobile Sidebar (same content, slides in from left) -->
    <aside x-show="mobileMenuOpen"
           x-transition:enter="transition ease-out duration-250"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="lg:hidden fixed left-0 top-0 h-screen w-[280px] bg-[#0f0f0f] border-r border-[var(--border)] z-40 flex flex-col overflow-y-auto"
           style="display:none">
        <div class="flex items-center justify-between px-5 h-[64px] border-b border-[var(--border)] shrink-0">
            <span class="font-manrope font-bold text-lg tracking-wider uppercase">Marigold<span class="text-[var(--gold)]">.</span></span>
            <button @click="mobileMenuOpen = false" class="text-[var(--text-secondary)] hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <nav class="flex-grow py-4 space-y-0.5 px-2">
            <?php
            $lastSection = null;
            foreach ($navItems as $item):
                $isActive = $item['href'] === '/admin'
                    ? ($currentUri === '/admin' || $currentUri === '/admin/')
                    : strpos($currentUri, $item['href']) === 0;
                if ($item['section'] && $item['section'] !== $lastSection):
                    $lastSection = $item['section'];
            ?>
                <div class="pt-4 pb-1 px-3">
                    <p class="text-[9px] uppercase tracking-[0.15em] text-[var(--text-muted)] font-bold"><?= $item['section'] ?></p>
                </div>
            <?php endif; ?>
            <a href="<?= $item['href'] ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-[8px] transition-colors
               <?= $isActive ? 'bg-[var(--gold)]/10 text-[var(--gold)]' : 'text-[var(--text-secondary)] hover:bg-[var(--surface)] hover:text-white' ?>">
                <i data-lucide="<?= $item['icon'] ?>" class="w-5 h-5 shrink-0"></i>
                <span class="text-sm font-medium"><?= $item['label'] ?></span>
            </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <!-- ===================== TOPBAR ===================== -->
    <header :class="sidebarOpen ? 'lg:left-[260px]' : 'lg:left-[72px]'"
            class="fixed top-0 right-0 left-0 h-[64px] bg-[#0f0f0f]/80 backdrop-blur-md border-b border-[var(--border)] z-30 flex items-center px-4 gap-4 transition-all duration-300">

        <!-- Mobile Menu Toggle -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-[var(--text-secondary)] hover:text-white transition-colors shrink-0">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>

        <!-- Global Search -->
        <div class="relative flex-grow max-w-[480px]">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[var(--text-muted)]"></i>
            <input type="text" placeholder="Search products, orders, customers…"
                   class="w-full bg-[var(--surface)] border border-[var(--border)] rounded-[10px] pl-10 pr-4 h-10 text-sm text-white placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--gold)]/50 transition-colors">
        </div>

        <!-- Spacer -->
        <div class="flex-grow"></div>

        <!-- Quick Actions -->
        <div class="hidden md:flex items-center gap-2">
            <a href="/admin/products/create" class="btn btn-primary h-9 px-4 text-sm">
                <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> Add Product
            </a>
        </div>

        <!-- Notifications -->
        <button class="relative w-9 h-9 rounded-[8px] bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)]/50 transition-colors">
            <i data-lucide="bell" class="w-4 h-4"></i>
            <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-[var(--gold)]"></span>
        </button>

        <!-- Avatar + Role -->
        <div class="flex items-center gap-2.5 cursor-pointer group">
            <div class="w-8 h-8 rounded-full bg-[var(--gold)]/20 border border-[var(--gold)]/40 flex items-center justify-center text-[var(--gold)] font-bold text-xs shrink-0">SA</div>
            <div class="hidden sm:block text-right">
                <p class="text-sm font-medium leading-none">Super Admin</p>
                <span class="text-[10px] px-1.5 py-0.5 rounded bg-[var(--gold)]/20 text-[var(--gold)] font-bold uppercase tracking-wider">Admin</span>
            </div>
        </div>
    </header>

    <!-- ===================== MAIN CONTENT ===================== -->
    <main :class="sidebarOpen ? 'lg:ml-[260px]' : 'lg:ml-[72px]'"
          class="pt-[64px] min-h-screen transition-all duration-300">
        <div class="p-6 lg:p-8">
            <?= $content ?? '' ?>
        </div>
    </main>

    <script>
        function adminLayout() {
            return {
                sidebarOpen: window.innerWidth >= 1280,
                mobileMenuOpen: false,
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
