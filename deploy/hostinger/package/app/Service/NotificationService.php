<?php

namespace App\Service;

use App\Core\Service;
use App\Core\Model;
use App\Core\Session;

class NotificationService extends Service
{
    /**
     * Create a notification for a user.
     */
    public static function notify(int $userId, string $type, array $data): void
    {
        $stmt = Model::getDB()->prepare(
            "INSERT INTO notifications (user_id, type, data_json, created_at) VALUES (:u, :t, :d, NOW())"
        );
        $stmt->execute([
            'u' => $userId,
            't' => $type,
            'd' => json_encode($data, JSON_UNESCAPED_UNICODE),
        ]);
    }

    /**
     * Notify the current user (no-op for guests).
     */
    public static function notifyCurrent(string $type, array $data): void
    {
        $userId = Session::get('user_id');
        if ($userId) {
            self::notify((int) $userId, $type, $data);
        }
    }

    /**
     * Resolve the user id for an order customer_id (null when the order
     * belongs to a guest session with no account).
     */
    public static function userIdForOrder(int $customerId): ?int
    {
        $stmt = Model::getDB()->prepare("SELECT user_id FROM customers WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $customerId]);
        $userId = $stmt->fetchColumn();
        return $userId ? (int) $userId : null;
    }

    public static function markAllRead(int $userId): void
    {
        $stmt = Model::getDB()->prepare("UPDATE notifications SET read_at = NOW() WHERE user_id = :u AND read_at IS NULL");
        $stmt->execute(['u' => $userId]);
    }

    public static function markRead(int $notificationId, int $userId): void
    {
        $stmt = Model::getDB()->prepare("UPDATE notifications SET read_at = NOW() WHERE id = :id AND user_id = :u");
        $stmt->execute(['id' => $notificationId, 'u' => $userId]);
    }
}
