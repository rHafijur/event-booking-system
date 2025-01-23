<?php

namespace Core\UseCases\Report;

use Core\Repositories\AttendeeRepository;

class GenerateEventReport
{
    private AttendeeRepository $attendeeRepository;

    public function __construct(AttendeeRepository $attendeeRepository)
    {
        $this->attendeeRepository = $attendeeRepository;
    }

    public function execute(int $eventId): string
    {
        $attendees = $this->attendeeRepository->findByEventId($eventId);

        $csvData = "Name,Email\n";
        foreach ($attendees as $attendee) {
            $csvData .= "{$attendee->getName()},{$attendee->getEmail()}\n";
        }

        return $csvData;
    }
}
