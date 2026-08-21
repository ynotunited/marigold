<?php
/**
 * Cron job for timezone-aware scheduled notifications.
 *
 * Add to crontab (every 5 minutes):
 *   0,5,10,15,20,25,30,35,40,45,50,55 * * * * php /path/to/ms/cron.php
 *
 * Processes all pending notifications whose scheduled UTC time has passed.
 * Each notification is sent in the recipient's stored timezone.
 */

define('BASE_PATH', dirname(__DIR__));

if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require BASE_PATH . '/vendor/autoload.php';
}

use App\Core\Env;
use App\Core\Logger;

Env::load(BASE_PATH . '/.env');
date_default_timezone_set('UTC');

try {
    $sent = \App\Service\NotificationScheduler::processPending();
    Logger::info("Cron: processed {$sent} scheduled notifications", 'cron');
} catch (\Throwable $e) {
    Logger::error("Cron failed: {$e->getMessage()}", 'cron');
}
