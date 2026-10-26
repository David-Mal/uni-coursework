<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$current_user = $_SESSION['username'];

$query = "SELECT a.id, a.user, a.description, a.date, c.name as category_name 
          FROM Auction a 
          JOIN Category c ON a.categoryID = c.id 
          ORDER BY a.date DESC";
$result = $conn->query($query);

$max_id_query = $conn->query("SELECT MAX(id) as max_id FROM Auction");
$max_id_row = $max_id_query->fetch(PDO::FETCH_ASSOC);
$last_known_id = $max_id_row['max_id'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Auctions</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px;}
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($current_user); ?>!</h2>
    <a href="logout.php">Logout</a>

    <h3>All Auctions</h3>
    <table>
        <tr>
            <th>User</th>
            <th>Category</th>
            <th>Description</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['user']); ?></td>
                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                
                <?php if ($row['user'] === $current_user): ?>
                    <td>
                        <form method="POST" action="update.php" style="margin: 0;">
                            <input type="hidden" name="auction_id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="description" value="<?php echo htmlspecialchars($row['description']); ?>" required>
                    </td>
                    <td><?php echo $row['date']; ?></td>
                    <td>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                <?php else: ?>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td>-</td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </table>

    <hr>
    <h3>Add New Auction</h3>
    <form method="POST" action="add.php">
        <label>Category Name:</label><br>
        <input type="text" name="category_name" required><br><br>
        
        <label>Description:</label><br>
        <input type="text" name="description" required><br><br>
        
        <button type="submit">Add Auction</button>
    </form>
    <script>
        let lastId = <?php echo $last_known_id; ?>;

        function checkNewAuctions() {
            fetch('get_notifications.php?last_id=' + lastId)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(auction => {
                            alert(`Noutate! ${auction.user} a adăugat în categoria ${auction.category_name}:\nDescriere: ${auction.description}\nData: ${auction.date}`);
                        });
                        
                        location.reload(); 
                    }
                })
                .catch(error => console.error('Eroare la verificare:', error));
        }

        setInterval(checkNewAuctions, 3000);
    </script>
</body>
</html>