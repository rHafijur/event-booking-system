<?php

namespace App\Middlewares;

class CsrfProtection
{
    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

            if (empty($_POST['_csrf_token']) || $_POST['_csrf_token'] !== $_SESSION['_csrf_token']) {
                http_response_code(419);
                die('Invalid CSRF token');
            }
        }
    }

    public static function generateToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }
}
