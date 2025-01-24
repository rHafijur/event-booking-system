<?php

namespace Core\UseCases\Event;

use Core\Repositories\EventRepository;

class ListEvents
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function execute(int $page, int $pageSize, ?string $search = null, ?array $filters = []): array
    {
        return $this->eventRepository->findAll($page, $pageSize);
    }
}
