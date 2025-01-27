<?php

namespace Core\UseCases\Report;

use Core\Repositories\AttendeeRepository;
use Core\Repositories\EventRepository;

class GenerateEventReport
{
    private AttendeeRepository $attendeeRepository;
    private EventRepository $eventRepository;

    public function __construct(AttendeeRepository $attendeeRepository)
    {
        $this->attendeeRepository = $attendeeRepository;
        // $this->eventRepository = $eventRepository;
    }

    public function execute(int $eventId): array
    {
        $attendees = $this->attendeeRepository->findByEventId($eventId);

        $data = [];
        $data[] = ['Name', 'Email'];
        foreach ($attendees as $attendee) {
            $data[] = [$attendee->getName(), $attendee->getEmail()];
        }

        return $data;
    }
}
