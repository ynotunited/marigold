<?php
// app/View/layouts/main.php
?>
<!DOCTYPE html>
<html lang="en" style="overflow-x: hidden;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?? 'Marigold Signature | Premium Corporate Merchandise' ?></title>
    <meta name="description" content="<?= $meta_description ?? 'Marigold Signature offers premium corporate merchandise, bespoke gifting solutions, and high-quality branded items for businesses.' ?>">
    
    <?php
    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $currentUrl = strtok($currentUrl, '?'); // Remove query params for canonical
    $ogImage = $og_image ?? '/ms-logo-removebg-preview.png';
    $ogImageFull = strpos($ogImage, 'http') === 0 ? $ogImage : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$ogImage";
    ?>
    <link rel="canonical" href="<?= $canonical_url ?? $currentUrl ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= $title ?? 'Marigold Signature | Premium Corporate Merchandise' ?>">
    <meta property="og:description" content="<?= $meta_description ?? 'Marigold Signature offers premium corporate merchandise and bespoke gifting solutions.' ?>">
    <meta property="og:url" content="<?= $currentUrl ?>">
    <meta property="og:type" content="<?= $og_type ?? 'website' ?>">
    <meta property="og:image" content="<?= $ogImageFull ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $title ?? 'Marigold Signature' ?>">
    <meta name="twitter:description" content="<?= $meta_description ?? 'Premium corporate merchandise and bespoke gifting solutions.' ?>">
    <meta name="twitter:image" content="<?= $ogImageFull ?>">

    <!-- Schema.org Organization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Marigold Signature",
      "url": "<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" ?>",
      "logo": "<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/ms-logo-removebg-preview.png" ?>"
    }
    </script>
    
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
    <script src="/assets/js/animations.js" defer></script>
</head>
<body class="bg-[var(--bg-primary)] text-[var(--text-primary)] min-h-screen flex flex-col antialiased overflow-x-hidden">
    
    <?php \App\Core\View::render('components/header') ?>

    <main class="flex-grow">
        <?= $content ?? '' ?>
    </main>

    <?php \App\Core\View::render('components/footer') ?>
    
    <!-- Mobile Bottom Navigation -->
    <?php \App\Core\View::render('components/mobile_nav') ?>

    <!-- Global Toast Component -->
    <?php \App\Core\View::render('components/toast') ?>
    
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
