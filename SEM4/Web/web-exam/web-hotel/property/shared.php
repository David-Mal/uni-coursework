<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'services/PropertyService.php';

requireLogin();

$propertyService = new PropertyService($conn);
$sharedProperties = $propertyService->getPropertiesWithMultipleOwners();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Property App - Shared Properties</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 24px auto; padding: 0 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
        nav { margin-bottom: 24px; }
    </style>
</head>
<body>
    <h2>Properties With More Than One Owner</h2>

    <nav>
        <a href="index.php">Home</a> |
        <a href="search.php">Search</a> |
        <a href="popular.php">Most Popular in Session</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <?php if (count($sharedProperties) === 0): ?>
        <p>No shared properties found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Address</th>
                    <th>Description</th>
                    <th>Owners</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sharedProperties as $property): ?>
                    <tr>
                        <td><?php echo (int) $property['id']; ?></td>
                        <td><?php echo htmlspecialchars($property['address']); ?></td>
                        <td><?php echo htmlspecialchars($property['description']); ?></td>
                        <td><?php echo (int) $property['ownerCount']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
