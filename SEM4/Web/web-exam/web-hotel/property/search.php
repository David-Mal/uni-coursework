<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'services/PropertyService.php';

requireLogin();
initSearchTracking();

$propertyService = new PropertyService($conn);
$query = trim($_GET['q'] ?? $_POST['q'] ?? '');
$results = [];

if ($query !== '') {
    $results = $propertyService->searchByDescription($query);
    recordSearchResults($results);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Property App - Search</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 24px auto; padding: 0 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
        nav { margin-bottom: 24px; }
    </style>
</head>
<body>
    <h2>Search Properties</h2>

    <nav>
        <a href="index.php">Home</a> |
        <a href="popular.php">Most Popular in Session</a> |
        <a href="shared.php">Properties With Multiple Owners</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <form method="GET" action="search.php">
        <label>Search by description:</label><br>
        <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if ($query !== ''): ?>
        <h3>Results for "<?php echo htmlspecialchars($query); ?>"</h3>

        <?php if (count($results) === 0): ?>
            <p>No properties found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Address</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $property): ?>
                        <tr>
                            <td><?php echo (int) $property['id']; ?></td>
                            <td><?php echo htmlspecialchars($property['address']); ?></td>
                            <td><?php echo htmlspecialchars($property['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
