<?php

// Auth security hardening — adds columns that older installs may be missing.
// Runs inside migrate.php where $pdo is in scope. Uses information_schema so it
// works on both MySQL 8+ and MariaDB (MySQL does not support ADD COLUMN IF NOT EXISTS).
//
// Columns are added in dependency order so each AFTER anchor exists first.

$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo) {
    throw new \RuntimeException('010 migration requires the PDO connection ($pdo) to be in scope.');
}

$queries = [];

$hasColumn = function (string $column) use ($pdo): bool {
    $stmt = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = " . $pdo->quote($column)
    );
    return (int)$stmt->fetchColumn() > 0;
};

$addColumn = function (string $column, string $definition, string $after) use (&$queries, $hasColumn, $pdo): void {
    if ($hasColumn($column)) {
        return;
    }
    $afterColumn = $hasColumn($after) ? "AFTER `$after`" : '';
    $queries["users_add_$column"] = "ALTER TABLE users ADD COLUMN `$column` $definition $afterColumn;";
};

$addColumn('email_verify_token', 'CHAR(64) NULL', 'email_verified_at');
$addColumn('email_verify_expires', 'TIMESTAMP NULL', 'email_verify_token');
$addColumn('password_reset_token', 'CHAR(64) NULL', 'email_verify_expires');
$addColumn('password_reset_expires', 'TIMESTAMP NULL', 'password_reset_token');

$queries['users_ensure_remember_token'] = "
    ALTER TABLE users MODIFY remember_token VARCHAR(255) NULL;
";

return $queries;
