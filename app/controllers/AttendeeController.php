<?php

namespace App\Controllers;

use Core\UseCases\Attendee\RegisterAttendee;
use Core\UseCases\Attendee\ListAttendeesForEvent;
use Core\UseCases\Event\GetEventDetails;

class AttendeeController
{
    private RegisterAttendee $registerAttendee;
    private ListAttendeesForEvent $listAttendeesForEvent;

    private GetEventDetails $getEventDetails;

    public function __construct(
        RegisterAttendee $registerAttendee,
        ListAttendeesForEvent $listAttendeesForEvent,
        GetEventDetails $getEventDetails
    ) {
        $this->registerAttendee = $registerAttendee;
        $this->listAttendeesForEvent = $listAttendeesForEvent;
        $this->getEventDetails = $getEventDetails;
    }

    public function registerView(int $eventId): void
    {
        $event = $this->getEventDetails->execute($eventId);
        require __DIR__."/../../presentation/views/attendees/register.php";
    }

    public function register(): void
    {
        $eventId = $_POST['event_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];

        try {
            $this->registerAttendee->execute($eventId, $name, $email);
            header('Location: /events/' . $eventId . '/attendees');
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function list(int $eventId): void
    {
        try {
            $attendees = $this->listAttendeesForEvent->execute($eventId);
            require '/../../presentation/views/attendees/list.php';
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }


}
