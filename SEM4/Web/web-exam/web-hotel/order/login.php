<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    $stmt = $conn->prepare('SELECT id, username FROM User WHERE username = :username');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['cart'] = [];

        header('Location: index.php');
        exit();
    }

    $error = 'User not found. Please enter an existing username.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order App - Login</title>
    <style>
        body { font-family: sans-serif; max-width: 480px; margin: 40px auto; }
        .error { color: #b00020; }
    </style>
</head>
<body>
    <h2>Enter your name</h2>
    <p>No password required. Use an existing username from the database.</p>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Username:</label><br>
        <input type="text" name="username" required autofocus><br><br>
        <button type="submit">Continue</button>
    </form>
</body>
</html>
