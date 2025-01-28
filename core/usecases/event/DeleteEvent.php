<?php

namespace Core\Usecases\Event;

use Core\Repositories\EventRepository;
use Exception;

class DeleteEvent
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function execute(int $eventId): void
    {
        $event = $this->eventRepository->findById($eventId);
        if (!$event) {
            throw new Exception("Event not found");
        }

        $this->eventRepository->delete($eventId);
    }
}
