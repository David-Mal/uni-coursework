<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: admin.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['id']) {
        $stmt = $pdo->prepare("UPDATE news SET title=?, text=?, producer=?, date=?, category=? WHERE id=?");
        $stmt->execute([$_POST['title'], $_POST['text'], $_POST['producer'], $_POST['date'], $_POST['category'], $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO news (title, text, producer, date, category) VALUES (?,?,?,?,?)");
        $stmt->execute([$_POST['title'], $_POST['text'], $_POST['producer'], $_POST['date'], $_POST['category']]);
    }
}

$news = $pdo->query("SELECT * FROM news")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <style>
        input, textarea { display: block; margin: 10px 0; width: 300px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
    <h2>Admin Panel - Manage News</h2>
    <p>Welcome, <?= $_SESSION['user'] ?> | <a href="logout.php" style="color: rgb(255 0 0 / 0.87);">Logout</a></p>
    <form method="POST" id="newsForm">
        <input type="hidden" name="id" id="form-id">
        <input type="text" name="title" id="form-title" placeholder="Title" required minlength="5">
        <textarea name="text" id="form-text" placeholder="News Text" required minlength="10"></textarea>
        <input type="text" name="producer" id="form-producer" placeholder="Producer">
        <input type="date" name="date" id="form-date" required>
        <select name="category" id="form-cat">
            <option value="politics">Politics</option>
            <option value="society">Society</option>
            <option value="health">Health</option>
            <option value="sports">Sports</option>
            <option value="weather">Weather</option>
        </select>
        <button type="submit">Save News</button>
        <button type="button" onclick="resetForm()">Cancel</button>
    </form>

    <table>
        <tr><th>Title</th><th>Category</th><th>Actions</th></tr>
        <?php foreach ($news as $n): ?>
        <tr>
            <td><?= $n['title'] ?></td>
            <td><?= $n['category'] ?></td>
            <td>
                <button onclick="editItem(<?= htmlspecialchars(json_encode($n)) ?>)">Edit</button>
                <a href="admin.php?delete=<?= $n['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <script>
        function editItem(item) {
            document.getElementById('form-id').value = item.id;
            document.getElementById('form-title').value = item.title;
            document.getElementById('form-text').value = item.text;
            document.getElementById('form-producer').value = item.producer;
            document.getElementById('form-date').value = item.date;
            document.getElementById('form-cat').value = item.category;
        }
        function resetForm() {
            if(confirm("Discard changes?")) document.getElementById('newsForm').reset();
        }
    </script>
</body>
</html>