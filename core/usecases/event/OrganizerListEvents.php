<?php

namespace Core\UseCases\Event;

use Core\Repositories\EventRepository;

class OrganizerListEvents
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function execute(int $organizerId, int $page, int $pageSize, ?string $search = null, ?array $filters = []): array
    {
        return $this->eventRepository->findAllByOrganizerId($organizerId,$page, $pageSize);
    }
}
