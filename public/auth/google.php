<?php
/**
 * Standalone Google OAuth redirect — bypasses router to avoid OPcache issues.
 * URL: /public/auth/google.php
 */
require dirname(__DIR__, 2) . '/vendor/autoload.php';
\App\Core\Env::load(dirname(__DIR__, 2) . '/.env');
\App\Core\Session::start();

if (empty($_ENV['GOOGLE_CLIENT_ID']) || empty($_ENV['GOOGLE_CLIENT_SECRET'])) {
    header('Location: ' . ($_ENV['APP_URL'] ?? '') . '/public/login');
    exit;
}

$clientId    = $_ENV['GOOGLE_CLIENT_ID'];
$redirectUri = $_ENV['GOOGLE_REDIRECT_URI'] ?? 'https://marigold.gt.tc/public/auth/google-callback.php';
$scope       = 'openid email profile';

$state = bin2hex(random_bytes(32));
\App\Core\Session::set('google_oauth_state', $state);

$authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
    'client_id'     => $clientId,
    'redirect_uri'  => $redirectUri,
    'response_type' => 'code',
    'scope'         => $scope,
    'state'         => $state,
    'prompt'        => 'select_account',
    'access_type'   => 'online',
]);

header('Location: ' . $authUrl);
exit;
