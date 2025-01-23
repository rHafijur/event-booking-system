<?php

namespace App\Middlewares;

class AdminMiddleware
{
    public function handle(): void
    {
        session_start();

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /unauthorized');
            exit;
        }
    }
}
