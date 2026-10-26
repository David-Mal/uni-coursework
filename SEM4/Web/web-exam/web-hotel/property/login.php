<?php
session_start();
require_once 'config.php';
require_once 'services/UserService.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$userService = new UserService($conn);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $user = $userService->findByName($name);

    if ($user) {
        $_SESSION['pending_user_id'] = (int) $user['id'];
        $_SESSION['pending_user_name'] = $user['name'];
        $_SESSION['pending_secret_question'] = $user['secretQuestion'];

        header('Location: authenticate.php');
        exit();
    }

    $error = 'User not found.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Property App - Login</title>
    <style>
        body { font-family: sans-serif; max-width: 480px; margin: 40px auto; }
        .error { color: #b00020; }
    </style>
</head>
<body>
    <h2>Step 1: Enter your name</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Name:</label><br>
        <input type="text" name="name" required autofocus><br><br>
        <button type="submit">Continue</button>
    </form>
</body>
</html>
