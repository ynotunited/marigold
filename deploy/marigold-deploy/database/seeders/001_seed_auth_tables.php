<?php
// database/seeders/001_seed_auth_tables.php

return function (\PDO $db) {
    // 1. Seed Roles
    $roles = [
        ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Unrestricted access to all modules'],
        ['name' => 'Administrator', 'slug' => 'admin', 'description' => 'Full access to most modules except system settings'],
        ['name' => 'Sales', 'slug' => 'sales', 'description' => 'Access to orders, quotes, customers'],
        ['name' => 'Inventory', 'slug' => 'inventory', 'description' => 'Access to products, categories, stock'],
        ['name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Access to blogs, coupons, newsletters'],
        ['name' => 'Support', 'slug' => 'support', 'description' => 'Access to customer tickets and basic order info'],
        ['name' => 'Finance', 'slug' => 'finance', 'description' => 'Access to reports and payment logs'],
        ['name' => 'Customer', 'slug' => 'customer', 'description' => 'Standard frontend user'],
    ];

    $roleStmt = $db->prepare("INSERT IGNORE INTO roles (name, slug, description) VALUES (:name, :slug, :description)");
    foreach ($roles as $role) {
        $roleStmt->execute($role);
    }

    // 2. Seed Permissions
    $modules = ['products', 'orders', 'quotes', 'customers', 'users', 'content', 'settings', 'reports'];
    $actions = ['view', 'create', 'edit', 'delete', 'approve', 'export'];

    $permStmt = $db->prepare("INSERT IGNORE INTO permissions (module, name, slug, description) VALUES (:module, :name, :slug, :description)");
    
    foreach ($modules as $module) {
        foreach ($actions as $action) {
            $permStmt->execute([
                'module' => $module,
                'name' => ucfirst($action) . ' ' . ucfirst($module),
                'slug' => $module . '.' . $action,
                'description' => "Can $action $module"
            ]);
        }
    }

    // 3. Assign all permissions to Super Admin
    $db->exec("
        INSERT IGNORE INTO role_permissions (role_id, permission_id)
        SELECT r.id, p.id FROM roles r 
        CROSS JOIN permissions p 
        WHERE r.slug = 'super-admin'
    ");
};
