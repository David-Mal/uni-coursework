<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $conn->prepare('SELECT id, username, password FROM Users WHERE username = :username');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];

        header('Location: index.php');
        exit();
    }

    $error = 'User not found. Please enter an existing username.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Files App - Login</title>
    <style>
        body { font-family: sans-serif; max-width: 480px; margin: 40px auto; }
        .error { color: #b00020; }
    </style>
</head>
<body>
    <h2>Enter your name and password:</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required autofocus><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required> <br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
