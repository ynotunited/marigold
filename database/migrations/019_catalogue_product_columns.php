<?php
// database/migrations/019_catalogue_product_columns.php
//
// Adds the product columns required by the catalogue feature
// (Catalogue core + admin product form):
//   - badge:         short label shown on cards (e.g. "New", "Bestseller")
//   - image:         featured image path/URL used by the storefront catalogue
//
// Uses information_schema so it is safe on MySQL 8+ and MariaDB, and safe to
// re-run on installs that already have the columns (like the dev database).

$pdo = $GLOBALS['pdo'] ?? null;

$queries = [];

$tableColumns = function (string $table) use ($pdo): array {
    if (!$pdo) {
        return [];
    }
    $stmt = $pdo->query(
        "SELECT COLUMN_NAME FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = " . $pdo->quote($table)
    );
    $cols = [];
    foreach ($stmt->fetchAll(\PDO::FETCH_COLUMN) as $name) {
        $cols[$name] = true;
    }
    return $cols;
};

$addColumnIfMissing = function (string $table, string $column, string $definition, string $after) use (&$queries, $tableColumns, $pdo): void {
    $cols = $tableColumns($table);
    if (isset($cols[$column])) {
        return;
    }
    $afterSql = (isset($cols[$after]) || $cols === []) ? " AFTER `$after`" : '';
    $queries["{$table}_add_{$column}"] = "ALTER TABLE `$table` ADD COLUMN `$column` $definition$afterSql;";
};

// If PDO is available (inside migrate.php) we resolve columns from the schema;
// otherwise fall back to a plain fresh-install ALTER.
if ($pdo) {
    $addColumnIfMissing('products', 'badge', 'VARCHAR(50) NULL', 'is_best_seller');
    $addColumnIfMissing('products', 'image', 'VARCHAR(500) NULL', 'badge');
} else {
    $queries['products_add_badge'] = "ALTER TABLE products ADD COLUMN `badge` VARCHAR(50) NULL AFTER `is_best_seller`;";
    $queries['products_add_image'] = "ALTER TABLE products ADD COLUMN `image` VARCHAR(500) NULL AFTER `badge`;";
}

return $queries;
