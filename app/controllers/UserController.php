<?php

namespace App\Controllers;

use Core\Usecases\User\RegisterUser;
use Core\Usecases\User\LoginUser;
use Core\Usecases\User\LogoutUser;

class UserController
{
    public function registerView(): void
    {
        require __DIR__."/../../presentation/views/auth/register.php";
    }

    public function register(RegisterUser $registerUser): void
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
            $registerUser->execute($name, $email, $password);
            setFlashMessage('success','Registration successfull');
            $location = url('/login');
            header("Location: $location");
        } catch (\Exception $e) {
            setOld('name', $name);
            setOld('email', $email);
            setFlashMessage('danger', $e->getMessage());
            $location = url('/register');
            header("Location: $location");
        }
    }

    public function loginView(): void
    {
        require __DIR__."/../../presentation/views/auth/login.php";
    }

    public function login(LoginUser $loginUser): void
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $result = $loginUser->execute($email, $password);
            $location = url('/dashboard');
            header("Location: $location");
        } catch (\Exception $e) {
            setOld('email',$email);
            setFlashMessage('danger', $e->getMessage());
            $location = url('/login');
            header("Location: $location");
        }
    }

    public function logout(LogoutUser $logoutUser): void
    {
        $logoutUser->execute();
        $location = url('/login');
        header("Location: $location");
    }

    public function seedAdmin()
    {
    }
}
