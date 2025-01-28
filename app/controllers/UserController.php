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
        $confirmPassword = $_POST['confirm_password'];
        $messages = [];
        if(empty($name)){
            $messages[]="Name is required";
        }
        if(empty($email)){
            $messages[]="Email is required";
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $messages[]="Email is invalid";
        }
        
        if(empty($password)){
            $messages[]="Password is required";
        }elseif($password != $confirmPassword){
            $messages[]="Password is not matched with the confirm password";
        }

        if(!empty($messages)){
            foreach($messages as $message){
                setFlashMessage('warning', $message);
            }
            setOld('name', $name);
            setOld('email', $email);
            $location = url('/register');
            header("Location: $location");
            exit;
        }

        try {
            $this->registerUser->execute($name, $email, $password);
            setFlashMessage('success','Registration successfull');
            $location = url('/login');
            header("Location: $location");
        } catch (\Exception $e) {
            setFlashMessage('danger', $e->getMessage());
            $location = url('/register');
            header("Location: $location");
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
            $location = url('/dashboard');
            header("Location: $location");
        } catch (\Exception $e) {
            setOld('email',$email);
            setFlashMessage('danger', $e->getMessage());
            $location = url('/login');
            header("Location: $location");
        }
    }

    public function logout(): void
    {
        $this->logoutUser->execute();
        $location = url('/login');
        header("Location: $location");
    }

    public function seedAdmin()
    {
    }
}
