<?php

namespace App\Controllers;

use Core\Usecases\Attendee\FindEventAttendeeByEmail;
use Core\Usecases\Attendee\RegisterAttendee;
use Core\Usecases\Attendee\ListAttendeesForEvent;
use Core\Usecases\Event\GetEventDetails;
use DateTime;

class AttendeeController
{
    public function registerView(int $eventId, GetEventDetails $getEventDetails): void
    {
        try{
            $event = $getEventDetails->execute($eventId);
            if($event->getBookingDeadline()->getTimestamp()< (new DateTime())->getTimestamp()){
                throw new \Exception("Booking Deadline Expired");
            }
        }catch(\Exception $e){
            echo "Message: ".$e->getMessage();
            exit;
        }

        require __DIR__."/../../presentation/views/attendees/register.php";
    }

    public function register(FindEventAttendeeByEmail $findEventAttendeeByEmail, GetEventDetails $getEventDetails, RegisterAttendee $registerAttendee): void
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

        $existingAttendees = $findEventAttendeeByEmail->execute($eventId, $email);
        
        if(count($existingAttendees)){
            $errors[] = "Email is already registered in this event.";
        }

        $event = $getEventDetails->execute($eventId);


        if($event->availableTicketCount() < 1){
            $errors[] = "All tickets are sold out.";
        }

        if($event->getBookingDeadline()->getTimestamp()< (new DateTime())->getTimestamp()){
            $errors[] = "Booking Deadline Expired";
        }

        if(count($errors)){
            http_response_code(400);
            echo json_encode(['errors'=> $errors]);
            exit;
        }

        try {
            $registerAttendee->execute($event->getId(), $name, $email, new DateTime());
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

    public function list(int $eventId, ListAttendeesForEvent $listAttendeesForEvent): void
    {
        try {
            $attendees = $listAttendeesForEvent->execute($eventId);
            require '/../../presentation/views/attendees/list.php';
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }


}
