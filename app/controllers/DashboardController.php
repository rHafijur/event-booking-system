<?php
namespace App\Controllers;

use Core\Usecases\User\GetAuthUser;

class DashboardController
{

    public function index(GetAuthUser $getAuthUser): void
    {
        $user = $getAuthUser->execute();

        require __DIR__.'/../../presentation/views/dashboard/index.php';
    }
}