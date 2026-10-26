<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'services/PropertyService.php';

requireLogin();
initSearchTracking();

$propertyService = new PropertyService($conn);
$userId = (int) $_SESSION['user_id'];
$myProperties = $propertyService->getUserProperties($userId);
$allProperties = $propertyService->getAllProperties();

$message = $_GET['message'] ?? '';
$success = ($_GET['success'] ?? '') === '1';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Property App</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 24px auto; padding: 0 16px; }
        nav { margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f5f5f5; }
        .message { padding: 10px; border-radius: 4px; margin-bottom: 16px; }
        .message.ok { background: #e8f5e9; color: #1b5e20; }
        .message.error { background: #ffebee; color: #b71c1c; }
        .panel { border: 1px solid #ddd; padding: 16px; margin-bottom: 24px; background: #fafafa; }
        .hint { color: #555; font-size: 0.95rem; }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>

    <nav>
        <a href="search.php">Search Properties</a> |
        <a href="popular.php">Most Popular in Session</a> |
        <a href="shared.php">Properties With Multiple Owners</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <?php if ($message): ?>
        <div class="message <?php echo $success ? 'ok' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="panel">
        <h3>Add Property To Yourself</h3>
        <p class="hint">
            Use the same form for both cases: leave "Existing property ID" empty to create a new property,
            or fill it to link an existing one.
        </p>

        <form method="POST" action="property_action.php">
            <input type="hidden" name="action" value="add">

            <label>Existing property ID (optional):</label><br>
            <input type="number" name="propertyId" min="1"><br><br>

            <label>Address (required only for new property):</label><br>
            <input type="text" name="address" style="width: 100%;"><br><br>

            <label>Description (required only for new property):</label><br>
            <textarea name="description" rows="3" style="width: 100%;"></textarea><br><br>

            <button type="submit">Add property</button>
        </form>

        <p class="hint">Existing properties you can link by ID:</p>
        <ul>
            <?php foreach ($allProperties as $property): ?>
                <li>
                    #<?php echo (int) $property['id']; ?> -
                    <?php echo htmlspecialchars($property['address']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <h3>My Properties</h3>
    <?php if (count($myProperties) === 0): ?>
        <p>You do not own any properties yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Address</th>
                    <th>Description</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($myProperties as $property): ?>
                    <tr>
                        <td><?php echo (int) $property['id']; ?></td>
                        <td><?php echo htmlspecialchars($property['address']); ?></td>
                        <td><?php echo htmlspecialchars($property['description']); ?></td>
                        <td>
                            <form method="POST" action="property_action.php" style="margin:0;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="propertyId" value="<?php echo (int) $property['id']; ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
