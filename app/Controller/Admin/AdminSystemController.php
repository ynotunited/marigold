<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class AdminSystemController extends Controller
{
    public function settings()
    {
        return View::renderTemplate('pages/admin/system/settings', 'admin', ['title' => 'Global Settings | Admin']);
    }

    public function users()
    {
        $users = [
            ['id' => 1, 'name' => 'Sarah Jenkins', 'email' => 'sarah@marigold.ng', 'role' => 'Super Admin', 'status' => 'Active', 'last_login' => '10 mins ago'],
            ['id' => 2, 'name' => 'Mike Olatunji', 'email' => 'mike@marigold.ng', 'role' => 'Sales Manager', 'status' => 'Active', 'last_login' => '2 hours ago'],
            ['id' => 3, 'name' => 'David Adeleke', 'email' => 'david@marigold.ng', 'role' => 'Content Editor', 'status' => 'Inactive', 'last_login' => '3 weeks ago'],
        ];
        return View::renderTemplate('pages/admin/system/users', 'admin', ['title' => 'Admin Users | Admin', 'users' => $users]);
    }

    public function roles()
    {
        $roles = [
            ['id' => 1, 'name' => 'Super Admin', 'users' => 1, 'description' => 'Full access to all modules and system settings.'],
            ['id' => 2, 'name' => 'Sales Manager', 'users' => 4, 'description' => 'Can manage products, orders, quotes, and customers.'],
            ['id' => 3, 'name' => 'Content Editor', 'users' => 2, 'description' => 'Can manage blog posts, CMS pages, and media library.'],
        ];
        return View::renderTemplate('pages/admin/system/roles', 'admin', ['title' => 'Roles & Permissions | Admin', 'roles' => $roles]);
    }

    public function audit()
    {
        $logs = [
            ['id' => 4092, 'user' => 'Sarah Jenkins', 'action' => 'Updated Settings', 'module' => 'System', 'record' => 'Payment Gateway', 'date' => '10 mins ago'],
            ['id' => 4091, 'user' => 'Mike Olatunji', 'action' => 'Approved Quote', 'module' => 'Quotes', 'record' => 'QT-1045', 'date' => '1 hour ago'],
            ['id' => 4090, 'user' => 'System', 'action' => 'Automated Backup', 'module' => 'Database', 'record' => 'db_backup_20260709.sql', 'date' => '5 hours ago'],
            ['id' => 4089, 'user' => 'David Adeleke', 'action' => 'Published Post', 'module' => 'Blog', 'record' => 'Top 10 Corporate Gifts', 'date' => '2 days ago'],
        ];
        return View::renderTemplate('pages/admin/system/audit', 'admin', ['title' => 'Audit Logs | Admin', 'logs' => $logs]);
    }
}
