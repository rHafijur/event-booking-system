<?php

namespace Core\UseCases\Attendee;

use Core\Repositories\AttendeeRepository;
use Core\Repositories\EventRepository;
use Core\Entities\Attendee;
use Exception;

class RegisterAttendee
{
    private AttendeeRepository $attendeeRepository;
    private EventRepository $eventRepository;

    public function __construct(AttendeeRepository $attendeeRepository, EventRepository $eventRepository)
    {
        $this->attendeeRepository = $attendeeRepository;
        $this->eventRepository = $eventRepository;
    }

    public function execute(int $eventId, string $name, string $email): Attendee
    {
        // Validate event
        $event = $this->eventRepository->findById($eventId);
        if (!$event) {
            throw new Exception("Event not found");
        }

        // Check capacity
        $attendees = $this->attendeeRepository->findByEventId($eventId);
        if (count($attendees) >= $event->getCapacity()) {
            throw new Exception("Event capacity reached");
        }

        // Register attendee
        $attendee = new Attendee( $eventId, $name, $email);
        $attendeeId = $this->attendeeRepository->register($attendee);
        $attendee->setId($attendeeId);

        return $attendee;
    }
}
