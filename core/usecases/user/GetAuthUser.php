<?php
namespace Core\Usecases\User;

use Core\Entities\User;
use Core\Repositories\UserRepository;

class GetAuthUser
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(): ?User
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // echo $_SESSION['user_id'];
        // die();

        if($userId = $_SESSION['user_id']){
            return $this->userRepository->findById($userId);
        }

        return null;
    }
}