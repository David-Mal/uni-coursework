<?php

class RoomService
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getTotalRoomCount(): int
    {
        return (int) $this->conn->query('SELECT COUNT(*) FROM HotelRoom')->fetchColumn();
    }

    public function getAvailableRooms(string $checkIn, string $checkOut): array
    {
        $stmt = $this->conn->prepare('
            SELECT * FROM HotelRoom
            WHERE id NOT IN (
                SELECT roomId FROM Reservation
                WHERE checkInDate < :checkOut AND checkOutDate > :checkIn
            )
            ORDER BY roomNumber
        ');
        $stmt->execute([
            ':checkIn' => $checkIn,
            ':checkOut' => $checkOut,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoomById(int $roomId): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM HotelRoom WHERE id = :id');
        $stmt->execute([':id' => $roomId]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        return $room ?: null;
    }

    public function isRoomAvailable(int $roomId, string $checkIn, string $checkOut): bool
    {
        $stmt = $this->conn->prepare('
            SELECT COUNT(*) FROM Reservation
            WHERE roomId = :roomId
            AND checkInDate < :checkOut AND checkOutDate > :checkIn
        ');
        $stmt->execute([
            ':roomId' => $roomId,
            ':checkIn' => $checkIn,
            ':checkOut' => $checkOut,
        ]);

        return (int) $stmt->fetchColumn() === 0;
    }
}
