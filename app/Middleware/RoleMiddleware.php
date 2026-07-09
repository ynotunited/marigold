<?php

namespace App\Middleware;

use App\Core\Session;

class RoleMiddleware
{
    /**
     * Ensure the user has the required role(s)
     * 
     * @param string|array $roles
     */
    public static function handle($roles)
    {
        AuthMiddleware::handle(); // Ensure logged in first

        $userRoles = Session::get('user_roles', []);
        
        if (in_array('super-admin', $userRoles)) {
            return; // Super admin bypasses all role checks
        }

        $roles = (array) $roles;
        $hasRole = false;

        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            header("HTTP/1.0 403 Forbidden");
            echo "403 Forbidden - You do not have permission to access this page.";
            exit;
        }
    }
}
