<?php

namespace App\Core;

class Policy
{
    /**
     * Check if the currently logged in user has a specific permission
     * E.g. Policy::can('products.create')
     */
    public static function can(string $permissionSlug): bool
    {
        $userId = Session::get('user_id');
        if (!$userId) return false;

        $userRoles = Session::get('user_roles', []);
        
        // Super Admin bypass
        if (in_array('super-admin', $userRoles)) {
            return true;
        }

        // Cache permissions per user for performance (e.g. 5 mins)
        $cacheKey = "user_{$userId}_permissions";
        $permissions = FileCache::get($cacheKey);

        if ($permissions === null) {
            $db = Model::getDB();
            $stmt = $db->prepare("
                SELECT p.slug 
                FROM permissions p
                JOIN role_permissions rp ON p.id = rp.permission_id
                JOIN user_roles ur ON rp.role_id = ur.role_id
                WHERE ur.user_id = :user_id
            ");
            $stmt->execute(['user_id' => $userId]);
            $permissions = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            
            FileCache::set($cacheKey, $permissions, 300);
        }

        return in_array($permissionSlug, $permissions);
    }

    /**
     * Throw 403 if unauthorized
     */
    public static function authorize(string $permissionSlug): void
    {
        if (!self::can($permissionSlug)) {
            header("HTTP/1.0 403 Forbidden");
            echo "403 Forbidden - You do not have permission for this action ($permissionSlug).";
            exit;
        }
    }
}
