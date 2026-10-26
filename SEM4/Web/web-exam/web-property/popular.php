<?php
require_once 'auth.php';
require_once 'db.php';

requireLogin();

$result = null;

if (!empty($_SESSION['search_history'])) {
    
    $countValues = array_count_values($_SESSION['search_history']);
    
    arsort($countValues);
    
    $mostPopularId = array_key_first($countValues);

    $stmt = $conn->prepare('SELECT id, address, description FROM Property WHERE id = :id');
    $stmt->execute([
        ':id' => $mostPopularId
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Most popular property</title>
</head>
<body>
    <h1>The most popular property based on your search history is:</h1><br>
    
    <?php if ($result): ?>
        <p style="font-size:20px">
            <?php echo htmlspecialchars($result['id']) . '. ' . htmlspecialchars($result['address']) . ' -> ' . htmlspecialchars($result['description']); ?>
        </p>
    <?php else: ?>
        <p style="font-size:20px; color: red;">No search history available yet.</p>
    <?php endif; ?>
    
    <br><br>
    <a href="index.php">Back to Dashboard</a>
</body>
</html>