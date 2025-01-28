<?php

namespace App\Factories;

use App\Controllers\LandingPageController;
use Core\Usecases\Attendee\ListAttendeesForEvent;
use Core\Usecases\Attendee\RegisterAttendee;
use Core\Usecases\Event\CreateEvent;
use Core\Usecases\Event\GetAllAvailableEventList;
use Core\Usecases\Event\ListEvents;
use Core\Usecases\User\GetAuthUser;
use Infrastructure\Repositories\MySQLAttendeeRepository;
use PDO;
use App\Controllers\UserController;
use App\Controllers\EventController;
use Infrastructure\Database\Database;
use App\Controllers\AttendeeController;
use App\Controllers\DashboardController;
use Core\Repositories\AttendeeRepository;
use Core\Repositories\EventRepository;
use Core\Usecases\Attendee\FindEventAttendeeByEmail;
use Core\Usecases\Event\DeleteEvent;
use Core\Usecases\Event\GetEventDetails;
use Core\Usecases\Event\OrganizerListEvents;
use Core\Usecases\Event\UpdateEvent;
use Core\Usecases\Report\GenerateEventReport;
use Core\Usecases\User\GetUserById;
use Infrastructure\Repositories\MySQLUserRepository;
use Core\Usecases\User\LoginUser;
use Core\Usecases\User\LogoutUser;
use Core\Usecases\User\RegisterUser;
use Infrastructure\Repositories\MySQLEventRepository;

class ControllerFactory
{
    private static function getDbConn(): PDO
    {
        $db = new Database();
        return $db->getConnection();
    }

    private static function getUserRepository(PDO $db): MySQLUserRepository
    {
        return new MySQLUserRepository($db);
    }
    
    private static function getAttendeeRepository(PDO $db): AttendeeRepository
    {
        return new MySQLAttendeeRepository($db);
    }
    
    private static function getEventRepository(PDO $db): EventRepository
    {
        return new MySQLEventRepository($db);
    }

    public static function getLandingPageController()
    {
        $conn = static::getDbConn();
        $userRepository = static::getUserRepository($conn);
        $getAuthUser = new GetAuthUser($userRepository);
        $getAllAvailableEventList = new GetAllAvailableEventList(static::getEventRepository($conn));
        return new LandingPageController($getAuthUser, $getAllAvailableEventList);
    }
    
    public static function getDashboardController()
    {
        $conn = static::getDbConn();
        $userRepository = static::getUserRepository($conn);
        $getAuthUser = new GetAuthUser($userRepository);
        return new DashboardController($getAuthUser);
    }

    public static function getUserController()
    {
        $conn = static::getDbConn();
        $userRepository = self::getUserRepository($conn);
        $loginUser = new LoginUser($userRepository);
        $registerUser = new RegisterUser($userRepository);
        $logoutUser = new LogoutUser();
        return new UserController($registerUser, $loginUser, $logoutUser);
    }

    public static function getEventController()
    {   
        $conn = static::getDbConn();
        $eventRepository = static::getEventRepository($conn);
        $attendeeRepository = static::getAttendeeRepository($conn);
        $createEvent = new CreateEvent($eventRepository);
        $updateEvent = new UpdateEvent($eventRepository);
        $deleteEvent = new DeleteEvent($eventRepository);
        $listEvents = new ListEvents($eventRepository);
        $organizerListEvents = new OrganizerListEvents($eventRepository);
        $getEventDetails = new GetEventDetails($eventRepository);
        $listAttendeesForEvent = new ListAttendeesForEvent($attendeeRepository);
        $generateEventReport = new GenerateEventReport($attendeeRepository);
        $userRepository = static::getUserRepository($conn);
        $getAuthUser = new GetAuthUser($userRepository);
        $getUserById = new GetUserById($userRepository);
        return new EventController($createEvent, $updateEvent, $deleteEvent, $organizerListEvents, $listEvents, $getEventDetails, $listAttendeesForEvent, $getAuthUser, $generateEventReport, $getUserById);
    }

    public static function getAttendeeController()
    {
        $conn = static::getDbConn();
        $eventRepository = static::getEventRepository($conn);
        $attendeeRepository = static::getAttendeeRepository($conn);
        $registerAttendee = new RegisterAttendee($attendeeRepository, $eventRepository);
        $listAttendeesForEvent = new ListAttendeesForEvent($attendeeRepository);
        $findEventAttendeeByEmail = new FindEventAttendeeByEmail($attendeeRepository);
        $getEventDetails = new GetEventDetails($eventRepository);
        return new AttendeeController($registerAttendee, $listAttendeesForEvent, $getEventDetails, $findEventAttendeeByEmail);
    }
}
