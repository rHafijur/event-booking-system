<?php
// require_once __DIR__.'/../Database.php';
namespace Infrastructure\Database\Migrations;

use Infrastructure\Database\Database;

class CreateUsersTable
{
    public static function up()
    {
        $db = Database::getConnection();
        $query = "
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('user','admin') DEFAULT 'user',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );
        ";
        $db->exec($query);
        echo "Users table created successfully.<br>";
    }

    public static function down()
    {
        $db = Database::getConnection();
        $query = "DROP TABLE IF EXISTS users;";
        $db->exec($query);
        echo "Users table dropped successfully.<br>";
    }
}
