<?php
require_once 'auth.php';
require_once 'db.php';

requireLogin();

$stmt = $conn->prepare("
    SELECT e.prompt,
           r.givenAnswer,
           r.correct,
           r.attemptedAt
    FROM Result r
    JOIN Exercise e ON r.exerciseId = e.id
    WHERE r.userId = :userId
    ORDER BY r.attemptedAt DESC
");
$stmt->execute([':userId' => $_SESSION['user_id']]);
$attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My History</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 32px auto; padding: 0 16px; }
        nav { margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px 12px; text-align: left; }
        th { background: #f5f5f5; }
        tr:nth-child(even) { background: #fafafa; }
        .correct   { color: #1b5e20; font-weight: bold; }
        .incorrect { color: #b71c1c; font-weight: bold; }
        .empty { color: #888; margin-top: 24px; }
    </style>
</head>
<body>
    <h2>Attempt History — <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <nav><a href="index.php">Back to Exercises</a> &nbsp;|&nbsp; <a href="logout.php">Logout</a></nav>

    <?php if (empty($attempts)): ?>
        <p class="empty">No attempts yet. Start practising!</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Exercise</th>
                    <th>Your Answer</th>
                    <th>Result</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attempts as $a): ?>
                <tr>
                    <td><?php echo htmlspecialchars($a['prompt']); ?></td>
                    <td><?php echo htmlspecialchars($a['givenAnswer']); ?></td>
                    <td class="<?php echo $a['correct'] ? 'correct' : 'incorrect'; ?>">
                        <?php echo $a['correct'] ? 'Correct' : 'Incorrect'; ?>
                    </td>
                    <td><?php echo htmlspecialchars($a['attemptedAt']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
