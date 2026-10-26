<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'services/PricingService.php';
require_once 'services/RoomService.php';
require_once 'services/ReservationService.php';

requireLogin();

$reservationService = new ReservationService($conn, new PricingService(), new RoomService($conn));

$guestDate = $_GET['guestDate'] ?? '';
$guestCount = null;

if ($guestDate !== '') {
    $guestCount = $reservationService->getGuestCountOnDate($guestDate);
}
?>
<!DOCTYPE html>
<html>
<head><title>Hotel</title></head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <nav>
        <a href="rooms.php">Available Rooms</a> |
        <a href="reservations.php">My Reservations</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <h3>Guests staying on a specific date</h3>
    <form method="GET" action="index.php">
        <label>Date:</label>
        <input type="date" name="guestDate" value="<?php echo htmlspecialchars($guestDate); ?>" required>
        <button type="submit">Check</button>
    </form>

    <?php if ($guestDate !== ''): ?>
        <p>Total guests on <?php echo htmlspecialchars($guestDate); ?>: <strong><?php echo $guestCount; ?></strong></p>
    <?php endif; ?>
</body>
</html>
