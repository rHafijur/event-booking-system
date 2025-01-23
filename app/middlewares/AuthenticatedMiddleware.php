<?php

namespace App\Middlewares;

class AuthenticatedMiddleware
{
    public function handle(): void
    {
        session_start();

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }
}
