<?php

namespace Core\Usecases\Event;

use Core\Repositories\EventRepository;
use Core\Entities\Event;
use Exception;

class GetEventDetails
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function execute(int $eventId, ?int $organizerId = null): Event
    {
        $event = $this->eventRepository->findById($eventId, $organizerId);
        if (!$event) {
            throw new Exception("Event not found");
        }

        return $event;
    }
}
