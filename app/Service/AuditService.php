<?php

namespace App\Service;

use App\Core\Model;

/**
 * Audit trail service — logs who did what, when, with before/after state.
 *
 * All methods are static and fail silently (audit should never break the
 * main request).
 */
class AuditService
{
    /**
     * Log an auditable action.
     *
     * @param string $action     Verb + noun, e.g. "order.status_changed", "product.deleted", "auth.login_failed"
     * @param string $entityType Table/entity, e.g. "orders", "products", "users"
     * @param int|null $entityId Primary key of the affected row
     * @param array $oldValues   State before the change (or context data)
     * @param array $newValues   State after the change (or extra metadata)
     * @param int|null $userId   Who performed the action (null = system/guest)
     */
    public static function log(
        string $action,
        string $entityType = '',
        ?int $entityId = null,
        array $oldValues = [],
        array $newValues = [],
        ?int $userId = null
    ): void {
        try {
            $db = Model::getDB();

            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

            $stmt = $db->prepare("
                INSERT INTO audit_log
                    (user_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent, request_uri, request_method, created_at)
                VALUES
                    (:user_id, :action, :entity_type, :entity_id, :old_values, :new_values, :ip, :ua, :uri, :method, NOW())
            ");
            $stmt->execute([
                'user_id'     => $userId,
                'action'      => $action,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'old_values'  => $oldValues ? json_encode($oldValues, JSON_UNESCAPED_UNICODE) : null,
                'new_values'  => $newValues ? json_encode($newValues, JSON_UNESCAPED_UNICODE) : null,
                'ip'          => mb_substr($ip, 0, 45),
                'ua'          => mb_substr($ua, 0, 500),
                'uri'         => mb_substr($uri, 0, 500),
                'method'      => $method,
            ]);
        } catch (\Throwable $e) {
            // Audit failures must never crash the request
        }
    }

    /**
     * Convenience: log with the current authenticated user auto-detected.
     */
    public static function act(
        string $action,
        string $entityType = '',
        ?int $entityId = null,
        array $oldValues = [],
        array $newValues = []
    ): void {
        $userId = \App\Core\Session::get('user_id');
        self::log($action, $entityType, $entityId, $oldValues, $newValues, $userId);
    }

    /**
     * Query audit logs with optional filters.
     *
     * @param array $filters  Supported: action, entity_type, user_id, date_from, date_to, search
     * @param int $page       1-indexed page number
     * @param int $perPage    Results per page (max 100)
     * @return array{rows: array, total: int, page: int, perPage: int, pages: int}
     */
    public static function query(array $filters = [], int $page = 1, int $perPage = 30): array
    {
        $db = Model::getDB();
        $where = [];
        $params = [];

        if (!empty($filters['action'])) {
            $where[] = 'a.action LIKE :action';
            $params['action'] = '%' . $filters['action'] . '%';
        }
        if (!empty($filters['entity_type'])) {
            $where[] = 'a.entity_type = :entity_type';
            $params['entity_type'] = $filters['entity_type'];
        }
        if (!empty($filters['user_id'])) {
            $where[] = 'a.user_id = :user_id';
            $params['user_id'] = (int)$filters['user_id'];
        }
        if (!empty($filters['date_from'])) {
            $where[] = 'a.created_at >= :date_from';
            $params['date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $where[] = 'a.created_at <= :date_to';
            $params['date_to'] = $filters['date_to'] . ' 23:59:59';
        }
        if (!empty($filters['search'])) {
            $where[] = '(a.action LIKE :search OR a.entity_type LIKE :search2 OR a.request_uri LIKE :search3)';
            $search = '%' . $filters['search'] . '%';
            $params['search'] = $search;
            $params['search2'] = $search;
            $params['search3'] = $search;
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // Count
        $countSql = "SELECT COUNT(*) FROM audit_log a {$whereClause}";
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Fetch page
        $perPage = min(max($perPage, 1), 100);
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $sql = "
            SELECT a.*, u.first_name, u.last_name, u.email
            FROM audit_log a
            LEFT JOIN users u ON u.id = a.user_id
            {$whereClause}
            ORDER BY a.created_at DESC
            LIMIT {$perPage} OFFSET {$offset}
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        return [
            'rows'    => $rows,
            'total'   => $total,
            'page'    => $page,
            'perPage' => $perPage,
            'pages'   => (int)ceil($total / $perPage),
        ];
    }
}
