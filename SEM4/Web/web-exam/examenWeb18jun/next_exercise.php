<?php
session_start();
require_once 'auth.php';
require_once 'db.php';

requireLogin();

header('Content-Type: application/json');

$data        = json_decode(file_get_contents('php://input'), true);
$topicScores = $data['topicScores'] ?? [];
$userId      = $_SESSION['user_id'];

// topics that exist in the exercise
$stmt      = $conn->query("SELECT DISTINCT topic FROM Exercise");
$allTopics = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Topics missing from the score map start at 0
foreach ($allTopics as $topic) {
    if (!array_key_exists($topic, $topicScores)) {
        $topicScores[$topic] = 0;
    }
}

// sort ascending (weakest first)
asort($topicScores);

foreach ($topicScores as $topic => $score) {
    if ($score < 40) {
        $difficulty = 1;
    } elseif ($score < 70) {
        $difficulty = 2;
    } else {
        $difficulty = 3;
    }

    $stmt = $conn->prepare("
        SELECT e.id, e.prompt, e.topic, e.difficulty
        FROM Exercise e
        WHERE e.topic     = :topic
          AND e.difficulty = :difficulty
          AND e.id NOT IN (
              SELECT r.exerciseId
              FROM Result r
              WHERE r.userId = :userId
          )
        LIMIT 1
    ");
    $stmt->execute([
        ':topic'      => $topic,
        ':difficulty' => $difficulty,
        ':userId'     => $userId,
    ]);
    $exercise = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($exercise) {
        echo json_encode($exercise);
        exit;
    }
}

echo json_encode(['done' => true]);
