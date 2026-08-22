<?php
// app/View/layouts/main.php
$__assetBase = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($__assetBase === '/' || $__assetBase === '.') {
    $__assetBase = '';
}
$__asset = function (string $path) use ($__assetBase): string {
    return $__assetBase . '/' . ltrim($path, '/');
};
?>
<!DOCTYPE html>
<html lang="en" style="overflow-x: clip;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($title ?? 'Marigold Signature | Premium Corporate Merchandise', ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= $meta_description ?? 'Marigold Signature offers premium corporate merchandise, bespoke gifting solutions, and high-quality branded items for businesses.' ?>">
    
    <?php
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $currentUrl = $scheme . '://' . $host . $requestUri;
    $currentUrl = strtok($currentUrl, '?'); // Remove query params for canonical
    $ogImage = $og_image ?? $__asset('/ms-logo.png');
    $ogImageFull = strpos($ogImage, 'http') === 0 ? $ogImage : $scheme . '://' . $host . $ogImage;
    ?>
    <link rel="canonical" href="<?= $canonical_url ?? $currentUrl ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($title ?? 'Marigold Signature | Premium Corporate Merchandise', ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= $meta_description ?? 'Marigold Signature offers premium corporate merchandise and bespoke gifting solutions.' ?>">
    <meta property="og:url" content="<?= $currentUrl ?>">
    <meta property="og:type" content="<?= $og_type ?? 'website' ?>">
    <meta property="og:image" content="<?= $ogImageFull ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($title ?? 'Marigold Signature', ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:description" content="<?= $meta_description ?? 'Premium corporate merchandise and bespoke gifting solutions.' ?>">
    <meta name="twitter:image" content="<?= $ogImageFull ?>">

    <!-- Schema.org Organization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Marigold Signature",
      "url": "<?= $scheme . '://' . $host ?>",
      "logo": "<?= $scheme . '://' . $host . $__asset('/ms-logo.png') ?>"
    }
    </script>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $__asset('/ms-logo.png') ?>">
    <link rel="apple-touch-icon" href="<?= $__asset('/ms-logo.png') ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,460;0,9..144,520;0,9..144,560;1,9..144,400;1,9..144,520&family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Compiled) -->
    <link href="<?= $__asset('/assets/css/app.css') ?>" rel="stylesheet">
    
    <!-- Marigold Signature design chrome (header / footer / cart / modal / toasts) -->
    <link href="<?= $__asset('/assets/css/marigold.css') ?>" rel="stylesheet">
    
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" defer></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Lenis Smooth Scroll -->
    <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.29/bundled/lenis.min.js" defer></script>

    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest" defer></script>
    
    <!-- Custom Animations JS -->
    <script src="<?= $__asset('/assets/js/animations.js') ?>" defer></script>
