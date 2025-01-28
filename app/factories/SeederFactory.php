<?php
namespace App\Factories;

use Infrastructure\Database\Seeders\UserSeeder;
use PDO;
use Infrastructure\Database\Database;
use Infrastructure\Repositories\MySQLUserRepository;

class SeederFactory
{
    private static function getDbConn(): PDO
    {
        $db = new Database();
        return $db->getConnection();
    }

    private static function getUserRepository(PDO $db): MySQLUserRepository
    {
        return new MySQLUserRepository($db);
    }

    public static function getUserSeeder()
    {
        return new UserSeeder(static::getUserRepository(static::getDbConn()));
    }
}