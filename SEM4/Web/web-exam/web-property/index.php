<?php
require_once 'auth.php';
require_once 'db.php';
requireLogin();
$error = $_GET['error'] ?? '';
$success = ($_GET['success'] ?? '') === '1';
$stmt = $conn->prepare('
    SELECT p.id, p.address, p.description 
    FROM Property p 
    JOIN UserToProperties up ON p.id = up.idProperty 
    WHERE up.idUser = :userId
');
$stmt->execute([':userId' => $_SESSION['user_id']]);
$myProperties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property App</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']) ?>!</h2>
    <a href="logout.php">logout</a><br>

    <?php if ($success): ?>
            <div style="color:green; font-weight:bold; margin-bottom:15px;">
            Property added successfully!
            </div>
    <?php endif; ?>

    <?php if ($error): ?>
            <div style="color:red; font-weight:bold; margin-bottom:15px;">
            <?php if($error === 'already_owned'): ?>
                You already own this property!
            <?php else: ?>
                An error has occured while saving this property!
            <?php endif; ?>
            </div>
    <?php endif; ?>

    <h3>Your Properties</h3>

<?php if (empty($myProperties)): ?>
    <p>You don't have any properties listed yet.</p>
<?php else: ?>
    <table border="1">
        <tr style="background-color: #f4f4f4;">
            <th style="padding: 8px;">Address</th>
            <th style="padding: 8px;">Description</th>
            <th style="padding: 8px;">Actions</th>
        </tr>
        <?php foreach ($myProperties as $property): ?>
            <tr>
                <td style="padding: 8px;"><?php echo htmlspecialchars($property['address']); ?></td>
                <td style="padding: 8px;"><?php echo htmlspecialchars($property['description']); ?></td>
                <td style="padding: 8px;">
                    <form action="delete_action.php" method="POST" onsubmit="return confirm('Are you sure?');">
                        <input type="hidden" name="property_id" value="<?php echo htmlspecialchars($property['id']); ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

    <p>Choose a property:</p>
    <form method="POST" action="property_action.php">
        <input type="text" name="address" placeholder="address" required><br>
        <input type="text" name="description" placeholder="description" required>
        <button type="submit">Add property</button>
    </form>

    <br>
    <br>
    <p>Search for a property:</p>
    <form method="GET" action="search.php">
        <input type="text" name="keyword" placeholder="type a property..." required>
        <button type="submit">Search</button>
    </form>
    <br>
    <form method="GET" action="popular.php">
        <button type="submit">Most popular property: </button>
    </form>
    <br>
    <form method="GET" action="shared_properties.php">
        <button type="submit">Properties with more than 1 owner: </button>
    </form>
    
</body>
</html>