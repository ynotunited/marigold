<?php

require __DIR__ . '/vendor/autoload.php';

use App\Core\Env;

// Load Env
Env::load(__DIR__ . '/.env');

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '3306';
$dbname = $_ENV['DB_NAME'] ?? 'marigold_db';
$user = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';

try {
    // Connect without DB first to create it
    $pdo = new \PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
    // Create DB
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");
    
    echo "Database created/selected successfully.\n";

    // Run Migrations
    echo "Running migrations...\n";
    $migrations = glob(__DIR__ . '/database/migrations/*.php');
    foreach ($migrations as $file) {
        $queries = require $file;
        foreach ($queries as $table => $sql) {
            $pdo->exec($sql);
            echo " - Migrated table: $table\n";
        }
    }

    // Run Seeders
    echo "\nRunning seeders...\n";
    $seeders = glob(__DIR__ . '/database/seeders/*.php');
    foreach ($seeders as $file) {
        $seeder = require $file;
        if (is_callable($seeder)) {
            $seeder($pdo);
            echo " - Ran seeder: " . basename($file) . "\n";
        }
    }

    echo "\nMigration and seeding complete!\n";

} catch (\PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
