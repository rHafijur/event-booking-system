<?php

namespace Core\Usecases\Event;

use Core\Repositories\EventRepository;

class AdminListEvents
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function execute(int $page, int $pageSize, ?string $search, ?string $orderBy): array
    {
        return $this->eventRepository->findAll($page, $pageSize, $search, $orderBy);
    }
}
