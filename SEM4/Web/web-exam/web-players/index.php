<?php
require_once 'db.php';
require_once 'auth.php';
require_once 'functions.php'; // getDegreeN, getColleagues
requireLogin();

$currentPlayerID = $_SESSION['user_id'];

// Valori default
$n = 1;
$checkboxChecked = false;
$players = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = (int)$_POST['degree'];
    $checkboxChecked = isset($_POST['samePosition']);

    $players = getDegreeN($conn, $currentPlayerID, $n);

    if ($checkboxChecked) {
        $stmtPos = $conn->prepare('SELECT position FROM Player WHERE ID = ?');
        $stmtPos->execute([$currentPlayerID]);
        $currentPosition = $stmtPos->fetchColumn();

        $players = array_filter($players, function($p) use ($currentPosition) {
            return $p['position'] === $currentPosition;
        });
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Players</title>
</head>
<body>

    <!-- SEARCH -->
    <input type="text" id="search" placeholder="Caută jucător...">
    <ul id="results"></ul>

    <hr>

    <!-- N-TH DEGREE -->
    <form method="POST">
        <label for="degree">Grad:</label>
        <select name="degree" id="degree">
            <option value="1" <?= $n == 1 ? 'selected' : '' ?>>1</option>
            <option value="2" <?= $n == 2 ? 'selected' : '' ?>>2</option>
            <option value="3" <?= $n == 3 ? 'selected' : '' ?>>3</option>
        </select>

        <label>
            <input type="checkbox" name="samePosition" <?= $checkboxChecked ? 'checked' : '' ?>>
            Aceeași poziție
        </label>

        <button type="submit">Afișează</button>
    </form>

    <!-- REZULTATE N-TH DEGREE -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php if (!empty($players)): ?>
            <ul>
                <?php foreach ($players as $p): ?>
                    <li><?= htmlspecialchars($p['name']) ?> — <?= htmlspecialchars($p['position']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No players found.</p>
        <?php endif; ?>
    <?php endif; ?>

    <script>
    document.getElementById('search').addEventListener('keyup', function() {
        const query = this.value;

        if (query.length === 0) {
            document.getElementById('results').innerHTML = '';
            return;
        }

        fetch('search.php?q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(players => {
                players.sort((a, b) => a.name.localeCompare(b.name));

                const ul = document.getElementById('results');
                ul.innerHTML = players
                    .map(p => `<li>${p.name}</li>`)
                    .join('');
            });
    });
    </script>

</body>
</html>