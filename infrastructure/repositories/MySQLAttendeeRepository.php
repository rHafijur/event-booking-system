<?php

namespace Infrastructure\Repositories;

use Core\Repositories\AttendeeRepository;
use Core\Entities\Attendee;
use PDO;

class MySQLAttendeeRepository implements AttendeeRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByEventId(int $eventId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM attendees WHERE event_id = :event_id");
        $stmt->execute(['event_id' => $eventId]);
        $rows = $stmt->fetchAll();

        return array_map([$this, 'mapToEntity'], $rows);
    }

    public function register(Attendee $attendee): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO attendees (event_id, name, email)
            VALUES (:event_id, :name, :email)
        ");
        $stmt->execute([
            'event_id' => $attendee->getEventId(),
            'name' => $attendee->getName(),
            'email' => $attendee->getEmail(),
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function deleteByEventId(int $eventId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM attendees WHERE event_id = :event_id");
        return $stmt->execute(['event_id' => $eventId]);
    }

    private function mapToEntity(array $row): Attendee
    {
        $attendee = new Attendee(
            $row['event_id'],
            $row['name'],
            $row['email'],
        );

        $attendee->setId($row['id']);

        return $attendee;
    }
}
