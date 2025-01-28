<?php

namespace App\Middlewares;

class AdminMiddleware
{
    public function handle(): void
    {
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /unauthorized');
            exit;
        }
    }
}
