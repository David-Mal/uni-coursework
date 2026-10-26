<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auction_id = $_POST['auction_id'];
    $new_description = trim($_POST['description']);
    $current_user = $_SESSION['username'];
    
    $stmt = $conn->prepare("UPDATE Auction SET description = ?, date = NOW() WHERE id = ? AND user = ?");
    $stmt->execute([$new_description, $auction_id, $current_user]);
}

header("Location: index.php");
exit();