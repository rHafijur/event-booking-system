<?php

namespace Core\UseCases\User;

use Core\Repositories\UserRepository;
use Core\Entities\User;

class RegisterUser
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $name, string $email, string $password): User
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $user = new User($name, $email, $hashedPassword, 'user');

        // Save user in repository
        $userId = $this->userRepository->create($user);
        $user->setId($userId);

        return $user;
    }
}
