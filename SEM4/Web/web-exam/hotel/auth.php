<?php
/**
 * Single Responsibility: manages authentication state and user credential checks.
 */
require_once __DIR__ . '/db.php';

function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn(): bool {
    startSession();
    return isset($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function getCurrentUserId(): int {
    return (int) ($_SESSION['user_id'] ?? 0);
}

function getCurrentUsername(): string {
    return $_SESSION['username'] ?? '';
}

function attemptLogin(string $username, string $password): bool {
    $stmt = getDB()->prepare('SELECT id, password FROM `User` WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && $user['password'] === $password) {
        startSession();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        return true;
    }
    return false;
}

function logoutUser(): void {
    startSession();
    session_destroy();
    header('Location: login.php');
    exit;
}
