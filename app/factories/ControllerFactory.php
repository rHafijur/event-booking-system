<?php

namespace App\Factories;

use Infrastructure\Container;
use App\Controllers\UserController;
use App\Controllers\EventController;
use App\Controllers\AttendeeController;
use App\Controllers\DashboardController;
use App\Controllers\LandingPageController;

class ControllerFactory
{
    public static function getLandingPageController(): LandingPageController
    {
        return Container::getInstance()->resolve(LandingPageController::class);
    }
    
    public static function getDashboardController(): DashboardController
    {
        return Container::getInstance()->resolve(DashboardController::class);
    }

    public static function getUserController(): UserController
    {
        return Container::getInstance()->resolve(UserController::class);
    }

    public static function getEventController(): EventController
    {   
        return Container::getInstance()->resolve(EventController::class);
    }

    public static function getAttendeeController(): AttendeeController
    {
        return Container::getInstance()->resolve(AttendeeController::class);
    }
}
