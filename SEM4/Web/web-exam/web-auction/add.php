<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);
    $description = trim($_POST['description']);
    $current_user = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT id FROM Category WHERE name = ?");
    $stmt->execute([$category_name]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        $category_id = $category['id'];
    } else {
        $stmt = $conn->prepare("INSERT INTO Category (name) VALUES (?)");
        $stmt->execute([$category_name]);
        $category_id = $conn->lastInsertId();
    }

    $stmt = $conn->prepare("INSERT INTO Auction (`user`, categoryID, `description`, `date`) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$current_user, $category_id, $description]);
}

header("Location: index.php");
exit();