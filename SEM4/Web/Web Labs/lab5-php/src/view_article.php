<?php
require 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { header("Location: index.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($article['title']) ?> - News Service</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: sans-serif; display: flex; flex-direction: column; align-items: center; }
        .article-box { width: 600px; margin-top: 20px; }
        .article-box h2 { color: #333; }
        .article-meta { color: #888; font-size: 0.9em; margin-bottom: 16px; }
        .article-body { line-height: 1.7; font-size: 1.05em; }
        .back-link { margin-top: 30px; display: inline-block; color: #0077cc; }
        .category-badge {
            display: inline-block;
            background: #0077cc;
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.8em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="article-box">
    <a href="index.php" class="back-link">&larr; Back to News</a>
    <br><br>
    <span class="category-badge"><?= htmlspecialchars($article['category']) ?></span>
    <h2><?= htmlspecialchars($article['title']) ?></h2>
    <div class="article-meta">
        By <strong><?= htmlspecialchars($article['producer']) ?></strong>
        &mdash; <?= htmlspecialchars($article['date']) ?>
    </div>
    <div class="article-body">
        <?= nl2br(htmlspecialchars($article['text'])) ?>
    </div>
    <a href="index.php" class="back-link">&larr; Back to News</a>
</div>
</body>
</html>