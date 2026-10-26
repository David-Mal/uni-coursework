<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if (!isset($_SESSION['temp_username'])) {
    header('Location: login_step1.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = trim($_POST['secretAnswer'] ?? '');

    $stmt = $conn->prepare('SELECT id, secretAnswer FROM User WHERE username = :username');
    $stmt->execute([':username' => $_SESSION['temp_username']]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userRow && $userRow['secretAnswer'] === $answer) {
        $_SESSION['user_id'] = (int) $userRow['id'];
        $_SESSION['username'] = $_SESSION['temp_username'];
        unset($_SESSION['temp_user_id']);
        unset($_SESSION['temp_username']);
        unset($_SESSION['secretQuestion']);
        header('Location: index.php');
        exit();
    }

    $error = 'Wrong answer.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Property App - Secret Question</title>
    <style>
        body { font-family: sans-serif; max-width: 480px; margin: 40px auto; }
        .error { color: #b00020; }
    </style>
</head>
<body>
    <h2>Answer secret question:</h2>
    <p><?php echo htmlspecialchars($_SESSION['secretQuestion'])?></p>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Answer:</label><br>
        <input type="text" name="secretAnswer" required autofocus><br><br>
        <button type="submit">Continue</button>
    </form>
</body>
</html>
