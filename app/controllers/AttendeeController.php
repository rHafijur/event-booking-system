<?php

namespace App\Controllers;

use Core\UseCases\Attendee\FindEventAttendeeByEmail;
use Core\UseCases\Attendee\RegisterAttendee;
use Core\UseCases\Attendee\ListAttendeesForEvent;
use Core\UseCases\Event\GetEventDetails;
use DateTime;

class AttendeeController
{
    private RegisterAttendee $registerAttendee;
    private ListAttendeesForEvent $listAttendeesForEvent;
    private GetEventDetails $getEventDetails;
    private FindEventAttendeeByEmail $findEventAttendeeByEmail;

    public function __construct(
        RegisterAttendee $registerAttendee,
        ListAttendeesForEvent $listAttendeesForEvent,
        GetEventDetails $getEventDetails,
        FindEventAttendeeByEmail $findEventAttendeeByEmail
    ) {
        $this->registerAttendee = $registerAttendee;
        $this->listAttendeesForEvent = $listAttendeesForEvent;
        $this->getEventDetails = $getEventDetails;
        $this->findEventAttendeeByEmail = $findEventAttendeeByEmail;
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

        $errors = [];

        if (empty($name)) {
            $errors[] = "Event name is required.";
        } elseif (strlen($name) > 100) {
            $errors[] = "Event name should not exceed 100 characters.";
        }
        
        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Given email is invalid.";
        }

        $existingAttendees = $this->findEventAttendeeByEmail->execute($eventId, $email);
        
        if(count($existingAttendees)){
            $errors[] = "Email is already registered in this event.";
        }

        $event = $this->getEventDetails->execute($eventId);


        if($event->availableTicketCount() < 1){
            $errors[] = "All tickets are sold out.";
        }

        if(count($errors)){
            http_response_code(400);
            echo json_encode(['errors'=> $errors]);
            exit;
        }

        try {
            $this->registerAttendee->execute($event->getId(), $name, $email, new DateTime());
            echo json_encode([
                'event_name' => $event->getName(),
                'venue' => $event->getVenue(),
                'event_date' => $event->getEventDate()->format('d/M/Y'),
                'attendee_name' => $name,
                'email' => $email
            ]);
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
