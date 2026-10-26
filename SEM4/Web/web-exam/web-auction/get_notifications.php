<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username']) || !isset($_GET['last_id'])) {
    exit();
}

$last_id = (int)$_GET['last_id'];
$current_user = $_SESSION['username'];

$query = "SELECT a.user, c.name as category_name, a.description, a.date 
          FROM Auction a 
          JOIN Category c ON a.categoryID = c.id 
          WHERE a.id > ? AND a.user != ?";
$stmt = $conn->prepare($query);
$stmt->execute([$last_id, $current_user]);

$new_auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($new_auctions);