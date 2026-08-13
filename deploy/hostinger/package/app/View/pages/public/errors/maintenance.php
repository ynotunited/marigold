<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance | Marigold Signature</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,460;0,9..144,520;0,9..144,560;1,9..144,400;1,9..144,520&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="<?= app_url('/assets/css/app.css') ?>" rel="stylesheet">
    <link href="<?= app_url('/assets/css/marigold.css') ?>" rel="stylesheet">
    <style>
        body { background: var(--ivory); color: var(--ink); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .m-logo { font-family: var(--font-display); font-size: 24px; font-weight: 520; letter-spacing: -0.01em; margin-bottom: 34px; }
        .m-logo em { font-style: italic; }
        .m-spinner { width: 46px; height: 46px; border-radius: 50%; border: 3px solid var(--line); border-top-color: var(--gold); animation: mspin 0.9s linear infinite; margin: 0 auto; }
        @keyframes mspin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="antialiased">
    <div class="max-w-[600px] w-full text-center px-6">
        <div class="m-logo">Marigold <em style="color: var(--gold-deep);">Signature</em></div>
        <span class="eyebrow center" style="justify-content: center;">Maintenance</span>
        <h1 class="display h2" style="margin: 18px 0 16px;">We'll be back shortly</h1>
        <p class="lead" style="font-size: 16px; margin: 0 auto 34px; max-width: 460px;">Marigold Signature is currently undergoing scheduled maintenance to improve your experience. We expect to be back online shortly.</p>

        <div class="m-spinner"></div>

        <p class="text-sm" style="color: var(--muted); margin-top: 44px;">
            If you need immediate assistance for an ongoing corporate order, please contact your account manager directly.
        </p>
    </div>
</body>
</html>
