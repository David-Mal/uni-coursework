<?php
session_start();
require 'cors.php';
require 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Parse URL-encoded body
    parse_str(file_get_contents('php://input'), $data);
    $user = $data['username'] ?? '';
    $pass = $data['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $userData = $stmt->fetch();

    if ($userData && $pass === $userData['password']) {
        $_SESSION['user'] = $userData['username'];
        echo json_encode(['success' => true, 'username' => $userData['username']]);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
    }
} else {
    echo json_encode(['message' => 'Use POST to login']);
}
