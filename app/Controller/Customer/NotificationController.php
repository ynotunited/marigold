<?php

namespace App\Controller\Customer;

use App\Core\Controller;
use App\Core\View;
use App\Core\Model;
use App\Core\Session;

class NotificationController extends Controller
{
    public function index()
    {
        $stmt = Model::getDB()->prepare("
            SELECT id, type, data_json, read_at, created_at
            FROM notifications
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT 50
        ");
        $stmt->execute(['user_id' => Session::get('user_id')]);

        $notifications = array_map(function ($row) {
            $data = json_decode($row['data_json'] ?? '{}', true) ?: [];
            return [
                'id' => $row['id'],
                'type' => $row['type'],
                'icon' => $data['icon'] ?? 'bell',
                'title' => $data['title'] ?? 'Notification',
                'message' => $data['message'] ?? '',
                'time' => $row['created_at'],
                'is_read' => !empty($row['read_at']),
                'link' => $data['link'] ?? '#',
            ];
        }, $stmt->fetchAll());

        return View::renderTemplate('pages/customer/notifications', 'customer', [
            'title' => 'Notifications | Marigold Signature',
            'notifications' => $notifications,
            'unread_count' => count(array_filter($notifications, fn($n) => !$n['is_read']))
        ]);
    }
}
