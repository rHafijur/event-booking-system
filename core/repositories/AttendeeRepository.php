<?php

namespace Core\Repositories;

use Core\Entities\Attendee;

interface AttendeeRepository
{
    public function findByEventId(int $eventId): array;

    public function register(Attendee $attendee): int;

    public function deleteByEventId(int $eventId): bool;
}
