<?php
namespace App\Controllers;

use Core\Usecases\User\GetAuthUser;

class DashboardController
{
    private GetAuthUser $getAuthUser;
    public function __construct(GetAuthUser $getAuthUser)
    {
        $this->getAuthUser = $getAuthUser;
    }

    public function index()
    {
        $user = $this->getAuthUser->execute();

        require __DIR__.'/../../presentation/views/dashboard/index.php';
    }
}