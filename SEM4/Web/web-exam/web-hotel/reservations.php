<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'services/PricingService.php';
require_once 'services/RoomService.php';
require_once 'services/ReservationService.php';

requireLogin();

$reservationService = new ReservationService($conn, new PricingService(), new RoomService($conn));
$reservations = $reservationService->getUserReservations((int) $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head><title>My Reservations</title></head>
<body>
    <h2>My Reservations</h2>
    <p>Logged in as: <?php echo htmlspecialchars($_SESSION['username']); ?></p>

    <nav>
        <a href="index.php">Home</a> |
        <a href="rooms.php">Available Rooms</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <?php if (count($reservations) === 0): ?>
        <p>You have no reservations.</p>
    <?php else: ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Guests</th>
                <th>Total Price</th>
            </tr>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation['roomNumber']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['checkInDate']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['checkOutDate']); ?></td>
                    <td><?php echo (int) $reservation['numberOfGuests']; ?></td>
                    <td><?php echo (int) $reservation['totalPrice']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
