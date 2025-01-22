<?php

namespace Core\UseCases\Attendee;

use Core\Repositories\AttendeeRepository;

class ListAttendeesForEvent
{
    private AttendeeRepository $attendeeRepository;

    public function __construct(AttendeeRepository $attendeeRepository)
    {
        $this->attendeeRepository = $attendeeRepository;
    }

    public function execute(int $eventId): array
    {
        return $this->attendeeRepository->findByEventId($eventId);
    }
}
