<?php
    session_start();
    require_once "db.php";

    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    $error = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username'] ?? '');
        
        $stmt = $conn->prepare("SELECT id, username FROM User WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['cart'] = [];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username.";
        }
    }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Enter your name</h2>
    <p>No password required. Use an existing username from the database.</p>
    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">Continue</button>
    </form>
    
</body>
</html>