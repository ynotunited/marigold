<?php
// database/migrations/018_account_manager_assignment.php
//
// Account manager system:
//   - customers: account_manager_id links a customer to their dedicated
//     account manager (a user with the 'account-manager' role).
//   - roles: introduces the 'account-manager' role so managers show up as
//     distinct staff accounts in Admin > Users.

return [
    'customers_add_account_manager_id' => "
        ALTER TABLE customers
            ADD COLUMN account_manager_id BIGINT UNSIGNED NULL AFTER user_id,
            ADD KEY idx_customers_account_manager (account_manager_id);
    ",

    'roles_add_account_manager' => "
        INSERT INTO roles (name, slug, description, created_at, updated_at)
        VALUES ('Account Manager', 'account-manager', 'Dedicated contact assigned to customers for orders, quotes, and support.', NOW(), NOW())
        ON DUPLICATE KEY UPDATE name = VALUES(name), description = VALUES(description);
    ",
];
