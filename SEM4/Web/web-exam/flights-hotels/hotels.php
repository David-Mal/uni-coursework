<?php
require_once 'auth.php';
require_once 'db.php';
requireSession();

$hotels = [];
$stmt = $conn->prepare("SELECT * FROM Hotels WHERE date = :date AND city = :city AND availableRooms > 0");
$stmt->execute([
    ':date' => $_SESSION['res_date'],
    ':city' => $_SESSION['res_city']
]);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><title>Hotels</title></head>
<body>
    <?php include 'menu.php'; ?>

    <h3>Available Hotels</h3>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'booked'): ?>
        <p style="color: green;">Hotel booked successfully!</p>
    <?php endif; ?>

    <?php if (empty($hotels)): ?>
        <p>No available hotels for this date and city.</p>
    <?php else: ?>
        <table border="1" style="border-collapse: collapse; width: 100%; max-width: 500px;">
            <tr><th>Hotel Name</th><th>Rooms Left</th><th>Action</th></tr>
            <?php foreach ($hotels as $h): ?>
                <tr>
                    <td><?php echo htmlspecialchars($h['hotelName']); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($h['availableRooms']); ?></td>
                    <td style="text-align: center;">
                        <form action="book_action.php" method="POST" style="margin:0;">
                            <input type="hidden" name="type" value="Hotel">
                            <input type="hidden" name="resource_id" value="<?php echo htmlspecialchars($h['hotelID']); ?>">
                            <button type="submit">Reserve Room</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>