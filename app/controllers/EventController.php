<?php

namespace App\Controllers;

use DateTime;
use Core\Usecases\Event\ListEvents;
use Core\Usecases\User\GetAuthUser;
use Core\Usecases\User\GetUserById;
use Core\Usecases\Event\CreateEvent;
use Core\Usecases\Event\DeleteEvent;
use Core\Usecases\Event\UpdateEvent;
use Core\Usecases\Event\GetEventDetails;
use Core\Usecases\Event\OrganizerListEvents;
use Core\Usecases\Report\GenerateEventReport;
use Core\Usecases\Attendee\ListAttendeesForEvent;

class EventController
{
    function __construct(private GetAuthUser $getAuthUser){}

    public function createView(): void
    {
        $user = $this->getAuthUser->execute();
        require __DIR__."/../../presentation/views/events/create.php";
    }

    public function create(CreateEvent $createEvent): void
    {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $capacity = $_POST['capacity'];
        $eventDate = $_POST['event_date'];
        $bookingDeadline = $_POST['booking_deadline'];
        $venue = $_POST['venue'];
        $ticketPrice = $_POST['ticket_price'];
        $createdAt = (new DateTime())->format('Y-m-d H:i:s');

        // var_dump($_POST);
        // http_response_code(400);
        // exit;

        $errors = [];

        $now = new DateTime();
        $today = $now->format('Y-m-d');

        if (empty($name)) {
            $errors[] = "Event name is required.";
        } elseif (strlen($name) > 100) {
            $errors[] = "Event name should not exceed 100 characters.";
        }

        if (empty($description)) {
            $errors[] = "Description is required.";
        } elseif (strlen($description) > 1000) {
            $errors[] = "Description should not exceed 1000 characters.";
        }

        if (empty($capacity)) {
            $errors[] = "Capacity is required.";
        } elseif (!is_numeric($capacity) || intval($capacity) <= 0) {
            $errors[] = "Capacity must be a positive number.";
        }

        if (empty($eventDate)) {
            $errors[] = "Event date is required.";
        } elseif (!strtotime($eventDate)) {
            $errors[] = "Event date must be a valid date.";
        } else {
            $eventDateTime = new DateTime($eventDate);
        
            if ($eventDateTime->format('Y-m-d') < $today) {
                $errors[] = "Event date cannot be in the past.";
            }
        }
        
        if (empty($bookingDeadline)) {
            $errors[] = "Booking deadline is required.";
        } elseif (!strtotime($bookingDeadline)) {
            $errors[] = "Booking deadline must be a valid date.";
        } else {
            $bookingDeadlineTime = new DateTime($bookingDeadline);
        
            if ($bookingDeadlineTime < $now) {
                $errors[] = "Booking deadline cannot be in the past.";
            }
        
            if ($bookingDeadlineTime->format('Y-m-d') > $eventDateTime->format('Y-m-d')) {
                $errors[] = "Booking deadline cannot be after the event date.";
            }
        }

        if (empty($venue)) {
            $errors[] = "Venue is required.";
        } elseif (strlen($venue) > 255) {
            $errors[] = "Venue should not exceed 255 characters.";
        }

        if (empty($ticketPrice)) {
            $errors[] = "Ticket price is required.";
        } elseif (!is_numeric($ticketPrice) || floatval($ticketPrice) < 0) {
            $errors[] = "Ticket price must be a non-negative number.";
        }

        try{
            $image = uploadImage('storage/images/','image');
        }catch(\Exception $e){
            $errors[] = $e->getMessage();
        }

        if(count($errors)){
            http_response_code(400);
            echo json_encode(['errors'=> $errors]);
            exit;
        }

        $user = $this->getAuthUser->execute();

        try {
            $createEvent->execute($name, $description, $capacity, $eventDate, $bookingDeadline, $image, $venue, $ticketPrice, $user->getId(), $createdAt);
            echo 'okay';
        } catch (\Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }

    public function edit(int $id, GetEventDetails $getEventDetails): void
    {
        try {
            $user = $this->getAuthUser->execute();
            $event = $getEventDetails->execute($id, $user->getId());
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
        require __DIR__."/../../presentation/views/events/edit.php";
    }

    public function update(int $eventId, GetEventDetails $getEventDetails, UpdateEvent $updateEvent): void
    {
        $errors = [];
        $previousImage = null;
        try {
            $user = $this->getAuthUser->execute();
            $event = $getEventDetails->execute($eventId, $user->getId());
            $previousImage = $event->getImage();
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        $now = new DateTime();
        $today = $now->format('Y-m-d');

        $name = $_POST['name'];
        $description = $_POST['description'];
        $capacity = $_POST['capacity'];
        $eventDate = $_POST['event_date'];
        $bookingDeadline = $_POST['booking_deadline'];
        $venue = $_POST['venue'];
        $ticketPrice = $_POST['ticket_price'];


        if (empty($name)) {
            $errors[] = "Event name is required.";
        } elseif (strlen($name) > 100) {
            $errors[] = "Event name should not exceed 100 characters.";
        }

        if (empty($description)) {
            $errors[] = "Description is required.";
        } elseif (strlen($description) > 1000) {
            $errors[] = "Description should not exceed 1000 characters.";
        }

        if (empty($capacity)) {
            $errors[] = "Capacity is required.";
        } elseif (!is_numeric($capacity) || intval($capacity) <= 0) {
            $errors[] = "Capacity must be a positive number.";
        }

        if (empty($eventDate)) {
            $errors[] = "Event date is required.";
        } elseif (!strtotime($eventDate)) {
            $errors[] = "Event date must be a valid date.";
        } else {
            $eventDateTime = new DateTime($eventDate);
        
            if ($eventDateTime->format('Y-m-d') < $today) {
                $errors[] = "Event date cannot be in the past.";
            }
        }
        
        if (empty($bookingDeadline)) {
            $errors[] = "Booking deadline is required.";
        } elseif (!strtotime($bookingDeadline)) {
            $errors[] = "Booking deadline must be a valid date.";
        } else {
            $bookingDeadlineTime = new DateTime($bookingDeadline);
        
            if ($bookingDeadlineTime < $now) {
                $errors[] = "Booking deadline cannot be in the past.";
            }
        
            if ($bookingDeadlineTime->format('Y-m-d') > $eventDateTime->format('Y-m-d')) {
                $errors[] = "Booking deadline cannot be after the event date.";
            }
        }

        if (empty($venue)) {
            $errors[] = "Venue is required.";
        } elseif (strlen($venue) > 255) {
            $errors[] = "Venue should not exceed 255 characters.";
        }

        if (empty($ticketPrice)) {
            $errors[] = "Ticket price is required.";
        } elseif (!is_numeric($ticketPrice) || floatval($ticketPrice) < 0) {
            $errors[] = "Ticket price must be a non-negative number.";
        }

        $shouldDeleteThePreviousImage = false;

        if(isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){
            try{
                $image = uploadImage('storage/images/','image');
                $shouldDeleteThePreviousImage = true;
            }catch(\Exception $e){
                $errors[] = $e->getMessage();
            }
        }else{
            $image = $previousImage;
        }

        if(count($errors)){
            http_response_code(400);
            echo json_encode(['errors'=> $errors]);
            exit;
        }

        try {
            $updateEvent->execute($eventId, $name, $description, $capacity, $eventDate, $bookingDeadline, $image, $venue, $ticketPrice);
            
            if($shouldDeleteThePreviousImage){
                try{
                    deleteFile($previousImage);
                }catch(\Exception $_){}
            }
            echo 'okay';
        } catch (\Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete(int $eventId, GetEventDetails $getEventDetails, DeleteEvent $deleteEvent): void
    {
        try {
            $user = $this->getAuthUser->execute();
            $event = $getEventDetails->execute($eventId, $user->getId());
            $deleteEvent->execute($event->getId());
            try{
                deleteFile($event->getImage());
            }catch(\Exception $_){}
            setFlashMessage('success', "Event deleted successfully");
            $location = url('/events');
            header("Location: $location");
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function list(ListEvents $listEvents, OrganizerListEvents $organizerListEvents): void
    {
        $page = $_GET['page'] ?? 1;
        $pageSize = $_GET['page_size'] ?? 10;
        $search = $_GET['search'] ?? null;
        $orderBy = $_GET['order_by']?? null;

        $user = $this->getAuthUser->execute();

        try {
            if($user->isAdmin()){
                $res = $listEvents->execute($page, $pageSize, $search, $orderBy);
            }else{
                $res = $organizerListEvents->execute($user->getId(),$page, $pageSize, $search, $orderBy);
            }
            
            [$events, $totalRecords, $totalPages, $currentPage] = array_values($res);
            require __DIR__.'/../../presentation/views/events/list.php';
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function details(int $eventId, GetEventDetails $getEventDetails, ListAttendeesForEvent $listAttendeesForEvent): void
    {
        try {
            $user = $this->getAuthUser->execute();

            if($user->isAdmin()){
                $event = $getEventDetails->execute($eventId);
            }else{
                $event = $getEventDetails->execute($eventId, $user->getId());
            }
            $attendees = $listAttendeesForEvent->execute($eventId);
            require __DIR__.'/../../presentation/views/events/details.php';
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function downloadAttendeesReport(int $eventId, GetEventDetails $getEventDetails, GenerateEventReport $generateEventReport): void
    {
        try {
            $user = $this->getAuthUser->execute();

            if($user->isAdmin()){
                $event = $getEventDetails->execute($eventId);
            }else{
                $event = $getEventDetails->execute($eventId, $user->getId());
            }
            $filename = strtolower(str_replace(' ', '_', $event->getName())).(new \DateTime())->format('d_m_Y_H_i_s').".csv";
            $data = $generateEventReport->execute($eventId);

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'"');

            $output = fopen('php://output', 'w');

            foreach ($data as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function listForApi(ListEvents $listEvents, ListAttendeesForEvent $listAttendeesForEvent, GetUserById $getUserById): void
    {
        $page = $_GET['page'] ?? 1;
        $pageSize = $_GET['page_size'] ?? 10;
        $search = $_GET['search'] ?? null;
        $orderBy = $_GET['order_by']?? null;

        header("Content-type: application/json; charset=utf-8");

        try{
            $res = $listEvents->execute($page, $pageSize, $search, $orderBy);
            [$events, $totalRecords, $totalPages, $currentPage] = array_values($res);

            $data = [];

            foreach($events as $event){
                $attendees = $listAttendeesForEvent->execute($event->getId());
                $event->setAttendeeCount(count($attendees));
                $attendeeData = [];
                foreach($attendees as $attendee){
                    $attendeeData[] = [
                        'id' => $attendee->getId(),
                        'name' => $attendee->getName(),
                        'email' => $attendee->getEmail(),
                        'registered_at' => $attendee->getRegisteredAt()->format(DateTime::ATOM),
                    ];
                }

                $organizer = $getUserById->execute($event->getOrganizerId());

                $data[] = [
                    'id' => $event->getId(),
                    'name' => $event->getName(),
                    'description' => $event->getDescription(),
                    'capacity' => $event->getCapacity(),
                    'venue' => $event->getVenue(),
                    'registration_fee' => $event->getTicketPrice(),
                    'event_date' => $event->getEventDate()->format(DateTime::ATOM),
                    'booking_deadline' => $event->getBookingDeadline()->format(DateTime::ATOM),
                    'created_at' => $event->getCreatedAt()->format(DateTime::ATOM),
                    'attendee_count' => $event->getAttendeeCount(),
                    'organizer' => [
                        'id' => $organizer->getId(),
                        'name' => $organizer->getName(),
                        'email' => $organizer->getEmail(),
                    ],
                    'attendees' => $attendeeData
                ];
            }

            echo json_encode([
                'events' => $data,
                'total_records' => $totalRecords,
                'total_page' => $totalPages,
                'current_page' => $currentPage,
                'status' => true
            ]);

        }catch(\Exception $e){

            echo json_encode([
                'error' => $e->getMessage(),
                'status' => false
            ]);
        }

        
    }
}
