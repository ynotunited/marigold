<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;
use App\Service\AuditService;

class AdminSystemController extends Controller
{
    public function settings()
    {
        $smtp = [
            'host'       => (string) ($_ENV['SMTP_HOST'] ?? ''),
            'port'       => (string) ($_ENV['SMTP_PORT'] ?? ''),
            'encryption' => (string) ($_ENV['SMTP_ENCRYPTION'] ?? 'tls'),
            'username'   => (string) ($_ENV['SMTP_USER'] ?? ''),
            'password'   => (string) ($_ENV['SMTP_PASS'] ?? ($_ENV['SMTP_PASSWORD'] ?? '')),
            'from_name'  => (string) ($_ENV['SMTP_FROM_NAME'] ?? ''),
            'from_email' => (string) ($_ENV['SMTP_FROM_EMAIL'] ?? ''),
        ];

        $shipping = [
            'api_key'           => (string) ($_ENV['SHIPBUBBLE_API_KEY'] ?? ''),
            'webhook_secret'    => (string) ($_ENV['SHIPBUBBLE_WEBHOOK_SECRET'] ?? ''),
            'sender_code'       => (string) ($_ENV['SHIPBUBBLE_SENDER_ADDRESS_CODE'] ?? ''),
            'category_id'       => (string) ($_ENV['SHIPBUBBLE_DEFAULT_CATEGORY_ID'] ?? ''),
            'package_length'    => (string) ($_ENV['SHIPBUBBLE_PACKAGE_LENGTH'] ?? ''),
            'package_width'     => (string) ($_ENV['SHIPBUBBLE_PACKAGE_WIDTH'] ?? ''),
            'package_height'    => (string) ($_ENV['SHIPBUBBLE_PACKAGE_HEIGHT'] ?? ''),
            'sender_address'    => (string) ($_ENV['SHIPBUBBLE_SENDER_ADDRESS'] ?? ''),
        ];

        return View::renderTemplate('pages/admin/system/settings', 'admin', [
            'title' => 'Global Settings | Admin',
            'smtp' => $smtp,
            'shipping' => $shipping,
        ]);
    }

    public function users()
    {
        $db = Model::getDB();

        $rows = $db->query("
            SELECT
                u.id,
                u.first_name,
                u.last_name,
                u.email,
                u.status,
                u.last_login_at,
                r.name AS role_name
            FROM users u
            LEFT JOIN user_roles ur ON ur.user_id = u.id
            LEFT JOIN roles r ON r.id = ur.role_id
            WHERE u.deleted_at IS NULL
            ORDER BY u.created_at DESC
        ")->fetchAll();

        $users = [];
        foreach ($rows as $u) {
            $users[] = [
                'id'         => $u['id'],
                'name'       => trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? '')) ?: $u['email'],
                'email'      => $u['email'],
                'role'       => $u['role_name'] ?: '—',
                'status'     => ucfirst($u['status']),
                'last_login' => $u['last_login_at'] ? date('M j, Y g:i A', strtotime($u['last_login_at'])) : 'Never',
            ];
        }

        return View::renderTemplate('pages/admin/system/users', 'admin', ['title' => 'Admin Users | Admin', 'users' => $users]);
    }

    public function roles()
    {
        $db = Model::getDB();

        $rows = $db->query("
            SELECT r.id, r.name, r.description,
                   (SELECT COUNT(*) FROM user_roles ur WHERE ur.role_id = r.id) AS user_count
            FROM roles r
            ORDER BY r.id ASC
        ")->fetchAll();

        $roles = [];
        foreach ($rows as $r) {
            $roles[] = [
                'id'          => $r['id'],
                'name'        => $r['name'],
                'users'       => (int)$r['user_count'],
                'description' => $r['description'] ?: '',
            ];
        }

        return View::renderTemplate('pages/admin/system/roles', 'admin', ['title' => 'Roles & Permissions | Admin', 'roles' => $roles]);
    }

    public function audit()
    {
        $filters = [];
        if (!empty($_GET['action']))  $filters['action'] = trim($_GET['action']);
        if (!empty($_GET['entity']))  $filters['entity_type'] = trim($_GET['entity']);
        if (!empty($_GET['user_id'])) $filters['user_id'] = (int)$_GET['user_id'];
        if (!empty($_GET['from']))    $filters['date_from'] = $_GET['from'];
        if (!empty($_GET['to']))      $filters['date_to'] = $_GET['to'];
        if (!empty($_GET['q']))       $filters['search'] = trim($_GET['q']);

        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = AuditService::query($filters, $page, 30);

        $logs = [];
        foreach ($result['rows'] as $r) {
            $old = json_decode($r['old_values'] ?? '{}', true) ?: [];
            $new = json_decode($r['new_values'] ?? '{}', true) ?: [];
            $userName = trim(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? ''));
            $logs[] = [
                'id'        => $r['id'],
                'user'      => $userName ?: ($r['email'] ?? 'System'),
                'action'    => $r['action'],
                'module'    => ucfirst(explode('.', $r['action'])[0] ?? $r['entity_type'] ?? 'System'),
                'entity'    => $r['entity_type'],
                'entity_id' => $r['entity_id'],
                'old'       => $old,
                'new'       => $new,
                'ip'        => $r['ip_address'] ?? '',
                'uri'       => $r['request_uri'] ?? '',
                'method'    => $r['request_method'] ?? '',
                'date'      => date('M j, Y g:i A', strtotime($r['created_at'])),
            ];
        }

        return View::renderTemplate('pages/admin/system/audit', 'admin', [
            'title'    => 'Audit Log | Admin',
            'logs'     => $logs,
            'total'    => $result['total'],
            'page'     => $result['page'],
            'pages'    => $result['pages'],
            'filters'  => $filters,
        ]);
    }
}
