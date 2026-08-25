<?php

/**
 * Marigold Signature Commerce Platform
 * 
 * Entry point for the application.
 */

define('APP_START', microtime(true));

// Bust PHP OPcache after deploys — shared hosts often serve stale compiled files.
// This resets once per deploy (checks a sentinel file).
if (function_exists('opcache_reset')) {
    $sentinel = __DIR__ . '/.opcache_bust';
    if (!file_exists($sentinel) || (time() - filemtime($sentinel)) > 3600) {
        @opcache_reset();
        @file_put_contents($sentinel, date('c'));
    }
}

// Resolve the app root: walk up from the entry point until a directory
// containing both app/ and vendor/ is found. Handles the project layout
// (public/ -> project root), a localhost subfolder, and any shared-hosting
// subfolder (e.g. public_html/ms/ with the app code alongside the entry file).
function resolveBasePath(string $start): string
{
    $dir = str_replace('\\', '/', $start);
    while (true) {
        if (is_dir($dir . '/app') && is_dir($dir . '/vendor')) {
            return $dir;
        }
        $parent = dirname($dir);
        if ($parent === $dir || $parent === '.' || $parent === '/') {
            return $dir;
        }
        $dir = $parent;
    }
}
define('BASE_PATH', resolveBasePath(__DIR__));

// Autoloader will go here
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require BASE_PATH . '/vendor/autoload.php';
}

use App\Core\Env;
use App\Core\Session;
use App\Core\ExceptionHandler;
use App\Core\Router;
use App\Service\RateLimiter;

function adminRoute($handler, array $roles = ['super-admin', 'admin']): callable
{
    return function (...$params) use ($handler, $roles) {
        \App\Middleware\AuthMiddleware::handle();
        \App\Middleware\RoleMiddleware::handle($roles);

        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $controllerObject = new $controller();
            return call_user_func_array([$controllerObject, $method], $params);
        }

        return call_user_func_array($handler, $params);
    };
}

function customerRoute($handler): callable
{
    return function (...$params) use ($handler) {
        \App\Middleware\AuthMiddleware::handle();

        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $controllerObject = new $controller();
            return call_user_func_array([$controllerObject, $method], $params);
        }

        return call_user_func_array($handler, $params);
    };
}

// Load Environment variables
Env::load(BASE_PATH . '/.env');

// ── Sentry: capture fatal errors and unhandled exceptions ────────────
$sentryDsn = trim($_ENV['SENTRY_DSN_PHP'] ?? $_ENV['SENTRY_DSN'] ?? '');
if ($sentryDsn !== '' && class_exists(\Sentry\SentrySdk::class)) {
    \Sentry\init([
        'dsn' => $sentryDsn,
        'environment' => $_ENV['APP_ENV'] ?? 'production',
        'traces_sample_rate' => 0.1,
        'send_default_pii' => false,
        'attach_stacktrace' => true,
        'before_send' => function (\Sentry\Event $event) {
            // Strip local file paths in production
            if (($_ENV['APP_ENV'] ?? 'production') === 'production') {
                $event->setMessage(str_replace(BASE_PATH, '', $event->getMessage() ?? ''));
            }
            return $event;
        },
    ]);
}

// Global URL helpers — compute the app base path once so every root-relative
// link/asset works whether the app is served from the domain root (base === '')
// or from a subdirectory such as /ms on shared hosting.
function app_base(): string
{
    static $base = null;
    if ($base === null) {
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        if ($base === '/' || $base === '.') {
            $base = '';
        }
    }
    return $base;
}

function app_url(string $path = ''): string
{
    return app_base() . '/' . ltrim($path, '/');
}

/**
 * Global helper: format a price using the session currency.
 * Usage in views: <?= money_format(15000) ?>
 * Admin views can pass 'NGN' explicitly: <?= money_format(15000, 'NGN') ?>
 */
function money_format(float $amount, ?string $code = null): string
{
    if ($code === null) {
        return \App\Core\Money::formatSession($amount);
    }
    return \App\Core\Money::format($amount, $code);
}

