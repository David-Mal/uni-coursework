<?php
require_once 'auth.php';
startSession();

if (isLoggedIn()) {
    header('Location: rooms.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (attemptLogin($username, $password)) {
        header('Location: rooms.php');
        exit;
    }
    $error = 'Invalid username or password.';
}
?>
<?php require 'header.php'; ?>
<div class="row justify-content-center mt-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4">Login</h3>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require 'footer.php'; ?>
