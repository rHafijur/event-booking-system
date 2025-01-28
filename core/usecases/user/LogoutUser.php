<?php

namespace Core\Usecases\User;

class LogoutUser
{
    public function execute(): void
    {
        // Destroy the session to log out
        session_unset();
        session_destroy();
    }
}
