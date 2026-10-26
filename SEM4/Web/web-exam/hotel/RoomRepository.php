<?php
/**
 * Single Responsibility: all database operations for the HotelRoom table.
 */
require_once __DIR__ . '/db.php';

class RoomRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getDB();
    }

    /**
     * Returns rooms that have NO overlapping reservations for [checkIn, checkOut).
     * Overlap condition: existingCheckIn < requestedCheckOut AND existingCheckOut > requestedCheckIn
     */
    public function getAvailableRooms(string $checkIn, string $checkOut): array {
        $sql = "SELECT * FROM HotelRoom
                WHERE id NOT IN (
                    SELECT DISTINCT roomId FROM Reservation
                    WHERE checkInDate < ? AND checkOutDate > ?
                )
                ORDER BY roomNumber";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$checkOut, $checkIn]);
        return $stmt->fetchAll();
    }

    public function getTotalCount(): int {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM HotelRoom")->fetchColumn();
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM HotelRoom WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
