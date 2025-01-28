<?php

// require_once __DIR__.'/../config/Env.php';
namespace Infrastructure\Database;
use PDO;
use Infrastructure\Config\Env;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                Env::load(__DIR__ . '/../../.env');

                $host = $_ENV['DB_HOST'] ?? 'localhost';
                $db = $_ENV['DB_NAME'];
                $user = $_ENV['DB_USER'];
                $pass = $_ENV['DB_PASSWORD'] ?? '';
                $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$connection = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                throw new \RuntimeException("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
