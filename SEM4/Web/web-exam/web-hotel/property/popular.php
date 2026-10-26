<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'services/PropertyService.php';
require_once 'services/SearchSessionService.php';

requireLogin();
initSearchTracking();

$propertyService = new PropertyService($conn);
$searchSessionService = new SearchSessionService($propertyService);
$popularProperty = $searchSessionService->getMostPopularProperty();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Property App - Most Popular</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 24px auto; padding: 0 16px; }
        .card { border: 1px solid #ddd; padding: 16px; background: #fafafa; }
        nav { margin-bottom: 24px; }
    </style>
</head>
<body>
    <h2>Most Popular Property in Current Session</h2>

    <nav>
        <a href="index.php">Home</a> |
        <a href="search.php">Search</a> |
        <a href="shared.php">Properties With Multiple Owners</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <?php if ($popularProperty === null): ?>
        <p>No search activity recorded in this session yet.</p>
    <?php else: ?>
        <div class="card">
            <p><strong>ID:</strong> <?php echo (int) $popularProperty['id']; ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($popularProperty['address']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($popularProperty['description']); ?></p>
            <p><strong>Times returned in searches:</strong> <?php echo (int) $popularProperty['searchHits']; ?></p>
        </div>
    <?php endif; ?>
</body>
</html>
