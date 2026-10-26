<?php
session_start();
require_once 'auth.php';
require_once 'db.php';

requireLogin();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$exerciseId  = (int) ($data['exerciseId']  ?? 0);
$givenAnswer = trim($data['givenAnswer'] ?? '');

if (!$exerciseId) {
    echo json_encode(['error' => 'Invalid exercise ID']);
    exit;
}

$stmt = $conn->prepare("SELECT correctAnswer, topic FROM Exercise WHERE id = :id");
$stmt->execute([':id' => $exerciseId]);
$exercise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exercise) {
    echo json_encode(['error' => 'Exercise not found']);
    exit;
}

$correct = (int) (strtolower($givenAnswer) === strtolower(trim($exercise['correctAnswer'])));

$ins = $conn->prepare("
    INSERT INTO Result (userId, exerciseId, givenAnswer, correct, attemptedAt)
    VALUES (:userId, :exerciseId, :givenAnswer, :correct, NOW())
");
$ins->execute([
    ':userId'      => $_SESSION['user_id'],
    ':exerciseId'  => $exerciseId,
    ':givenAnswer' => $givenAnswer,
    ':correct'     => $correct,
]);

echo json_encode(['correct' => (bool) $correct, 'topic' => $exercise['topic']]);