// Check Maintenance Mode
if (file_exists(BASE_PATH . '/.maintenance') || ($_ENV['APP_MAINTENANCE'] ?? 'false') === 'true') {
    http_response_code(503);
    require BASE_PATH . '/app/View/pages/public/errors/maintenance.php';
    exit;
}

// Set error handler
set_exception_handler(['App\Core\ExceptionHandler', 'handle']);
set_error_handler(['App\Core\ExceptionHandler', 'errorHandler']);

// Timezone — set from user preference or APP_TIMEZONE default
date_default_timezone_set(\App\Core\Timezone::forCurrentUser());

// Currency — persist via cookie BEFORE session (avoids "headers already sent" if session_path fails)
if (isset($_GET['currency']) && in_array(strtoupper($_GET['currency']), \App\Core\Money::supportedCodes(), true)) {
    $cur = strtoupper($_GET['currency']);
    setcookie('ms_currency', $cur, [
        'expires'  => time() + 86400 * 365,
        'path'     => '/',
        'secure'   => false,
        'httponly'  => false,
        'samesite' => 'Lax',
    ]);
    $_COOKIE['ms_currency'] = $cur;
}

// Start Session
Session::start();

// Sync currency: cookie → session
if (isset($_GET['currency']) && in_array(strtoupper($_GET['currency']), \App\Core\Money::supportedCodes(), true)) {
    \App\Core\Session::set('currency', strtoupper($_GET['currency']));
} elseif (!\App\Core\Session::get('currency') && isset($_COOKIE['ms_currency'])
    && in_array(strtoupper($_COOKIE['ms_currency']), \App\Core\Money::supportedCodes(), true)) {
    \App\Core\Session::set('currency', strtoupper($_COOKIE['ms_currency']));
}

// HTML pages are never cached by the browser: the storefront embeds the live
// DB-backed catalogue (window.MS_CATALOG) directly in the page, so serving a
// cached copy would hide newly published products. API routes set their own
// (JSON) headers below.
if (stripos($_SERVER['REQUEST_URI'] ?? '', '/api') !== 0 && !headers_sent()) {
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
}

// Initialize Router
$router = new Router();

// Define Auth Routes
$router->get('/login', ['App\Controller\AuthController', 'showLogin']);
$router->post('/login', ['App\Controller\AuthController', 'login']);
$router->get('/register', ['App\Controller\AuthController', 'showRegister']);
$router->post('/register', ['App\Controller\AuthController', 'register']);
$router->get('/verify-email', ['App\Controller\AuthController', 'verifyEmail']);
$router->get('/forgot-password', ['App\Controller\AuthController', 'showForgotPassword']);
$router->post('/forgot-password', ['App\Controller\AuthController', 'forgotPassword']);
$router->get('/reset-password', ['App\Controller\AuthController', 'showResetPassword']);
$router->post('/reset-password', ['App\Controller\AuthController', 'resetPassword']);
$router->post('/logout', ['App\Controller\AuthController', 'logout']);
$router->get('/auth/google', ['App\Controller\AuthController', 'googleLogin']);
$router->get('/auth/google/callback', ['App\Controller\AuthController', 'googleCallback']);

// Public Routes
$router->get('/', ['App\Controller\HomeController', 'index']);
$router->get('/shop', ['App\Controller\ShopController', 'index']);
$router->get('/product/{slug}', ['App\Controller\ProductController', 'show']);
$router->get('/cart', ['App\Controller\CartController', 'index']);
$router->get('/checkout', ['App\Controller\CheckoutController', 'index']);
$router->post('/checkout', ['App\Controller\CheckoutController', 'submit']);
$router->get('/order-confirmation', ['App\Controller\CheckoutController', 'confirmation']);
$router->get('/about', ['App\Controller\PageController', 'about']);
$router->get('/solutions', ['App\Controller\PageController', 'solutions']);
$router->get('/contact', ['App\Controller\PageController', 'contact']);
$router->post('/contact', ['App\Controller\ContactController', 'submit']);

