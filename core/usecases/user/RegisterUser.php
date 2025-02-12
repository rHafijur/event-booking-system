<?php

namespace Core\Usecases\User;

use Core\Repositories\UserRepository;
use Core\Entities\User;
use Exception;

class RegisterUser
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $name, string $email, string $password, string $role = 'user'): User
    {
        if($this->userRepository->findByEmail($email)){
            throw new Exception("$email is already registered.");
        }
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $user = new User($name, $email, $hashedPassword, $role);

        // Save user in repository
        $userId = $this->userRepository->create($user);
        $user->setId($userId);

        return $user;
    }
}
