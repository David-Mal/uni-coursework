<?php

class PropertyService
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getAllProperties(): array
    {
        $stmt = $this->conn->query('SELECT id, address, description FROM Property ORDER BY address');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchByDescription(string $query): array
    {
        $stmt = $this->conn->prepare('
            SELECT id, address, description
            FROM Property
            WHERE description LIKE :query
            ORDER BY address
        ');
        $stmt->execute([':query' => '%' . $query . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPropertyById(int $propertyId): ?array
    {
        $stmt = $this->conn->prepare('SELECT id, address, description FROM Property WHERE id = :id');
        $stmt->execute([':id' => $propertyId]);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);

        return $property ?: null;
    }

    public function getUserProperties(int $userId): array
    {
        $stmt = $this->conn->prepare('
            SELECT p.id, p.address, p.description
            FROM Property p
            JOIN UserToProperties utp ON utp.idProperty = p.id
            WHERE utp.idUser = :userId
            ORDER BY p.address
        ');
        $stmt->execute([':userId' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPropertiesWithMultipleOwners(): array
    {
        $stmt = $this->conn->query('
            SELECT p.id, p.address, p.description, COUNT(utp.idUser) AS ownerCount
            FROM Property p
            JOIN UserToProperties utp ON utp.idProperty = p.id
            GROUP BY p.id, p.address, p.description
            HAVING COUNT(utp.idUser) > 1
            ORDER BY p.address
        ');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countOwners(int $propertyId): int
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM UserToProperties WHERE idProperty = :propertyId');
        $stmt->execute([':propertyId' => $propertyId]);

        return (int) $stmt->fetchColumn();
    }

    public function userOwnsProperty(int $userId, int $propertyId): bool
    {
        $stmt = $this->conn->prepare('
            SELECT COUNT(*) FROM UserToProperties
            WHERE idUser = :userId AND idProperty = :propertyId
        ');
        $stmt->execute([
            ':userId' => $userId,
            ':propertyId' => $propertyId,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function addPropertyToUser(int $userId, ?int $propertyId, string $address, string $description): array
    {
        $address = trim($address);
        $description = trim($description);

        if ($propertyId !== null && $propertyId > 0) {
            $property = $this->getPropertyById($propertyId);
            if ($property === null) {
                return ['success' => false, 'message' => 'Selected property does not exist.'];
            }

            if ($this->userOwnsProperty($userId, $propertyId)) {
                return ['success' => false, 'message' => 'You already own this property.'];
            }

            $this->linkUserToProperty($userId, $propertyId);

            return [
                'success' => true,
                'message' => 'Existing property linked to your account: ' . $property['address'],
            ];
        }

        if ($address === '' || $description === '') {
            return ['success' => false, 'message' => 'Address and description are required for a new property.'];
        }

        $stmt = $this->conn->prepare('INSERT INTO Property (address, description) VALUES (:address, :description)');
        $stmt->execute([
            ':address' => $address,
            ':description' => $description,
        ]);

        $newPropertyId = (int) $this->conn->lastInsertId();
        $this->linkUserToProperty($userId, $newPropertyId);

        return [
            'success' => true,
            'message' => 'New property created and linked to your account.',
        ];
    }

    public function deletePropertyForUser(int $userId, int $propertyId): array
    {
        if (!$this->userOwnsProperty($userId, $propertyId)) {
            return ['success' => false, 'message' => 'This property is not in your list.'];
        }

        $ownerCount = $this->countOwners($propertyId);

        $stmt = $this->conn->prepare('
            DELETE FROM UserToProperties
            WHERE idUser = :userId AND idProperty = :propertyId
        ');
        $stmt->execute([
            ':userId' => $userId,
            ':propertyId' => $propertyId,
        ]);

        if ($ownerCount > 1) {
            return [
                'success' => true,
                'message' => 'Property removed from your account. Other owners still keep it.',
            ];
        }

        $deleteProperty = $this->conn->prepare('DELETE FROM Property WHERE id = :propertyId');
        $deleteProperty->execute([':propertyId' => $propertyId]);

        return [
            'success' => true,
            'message' => 'Property deleted completely because you were the only owner.',
        ];
    }

    private function linkUserToProperty(int $userId, int $propertyId): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO UserToProperties (idUser, idProperty)
            VALUES (:userId, :propertyId)
        ');
        $stmt->execute([
            ':userId' => $userId,
            ':propertyId' => $propertyId,
        ]);
    }
}
