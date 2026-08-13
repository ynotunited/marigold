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
    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $currentUrl = strtok($currentUrl, '?'); // Remove query params for canonical
    $ogImage = $og_image ?? $__asset('/ms-logo.png');
    $ogImageFull = strpos($ogImage, 'http') === 0 ? $ogImage : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$ogImage";
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
      "url": "<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" ?>",
      "logo": "<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . $__asset('/ms-logo.png') ?>"
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
    </script>
    <script src="<?= $__asset('/assets/js/data.js') ?>"></script>
    <script src="<?= $__asset('/assets/js/app.js') ?>"></script>
    <script src="<?= $__asset('/assets/js/site.js') ?>"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            
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
