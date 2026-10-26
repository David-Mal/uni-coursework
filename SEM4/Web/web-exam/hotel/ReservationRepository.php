<?php
/**
 * Single Responsibility: all database operations for the Reservation table.
 */
require_once __DIR__ . '/db.php';

class ReservationRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getDB();
    }

    /**
     * Count distinct rooms booked during [checkIn, checkOut).
     * Used by PricingService to determine occupancy rate.
     */
    public function getBookedRoomCount(string $checkIn, string $checkOut): int {
        $sql = "SELECT COUNT(DISTINCT roomId) FROM Reservation
                WHERE checkInDate < ? AND checkOutDate > ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$checkOut, $checkIn]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * True if the given room has any reservation overlapping [checkIn, checkOut).
     * Prevents double-booking a room.
     */
    public function roomHasOverlap(int $roomId, string $checkIn, string $checkOut): bool {
        $sql = "SELECT COUNT(*) FROM Reservation
                WHERE roomId = ? AND checkInDate < ? AND checkOutDate > ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$roomId, $checkOut, $checkIn]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * True if the given user already has ANY reservation overlapping [checkIn, checkOut).
     * Prevents a user from having two overlapping stays.
     */
    public function userHasOverlap(int $userId, string $checkIn, string $checkOut): bool {
        $sql = "SELECT COUNT(*) FROM Reservation
                WHERE userId = ? AND checkInDate < ? AND checkOutDate > ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $checkOut, $checkIn]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(
        int $userId, int $roomId,
        string $checkIn, string $checkOut,
        int $guests, int $totalPrice
    ): bool {
        $sql = "INSERT INTO Reservation
                    (userId, roomId, checkInDate, checkOutDate, numberOfGuests, totalPrice)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $roomId, $checkIn, $checkOut, $guests, $totalPrice]);
    }

    /**
     * Returns all reservations for a user, joined with room details.
     */
    public function getByUser(int $userId): array {
        $sql = "SELECT r.*, hr.roomNumber, hr.capacity, hr.basePrice
                FROM Reservation r
                JOIN HotelRoom hr ON r.roomId = hr.id
                WHERE r.userId = ?
                ORDER BY r.checkInDate DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Total number of guests staying on a given date.
     * A guest is "staying" if checkInDate <= date < checkOutDate.
     */
    public function getTotalGuestsOnDate(string $date): int {
        $sql = "SELECT COALESCE(SUM(numberOfGuests), 0) FROM Reservation
                WHERE checkInDate <= ? AND checkOutDate > ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date, $date]);
        return (int) $stmt->fetchColumn();
    }
}
