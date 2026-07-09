<?php

/**
 * Marigold Signature Commerce Platform
 * 
 * Entry point for the application.
 */

define('APP_START', microtime(true));
define('BASE_PATH', dirname(__DIR__));

// Autoloader will go here
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require BASE_PATH . '/vendor/autoload.php';
}

use App\Core\Env;
use App\Core\Session;
use App\Core\ExceptionHandler;
use App\Core\Router;

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

// Load Environment variables
Env::load(BASE_PATH . '/.env');

// Check Maintenance Mode
if (file_exists(BASE_PATH . '/.maintenance') || ($_ENV['APP_MAINTENANCE'] ?? 'false') === 'true') {
    http_response_code(503);
    require BASE_PATH . '/app/View/pages/public/errors/maintenance.php';
    exit;
}

// Set error handler
set_exception_handler(['App\Core\ExceptionHandler', 'handle']);
set_error_handler(['App\Core\ExceptionHandler', 'errorHandler']);

// Start Session
Session::start();

// Initialize Router
$router = new Router();

// Define Auth Routes
$router->get('/login', ['App\Controller\AuthController', 'showLogin']);
$router->post('/login', ['App\Controller\AuthController', 'login']);
$router->get('/register', ['App\Controller\AuthController', 'showRegister']);
$router->post('/register', ['App\Controller\AuthController', 'register']);
$router->get('/forgot-password', ['App\Controller\AuthController', 'showForgotPassword']);
$router->post('/forgot-password', ['App\Controller\AuthController', 'forgotPassword']);
$router->get('/reset-password', ['App\Controller\AuthController', 'showResetPassword']);
$router->post('/reset-password', ['App\Controller\AuthController', 'resetPassword']);
$router->post('/logout', ['App\Controller\AuthController', 'logout']);

// Public Routes
$router->get('/', ['App\Controller\HomeController', 'index']);
$router->get('/shop', ['App\Controller\ShopController', 'index']);
$router->get('/product/{slug}', ['App\Controller\ProductController', 'show']);
$router->get('/cart', ['App\Controller\CartController', 'index']);
$router->get('/checkout', ['App\Controller\CheckoutController', 'index']);
$router->get('/order-confirmation', ['App\Controller\CheckoutController', 'confirmation']);
$router->get('/about', ['App\Controller\PageController', 'about']);
$router->get('/solutions', ['App\Controller\PageController', 'solutions']);
$router->get('/contact', ['App\Controller\PageController', 'contact']);

// Blog Routes
$router->get('/blog', ['App\Controller\BlogController', 'index']);
$router->get('/blog/{slug}', ['App\Controller\BlogController', 'show']);

// FAQ Route
$router->get('/faq', ['App\Controller\FaqController', 'index']);

// Static Policy Pages
$router->get('/privacy-policy', ['App\Controller\PageController', 'privacy']);
$router->get('/terms-and-conditions', ['App\Controller\PageController', 'terms']);
$router->get('/shipping-policy', ['App\Controller\PageController', 'shipping']);
$router->get('/return-policy', ['App\Controller\PageController', 'returns']);

// Events Pages
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

// Catalogue Routes
$router->get('/admin/categories', adminRoute(['App\Controller\Admin\CatalogueController', 'categories']));
$router->get('/admin/brands', adminRoute(['App\Controller\Admin\CatalogueController', 'brands']));
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

// Email Previews
$router->get('/admin/email-previews', adminRoute(['App\Controller\Admin\EmailPreviewController', 'index']));
$router->get('/admin/email-previews/{template}', adminRoute(['App\Controller\Admin\EmailPreviewController', 'preview']));

// Storefront Routes
$router->get('/search', ['App\Controller\Storefront\SearchController', 'index']);
$router->get('/api/search', ['App\Controller\Storefront\SearchController', 'ajaxSearch']);

// Customer Portal Routes
$router->get('/account/dashboard', ['App\Controller\Customer\DashboardController', 'index']);
$router->get('/account/orders', ['App\Controller\Customer\OrderController', 'index']);
$router->get('/account/orders/{id}', ['App\Controller\Customer\OrderController', 'show']);
$router->get('/account/quotes', ['App\Controller\Customer\QuoteController', 'index']);
$router->get('/account/quotes/{id}', ['App\Controller\Customer\QuoteController', 'show']);
$router->get('/account/wishlist', ['App\Controller\Customer\WishlistController', 'index']);
$router->get('/account/addresses', ['App\Controller\Customer\AddressController', 'index']);
$router->get('/account/downloads', ['App\Controller\Customer\DownloadController', 'index']);
$router->get('/account/notifications', ['App\Controller\Customer\NotificationController', 'index']);
$router->get('/account/settings', ['App\Controller\Customer\SettingsController', 'index']);

// Dispatch
$url = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$router->dispatch($url, $method);
