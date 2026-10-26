<?php
require 'config.php';

if (!$me) { header('Location: index.php'); exit; }

$msg = '';
$msgColor = '';
$balanceMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classId = (int)($_POST['class_id'] ?? 0);

    $stmt = $pdo->prepare("SELECT " . C_CLASS_ID . ", " . C_CLASS_NAME . ", " . C_CLASS_MAXCAP . " FROM " . TBL_CLASS . " WHERE " . C_CLASS_ID . " = ?" );
    $stmt->execute([$classId]);
    $class = $stmt->fetch();

    if ($class) {
        $nameParts = explode('-', $class[C_CLASS_NAME]);
        $isHighIntensity = strtolower(trim(end($nameParts))) === 'high';

        if ($isHighIntensity && strtolower($membershipType) === 'basic') {
            $msg = "High-intensity classes are available for Premium members only.";
            $msgColor = 'red';
        } else {
            $stmt = $pdo->prepare( "SELECT " . C_BOOKING_ID . " FROM " . T_BOOKING . " WHERE " . C_BOOKING_USERID . " = ? AND " . C_BOOKING_CLASSID . " = ? AND " . C_BOOKING_CANCELLED . " = 0" );
            $stmt->execute([$userId, $classId]);
            $alreadyBooked = $stmt->fetch();

            $stmt = $pdo->prepare( "SELECT " . C_WAITLIST_ID . " FROM " . T_WAITLIST . " WHERE " . C_WAITLIST_USERID . " = ? AND " . C_WAITLIST_CLASSID . " = ?" );
            $stmt->execute([$userId, $classId]);
            $alreadyWaitlisted = $stmt->fetch();

            if ($alreadyBooked || $alreadyWaitlisted) {
                $msg = "You have already registered for this class.";
                $msgColor = 'orange';
            } else {
                $stmt = $pdo->prepare( "SELECT COUNT(*) FROM " . T_BOOKING . " WHERE " . C_BOOKING_CLASSID . " = ? AND " . C_BOOKING_CANCELLED . " = 0" );
                $stmt->execute([$classId]);
                $activeBookings = (int)$stmt->fetchColumn();
                $remaining = $class[C_CLASS_MAXCAP] - $activeBookings;

                if ($remaining > 0) {
                    $pdo->prepare( "INSERT INTO " . T_BOOKING . " (" . C_BOOKING_USERID . ", " . C_BOOKING_CLASSID . ", " . C_BOOKING_BOOKEDAT . ", " . C_BOOKING_CANCELLED . ")" . " VALUES (?, ?, NOW(), 0)" )->execute([$userId, $classId]);
                    $msg = "Successfully booked " . htmlspecialchars($class[C_CLASS_NAME]) . "!";
                    $msgColor = 'green';

                    $stmt = $pdo->prepare( "SELECT c." . C_CLASS_NAME . " FROM " . T_BOOKING . " b" . " JOIN " . TBL_CLASS . " c ON c." . C_CLASS_ID . " = b." . C_BOOKING_CLASSID . " WHERE b." . C_BOOKING_USERID . " = ? AND b." . C_BOOKING_CANCELLED . " = 0" );
                    $stmt->execute([$userId]);
                    $activeNames = $stmt->fetchAll();
                    if (count($activeNames) >= 3) {
                        $intensities = array_map(function($row) {
                            $parts = explode('-', $row[C_CLASS_NAME]);
                            return strtolower(trim(end($parts)));
                        }, $activeNames);
                        if (count(array_unique($intensities)) === 1) {
                            $balanceMsg = "All your bookings this week are " . ucfirst($intensities[0]) . " intensity. Consider balancing your workout routine!";
                        }
                    }
                } else {
                    $pdo->prepare( "INSERT INTO " . T_WAITLIST . " (" . C_WAITLIST_USERID . ", " . C_WAITLIST_CLASSID . ", " . C_WAITLIST_ADDEDAT . ")" . " VALUES (?, ?, NOW())" )->execute([$userId, $classId]);
                    $msg = "You've been added to the waitlist for " . htmlspecialchars($class[C_CLASS_NAME]) . ".";
                    $msgColor = 'blue';
                }
            }
        }
    }
}

$classes = $pdo->query( "SELECT c." . C_CLASS_ID . ", c." . C_CLASS_NAME . ", c." . C_CLASS_INSTRNAME . ", c." . C_CLASS_DATE . ", c." . C_CLASS_MAXCAP . "," . " (c." . C_CLASS_MAXCAP . " - COUNT(b." . C_BOOKING_ID . ")) AS remainingSpots" . " FROM " . TBL_CLASS . " c" . " LEFT JOIN " . T_BOOKING . " b ON b." . C_BOOKING_CLASSID . " = c." . C_CLASS_ID . " AND b." . C_BOOKING_CANCELLED . " = 0" . " WHERE c." . C_CLASS_DATE . " >= CURDATE()" . " GROUP BY c." . C_CLASS_ID . " ORDER BY c." . C_CLASS_DATE )->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upcoming Classes</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require 'nav.php'; ?>

<div class="section">
  <h2>Upcoming Classes</h2>
  <p>Logged in as <strong><?= htmlspecialchars($me) ?></strong> (<?= htmlspecialchars($membershipType) ?>)</p>

  <?php if ($msg): ?>
  <p style="color:<?= $msgColor ?>"><strong><?= $msg ?></strong></p>
  <?php endif; ?>
  <?php if ($balanceMsg): ?>
  <p style="color:orange"><strong><?= htmlspecialchars($balanceMsg) ?></strong></p>
  <?php endif; ?>

  <?php if ($classes): ?>
  <table>
    <tr>
      <th>Class Name</th>
      <th>Instructor</th>
      <th>Date</th>
      <th>Spots Left</th>
      <th></th>
    </tr>
    <?php foreach ($classes as $c): ?>
    <tr>
      <td><?= htmlspecialchars($c[C_CLASS_NAME]) ?></td>
      <td><?= htmlspecialchars($c[C_CLASS_INSTRNAME] ?? '') ?></td>
      <td><?= htmlspecialchars($c[C_CLASS_DATE]) ?></td>
      <td><?= (int)$c['remainingSpots'] <= 0 ? 'Full' : (int)$c['remainingSpots'] ?></td>
      <td>
        <form method="post">
          <input type="hidden" name="class_id" value="<?= (int)$c[C_CLASS_ID] ?>">
          <button type="submit">
            <?= (int)$c['remainingSpots'] <= 0 ? 'Join Waitlist' : 'Book' ?>
          </button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
  <?php else: ?>
  <p>No upcoming classes.</p>
  <?php endif; ?>
</div>

</body>
</html>
