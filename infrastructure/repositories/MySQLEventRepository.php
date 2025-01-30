<?php

namespace Infrastructure\Repositories;

use Core\Repositories\EventRepository;
use Core\Entities\Event;
use DateTime;
use PDO;

class MySQLEventRepository implements EventRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findById(int $id, ?int $organizerId = null): ?Event
    {
        $params = ['id' => $id];
        $query = "SELECT events.*, COUNT(attendees.id) as attendee_count FROM events LEFT JOIN attendees on events.id = attendees.event_id GROUP BY events.id HAVING events.id = :id";

        if($organizerId){
            $query.=" AND events.organizer_id = :organizer_id";
            $params['organizer_id'] = $organizerId;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ? $this->mapToEntity($row) : null;
    }

    public function findAll(int $page, int $limit, ?string $search, ?string $orderBy): array
    {
        return $this->_commonFindAll(null, $page, $limit, $search, $orderBy);
    }
    public function findAllAvailable(): array
    {
        $now = (new DateTime())->format('Y-m-d H:i:s');
        $stmt = $this->db->prepare("SELECT events.*, COUNT(attendees.id) as attendee_count FROM events LEFT JOIN attendees on events.id = attendees.event_id WHERE booking_deadline >= :now GROUP BY events.id HAVING attendee_count < events.capacity ORDER BY events.event_date DESC");
        $stmt->bindValue(':now', $now, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return array_map([$this, 'mapToEntity'], $rows);
    }
    public function findAllByOrganizerId(int $organizerId, int $page, int $limit, ?string $search, ?string $orderBy): array
    {
        return $this->_commonFindAll($organizerId, $page, $limit, $search, $orderBy);
    }

    private function _commonFindAll(?int $organizerId, int $page, int $limit, ?string $search, ?string $orderBy): array
    {
        $offset = ($page - 1) * $limit;

        $query = "SELECT events.* FROM events ";

        $countQuery = "SELECT COUNT(events.id) AS total FROM events ";

        $organizerCondition = $organizerId? "WHERE events.organizer_id = :organizer_id" : "";

        if (!empty($search)) {
            if($organizerId){
                $searchCondition = "LEFT JOIN attendees on attendees.event_id = events.id $organizerCondition AND (events.name LIKE :search_name OR events.description LIKE :search_description OR events.venue LIKE :search_venue OR attendees.name LIKE :search_attendee_name OR attendees.email LIKE :search_attendee_email)";
            }else{
                $searchCondition = "LEFT JOIN attendees on attendees.event_id = events.id WHERE (events.name LIKE :search_name OR events.description LIKE :search_description OR events.venue LIKE :search_venue OR attendees.name LIKE :search_attendee_name OR attendees.email LIKE :search_attendee_email)";
            }
            $query .= $searchCondition;
            $countQuery .= $searchCondition;
        }elseif($organizerId){
            $query .= $organizerCondition;
            $countQuery .= $organizerCondition;
        }

        if (!empty($orderBy)) {
            [$column, $direction] = explode('-', $orderBy, 2) + [null, null];
            $allowedColumns = ['name', 'event_date', 'capacity'];
            $allowedDirections = ['ASC', 'DESC'];

            if (in_array($column, $allowedColumns, true) && in_array($direction, $allowedDirections, true)) {
                $query .= " ORDER BY $column $direction";
            }
        }

        $query .= " GROUP BY events.id LIMIT :offset, :limit";


        $countStmt = $this->db->prepare($countQuery);
        if($organizerId){
            $countStmt->bindValue(':organizer_id', $organizerId, PDO::PARAM_INT);
        }
        if (!empty($search)) {
            $countStmt->bindValue(':search_name', '%' . $search . '%', PDO::PARAM_STR);
            $countStmt->bindValue(':search_description', '%' . $search . '%', PDO::PARAM_STR);
            $countStmt->bindValue(':search_venue', '%' . $search . '%', PDO::PARAM_STR);
            $countStmt->bindValue(':search_attendee_name', '%' . $search . '%', PDO::PARAM_STR);
            $countStmt->bindValue(':search_attendee_email', '%' . $search . '%', PDO::PARAM_STR);
        }
        $countStmt->execute();
        $totalRecords = (int)$countStmt->fetchColumn();

        $stmt = $this->db->prepare($query);
        if($organizerId){
            $stmt->bindValue(':organizer_id', $organizerId, PDO::PARAM_INT);
        }
        if (!empty($search)) {
            $stmt->bindValue(':search_name', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':search_description', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':search_venue', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':search_attendee_name', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':search_attendee_email', '%' . $search . '%', PDO::PARAM_STR);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        $events = array_map([$this, 'mapToEntity'], $rows);

        return [
            'events' => $events,
            'totalRecords' => $totalRecords,
            'totalPages' => ceil($totalRecords / $limit),
            'currentPage' => $page
        ];
    }

    public function create(Event $event): int
    {
        $stmt = $this->db->prepare("INSERT INTO events (name, description, capacity, event_date, booking_deadline, image, venue, ticket_price, organizer_id, created_at) VALUES (:name, :description, :capacity, :event_date, :booking_deadline, :image, :venue, :ticket_price, :organizer_id, :created_at)");
        $stmt->execute([
            'name' => $event->getName(),
            'description' => $event->getDescription(),
            'capacity' => $event->getCapacity(),
            'event_date' => $event->getEventDate()->format('Y-m-d H:i:s'),
            'booking_deadline' => $event->getBookingDeadline()->format('Y-m-d H:i:s'),
            'image' => $event->getImage(),
            'venue' => $event->getVenue(),
            'ticket_price' => $event->getTicketPrice(),
            'organizer_id' => $event->getOrganizerId(),
            'created_at' => $event->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(Event $event): bool
    {
        $stmt = $this->db->prepare("UPDATE events SET name = :name, description = :description, capacity = :capacity, event_date = :event_date, booking_deadline = :booking_deadline, image = :image, venue = :venue, ticket_price = :ticket_price WHERE id = :id");
        return $stmt->execute([
            'name' => $event->getName(),
            'description' => $event->getDescription(),
            'capacity' => $event->getCapacity(),
            'event_date' => $event->getEventDate()->format('Y-m-d H:i:s'),
            'booking_deadline' => $event->getBookingDeadline()->format('Y-m-d H:i:s'),
            'image' => $event->getImage(),
            'venue' => $event->getVenue(),
            'ticket_price' => $event->getTicketPrice(),
            'id' => $event->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    private function mapToEntity(array $row): Event
    {
        $event = new Event($row['name'], $row['description'], $row['capacity'],  new DateTime($row['event_date']), new DateTime($row['booking_deadline']), $row['image'], $row['venue'], $row['ticket_price'], $row['organizer_id'], new \DateTime($row['created_at']));
        $event->setId($row['id']);
        if(isset($row['attendee_count'])){
            $event->setAttendeeCount($row['attendee_count']);
        }
        return $event;
    }
}
