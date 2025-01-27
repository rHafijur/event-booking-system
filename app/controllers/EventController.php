<?php

namespace App\Controllers;

use Core\UseCases\Attendee\ListAttendeesForEvent;
use Core\UseCases\Event\CreateEvent;
use Core\UseCases\Event\UpdateEvent;
use Core\UseCases\Event\DeleteEvent;
use Core\UseCases\Event\OrganizerListEvents;
use Core\UseCases\Event\GetEventDetails;
use Core\UseCases\Event\ListEvents;
use Core\UseCases\Report\GenerateEventReport;
use Core\Usecases\User\GetAuthUser;
use Core\Usecases\User\GetUserById;

class EventController
{
    private CreateEvent $createEvent;
    private UpdateEvent $updateEvent;
    private DeleteEvent $deleteEvent;
    private OrganizerListEvents $organizerListEvents;
    private ListEvents $listEvents;
    private GetEventDetails $getEventDetails;
    private ListAttendeesForEvent $listAttendeesForEvent;
    private GetAuthUser $getAuthUser;
    private GenerateEventReport $generateEventReport;
    private GetUserById $getUserById;

    public function __construct(
        CreateEvent $createEvent,
        UpdateEvent $updateEvent,
        DeleteEvent $deleteEvent,
        OrganizerListEvents $organizerListEvents,
        ListEvents $listEvents,
        GetEventDetails $getEventDetails,
        ListAttendeesForEvent $listAttendeesForEvent,
        GetAuthUser $getAuthUser,
        GenerateEventReport $generateEventReport,
        GetUserById $getUserById
    ) {
        $this->createEvent = $createEvent;
        $this->updateEvent = $updateEvent;
        $this->deleteEvent = $deleteEvent;
        $this->organizerListEvents = $organizerListEvents;
        $this->listEvents = $listEvents;
        $this->getEventDetails = $getEventDetails;
        $this->listAttendeesForEvent = $listAttendeesForEvent;
        $this->getAuthUser = $getAuthUser;
        $this->generateEventReport = $generateEventReport;
        $this->getUserById = $getUserById;
    }

    public function createView(): void
    {
        require __DIR__."/../../presentation/views/events/create.php";
    }

    public function create(): void
    {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $capacity = $_POST['capacity'];
        $eventDate = $_POST['event_date'];
        $bookingDeadline = $_POST['booking_deadline'];
        $venue = $_POST['venue'];
        $ticketPrice = $_POST['ticket_price'];
        $createdAt = (new \DateTime())->format('Y-m-d H:i:s');

        $errors = [];

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
        }

