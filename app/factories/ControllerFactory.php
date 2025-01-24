<?php

namespace App\Factories;

use Core\UseCases\Attendee\ListAttendeesForEvent;
use Core\UseCases\Attendee\RegisterAttendee;
use Core\UseCases\Event\CreateEvent;
use Core\UseCases\Event\ListEvents;
use Infrastructure\Repositories\MySQLAttendeeRepository;
use PDO;
use App\Controllers\UserController;
use App\Controllers\EventController;
use Infrastructure\Database\Database;
use App\Controllers\AttendeeController;
use Core\Repositories\AttendeeRepository;
use Core\Repositories\EventRepository;
use Core\UseCases\Event\DeleteEvent;
use Core\UseCases\Event\GetEventDetails;
use Core\UseCases\Event\UpdateEvent;
use Infrastructure\Repositories\MySQLUserRepository;
use Core\UseCases\User\LoginUser;
use Core\UseCases\User\LogoutUser;
use Core\UseCases\User\RegisterUser;
use Infrastructure\Repositories\MySQLEventRepository;

class ControllerFactory
{
    private static function getDbConn(): PDO
    {
        $db = new Database();
        return $db->getConnection();
    }

    private static function getAttendeeRepository(PDO $db): AttendeeRepository
    {
        return new MySQLAttendeeRepository($db);
    }
    
    private static function getEventRepository(PDO $db): EventRepository
    {
        return new MySQLEventRepository($db);
    }

    public static function getUserController()
    {
        $conn = static::getDbConn();
        $userRepository = new MySQLUserRepository($conn);
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
        $getEventDetails = new GetEventDetails($eventRepository);
        $listAttendeesForEvent = new ListAttendeesForEvent($attendeeRepository);
        return new EventController($createEvent, $updateEvent, $deleteEvent, $listEvents, $getEventDetails, $listAttendeesForEvent);
    }

    public static function getAttendeeController()
    {
        $conn = static::getDbConn();
        $eventRepository = static::getEventRepository($conn);
        $attendeeRepository = static::getAttendeeRepository($conn);
        $registerAttendee = new RegisterAttendee($attendeeRepository, $eventRepository);
        $listAttendeesForEvent = new ListAttendeesForEvent($attendeeRepository);
        $getEventDetails = new GetEventDetails($eventRepository);
        return new AttendeeController($registerAttendee, $listAttendeesForEvent, $getEventDetails);
    }
}
