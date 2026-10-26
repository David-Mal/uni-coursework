<?php
require_once 'auth.php';
require_once 'RoomRepository.php';
require_once 'ReservationRepository.php';
require_once 'PricingService.php';
requireLogin();

// ── Input ──────────────────────────────────────────────────────────────────
$roomId   = (int)   ($_REQUEST['roomId']   ?? 0);
$checkIn  = trim(   $_REQUEST['checkIn']   ?? '');
$checkOut = trim(   $_REQUEST['checkOut']  ?? '');

$roomRepo        = new RoomRepository();
$reservationRepo = new ReservationRepository();
$pricingService  = new PricingService();

// Guard: room must exist and dates must be valid
$room = $roomRepo->findById($roomId);
if (!$room || $checkIn === '' || $checkOut === '' || $checkOut <= $checkIn) {
    header('Location: rooms.php');
    exit;
}

// ── Calculate current price preview ───────────────────────────────────────
$totalRooms   = $roomRepo->getTotalCount();
$bookedRooms  = $reservationRepo->getBookedRoomCount($checkIn, $checkOut);
$multiplier   = $pricingService->getMultiplier($bookedRooms, $totalRooms);
$totalPrice   = $pricingService->calculateTotalPrice($room['basePrice'], $multiplier, $checkIn, $checkOut);
$nights       = (new DateTime($checkOut))->diff(new DateTime($checkIn))->days;
$occupancyPct = $totalRooms > 0 ? round(($bookedRooms / $totalRooms) * 100) : 0;
$tierLabel    = $pricingService->getTierLabel($multiplier);

$error   = '';
$success = '';

// ── Handle form submission ─────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guests = (int) ($_POST['numberOfGuests'] ?? 1);
    $userId = getCurrentUserId();

    if ($guests < 1 || $guests > (int) $room['capacity']) {
        $error = "Number of guests must be between 1 and {$room['capacity']}.";

    } elseif ($reservationRepo->userHasOverlap($userId, $checkIn, $checkOut)) {
        // Overlap prevention for same user
        $error = 'You already have a reservation that overlaps with these dates.';

    } elseif ($reservationRepo->roomHasOverlap($roomId, $checkIn, $checkOut)) {
        // Race-condition guard: room was booked between search and confirmation
        $error = 'This room is no longer available for the selected dates. Please search again.';

    } else {
        // Recalculate price at the exact moment of booking (occupancy may have changed)
        $bookedRooms = $reservationRepo->getBookedRoomCount($checkIn, $checkOut);
        $multiplier  = $pricingService->getMultiplier($bookedRooms, $totalRooms);
        $totalPrice  = $pricingService->calculateTotalPrice($room['basePrice'], $multiplier, $checkIn, $checkOut);

        if ($reservationRepo->create($userId, $roomId, $checkIn, $checkOut, $guests, $totalPrice)) {
            $success = "Reservation confirmed! Total price: \${$totalPrice} for {$nights} night(s).";
        } else {
            $error = 'Could not save the reservation. Please try again.';
        }
    }
}
?>
<?php require 'header.php'; ?>
<h2 class="mb-4">Reserve Room <?= htmlspecialchars($room['roomNumber']) ?></h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($success) ?>
        <a href="reservations.php" class="alert-link">View my reservations →</a>
    </div>
    <a href="rooms.php" class="btn btn-primary">Search more rooms</a>
<?php else: ?>

<!-- Price summary card -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-secondary text-white">Booking Summary</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Room:</strong> <?= htmlspecialchars($room['roomNumber']) ?></p>
                <p><strong>Check-in:</strong> <?= htmlspecialchars($checkIn) ?></p>
                <p><strong>Check-out:</strong> <?= htmlspecialchars($checkOut) ?></p>
                <p><strong>Duration:</strong> <?= $nights ?> night(s)</p>
                <p><strong>Capacity:</strong> <?= (int) $room['capacity'] ?> guest(s)</p>
            </div>
            <div class="col-md-6">
                <p><strong>Base price:</strong> $<?= (int) $room['basePrice'] ?> / night</p>
                <p><strong>Hotel occupancy:</strong> <?= $occupancyPct ?>%
                   (<?= $bookedRooms ?> / <?= $totalRooms ?> rooms booked)</p>
                <p><strong>Pricing tier:</strong>
                    <span class="badge <?= $multiplier >= 1.50 ? 'bg-danger' : ($multiplier >= 1.20 ? 'bg-warning text-dark' : 'bg-success') ?>">
                        <?= htmlspecialchars($tierLabel) ?>
                    </span>
                </p>
                <p class="fs-5 fw-bold">Estimated total: $<?= $totalPrice ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Reservation form -->
<form method="POST">
    <input type="hidden" name="roomId"   value="<?= $roomId ?>">
    <input type="hidden" name="checkIn"  value="<?= htmlspecialchars($checkIn) ?>">
    <input type="hidden" name="checkOut" value="<?= htmlspecialchars($checkOut) ?>">

    <div class="mb-3" style="max-width:300px;">
        <label class="form-label">Number of Guests</label>
        <input type="number" name="numberOfGuests" class="form-control"
               min="1" max="<?= (int) $room['capacity'] ?>" value="1" required>
        <div class="form-text">Max capacity: <?= (int) $room['capacity'] ?></div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">Confirm Reservation</button>
        <a href="rooms.php?checkIn=<?= urlencode($checkIn) ?>&checkOut=<?= urlencode($checkOut) ?>"
           class="btn btn-outline-secondary">Back to results</a>
    </div>
</form>
<?php endif; ?>
<?php require 'footer.php'; ?>
