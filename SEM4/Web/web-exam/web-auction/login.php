<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    if ($username) {
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit();
    }

    $error = 'User not found. Please enter an existing username.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Auction App - Login</title>
    <style>
        body { font-family: sans-serif; max-width: 480px; margin: 40px auto; }
        .error { color: #b00020; }
    </style>
</head>
<body>
    <h2>Enter your name</h2>
    <p>Introduceți un nume pentru a continua (ex: David, Alice).</p>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required autofocus><br><br>
        <button type="submit">Continue</button>
    </form>
</body>
</html>
