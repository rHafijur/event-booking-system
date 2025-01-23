<?php

namespace App\Controllers;

use Core\UseCases\Attendee\ListAttendeesForEvent;
use Core\UseCases\Event\CreateEvent;
use Core\UseCases\Event\UpdateEvent;
use Core\UseCases\Event\DeleteEvent;
use Core\UseCases\Event\ListEvents;
use Core\UseCases\Event\GetEventDetails;

class EventController
{
    private CreateEvent $createEvent;
    private UpdateEvent $updateEvent;
    private DeleteEvent $deleteEvent;
    private ListEvents $listEvents;
    private GetEventDetails $getEventDetails;
    private ListAttendeesForEvent $listAttendeesForEvent;

    public function __construct(
        CreateEvent $createEvent,
        UpdateEvent $updateEvent,
        DeleteEvent $deleteEvent,
        ListEvents $listEvents,
        GetEventDetails $getEventDetails,
        ListAttendeesForEvent $listAttendeesForEvent
    ) {
        $this->createEvent = $createEvent;
        $this->updateEvent = $updateEvent;
        $this->deleteEvent = $deleteEvent;
        $this->listEvents = $listEvents;
        $this->getEventDetails = $getEventDetails;
        $this->listAttendeesForEvent = $listAttendeesForEvent;
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
        $eventDate = $_POST['venue'];
        $createdAt = (new \DateTime())->format('Y-m-d H:i:s');

        try {
            $this->createEvent->execute($name, $description, $capacity, $eventDate, $bookingDeadline, null, $venue, $ticketPrice, 0, $createdAt);
            header('Location: /events');
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function edit(int $id): void
    {
        $event = $this->getEventDetails->execute($id);
        require __DIR__."/../../presentation/views/events/edit.php";
    }

    public function update(int $eventId): void
    {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $capacity = $_POST['capacity'];
        $eventDate = $_POST['event_date'];
        $bookingDeadline = $_POST['booking_deadline'];
        $venue = $_POST['venue'];
        $ticketPrice = $_POST['ticket_price'];
        $eventDate = $_POST['venue'];

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

        try {
            $events = $this->listEvents->execute($page, $pageSize);
            require __DIR__.'/../../presentation/views/events/list.php';
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function details(int $eventId): void
    {
        try {
            $event = $this->getEventDetails->execute($eventId);
            $attendees = $this->listAttendeesForEvent->execute($eventId);
            require __DIR__.'/../../presentation/views/events/details.php';
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