        if (empty($bookingDeadline)) {
            $errors[] = "Booking deadline is required.";
        } elseif (!strtotime($bookingDeadline)) {
            $errors[] = "Booking deadline must be a valid date.";
        } elseif (strtotime($bookingDeadline) > strtotime($eventDate)) {
            $errors[] = "Booking deadline cannot be after the event date.";
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

        if(isset($_FILES['image'])){
            try{
                $image = uploadImage('storage/images/','image');
            }catch(\Exception $e){
                $errors[] = $e->getMessage();
            }
        }else{
            $image = null;
        }

        if(count($errors)){
            http_response_code(400);
            echo json_encode(['errors'=> $errors]);
            exit;
        }

        $user = $this->getAuthUser->execute();

        try {
            $this->createEvent->execute($name, $description, $capacity, $eventDate, $bookingDeadline, $image, $venue, $ticketPrice, $user->getId(), $createdAt);
            header('Location: /events');
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function edit(int $id): void
    {
        try {
            $user = $this->getAuthUser->execute();
            $event = $this->getEventDetails->execute($id, $user->getId());
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
        require __DIR__."/../../presentation/views/events/edit.php";
    }

    public function update(int $eventId): void
    {
        $errors = [];
        $previousImage = null;
        try {
            $user = $this->getAuthUser->execute();
            $event = $this->getEventDetails->execute($eventId, $user->getId());
            $previousImage = $event->getImage();
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

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
        }

        if (empty($bookingDeadline)) {
            $errors[] = "Booking deadline is required.";
        } elseif (!strtotime($bookingDeadline)) {
            $errors[] = "Booking deadline must be a valid date.";
        } elseif (strtotime($bookingDeadline) > strtotime($eventDate)) {
            $errors[] = "Booking deadline cannot be after the event date.";
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

        if(isset($_FILES['image'])){
            try{
                $image = uploadImage('storage/images/','image');
            }catch(\Exception $e){
                $errors[] = $e->getMessage();
            }
        }else{
            $image = null;
        }

        if(count($errors)){
            http_response_code(400);
            echo json_encode(['errors'=> $errors]);
            exit;
        }

        try {
            $this->updateEvent->execute($eventId, $name, $description, $capacity, $eventDate, $bookingDeadline, $image, $venue, $ticketPrice);
            
            if($previousImage){
                try{
                    deleteFile($previousImage);
                }catch(\Exception $_){}
            }
            header('Location: /events');
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete(int $eventId): void
    {
        try {
            $user = $this->getAuthUser->execute();
            $event = $this->getEventDetails->execute($eventId, $user->getId());
            $this->deleteEvent->execute($event->getId());
            setFlashMessage('success', "Event deleted successfully");
            header('Location: /events');
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function list(): void
    {
        $page = $_GET['page'] ?? 1;
        $pageSize = $_GET['page_size'] ?? 10;
        $search = $_GET['search'] ?? null;
        $orderBy = $_GET['order_by']?? null;

        $user = $this->getAuthUser->execute();

        try {
            if($user->isAdmin()){
                $res = $this->listEvents->execute($page, $pageSize, $search, $orderBy);
            }else{
                $res = $this->organizerListEvents->execute($user->getId(),$page, $pageSize, $search, $orderBy);
            }
            
            [$events, $totalRecords, $totalPages, $currentPage] = array_values($res);
            require __DIR__.'/../../presentation/views/events/list.php';
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function details(int $eventId): void
    {
        try {
            $user = $this->getAuthUser->execute();

            if($user->isAdmin()){
                $event = $this->getEventDetails->execute($eventId);
            }else{
                $event = $this->getEventDetails->execute($eventId, $user->getId());
            }
            $attendees = $this->listAttendeesForEvent->execute($eventId);
            require __DIR__.'/../../presentation/views/events/details.php';
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function downloadAttendeesReport(int $eventId): void
    {
        try {
            $user = $this->getAuthUser->execute();

            if($user->isAdmin()){
                $event = $this->getEventDetails->execute($eventId);
            }else{
                $event = $this->getEventDetails->execute($eventId, $user->getId());
            }
            $filename = strtolower(str_replace(' ', '_', $event->getName())).(new \DateTime())->format('d_m_Y_H_i_s').".csv";
            $data = $this->generateEventReport->execute($eventId);

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

    public function listForApi(): void
    {
        $page = $_GET['page'] ?? 1;
        $pageSize = $_GET['page_size'] ?? 10;
        $search = $_GET['search'] ?? null;
        $orderBy = $_GET['order_by']?? null;

        header("Content-type: application/json; charset=utf-8");

        try{
            $res = $this->listEvents->execute($page, $pageSize, $search, $orderBy);
            [$events, $totalRecords, $totalPages, $currentPage] = array_values($res);

            $data = [];

            foreach($events as $event){
                $attendees = $this->listAttendeesForEvent->execute($event->getId());
                $event->setAttendeeCount(count($attendees));
                $attendeeData = [];
                foreach($attendees as $attendee){
                    $attendeeData[] = [
                        'id' => $attendee->getId(),
                        'name' => $attendee->getName(),
                        'email' => $attendee->getEmail(),
                    ];
                }

                $organizer = $this->getUserById->execute($event->getOrganizerId());

                $data[] = [
                    'id' => $event->getId(),
                    'name' => $event->getName(),
                    'description' => $event->getDescription(),
                    'capacity' => $event->getCapacity(),
                    'venue' => $event->getVenue(),
                    'registration_fee' => $event->getTicketPrice(),
                    'event_date' => $event->getEventDate()->format('d/M/Y'),
                    'booking_deadline' => $event->getBookingDeadline()->format('d/M/Y'),
                    'created_at' => $event->getCreatedAt()->format('d/M/Y h:i:s'),
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
