<?php

use Infrastructure\Container;
use Infrastructure\Config\Env;
use Core\Repositories\AttendeeRepository;
use Core\Repositories\EventRepository;
use Core\Repositories\UserRepository;
use Infrastructure\Repositories\MySQLAttendeeRepository;
use Infrastructure\Repositories\MySQLEventRepository;
use Infrastructure\Repositories\MySQLUserRepository;

$container = Container::getInstance();

$container->bind(PDO::class, function(){
        Env::load(__DIR__ . '/../.env');

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

        return new PDO($dsn, $user, $pass, $options);
});

$container->bind(AttendeeRepository::class, MySQLAttendeeRepository::class);
$container->bind(EventRepository::class, MySQLEventRepository::class);
$container->bind(UserRepository::class, MySQLUserRepository::class);
