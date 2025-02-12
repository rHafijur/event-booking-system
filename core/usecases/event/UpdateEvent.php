<?php

namespace Core\Usecases\Event;

use Core\Repositories\EventRepository;
use Core\Entities\Event;
use Exception;

class UpdateEvent
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function execute(int $eventId, string $name, string $description, int $capacity, string $eventDate, string $bookingDeadline, ?string $image, string $venue, float $ticketPrice): Event
    {
        // Fetch the event
        $event = $this->eventRepository->findById($eventId);
        if (!$event) {
            throw new Exception("Event not found");
        }

        // Update event details
        $event->setName($name);
        $event->setDescription($description);
        $event->setCapacity($capacity);
        $event->setEventDate(new \DateTime($eventDate));
        $event->setBookingDeadline(new \DateTime($bookingDeadline));
        $event->setImage($image);
        $event->setVenue($venue);
        $event->setTicketPrice($ticketPrice);
        $event->setOrganizerId($event->getOrganizerId());
        $event->setCreatedAt($event->getCreatedAt());

        // Save the updated event
        $this->eventRepository->update($event);

        return $event;
    }
}
