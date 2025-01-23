<?php

namespace App\Controllers;

use Core\UseCases\User\RegisterUser;
use Core\UseCases\User\LoginUser;
use Core\UseCases\User\LogoutUser;

class UserController
{
    private RegisterUser $registerUser;
    private LoginUser $loginUser;
    private LogoutUser $logoutUser;

    public function __construct(RegisterUser $registerUser, LoginUser $loginUser, LogoutUser $logoutUser)
    {
        $this->registerUser = $registerUser;
        $this->loginUser = $loginUser;
        $this->logoutUser = $logoutUser;
    }

    public function registerView(): void
    {
        require __DIR__."/../../presentation/views/auth/register.php";
    }

    public function register(): void
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $this->registerUser->execute($name, $email, $password);
            header('Location: /login');
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function loginView(): void
    {
        require __DIR__."/../../presentation/views/auth/login.php";
    }

    public function login(): void
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $result = $this->loginUser->execute($email, $password);
            header('Location: /dashboard');
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function logout(): void
    {
        $this->logoutUser->execute();
        header('Location: /login');
    }
}
