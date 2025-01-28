<?php

namespace Core\Usecases\Event;

use Core\Repositories\EventRepository;
use Core\Entities\Event;

class CreateEvent
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function execute(string $name, string $description, int $capacity, string $eventDate, string $bookingDeadline, ?string $image, string $venue, float $ticketPrice, int $organizerId, string $createdAt): Event
    {
        $event = new Event($name, $description, $capacity, new \DateTime($eventDate), new \DateTime($bookingDeadline), $image, $venue, $ticketPrice, $organizerId, new \DateTime($createdAt));

        $eventId = $this->eventRepository->create($event);
        $event->setId($eventId);

        return $event;
    }
}
