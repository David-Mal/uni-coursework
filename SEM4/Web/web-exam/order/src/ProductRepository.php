<?php

class ProductRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        return $this->db->query('SELECT id, name, price FROM Product ORDER BY name')->fetchAll();
    }

    /**
     * @param int[] $ids
     */
    public function findByIds(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("SELECT id, name, price FROM Product WHERE id IN ($placeholders)");
        $stmt->execute(array_values($ids));

        return $stmt->fetchAll();
    }
}
