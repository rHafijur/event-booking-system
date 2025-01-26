<?php

namespace Core\UseCases\Attendee;

use Core\Repositories\AttendeeRepository;

class FindEventAttendeeByEmail
{
    private AttendeeRepository $attendeeRepository;

    public function __construct(AttendeeRepository $attendeeRepository)
    {
        $this->attendeeRepository = $attendeeRepository;
    }

    public function execute(int $eventId, string $email): array
    {
        return $this->attendeeRepository->findByEventIdAndEmail($eventId, $email);
    }
}
