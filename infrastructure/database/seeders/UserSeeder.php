<?php
namespace Infrastructure\Database\Seeders;

use Core\Entities\User;
use Infrastructure\Database\Database;
use Infrastructure\Repositories\MySQLUserRepository;

class UserSeeder
{
    private $mySQLUserRepository;
    public function __construct(MySQLUserRepository $mySQLUserRepository){
        $this->mySQLUserRepository = $mySQLUserRepository;
    }
    
    public function run(): void
    {
        $this->mySQLUserRepository->create(new User('Admin', 'admin@site.com', password_hash('123456', PASSWORD_BCRYPT), 'admin'));
        $this->mySQLUserRepository->create(new User('Hafijur Rahman', 'hafijur@site.com', password_hash('123456', PASSWORD_BCRYPT), 'user'));
        
        echo 'Seeder run successfully.';
    }
}
