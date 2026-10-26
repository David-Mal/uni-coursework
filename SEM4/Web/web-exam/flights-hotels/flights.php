<?php
require_once 'auth.php';
require_once 'db.php';
requireSession();

$flights = [];
$stmt = $conn->prepare("SELECT * FROM Flights WHERE date = :date AND destinationCity = :city AND availableSeats > 0");
$stmt->execute([
    ':date' => $_SESSION['res_date'],
    ':city' => $_SESSION['res_city']
]);
$flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><title>Flights</title></head>
<body>
    <?php include 'menu.php'; ?>

    <h3>Available Flights</h3>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'booked'): ?>
        <p style="color: green;">Flight booked successfully!</p>
    <?php endif; ?>

    <?php if (empty($flights)): ?>
        <p>No available flights for this date and city.</p>
    <?php else: ?>
        <table border="1" style="border-collapse: collapse; width: 100%; max-width: 500px;">
            <tr><th>Flight ID</th><th>Seats Left</th><th>Action</th></tr>
            <?php foreach ($flights as $f): ?>
                <tr>
                    <td style="text-align: center;"><?php echo htmlspecialchars($f['flightID']); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($f['availableSeats']); ?></td>
                    <td style="text-align: center;">
                        <form action="book_action.php" method="POST" style="margin:0;">
                            <input type="hidden" name="type" value="Flight">
                            <input type="hidden" name="resource_id" value="<?php echo htmlspecialchars($f['flightID']); ?>">
                            <button type="submit">Reserve Seat</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>