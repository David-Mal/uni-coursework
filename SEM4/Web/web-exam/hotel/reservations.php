<?php
require_once 'auth.php';
require_once 'ReservationRepository.php';
requireLogin();

$reservationRepo = new ReservationRepository();
$reservations    = $reservationRepo->getByUser(getCurrentUserId());
?>
<?php require 'header.php'; ?>
<h2 class="mb-4">My Reservations</h2>

<?php if (empty($reservations)): ?>
    <div class="alert alert-info">
        You have no reservations yet.
        <a href="rooms.php" class="alert-link">Browse available rooms →</a>
    </div>
<?php else: ?>
    <table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Nights</th>
                <th>Guests</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $i => $res):
                $nights = (new DateTime($res['checkOutDate']))->diff(new DateTime($res['checkInDate']))->days;
            ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td>Room <?= htmlspecialchars($res['roomNumber']) ?></td>
                <td><?= htmlspecialchars($res['checkInDate']) ?></td>
                <td><?= htmlspecialchars($res['checkOutDate']) ?></td>
                <td><?= $nights ?></td>
                <td><?= (int) $res['numberOfGuests'] ?></td>
                <td><strong>$<?= (int) $res['totalPrice'] ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php require 'footer.php'; ?>
