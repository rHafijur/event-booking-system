<?php

// require_once __DIR__.'/../Database.php';

namespace Infrastructure\Database\Migrations;

use Infrastructure\Database\Database;

class CreateAttendeesTable
{
    public static function up(): void
    {
        $db = Database::getConnection();
        $query = "
            CREATE TABLE attendees (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                event_id INT NOT NULL,
                registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
            );
        ";
        $db->exec($query);
        echo "Attendees table created successfully.<br>";
    }

    public static function down(): void
    {
        $db = Database::getConnection();
        $query = "DROP TABLE IF EXISTS attendees;";
        $db->exec($query);
        echo "Attendees table dropped successfully.<br>";
    }
}
