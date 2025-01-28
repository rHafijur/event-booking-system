<?php

namespace Core\Usecases\Event;

use Core\Repositories\EventRepository;

class GetAllAvailableEventList
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function execute(): array
    {
        return $this->eventRepository->findAllAvailable();
    }
}
