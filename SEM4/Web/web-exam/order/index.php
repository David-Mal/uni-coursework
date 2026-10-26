<?php

require_once __DIR__ . '/bootstrap.php';

$db = Database::getConnection();
$userRepository = new UserRepository($db);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $user = $userRepository->findByUsername($username);

    if ($user === null) {
        $error = 'User not found. Please enter a valid username (alice, bob, or charlie).';
    } else {
        $_SESSION['user'] = $user;
        $_SESSION['cart'] = [];
        $_SESSION['warnings'] = [];
        header('Location: order.php');
        exit;
    }
}

renderHeader('Login');
?>

<h1>Order Application</h1>
<p>Enter your username to start building an order.</p>

<?php if ($error !== ''): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <label>
        Username:
        <input type="text" name="username" required autofocus>
    </label>
    <button type="submit">Continue</button>
</form>

<?php renderFooter(); ?>
