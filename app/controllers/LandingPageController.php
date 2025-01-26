<?php
namespace App\Controllers;

use Core\UseCases\Event\GetAllUpcomingEventList;
use Core\Usecases\User\GetAuthUser;

class LandingPageController
{
    private GetAuthUser $getAuthUser;
    private GetAllUpcomingEventList $getAllUpcomingEventList;

    public function __construct(GetAuthUser $getAuthUser, GetAllUpcomingEventList $getAllUpcomingEventList)
    {
        $this->getAuthUser = $getAuthUser;
        $this->getAllUpcomingEventList = $getAllUpcomingEventList;
    }

    public function index()
    {
        $user = $this->getAuthUser->execute();

        $upcomingEvents = $this->getAllUpcomingEventList->execute();
        
        require __DIR__.'/../../presentation/views/landing/index.php';
    }
}