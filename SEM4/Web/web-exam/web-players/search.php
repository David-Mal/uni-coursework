<?php
require_once 'db.php';
require_once 'auth.php';
requireLogin();
$q = '%' . $_GET['q'] . '%';
$stmt = $conn->prepare('SELECT name FROM Player WHERE name LIKE ?');
$stmt->execute([$q]);
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($players);
?>