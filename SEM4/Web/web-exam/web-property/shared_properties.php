<?php
require_once 'auth.php';
require_once 'db.php';
requireLogin();

$results = [];

try {
    $stmt = $conn->query("
        SELECT p.address, p.description, COUNT(up.idUser) as owners_count
        FROM Property p 
        JOIN UserToProperties up ON p.id = up.idProperty 
        GROUP BY p.id 
        HAVING COUNT(up.idUser) > 1
    ");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Eroare la extragerea datelor: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Properties</title>
</head>
<body>
    <h2>Properties with Multiple Owners</h2>
    <a href="index.php">Back to Dashboard</a><br><br>

    <?php if (empty($results)): ?>
        <p>No properties are currently owned by multiple users.</p>
    <?php else: ?>
        <table border="1" style="border-collapse: collapse; width: 100%; max-width: 600px;">
            <tr style="background-color: #f4f4f4;">
                <th style="padding: 8px;">Address</th>
                <th style="padding: 8px;">Description</th>
                <th style="padding: 8px;">Total Owners</th>
            </tr>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td style="padding: 8px;"><?php echo htmlspecialchars($row['address']); ?></td>
                    <td style="padding: 8px;"><?php echo htmlspecialchars($row['description']); ?></td>
                    <td style="padding: 8px; text-align: center;"><?php echo htmlspecialchars($row['owners_count']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>