// Newsletter subscriptions from the storefront footer / home / popup
$router->post('/newsletter/subscribe', ['App\Controller\NewsletterController', 'subscribe']);

// Blog Routes
$router->get('/blog', ['App\Controller\BlogController', 'index']);
$router->get('/blog/{slug}', ['App\Controller\BlogController', 'show']);

// FAQ Route
$router->get('/faq', ['App\Controller\FaqController', 'index']);

$router->get('/hero', function () {
    // Hero concept preview — a standalone 3D WebGL experiment. Kept on its own
    // route so the live homepage is never affected.
    try {
        $catalogue = \App\Core\Catalogue::all();
        $conceptProducts = $catalogue['products'] ?? [];
    } catch (\Throwable $e) {
        $conceptProducts = [];
    }
    return \App\Core\View::render('pages/public/hero_concept', [
        'title' => 'Hero Concept | Marigold Signature',
        'concept_products' => $conceptProducts,
    ]);
});

// Static Policy Pages
$router->get('/privacy-policy', ['App\Controller\PageController', 'privacy']);
$router->get('/terms-and-conditions', ['App\Controller\PageController', 'terms']);
$router->get('/shipping-policy', ['App\Controller\PageController', 'shipping']);
$router->get('/return-policy', ['App\Controller\PageController', 'returns']);
$router->get('/data-and-compliance', ['App\Controller\PageController', 'dataCompliance']);
$router->get('/ip-infringement', ['App\Controller\PageController', 'ipInfringement']);

// Events Pages
$router->get('/events', ['App\Controller\PageController', 'events']);
$router->get('/events/corporate-meeting', ['App\Controller\PageController', 'corporateMeeting']);
$router->get('/events/conference', ['App\Controller\PageController', 'conference']);
$router->get('/events/dinner', ['App\Controller\PageController', 'dinner']);

// Category Pages
$router->get('/categories/drinkware', ['App\Controller\PageController', 'categoryDrinkware']);
$router->get('/categories/technology-accessories', ['App\Controller\PageController', 'categoryTechnology']);
$router->get('/categories/bags-travel', ['App\Controller\PageController', 'categoryBags']);
$router->get('/categories/apparels', ['App\Controller\PageController', 'categoryApparels']);
$router->get('/categories/corporate-gifts', ['App\Controller\PageController', 'categoryCorporateGifts']);
$router->get('/categories/souvenirs', ['App\Controller\PageController', 'categorySouvenirs']);
$router->get('/categories/seasonal-gifts', ['App\Controller\PageController', 'categorySeasonalGifts']);

// Sitemap
$router->get('/sitemap.xml', ['App\Controller\SitemapController', 'index']);

// Quote Request System
$router->get('/quote-request', ['App\Controller\QuoteRequestController', 'index']);
$router->post('/quote-request', ['App\Controller\QuoteRequestController', 'submit']);
$router->get('/quotes/new', ['App\Controller\QuoteRequestController', 'index']);
$router->get('/quote-request/success', function() {
    \App\Core\View::renderTemplate('pages/public/quote_success', 'main', [
        'title' => 'Quote Submitted | Marigold Signature',
    ]);
});

// Admin Routes
$router->get('/admin', adminRoute(['App\Controller\Admin\AdminDashboardController', 'index']));
$router->get('/admin/products', adminRoute(['App\Controller\Admin\ProductController', 'index']));
$router->get('/admin/products/create', adminRoute(['App\Controller\Admin\ProductController', 'create']));
$router->get('/admin/products/{id}/edit', adminRoute(['App\Controller\Admin\ProductController', 'edit']));
$router->post('/admin/products', adminRoute(['App\Controller\Admin\ProductController', 'store']));
$router->post('/admin/products/{id}', adminRoute(['App\Controller\Admin\ProductController', 'update']));
$router->put('/admin/products/{id}', adminRoute(['App\Controller\Admin\ProductController', 'update']));
$router->post('/admin/products/{id}/delete', adminRoute(['App\Controller\Admin\ProductController', 'destroy']));

