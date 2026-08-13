<?php

namespace App\Core;

abstract class Model
{
    protected static $db = null;

    /**
     * Get the PDO database connection
     */
    public static function getDB()
    {
        if (self::$db === null) {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $dbname = $_ENV['DB_NAME'] ?? 'marigold_db';
            $user = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASS'] ?? '';

            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
            
            try {
                self::$db = new \PDO($dsn, $user, $password);
                self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            } catch (\PDOException $e) {
                // In production, log this instead of displaying
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$db;
    }
}
