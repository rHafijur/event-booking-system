<?php

namespace Core\Repositories;

use Core\Entities\Event;

interface EventRepository
{
    public function findById(int $id, ?int $organizerId = null): ?Event;

    public function findAll(int $page, int $limit): array;

    public function findAllByOrganizerId(int $organizerId, int $page, int $limit, ?string $search, ?string $orderby): array;

    public function create(Event $event): int;

    public function update(Event $event): bool;

    public function delete(int $id): bool;
}