// Catalogue Routes
$router->get('/admin/categories', adminRoute(['App\Controller\Admin\CatalogueController', 'categories']));
$router->post('/admin/categories', adminRoute(['App\Controller\Admin\CatalogueController', 'store']));
$router->post('/admin/categories/{id}/delete', adminRoute(['App\Controller\Admin\CatalogueController', 'destroy']));
$router->post('/admin/categories/{id}', adminRoute(['App\Controller\Admin\CatalogueController', 'update']));
$router->put('/admin/categories/{id}', adminRoute(['App\Controller\Admin\CatalogueController', 'update']));
$router->get('/admin/brands', adminRoute(['App\Controller\Admin\CatalogueController', 'brands']));
$router->post('/admin/brands', adminRoute(['App\Controller\Admin\CatalogueController', 'storeBrand']));
$router->post('/admin/brands/{id}/delete', adminRoute(['App\Controller\Admin\CatalogueController', 'destroyBrand']));
$router->post('/admin/brands/{id}', adminRoute(['App\Controller\Admin\CatalogueController', 'updateBrand']));
$router->put('/admin/brands/{id}', adminRoute(['App\Controller\Admin\CatalogueController', 'updateBrand']));
$router->get('/admin/collections', adminRoute(['App\Controller\Admin\CatalogueController', 'collections']));
$router->get('/admin/solutions', adminRoute(['App\Controller\Admin\CatalogueController', 'solutions']));

// Order Routes
$router->get('/admin/orders', adminRoute(['App\Controller\Admin\AdminOrderController', 'index']));
$router->get('/admin/orders/{id}', adminRoute(['App\Controller\Admin\AdminOrderController', 'show']));

// Quote Routes
$router->get('/admin/quotes', adminRoute(['App\Controller\Admin\AdminQuoteController', 'index']));
$router->get('/admin/quotes/{id}', adminRoute(['App\Controller\Admin\AdminQuoteController', 'show']));

// Customer & Media Routes
$router->get('/admin/customers', adminRoute(['App\Controller\Admin\AdminCustomerController', 'index']));
$router->get('/admin/customers/{id}', adminRoute(['App\Controller\Admin\AdminCustomerController', 'show']));
$router->post('/admin/customers/{id}/account-manager', adminRoute(['App\Controller\Admin\AdminCustomerController', 'updateAccountManager']));
$router->get('/admin/media', adminRoute(['App\Controller\Admin\AdminMediaController', 'index']));

// Blog & CMS Routes
$router->get('/admin/blog', adminRoute(['App\Controller\Admin\AdminBlogController', 'index']));
$router->get('/admin/blog/create', adminRoute(['App\Controller\Admin\AdminBlogController', 'create']));
$router->get('/admin/blog/{id}/edit', adminRoute(['App\Controller\Admin\AdminBlogController', 'edit']));
$router->post('/admin/blog', adminRoute(['App\Controller\Admin\AdminBlogController', 'store']));
$router->post('/admin/blog/{id}', adminRoute(['App\Controller\Admin\AdminBlogController', 'update']));
$router->put('/admin/blog/{id}', adminRoute(['App\Controller\Admin\AdminBlogController', 'update']));
$router->get('/admin/cms', adminRoute(['App\Controller\Admin\AdminCmsController', 'index']));
$router->get('/admin/cms/{id}/builder', adminRoute(['App\Controller\Admin\AdminCmsController', 'builder']));

// Settings & System Routes
$router->get('/admin/navigation', adminRoute(['App\Controller\Admin\AdminNavigationController', 'index']));
$router->get('/admin/testimonials', adminRoute(['App\Controller\Admin\AdminContentController', 'testimonials']));
$router->get('/admin/faqs', adminRoute(['App\Controller\Admin\AdminContentController', 'faqs']));
$router->get('/admin/announcements', adminRoute(['App\Controller\Admin\AdminContentController', 'announcements']));
$router->get('/admin/popups', adminRoute(['App\Controller\Admin\AdminContentController', 'popups']));

