<?php

class UserRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT id, username FROM User WHERE username = ?');
        $stmt->execute([$username]);

        $user = $stmt->fetch();

        return $user ?: null;
    }
}
