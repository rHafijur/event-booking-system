<?php

namespace Core\UseCases\User;

use Core\Repositories\UserRepository;
use Exception;

class LoginUser
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $email, string $password): array
    {
        // Fetch user by email
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            throw new Exception("Invalid password or email.");
        }

        // Verify the password
        if (!password_verify($password, $user->getPassword())) {
            throw new Exception("Invalid email or password.");
        }

        // Generate a session or token
        $sessionId = $this->createSession($user->getId(), $user->getRole());

        // Return user details and session information
        return [
            'user' => $user,
            'sessionId' => $sessionId,
        ];
    }

    private function createSession(int $userId, string $role): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $role;
        return session_id();
    }
}