// Newsletter Routes
$router->get('/admin/newsletter/subscribers', adminRoute(['App\Controller\Admin\AdminNewsletterController', 'subscribers']));
$router->get('/admin/newsletter/campaign', adminRoute(['App\Controller\Admin\AdminNewsletterController', 'campaign']));

// Contact Inbox Routes
$router->get('/admin/messages', adminRoute(['App\Controller\Admin\AdminMessagesController', 'index']));

// Marketing Routes
$router->get('/admin/marketing/coupons', adminRoute(['App\Controller\Admin\AdminMarketingController', 'coupons']));
$router->get('/admin/marketing/reviews', adminRoute(['App\Controller\Admin\AdminMarketingController', 'reviews']));

// Reports Routes
$router->get('/admin/reports', adminRoute(['App\Controller\Admin\AdminReportController', 'index']));

// System & Security Routes
$router->get('/admin/settings', adminRoute(['App\Controller\Admin\AdminSystemController', 'settings']));
$router->get('/admin/users', adminRoute(['App\Controller\Admin\AdminSystemController', 'users']));
$router->get('/admin/roles', adminRoute(['App\Controller\Admin\AdminSystemController', 'roles']));
$router->get('/admin/audit', adminRoute(['App\Controller\Admin\AdminSystemController', 'audit']));

// GDPR & Data Retention Routes
$router->get('/admin/gdpr', adminRoute(['App\Controller\Admin\AdminGdprController', 'index']));
$router->get('/admin/gdpr/export/{id}', adminRoute(['App\Controller\Admin\AdminGdprController', 'export']));
$router->get('/admin/gdpr/export/{id}/json', adminRoute(['App\Controller\Admin\AdminGdprController', 'exportJson']));
$router->post('/admin/gdpr/force-delete/{id}', adminRoute(['App\Controller\Admin\AdminGdprController', 'forceDelete']));
$router->post('/admin/gdpr/restore/{id}', adminRoute(['App\Controller\Admin\AdminGdprController', 'restore']));

// Email Previews
$router->get('/admin/email-previews', adminRoute(['App\Controller\Admin\EmailPreviewController', 'index']));
$router->get('/admin/email-previews/{template}', adminRoute(['App\Controller\Admin\EmailPreviewController', 'preview']));

// Invoice Routes (Admin)
$router->get('/admin/invoices', adminRoute(['App\Controller\Admin\InvoiceController', 'index']));
$router->get('/admin/invoices/create', adminRoute(['App\Controller\Admin\InvoiceController', 'create']));
$router->post('/admin/invoices', adminRoute(['App\Controller\Admin\InvoiceController', 'store']));
$router->get('/admin/invoices/{id}', adminRoute(['App\Controller\Admin\InvoiceController', 'show']));
$router->post('/admin/invoices/{id}', adminRoute(['App\Controller\Admin\InvoiceController', 'update']));
$router->post('/admin/invoices/{id}/items', adminRoute(['App\Controller\Admin\InvoiceController', 'addItem']));
$router->post('/admin/invoices/{id}/items/{itemId}/remove', adminRoute(['App\Controller\Admin\InvoiceController', 'removeItem']));
$router->post('/admin/invoices/{id}/send', adminRoute(['App\Controller\Admin\InvoiceController', 'send']));
$router->post('/admin/invoices/{id}/cancel', adminRoute(['App\Controller\Admin\InvoiceController', 'cancel']));
$router->post('/admin/invoices/{id}/delete', adminRoute(['App\Controller\Admin\InvoiceController', 'destroy']));

// Public Invoice Routes
$router->get('/invoice/{token}', ['App\Controller\InvoicePublicController', 'show']);
$router->post('/invoice/{token}/pay', ['App\Controller\InvoicePublicController', 'pay']);
$router->get('/invoice/{token}/callback', ['App\Controller\InvoicePublicController', 'callback']);

// Storefront Routes
$router->get('/search', ['App\Controller\Storefront\SearchController', 'index']);
$router->get('/api/search', ['App\Controller\Storefront\SearchController', 'ajaxSearch']);

