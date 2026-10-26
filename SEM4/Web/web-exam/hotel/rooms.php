<?php
require_once 'auth.php';
require_once 'RoomRepository.php';
requireLogin();

$checkIn  = trim($_GET['checkIn']  ?? '');
$checkOut = trim($_GET['checkOut'] ?? '');
$rooms    = [];
$error    = '';
$searched = false;

if ($checkIn !== '' && $checkOut !== '') {
    if ($checkOut <= $checkIn) {
        $error = 'Check-out date must be after check-in date.';
    } else {
        $roomRepo = new RoomRepository();
        $rooms    = $roomRepo->getAvailableRooms($checkIn, $checkOut);
        $searched = true;
    }
}
?>
<?php require 'header.php'; ?>
<h2 class="mb-4">Available Rooms</h2>

<form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Check-in Date</label>
        <input type="date" name="checkIn" class="form-control"
               value="<?= htmlspecialchars($checkIn) ?>" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Check-out Date</label>
        <input type="date" name="checkOut" class="form-control"
               value="<?= htmlspecialchars($checkOut) ?>" required>
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($searched): ?>
    <?php if (empty($rooms)): ?>
        <div class="alert alert-warning">No rooms available for the selected period.</div>
    <?php else: ?>
        <p class="text-muted"><?= count($rooms) ?> room(s) available from
            <strong><?= htmlspecialchars($checkIn) ?></strong> to
            <strong><?= htmlspecialchars($checkOut) ?></strong>.</p>
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Room</th>
                    <th>Capacity</th>
                    <th>Base Price / night</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                <tr>
                    <td><?= htmlspecialchars($room['roomNumber']) ?></td>
                    <td><?= (int) $room['capacity'] ?> guest(s)</td>
                    <td>$<?= (int) $room['basePrice'] ?></td>
                    <td>
                        <a href="reserve.php?roomId=<?= (int) $room['id'] ?>&checkIn=<?= urlencode($checkIn) ?>&checkOut=<?= urlencode($checkOut) ?>"
                           class="btn btn-sm btn-success">Reserve</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php endif; ?>
<?php require 'footer.php'; ?>
