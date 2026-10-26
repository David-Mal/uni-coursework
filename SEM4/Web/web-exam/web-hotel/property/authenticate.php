<?php
session_start();
require_once 'config.php';
require_once 'services/UserService.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if (!isset($_SESSION['pending_user_id'])) {
    header('Location: login.php');
    exit();
}

$userService = new UserService($conn);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = trim($_POST['secretAnswer'] ?? '');
    $userId = (int) $_SESSION['pending_user_id'];

    if ($userService->verifySecretAnswer($userId, $answer)) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $_SESSION['pending_user_name'];
        $_SESSION['search_hits'] = [];

        unset(
            $_SESSION['pending_user_id'],
            $_SESSION['pending_user_name'],
            $_SESSION['pending_secret_question']
        );

        header('Location: index.php');
        exit();
    }

    $error = 'Incorrect secret answer.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Property App - Authentication</title>
    <style>
        body { font-family: sans-serif; max-width: 480px; margin: 40px auto; }
        .error { color: #b00020; }
    </style>
</head>
<body>
    <h2>Step 2: Answer your secret question</h2>
    <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['pending_user_name']); ?></strong>.</p>
    <p><strong>Question:</strong> <?php echo htmlspecialchars($_SESSION['pending_secret_question']); ?></p>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="authenticate.php">
        <label>Secret answer:</label><br>
        <input type="text" name="secretAnswer" required autofocus><br><br>
        <button type="submit">Authenticate</button>
    </form>

    <p><a href="login.php">Start over</a></p>
</body>
</html>
