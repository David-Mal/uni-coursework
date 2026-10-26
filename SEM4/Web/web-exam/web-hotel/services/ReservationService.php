<?php

require_once __DIR__ . '/PricingService.php';
require_once __DIR__ . '/RoomService.php';

class ReservationService
{
    private PDO $conn;
    private PricingService $pricingService;
    private RoomService $roomService;

    public function __construct(PDO $conn, PricingService $pricingService, RoomService $roomService)
    {
        $this->conn = $conn;
        $this->pricingService = $pricingService;
        $this->roomService = $roomService;
    }

    public function countBookedRoomsInPeriod(string $checkIn, string $checkOut): int
    {
        $stmt = $this->conn->prepare('
            SELECT COUNT(DISTINCT roomId) FROM Reservation
            WHERE checkInDate < :checkOut AND checkOutDate > :checkIn
        ');
        $stmt->execute([
            ':checkIn' => $checkIn,
            ':checkOut' => $checkOut,
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function hasUserOverlap(int $userId, string $checkIn, string $checkOut): bool
    {
        $stmt = $this->conn->prepare('
            SELECT COUNT(*) FROM Reservation
            WHERE userId = :userId
            AND checkInDate < :checkOut AND checkOutDate > :checkIn
        ');
        $stmt->execute([
            ':userId' => $userId,
            ':checkIn' => $checkIn,
            ':checkOut' => $checkOut,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function getUserReservations(int $userId): array
    {
        $stmt = $this->conn->prepare('
            SELECT r.*, h.roomNumber, h.basePrice
            FROM Reservation r
            JOIN HotelRoom h ON r.roomId = h.id
            WHERE r.userId = :userId
            ORDER BY r.checkInDate DESC
        ');
        $stmt->execute([':userId' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGuestCountOnDate(string $date): int
    {
        $stmt = $this->conn->prepare('
            SELECT COALESCE(SUM(numberOfGuests), 0) FROM Reservation
            WHERE checkInDate <= :date AND checkOutDate > :date
        ');
        $stmt->execute([':date' => $date]);

        return (int) $stmt->fetchColumn();
    }

    public function createReservation(int $userId, int $roomId, string $checkIn, string $checkOut, int $numberOfGuests): array
    {
        if ($checkOut <= $checkIn) {
            return ['success' => false, 'message' => 'Check-out date must be after check-in date.'];
        }

        $room = $this->roomService->getRoomById($roomId);
        if ($room === null) {
            return ['success' => false, 'message' => 'Room not found.'];
        }

        if ($numberOfGuests < 1 || $numberOfGuests > (int) $room['capacity']) {
            return ['success' => false, 'message' => 'Invalid number of guests for this room.'];
        }

        if (!$this->roomService->isRoomAvailable($roomId, $checkIn, $checkOut)) {
            return ['success' => false, 'message' => 'Room is no longer available for these dates.'];
        }

        if ($this->hasUserOverlap($userId, $checkIn, $checkOut)) {
            return ['success' => false, 'message' => 'You already have a reservation that overlaps these dates.'];
        }

        $nights = (int) ((strtotime($checkOut) - strtotime($checkIn)) / 86400);
        $bookedRooms = $this->countBookedRoomsInPeriod($checkIn, $checkOut);
        $totalRooms = $this->roomService->getTotalRoomCount();
        $totalPrice = $this->pricingService->calculateTotalPrice(
            (int) $room['basePrice'],
            $bookedRooms,
            $totalRooms,
            $nights
        );

        $stmt = $this->conn->prepare('
            INSERT INTO Reservation (userId, roomId, checkInDate, checkOutDate, numberOfGuests, totalPrice)
            VALUES (:userId, :roomId, :checkIn, :checkOut, :numberOfGuests, :totalPrice)
        ');
        $stmt->execute([
            ':userId' => $userId,
            ':roomId' => $roomId,
            ':checkIn' => $checkIn,
            ':checkOut' => $checkOut,
            ':numberOfGuests' => $numberOfGuests,
            ':totalPrice' => $totalPrice,
        ]);

        return ['success' => true, 'message' => 'Reservation created. Total price: ' . $totalPrice];
    }
}
