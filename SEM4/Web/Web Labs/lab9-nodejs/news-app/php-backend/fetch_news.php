<?php
session_start();
require 'cors.php';
require 'db.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$category = $_GET['category'] ?? '';
$startDate = (!empty($_GET['start'])) ? $_GET['start'] : '1900-01-01';
$endDate = (!empty($_GET['end'])) ? $_GET['end'] : '2099-12-31';

$sql = "SELECT * FROM news WHERE date BETWEEN ? AND ?";
$params = [$startDate, $endDate];

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($results);
