<?php

namespace Core\Entities;

class Event
{
    private int $id;
    private string $name;
    private string $description;
    private int $capacity;
    private \DateTime $eventDate;
    private \DateTime $bookingDeadline;
    private ?string $image;
    private string $venue;
    private float $ticketPrice;
    private int $organizerId;
    private \DateTime $createdAt;

    private ?int $attendeeCount;

    public function __construct(
        string $name,
        string $description,
        int $capacity,
        \DateTime $eventDate,
        \DateTime $bookingDeadline,
        ?string $image,
        string $venue,
        float $ticketPrice,
        int $organizerId,
        \DateTime $createdAt
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->capacity = $capacity;
        $this->eventDate = $eventDate;
        $this->bookingDeadline = $bookingDeadline;
        $this->image = $image;
        $this->venue = $venue;
        $this->ticketPrice = $ticketPrice;
        $this->organizerId = $organizerId;
        $this->createdAt = $createdAt;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getCapacity(): int { return $this->capacity; }
    public function getEventDate(): \DateTime { return $this->eventDate; }
    public function getBookingDeadline(): \DateTime { return $this->bookingDeadline; }
    public function getImage(): ?string { return $this->image; }
    public function getVenue(): string { return $this->venue; }
    public function getTicketPrice(): float { return $this->ticketPrice; }
    public function getOrganizerId(): int { return $this->organizerId; }
    public function getCreatedAt(): \DateTime { return $this->createdAt; }
    public function getAttendeeCount(): ?int { return $this->attendeeCount; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setCapacity(int $capacity): void { $this->capacity = $capacity; }
    public function setEventDate(\DateTime $eventDate): void { $this->eventDate = $eventDate; }
    public function setBookingDeadline(\DateTime $bookingDeadline): void { $this->bookingDeadline = $bookingDeadline; }
    public function setImage(?string $image): void { $this->image = $image; }
    public function setVenue(string $venue): void { $this->venue = $venue; }
    public function setTicketPrice(float $ticketPrice): void { $this->ticketPrice = $ticketPrice; }
    public function setOrganizerId(int $organizerId): void { $this->organizerId = $organizerId; }
    public function setCreatedAt(\DateTime $createdAt): void { $this->createdAt = $createdAt; }
    public function setAttendeeCount(int $attendeeCount): void { $this->attendeeCount = $attendeeCount; }

    // Utility Methods
    public function isBookingAllowed(\DateTime $currentDate = new \DateTime()): bool
    {
        return $currentDate <= $this->bookingDeadline;
    }

    public function hasPassed(\DateTime $currentDate): bool
    {
        return $currentDate > $this->eventDate;
    }
    
    public function availableTicketCount(): ?int
    {
        if($this->attendeeCount !==null){
            return $this->capacity - $this->attendeeCount;
        }

        return null;
    }
}
