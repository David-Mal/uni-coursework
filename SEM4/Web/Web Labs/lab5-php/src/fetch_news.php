<?php
require 'db.php';

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

echo json_encode($results);