<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?? 'Marigold Signature' ?></title>
    
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
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest" defer></script>
    
    <!-- Custom Animations JS -->
    <script src="/assets/js/animations.js" defer></script>
</head>
<body class="bg-[var(--bg-primary)] text-[var(--text-primary)] min-h-screen flex flex-col antialiased">
    
    <main class="flex-grow flex items-center justify-center p-4 sm:p-8">
        <div class="w-full max-w-md w-full">
            
            <div class="text-center mb-8">
                <!-- Placeholder for Logo -->
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[var(--surface)] border border-[var(--gold)] mb-4 shadow-lg shadow-[var(--gold)]/20">
                    <span class="text-2xl font-bold font-['Manrope'] text-[var(--gold)]">M</span>
                </div>
                <h1 class="text-3xl font-bold font-['Manrope'] tracking-tight">Marigold Signature</h1>
                <p class="text-[var(--text-secondary)] mt-2">Premium Corporate Merchandise</p>
            </div>

            <!-- Content injected here -->
            <?= $content ?? '' ?>
            
        </div>
    </main>

    <!-- Global Toast Component -->
    <?php \App\Core\View::render('components/toast') ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            <?php if (isset($_GET['expired']) && $_GET['expired'] === '1'): ?>
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Session expired due to inactivity. Please login again.', type: 'error' }}));
            <?php endif; ?>
            
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
