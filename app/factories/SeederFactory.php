<?php
namespace App\Factories;

use PDO;
use Infrastructure\Container;
use Infrastructure\Database\Database;
use Infrastructure\Database\Seeders\UserSeeder;
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

    public static function getUserSeeder(): UserSeeder
    {
        // return new UserSeeder(static::getUserRepository(static::getDbConn()));
        return Container::getInstance()->resolve(UserSeeder::class);
    }
}