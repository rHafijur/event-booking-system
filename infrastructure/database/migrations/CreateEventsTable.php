<?php

// require_once __DIR__.'/../Database.php';
namespace Infrastructure\Database\Migrations;

use Infrastructure\Database\Database;

class CreateEventsTable
{
    public static function up()
    {
        $db = Database::getConnection();
        $query = "
            CREATE TABLE events (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                capacity INT NOT NULL,
                event_date DATETIME NOT NULL,
                booking_deadline DATETIME NOT NULL,
                image VARCHAR(255),
                venue VARCHAR(255) NOT NULL,
                ticket_price DECIMAL(10, 2) NOT NULL,
                organizer_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (organizer_id) REFERENCES users(id) ON DELETE CASCADE
            );
        ";
        $db->exec($query);
        echo "Events table created successfully.<br>";
    }

    public static function down()
    {
        $db = Database::getConnection();
        $query = "DROP TABLE IF EXISTS events;";
        $db->exec($query);
        echo "Events table dropped successfully.<br>";
    }
}
