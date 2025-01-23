<?php

namespace Core\UseCases\User;

class LogoutUser
{
    public function execute(): void
    {
        // Destroy the session to log out
        session_start();
        session_unset();
        session_destroy();
    }
}
