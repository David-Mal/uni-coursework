<?php
require 'db.php';
$count = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
$cats  = $pdo->query("SELECT COUNT(DISTINCT category) FROM news")->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head>
    <title>About - News Service</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: sans-serif; display: flex; flex-direction: column; align-items: center; }
        .about-box { width: 600px; margin-top: 20px; }
        .about-box h2 { color: #333; }
        .about-box p { line-height: 1.7; color: #444; }
        .stats { display: flex; gap: 40px; margin: 24px 0; }
        .stat { text-align: center; }
        .stat-num { font-size: 2.2em; font-weight: bold; color: #0077cc; }
        .stat-label { font-size: 0.9em; color: #888; }
        .category-list { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .cat-tag {
            background: #f0f0f0;
            border-radius: 12px;
            padding: 4px 14px;
            font-size: 0.9em;
            color: #333;
        }
        .back-link { margin-top: 30px; display: inline-block; color: #0077cc; }
    </style>
</head>
<body>
<div class="about-box">
    <a href="index.php" class="back-link">&larr; Back to News</a>
    <br><br>
    <h2>About This News Service</h2>
    <p>
        This platform provides up-to-date news across a range of categories.
        Our editorial team publishes and updates articles daily to keep you
        informed on the topics that matter most.
    </p>

    <h3>Our Coverage</h3>
    <div class="category-list">
        <span class="cat-tag">Politics</span>
        <span class="cat-tag">Society</span>
        <span class="cat-tag">Health</span>
        <span class="cat-tag">Sports</span>
        <span class="cat-tag">Weather</span>
    </div>

    <h3>By the Numbers</h3>
    <div class="stats">
        <div class="stat">
            <div class="stat-num" id="js-article-count">...</div>
            <div class="stat-label">Articles published</div>
        </div>
        <div class="stat">
            <div class="stat-num"><?= $cats ?></div>
            <div class="stat-label">Categories covered</div>
        </div>
    </div>

    <h3>How It Works</h3>
    <p>
        Editors log in using a secure username and password to add or update
        news articles. Visitors can browse and filter news by date range and
        category without needing an account. All data is stored in a MySQL
        database and fetched asynchronously using AJAX.
    </p>

    <h3>Contact</h3>
    <p>For editorial inquiries, reach us at <a href="mailto:news@example.com">news@example.com</a>.</p>

    <a href="index.php" class="back-link">&larr; Back to News</a>
</div>

<script>
    fetch('fetch_news.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('js-article-count').textContent = data.length;
        })
        .catch(() => {
            document.getElementById('js-article-count').textContent = <?= $count ?>;
        });
</script>
</body>
</html>