</head>
<body class="bg-[var(--bg-primary)] text-[var(--text-primary)] min-h-screen flex flex-col antialiased" data-page="<?= htmlspecialchars($page_key ?? '', ENT_QUOTES, 'UTF-8') ?>">
    
    <?php \App\Core\View::render('components/header') ?>

    <!-- Flash Messages -->
    <?php
    $flashes = \App\Core\Session::getFlashes();
    if ($flashes): ?>
    <div class="fixed top-4 right-4 z-[9999] max-w-md space-y-3" id="flash-container">
        <?php foreach ($flashes as $flash): ?>
        <div class="flash-msg flex items-start gap-3 p-4 rounded-2xl shadow-lg border backdrop-blur-sm transition-all duration-300
            <?= $flash['type'] === 'success' ? 'bg-green-500/10 border-green-500/20 text-green-300' : '' ?>
            <?= $flash['type'] === 'error' ? 'bg-red-500/10 border-red-500/20 text-red-300' : '' ?>
            <?= $flash['type'] === 'warning' ? 'bg-yellow-500/10 border-yellow-500/20 text-yellow-300' : '' ?>
            <?= $flash['type'] === 'info' ? 'bg-blue-500/10 border-blue-500/20 text-blue-300' : '' ?>
            <?= !in_array($flash['type'], ['success','error','warning','info']) ? 'bg-[var(--surface)] border-[var(--border)] text-[var(--text-secondary)]' : '' ?>"
            role="alert">
            <div class="shrink-0 mt-0.5">
                <?php if ($flash['type'] === 'success'): ?>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php elseif ($flash['type'] === 'error'): ?>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <?php elseif ($flash['type'] === 'warning'): ?>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <?php else: ?>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php endif; ?>
            </div>
            <div class="flex-grow min-w-0">
                <?php if ($flash['title']): ?><p class="font-semibold text-sm mb-0.5"><?= htmlspecialchars($flash['title']) ?></p><?php endif; ?>
                <p class="text-sm leading-relaxed"><?= htmlspecialchars($flash['message']) ?></p>
            </div>
            <button onclick="this.closest('.flash-msg').remove()" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity" aria-label="Dismiss">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <?php endforeach; ?>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var msgs = document.querySelectorAll('.flash-msg');
        msgs.forEach(function(el, i) {
            setTimeout(function() { el.style.opacity = '0'; el.style.transform = 'translateX(100%)'; setTimeout(function() { el.remove(); }, 300); }, 4000 + i * 500);
        });
    });
    </script>
    <?php endif; ?>

    <main class="flex-grow">
        <?= $content ?? '' ?>
    </main>

    <?php \App\Core\View::render('components/footer') ?>
    
    <!-- Global Toast Component -->
    <?php \App\Core\View::render('components/toast') ?>

    <!-- Cart Drawer / Checkout Modal / Toasts (design chrome) -->
    <?php \App\Core\View::render('components/cart_chrome') ?>

    <!-- Marigold storefront scripts (load order matters: data -> app -> site) -->
    <script>
        // App base URL so root-relative paths work from a subdirectory (e.g. /ms).
        // Mirrors the PHP app_base() helper in public/index.php.
        window.APP_BASE = "<?= htmlspecialchars(app_base(), ENT_QUOTES, 'UTF-8') ?>";
        window.appUrl = function (path) {
            path = String(path || '').replace(/^\/+/, '');
            return (window.APP_BASE || '') + '/' + path;
        };
    </script>
    <?php
        // Live DB-backed catalogue (categories + products). data.js falls back
        // to its static seed whenever this is missing or empty.
        try {
            $__catalogue = \App\Core\Catalogue::all();
        } catch (\Throwable $__e) {
            $__catalogue = [];
        }
    ?>
    <script>
        window.MS_CATALOG = <?= json_encode($__catalogue, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        window.MS_CURRENCY = <?= json_encode(\App\Core\Money::jsContext(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    </script>
    <!-- Currency cookie fallback: if OPcache serves an old layout without
         MS_CURRENCY, or if the server context is stale, read the cookie directly
         so prices always reflect the user's choice. -->
    <script>
        (function() {
            function getCookie(name) {
                var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
                return m ? decodeURIComponent(m[1]) : null;
            }
            var RATES_NGN = {
                NGN: 1, USD: 0.000653, GBP: 0.000485, EUR: 0.000573,
                GHS: 0.00801, ZAR: 0.0118, KES: 0.0838, XAF: 0.361, GMD: 0.0460
            };
            var saved = getCookie('ms_currency');
            if (saved) saved = saved.toUpperCase();
            if (!window.MS_CURRENCY) {
                window.MS_CURRENCY = { base: 'NGN', selected: saved || 'NGN', detected: null, rates: RATES_NGN, currencies: ['NGN','USD','GBP','EUR','GHS','ZAR','KES','XAF','GMD'] };
            } else if (saved && window.MS_CURRENCY.selected !== saved) {
                window.MS_CURRENCY.selected = saved;
            }
        })();
    </script>
    <script>
        (function() {
            try {
                var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                if (tz) document.cookie = 'ms_tz=' + encodeURIComponent(tz) + ';path=/;max-age=31536000;samesite=strict';
            } catch(e) {}
        })();
    </script>
    <script src="<?= $__asset('/assets/js/data.js') ?>"></script>
    <script src="<?= $__asset('/assets/js/app.js') ?>"></script>
    <script src="<?= $__asset('/assets/js/site.js') ?>"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            
            // Currency selector toggle
            var curBtn = document.getElementById('curToggle');
            var curMenu = document.getElementById('curMenu');
            if (curBtn && curMenu) {
                curBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    curMenu.style.display = curMenu.style.display === 'block' ? 'none' : 'block';
                });
                document.addEventListener('click', function() { curMenu.style.display = 'none'; });
            }
            
            // Check for PHP session errors/messages and dispatch toasts
            <?php if ($error = \App\Core\Session::get('error')): ?>
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: '<?= addslashes($error) ?>', type: 'error' }}));
                <?php \App\Core\Session::remove('error'); ?>
            <?php endif; ?>
            
            <?php if ($success = \App\Core\Session::get('success')): ?>
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: '<?= addslashes($success) ?>', type: 'success' }}));
                <?php \App\Core\Session::remove('success'); ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
