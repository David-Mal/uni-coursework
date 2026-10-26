<?php

class UserService
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function findByName(string $name): ?array
    {
        $stmt = $this->conn->prepare('SELECT id, name, secretQuestion, secretAnswer FROM User WHERE name = :name');
        $stmt->execute([':name' => $name]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function verifySecretAnswer(int $userId, string $answer): bool
    {
        $stmt = $this->conn->prepare('SELECT secretAnswer FROM User WHERE id = :id');
        $stmt->execute([':id' => $userId]);
        $storedAnswer = $stmt->fetchColumn();

        if ($storedAnswer === false) {
            return false;
        }

        return strcasecmp(trim($storedAnswer), trim($answer)) === 0;
    }
}
