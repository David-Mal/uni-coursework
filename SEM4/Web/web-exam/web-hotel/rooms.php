<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'services/PricingService.php';
require_once 'services/RoomService.php';
require_once 'services/ReservationService.php';

requireLogin();

$roomService = new RoomService($conn);
$reservationService = new ReservationService($conn, new PricingService(), $roomService);

$checkIn = $_GET['checkIn'] ?? '';
$checkOut = $_GET['checkOut'] ?? '';
$message = $_GET['message'] ?? '';
$success = ($_GET['success'] ?? '') === '1';
$availableRooms = [];

if ($checkIn !== '' && $checkOut !== '') {
    $availableRooms = $roomService->getAvailableRooms($checkIn, $checkOut);
}
?>
<!DOCTYPE html>
<html>
<head><title>Available Rooms</title></head>
<body>
    <h2>Available Rooms</h2>
    <p>Logged in as: <?php echo htmlspecialchars($_SESSION['username']); ?></p>

    <nav>
        <a href="index.php">Home</a> |
        <a href="reservations.php">My Reservations</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <?php if ($message !== ''): ?>
        <p style="color:<?php echo $success ? 'green' : 'red'; ?>"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="GET" action="rooms.php">
        <label>Check-in:</label>
        <input type="date" name="checkIn" value="<?php echo htmlspecialchars($checkIn); ?>" required><br><br>

        <label>Check-out:</label>
        <input type="date" name="checkOut" value="<?php echo htmlspecialchars($checkOut); ?>" required><br><br>

        <button type="submit">Search</button>
    </form>

    <?php if ($checkIn !== '' && $checkOut !== ''): ?>
        <h3>Results for <?php echo htmlspecialchars($checkIn); ?> to <?php echo htmlspecialchars($checkOut); ?></h3>

        <?php if (count($availableRooms) === 0): ?>
            <p>No rooms available for this period.</p>
        <?php else: ?>
            <table border="1" cellpadding="5">
                <tr>
                    <th>Room Number</th>
                    <th>Capacity</th>
                    <th>Base Price / night</th>
                    <th>Reserve</th>
                </tr>
                <?php foreach ($availableRooms as $room): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($room['roomNumber']); ?></td>
                        <td><?php echo (int) $room['capacity']; ?></td>
                        <td><?php echo (int) $room['basePrice']; ?></td>
                        <td>
                            <form method="POST" action="reserve.php">
                                <input type="hidden" name="roomId" value="<?php echo (int) $room['id']; ?>">
                                <input type="hidden" name="checkIn" value="<?php echo htmlspecialchars($checkIn); ?>">
                                <input type="hidden" name="checkOut" value="<?php echo htmlspecialchars($checkOut); ?>">
                                <label>Guests:</label>
                                <input type="number" name="numberOfGuests" min="1" max="<?php echo (int) $room['capacity']; ?>" value="1" required>
                                <button type="submit">Reserve</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
