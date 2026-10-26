<?php
require 'config.php';

if (!$me) { header('Location: index.php'); exit; }

$msg = '';
$msgColor = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking_id'])) {
    $bookingId = (int)$_POST['cancel_booking_id'];

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare( "UPDATE " . T_BOOKING . " SET " . C_BOOKING_CANCELLED . " = 1" . " WHERE " . C_BOOKING_ID . " = ? AND " . C_BOOKING_USERID . " = ? AND " . C_BOOKING_CANCELLED . " = 0" );
        $stmt->execute([$bookingId, $userId]);

        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->prepare( "SELECT " . C_BOOKING_CLASSID . " FROM " . T_BOOKING . " WHERE " . C_BOOKING_ID . " = ?" );
            $stmt->execute([$bookingId]);
            $classId = (int)$stmt->fetchColumn();

            $stmt = $pdo->prepare( "SELECT w." . C_WAITLIST_ID . ", w." . C_WAITLIST_USERID . ", u." . C_USER_USERNAME . " FROM " . T_WAITLIST . " w" . " JOIN " . T_USER . " u ON u." . C_USER_ID . " = w." . C_WAITLIST_USERID . " WHERE w." . C_WAITLIST_CLASSID . " = ?" . " ORDER BY w." . C_WAITLIST_ADDEDAT . " ASC LIMIT 1" );
            $stmt->execute([$classId]);
            $next = $stmt->fetch();

            if ($next) {
                $pdo->prepare( "DELETE FROM " . T_WAITLIST . " WHERE " . C_WAITLIST_ID . " = ?" )->execute([$next[C_WAITLIST_ID]]);

                $pdo->prepare( "INSERT INTO " . T_BOOKING . " (" . C_BOOKING_USERID . ", " . C_BOOKING_CLASSID . ", " . C_BOOKING_BOOKEDAT . ", " . C_BOOKING_CANCELLED . ")" . " VALUES (?, ?, NOW(), 0)" )->execute([$next[C_WAITLIST_USERID], $classId]);

                $msg = htmlspecialchars($next[C_USER_USERNAME]) . " has been automatically moved from the waitlist to a confirmed booking.";
                $msgColor = 'green';
            } else {
                $msg = "Booking cancelled.";
                $msgColor = 'gray';
            }
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = "An error occurred. Please try again.";
        $msgColor = 'red';
    }
}

$stmt = $pdo->prepare( "SELECT b." . C_BOOKING_ID . ", c." . C_CLASS_NAME . ", c." . C_CLASS_INSTRNAME . ", c." . C_CLASS_DATE . ", b." . C_BOOKING_BOOKEDAT . " FROM " . T_BOOKING . " b" . " JOIN " . TBL_CLASS . " c ON c." . C_CLASS_ID . " = b." . C_BOOKING_CLASSID . " WHERE b." . C_BOOKING_USERID . " = ? AND b." . C_BOOKING_CANCELLED . " = 0" . " ORDER BY c." . C_CLASS_DATE );
$stmt->execute([$userId]);
$bookings = $stmt->fetchAll();

$stmt = $pdo->prepare( "SELECT c." . C_CLASS_NAME . ", c." . C_CLASS_DATE . ", b." . C_BOOKING_BOOKEDAT . ", b." . C_BOOKING_CANCELLED . " FROM " . T_BOOKING . " b" . " JOIN " . TBL_CLASS . " c ON c." . C_CLASS_ID . " = b." . C_BOOKING_CLASSID . " WHERE b." . C_BOOKING_USERID . " = ?" . " ORDER BY b." . C_BOOKING_BOOKEDAT . " DESC" );
$stmt->execute([$userId]);
$history = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Classes</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require 'nav.php'; ?>

<div class="section">
  <h2>My Bookings</h2>
  <p>Logged in as <strong><?= htmlspecialchars($me) ?></strong></p>

  <?php if ($msg): ?>
  <p style="color:<?= $msgColor ?>"><strong><?= $msg ?></strong></p>
  <?php endif; ?>

  <?php if ($bookings): ?>
  <table>
    <tr>
      <th>Class Name</th>
      <th>Instructor</th>
      <th>Date</th>
      <th>Booked At</th>
      <th></th>
    </tr>
    <?php foreach ($bookings as $b): ?>
    <tr>
      <td><?= htmlspecialchars($b[C_CLASS_NAME]) ?></td>
      <td><?= htmlspecialchars($b[C_CLASS_INSTRNAME] ?? '') ?></td>
      <td><?= htmlspecialchars($b[C_CLASS_DATE]) ?></td>
      <td><?= htmlspecialchars($b[C_BOOKING_BOOKEDAT]) ?></td>
      <td>
        <form method="post">
          <input type="hidden" name="cancel_booking_id" value="<?= (int)$b[C_BOOKING_ID] ?>">
          <button type="submit" onclick="return confirm('Cancel this booking?')">Cancel</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
  <?php else: ?>
  <p>You have no active bookings.</p>
  <?php endif; ?>

  <h2>Booking History</h2>
  <?php if ($history): ?>
  <table>
    <tr>
      <th>Class Name</th>
      <th>Date / Time</th>
      <th>Booked At</th>
      <th>Status</th>
    </tr>
    <?php foreach ($history as $h): ?>
    <tr>
      <td><?= htmlspecialchars($h[C_CLASS_NAME]) ?></td>
      <td><?= htmlspecialchars($h[C_CLASS_DATE]) ?></td>
      <td><?= htmlspecialchars($h[C_BOOKING_BOOKEDAT]) ?></td>
      <td><?= $h[C_BOOKING_CANCELLED] ? 'Cancelled' : 'Active' ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
  <?php else: ?>
  <p>No booking history.</p>
  <?php endif; ?>
</div>

</body>
</html>
