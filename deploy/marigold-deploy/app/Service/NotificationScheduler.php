<?php

namespace App\Service;

use App\Core\Model;
use App\Core\Logger;
use App\Core\Timezone;

/**
 * Timezone-aware notification scheduler.
 *
 * All scheduled events are stored in UTC. When sending, the scheduler
 * resolves each recipient's timezone and sends at the correct local time.
 */
class NotificationScheduler
{
    /**
     * Schedule a notification for a user at a specific local time.
     *
     * @param int    $userId    User ID
     * @param string $type      Notification type (email, sms, push)
     * @param string $template  Email template name or message key
     * @param array  $data      Template data
     * @param string $localTime DATETIME in the user's local timezone
     * @param string $tzName    IANA timezone name
     */
    public static function schedule(
        int $userId,
        string $type,
        string $template,
        array $data,
        string $localTime,
        string $tzName
    ): void {
        $utcTime = Timezone::toUtc($localTime, $tzName);

        $db = Model::getDB();
        $db->prepare("
            INSERT INTO scheduled_notifications
                (user_id, type, template, data_json, scheduled_at_utc, status, created_at)
            VALUES
                (:uid, :type, :tpl, :data, :at, 'pending', NOW())
        ")->execute([
            'uid' => $userId,
            'type' => $type,
            'tpl' => $template,
            'data' => json_encode($data, JSON_UNESCAPED_SLASHES),
            'at' => $utcTime,
        ]);

        Logger::info("Scheduled {$type} notification for user #{$userId} at {$utcTime} UTC", 'scheduler');
    }

    /**
     * Process all pending notifications whose scheduled time has passed.
     * Designed to be called by a cron job: `php /path/to/cron.php`.
     */
    public static function processPending(): int
    {
        $db = Model::getDB();
        $now = gmdate('Y-m-d H:i:s');

        $stmt = $db->prepare("
            SELECT id, user_id, type, template, data_json
            FROM scheduled_notifications
            WHERE status = 'pending' AND scheduled_at_utc <= :now
            ORDER BY scheduled_at_utc ASC
            LIMIT 50
        ");
        $stmt->execute(['now' => $now]);
        $pending = $stmt->fetchAll();

        $sent = 0;
        foreach ($pending as $job) {
            try {
                self::send($job);
                $db->prepare("
                    UPDATE scheduled_notifications
                    SET status = 'sent', sent_at = NOW()
                    WHERE id = :id
                ")->execute(['id' => $job['id']]);
                $sent++;
            } catch (\Throwable $e) {
                $db->prepare("
                    UPDATE scheduled_notifications
                    SET status = 'failed', error_message = :err, sent_at = NOW()
                    WHERE id = :id
                ")->execute(['id' => $job['id'], 'err' => $e->getMessage()]);
                Logger::error("Scheduled notification #{$job['id']} failed: {$e->getMessage()}", 'scheduler');
            }
        }

        return $sent;
    }

    /**
     * Send a single notification (email).
     */
    private static function send(array $job): void
    {
        $data = json_decode($job['data_json'], true) ?: [];

        // Resolve user's timezone for date formatting in the template
        $tz = Timezone::forUser((int) $job['user_id']) ?? 'Africa/Lagos';
        $data['user_timezone'] = $tz;
        $data['user_local_date'] = Timezone::now($tz, 'F j, Y');
        $data['user_local_time'] = Timezone::now($tz, 'g:i A');

        if ($job['type'] === 'email') {
            $stmt = Model::getDB()->prepare("SELECT email FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $job['user_id']]);
            $email = $stmt->fetchColumn();

            if ($email) {
                Mailer::sendTemplate(
                    $email,
                    $data['subject'] ?? 'Notification from Marigold Signature',
                    $job['template'],
                    $data
                );
            }
        }
    }

    /**
     * Send a notification now, respecting the user's timezone for template rendering.
     */
    public static function sendNow(int $userId, string $template, array $data, string $subject = ''): void
    {
        $tz = Timezone::forUser($userId) ?? 'Africa/Lagos';
        $data['user_timezone'] = $tz;
        $data['user_local_date'] = Timezone::now($tz, 'F j, Y');
        $data['user_local_time'] = Timezone::now($tz, 'g:i A');
        $data['subject'] = $subject;

        $stmt = Model::getDB()->prepare("SELECT email FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $userId]);
        $email = $stmt->fetchColumn();

        if ($email) {
            Mailer::sendTemplate($email, $subject, $template, $data);
        }
    }

    /**
     * Cancel a pending scheduled notification.
     */
    public static function cancel(int $notificationId): void
    {
        Model::getDB()->prepare("
            UPDATE scheduled_notifications SET status = 'cancelled'
            WHERE id = :id AND status = 'pending'
        ")->execute(['id' => $notificationId]);
    }
}
