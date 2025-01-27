<?php
namespace Core\Usecases\User;

use Core\Entities\User;
use Core\Repositories\UserRepository;

class GetUserById
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }
}