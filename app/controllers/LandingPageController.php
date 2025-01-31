<?php
namespace App\Controllers;

use Core\Usecases\Event\GetAllAvailableEventList;
use Core\Usecases\User\GetAuthUser;

class LandingPageController
{
    public function index(GetAuthUser $getAuthUser, GetAllAvailableEventList $getAllAvailableEventList)
    {
        $user = $getAuthUser->execute();

        $availableEvents = $getAllAvailableEventList->execute();
        
        require __DIR__.'/../../presentation/views/landing/index.php';
    }
}