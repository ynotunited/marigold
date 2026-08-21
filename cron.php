<?php
/**
 * Cron job for timezone-aware scheduled notifications + GDPR hard-delete sweep.
 *
 * Add to crontab (daily at 02:00):
 *   0 2 * * * php /path/to/ms/cron.php
 *
 * The notification scheduler runs every 5 minutes; the GDPR sweep runs once daily.
 */

define('BASE_PATH', dirname(__DIR__));

if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require BASE_PATH . '/vendor/autoload.php';
}

use App\Core\Env;
use App\Core\Logger;

Env::load(BASE_PATH . '/.env');
date_default_timezone_set('UTC');

// ── 1. Process pending scheduled notifications ──
try {
    $sent = \App\Service\NotificationScheduler::processPending();
    Logger::info("Cron: processed {$sent} scheduled notifications", 'cron');
} catch (\Throwable $e) {
    Logger::error("Cron notification sweep failed: {$e->getMessage()}", 'cron');
}

// ── 2. GDPR hard-delete sweep (daily) ──
// Only run if not already run today (idempotency via file lock)
$sweepLock = sys_get_temp_dir() . '/ms_gdpr_sweep_' . date('Y-m-d');
if (!file_exists($sweepLock)) {
    try {
        $expired = \App\Service\DeletionService::findExpired();
        if ($expired) {
            $deleted = 0;
            foreach ($expired as $user) {
                try {
                    \App\Service\DeletionService::hardDelete((int)$user['id'], 'system');
                    $deleted++;
                    Logger::info("Cron GDPR: hard-deleted user #{$user['id']} ({$user['email']})", 'gdpr');
                } catch (\Throwable $e) {
                    Logger::error("Cron GDPR: failed to delete user #{$user['id']}: {$e->getMessage()}", 'gdpr');
                }
            }
            Logger::info("Cron GDPR sweep complete: {$deleted}/" . count($expired) . " users hard-deleted", 'gdpr');
        }
        @file_put_contents($sweepLock, date('c'));
    } catch (\Throwable $e) {
        Logger::error("Cron GDPR sweep failed: {$e->getMessage()}", 'gdpr');
    }
}
