<?php

namespace Core\Entities;

class Attendee
{
    private int $id;
    private string $name;
    private string $email;
    private int $eventId;

    public function __construct(string $name, string $email, int $eventId)
    {
        $this->name = $name;
        $this->email = $email;
        $this->eventId = $eventId;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getEventId(): int { return $this->eventId; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setEventId(int $eventId): void { $this->eventId = $eventId; }
}
