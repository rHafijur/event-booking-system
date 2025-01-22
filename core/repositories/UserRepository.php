<?php

namespace Core\Repositories;

use Core\Entities\User;

interface UserRepository
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function create(User $user): int;

    public function update(User $user): bool;

    public function delete(int $id): bool;
}
