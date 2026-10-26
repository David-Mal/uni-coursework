<?php
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = trim($_POST['username'] ?? '');
    if ($n !== '') {
        $stmt = $pdo->prepare( "SELECT " . C_USER_ID . ", " . C_USER_USERNAME . ", " . C_USER_MEMBERSHIP . " FROM " . T_USER . " WHERE " . C_USER_USERNAME . " = ?" );
        $stmt->execute([$n]);
        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['username'] = $user[C_USER_USERNAME];
            $_SESSION['user_id'] = $user[C_USER_ID];
            $_SESSION['membership_type'] = $user[C_USER_MEMBERSHIP];
            header('Location: all_classes.php'); exit;
        } else {
            $error = "User not found.";
        }
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require 'nav.php'; ?>

<div class="section">
  <h2>Login</h2>
  <?php if ($error): ?>
  <p style="color:red"><strong><?= htmlspecialchars($error) ?></strong></p>
  <?php endif; ?>
  <form method="post">
    <label>Username:</label>
    <input type="text" name="username" placeholder="e.g. alice" required autofocus>
    <button type="submit">Login</button>
  </form>
  <?php if ($me): ?>
  <p>Logged in as <strong><?= htmlspecialchars($me) ?></strong>.
     <a href="?logout=1">Logout</a></p>
  <?php endif; ?>
</div>

</body>
</html>
