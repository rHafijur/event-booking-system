<?php
namespace App\Controllers;

use Core\UseCases\Event\GetAllAvailableEventList;
use Core\Usecases\User\GetAuthUser;

class LandingPageController
{
    private GetAuthUser $getAuthUser;
    private GetAllAvailableEventList $getAllAvailableEventList;

    public function __construct(GetAuthUser $getAuthUser, GetAllAvailableEventList $getAllAvailableEventList)
    {
        $this->getAuthUser = $getAuthUser;
        $this->getAllAvailableEventList = $getAllAvailableEventList;
    }

    public function index()
    {
        $user = $this->getAuthUser->execute();

        $availableEvents = $this->getAllAvailableEventList->execute();
        
        require __DIR__.'/../../presentation/views/landing/index.php';
    }
}