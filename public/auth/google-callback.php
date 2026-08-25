<?php
/**
 * Standalone Google OAuth callback — bypasses router to avoid OPcache issues.
 * URL: /public/auth/google-callback.php
 */
require dirname(__DIR__, 2) . '/vendor/autoload.php';
\App\Core\Env::load(dirname(__DIR__, 2) . '/.env');
\App\Core\Session::start();

use App\Core\Session;
use App\Service\GoogleOAuthService;
use App\Service\AuthService;
use App\Service\AuditService;
use App\Service\RateLimiter;

$loginUrl = ($_ENV['APP_URL'] ?? '') . '/public/login';

// Validate state
$state = $_GET['state'] ?? '';
$stored = Session::get('google_oauth_state');
Session::remove('google_oauth_state');
if (!$state || !$stored || !hash_equals($stored, $state)) {
    Session::error('Invalid sign-in state. Please try again.');
    header('Location: ' . $loginUrl);
    exit;
}

// Check for error
if (!empty($_GET['error'])) {
    Session::error('Google sign-in was cancelled. Please try again.');
    header('Location: ' . $loginUrl);
    exit;
}

$code = $_GET['code'] ?? '';
if (empty($code)) {
    Session::error('Missing authorization code.');
    header('Location: ' . $loginUrl);
    exit;
}

// Rate limit
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$rateKey = 'ggl_cb_' . hash('sha256', $ip);
if (RateLimiter::tooManyAttempts($rateKey, 10)) {
    Session::error('Too many attempts. Please try again later.');
    header('Location: ' . $loginUrl);
    exit;
}

// Exchange code for profile
$profile = GoogleOAuthService::getUserProfile($code);
if (!$profile) {
    RateLimiter::hit($rateKey, 3600);
    Session::error('Could not retrieve your Google profile. Please try again.');
    header('Location: ' . $loginUrl);
    exit;
}

// Find or create user
try {
    $result = GoogleOAuthService::findOrCreateUser($profile);
} catch (\Throwable $e) {
    RateLimiter::hit($rateKey, 3600);
    \App\Core\Logger::error('Google login failed: ' . $e->getMessage(), 'auth');
    Session::error('Sign-in failed. Please try again.');
    header('Location: ' . $loginUrl);
    exit;
}

RateLimiter::clear($rateKey);
$user = $result['user'];
$created = $result['created'];

AuthService::establishSessionById((int)$user['id']);
AuditService::act(
    $created ? 'auth.google_register' : 'auth.google_login',
    'users',
    $user['id'],
    [],
    ['email' => $user['email']]
);

Session::success($created
    ? 'Welcome! Your account has been created via Google.'
    : 'Welcome back, ' . htmlspecialchars($user['first_name'] ?? '') . '!');

$dashboardUrl = ($_ENV['APP_URL'] ?? '') . '/public/account/dashboard';
header('Location: ' . $dashboardUrl);
exit;
