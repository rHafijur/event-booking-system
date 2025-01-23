<?php

namespace App\Middlewares;

class CsrfProtection
{
    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();

            if (empty($_POST['_csrf_token']) || $_POST['_csrf_token'] !== $_SESSION['_csrf_token']) {
                die('Invalid CSRF token');
            }
        }
    }

    public static function generateToken(): string
    {
        session_start();

        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }
}
