<?php
require_once 'auth.php';
require_once 'db.php';
requireLogin();

$keyword = trim($_GET['keyword'] ?? '');
$result = [];
$error = '';

try {

    $stmt = $conn->prepare('SELECT id, address, description FROM Property WHERE description LIKE :keyword');
    $stmt->execute([
        ':keyword' => "%$keyword%"
    ]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $_SESSION['search_history'][] = $row['id'];
    }

} catch (PDOException $e){
    die("Eroare de cautare: ".$e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
</head>
<body>
    <h2>Search Results for: "<?php echo htmlspecialchars($keyword); ?>"</h2>
    <a href="index.php">Back to Dashboard</a><br><br>
    <?php if(empty($result)): ?>
        <p>Nu exista rezultate corespunzatoare cautarii tale.</p><br>
    <?php else: ?>
        <table>
            <tr>
                <th>Id</th>
                <th>Address</th>
                <th>Description</th>
            </tr>
            <?php foreach($result as $row):?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']);?></td>
                    <td><?php echo htmlspecialchars($row['address']);?></td>
                    <td><?php echo htmlspecialchars($row['description']);?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>