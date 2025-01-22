<?php

namespace Core\Entities;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $role;

    public function __construct(string $name, string $email, string $password, string $role = 'user')
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getRole(): string { return $this->role; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function setRole(string $role): void { $this->role = $role; }

    // Utility method
    public function isAdmin(): bool { return $this->role === 'admin'; }
}