// Catalogue API - full product/category payload for dynamic storefronts
$router->get('/api/catalogue', function () {
    header('Content-Type: application/json');
    echo json_encode(\App\Core\Catalogue::all());
    exit;
});

// Payment Integrity API
// Browser-initiated writes require an `Idempotency-Key` (UUID) header and an
// `X-CSRF-Token` header. The webhook endpoint is signed by the gateway (HMAC).
$router->post('/api/payments', ['App\Controller\Api\PaymentApiController', 'createIntent']);
$router->post('/api/payments/{id}/capture', ['App\Controller\Api\PaymentApiController', 'capture']);
$router->post('/api/payments/{id}/refund', ['App\Controller\Api\PaymentApiController', 'refund']);
$router->get('/api/payments/{id}/events', adminRoute(['App\Controller\Api\PaymentApiController', 'events']));
$router->post('/api/webhooks/paystack', ['App\Controller\Api\WebhookController', 'handle']);
$router->post('/api/webhooks/flutterwave', ['App\Controller\Api\WebhookController', 'handleFlutterwave']);
$router->post('/api/webhooks/shipbubble', ['App\Controller\Api\ShipbubbleApiController', 'webhook']);

// ShipBubble logistics — live courier delivery rates for the checkout
$router->post('/api/shipping/rates', ['App\Controller\Api\ShipbubbleApiController', 'rates']);

// Customer Portal Routes
$router->get('/account/dashboard', customerRoute(['App\Controller\Customer\DashboardController', 'index']));
$router->get('/account/orders', customerRoute(['App\Controller\Customer\OrderController', 'index']));
$router->get('/account/orders/{id}', customerRoute(['App\Controller\Customer\OrderController', 'show']));
$router->get('/account/quotes', customerRoute(['App\Controller\Customer\QuoteController', 'index']));
$router->get('/account/quotes/{id}', customerRoute(['App\Controller\Customer\QuoteController', 'show']));
$router->get('/account/wishlist', customerRoute(['App\Controller\Customer\WishlistController', 'index']));
$router->get('/account/addresses', customerRoute(['App\Controller\Customer\AddressController', 'index']));
$router->get('/account/downloads', customerRoute(['App\Controller\Customer\DownloadController', 'index']));
$router->get('/account/notifications', customerRoute(['App\Controller\Customer\NotificationController', 'index']));
$router->post('/account/notifications/read-all', customerRoute(['App\Controller\Customer\NotificationController', 'markAllRead']));
$router->get('/account/settings', customerRoute(['App\Controller\Customer\SettingsController', 'index']));

// Account Deletion & GDPR (customer self-serve)
$router->get('/account/delete', customerRoute(['App\Controller\Customer\AccountDeletionController', 'index']));
$router->post('/account/delete/request', customerRoute(['App\Controller\Customer\AccountDeletionController', 'request']));
$router->post('/account/delete/cancel', customerRoute(['App\Controller\Customer\AccountDeletionController', 'cancel']));
$router->get('/account/delete/export', customerRoute(['App\Controller\Customer\AccountDeletionController', 'exportData']));
$router->get('/account/delete/view', customerRoute(['App\Controller\Customer\AccountDeletionController', 'viewData']));

// Dispatch
$url = $_SERVER['REQUEST_URI'] ?? '/';
// Strip the app's base path so routing works in a subdirectory (e.g. /ms)
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($basePath !== '' && $basePath !== '/') {
    $path = parse_url($url, PHP_URL_PATH) ?: '/';
    if (stripos($path, $basePath) === 0) {
        $url = substr($path, strlen($basePath)) ?: '/';
    }
}

// Global rate limit for all /api/* endpoints (per-IP backstop against
// scraping / abusive bots). Individual endpoints may impose stricter limits.
if (stripos($url, '/api') === 0) {
    $apiKey = 'api_' . hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    if (RateLimiter::tooManyAttempts($apiKey, 120)) {
        http_response_code(429);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Too many requests. Please try again later.']);
        exit;
    }
    RateLimiter::hit($apiKey, 60);
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$router->dispatch($url, $method);
