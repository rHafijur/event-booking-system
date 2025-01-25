<?php

namespace App\Controllers;

use Core\UseCases\Attendee\ListAttendeesForEvent;
use Core\UseCases\Event\CreateEvent;
use Core\UseCases\Event\UpdateEvent;
use Core\UseCases\Event\DeleteEvent;
use Core\UseCases\Event\OrganizerListEvents;
use Core\UseCases\Event\GetEventDetails;
use Core\Usecases\User\GetAuthUser;

class EventController
{
    private CreateEvent $createEvent;
    private UpdateEvent $updateEvent;
    private DeleteEvent $deleteEvent;
    private OrganizerListEvents $organizerListEvents;
    private GetEventDetails $getEventDetails;
    private ListAttendeesForEvent $listAttendeesForEvent;
    private GetAuthUser $getAuthUser;

    public function __construct(
        CreateEvent $createEvent,
        UpdateEvent $updateEvent,
        DeleteEvent $deleteEvent,
        OrganizerListEvents $organizerListEvents,
        GetEventDetails $getEventDetails,
        ListAttendeesForEvent $listAttendeesForEvent,
        GetAuthUser $getAuthUser
    ) {
        $this->createEvent = $createEvent;
        $this->updateEvent = $updateEvent;
        $this->deleteEvent = $deleteEvent;
        $this->organizerListEvents = $organizerListEvents;
        $this->getEventDetails = $getEventDetails;
        $this->listAttendeesForEvent = $listAttendeesForEvent;
        $this->getAuthUser = $getAuthUser;
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

        if(count($errors)){
            http_response_code(400);
            echo json_encode(['errors'=> $errors]);
            exit;
        }

        $user = $this->getAuthUser->execute();

        try {
            $this->createEvent->execute($name, $description, $capacity, $eventDate, $bookingDeadline, null, $venue, $ticketPrice, $user->getId(), $createdAt);
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
        try {
            $user = $this->getAuthUser->execute();
            $event = $this->getEventDetails->execute($eventId, $user->getId());
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

        if(count($errors)){
            http_response_code(400);
            echo json_encode(['errors'=> $errors]);
            exit;
        }

        try {
            $this->updateEvent->execute($eventId, $name, $description, $capacity, $eventDate, $bookingDeadline, null, $venue, $ticketPrice);
            header('Location: /events');
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete(int $eventId): void
    {
        try {
            $this->deleteEvent->execute($eventId);
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
            $res = $this->organizerListEvents->execute($user->getId(),$page, $pageSize, $search, $orderBy);
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
            $event = $this->getEventDetails->execute($eventId, $user->getId());
            $attendees = $this->listAttendeesForEvent->execute($eventId);
            require __DIR__.'/../../presentation/views/events/details.php';
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
