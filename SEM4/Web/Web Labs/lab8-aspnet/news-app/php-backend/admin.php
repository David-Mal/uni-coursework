<?php
session_start();
require 'cors.php';
require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

// DELETE
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    echo json_encode(['success' => true, 'action' => 'deleted']);
    exit;
}

// CREATE or UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    parse_str(file_get_contents('php://input'), $data);

    if (!empty($data['id'])) {
        $stmt = $pdo->prepare("UPDATE news SET title=?, text=?, producer=?, date=?, category=? WHERE id=?");
        $stmt->execute([$data['title'], $data['text'], $data['producer'], $data['date'], $data['category'], $data['id']]);
        echo json_encode(['success' => true, 'action' => 'updated']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO news (title, text, producer, date, category) VALUES (?,?,?,?,?)");
        $stmt->execute([$data['title'], $data['text'], $data['producer'], $data['date'], $data['category']]);
        echo json_encode(['success' => true, 'action' => 'created', 'id' => $pdo->lastInsertId()]);
    }
    exit;
}

// GET — list all news (for admin table)
$news = $pdo->query("SELECT * FROM news ORDER BY date DESC")->fetchAll();
echo json_encode($news);
