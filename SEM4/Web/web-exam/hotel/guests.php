<?php
require_once 'auth.php';
require_once 'ReservationRepository.php';
requireLogin();

$date        = trim($_GET['date'] ?? '');
$totalGuests = null;

if ($date !== '') {
    $reservationRepo = new ReservationRepository();
    $totalGuests     = $reservationRepo->getTotalGuestsOnDate($date);
}
?>
<?php require 'header.php'; ?>
<h2 class="mb-4">Total Guests on a Specific Day</h2>

<form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control"
               value="<?= htmlspecialchars($date) ?>" required>
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Check</button>
    </div>
</form>

<?php if ($totalGuests !== null): ?>
    <div class="alert alert-info fs-5">
        Guests staying on <strong><?= htmlspecialchars($date) ?></strong>:
        <span class="fw-bold fs-4"><?= $totalGuests ?></span>
    </div>
    <?php if ($totalGuests === 0): ?>
        <p class="text-muted">No active reservations on this date.</p>
    <?php endif; ?>
<?php endif; ?>
<?php require 'footer.php'; ?>
