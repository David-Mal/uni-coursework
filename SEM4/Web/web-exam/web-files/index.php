<?php
require_once 'auth.php';
require_once 'db.php';
require_once 'functions.php';
requireLogin();


$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];

$limit = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$totalFiles = getTotalFilesCount($conn, $userId);
$totalPages = ceil($totalFiles / $limit);
$files = getPaginatedFiles($conn, $userId, $limit, $offset);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Files App - Dashboard</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .pagination { margin-top: 20px; }
        .pagination a { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; margin-right: 5px; color: black; }
        .pagination a.active { background-color: #007BFF; color: white; border: 1px solid #007BFF; }
        .popular-box { margin-top: 30px; background: #f0f4f8; padding: 15px; border-left: 4px solid #007BFF; }
    </style>
</head>
<body>
    <h2>Salut, <?php echo htmlspecialchars($username); ?>! (<a href="logout.php">Logout</a>)</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Filename</th>
                <th>Filepath</th>
                <th>Size (bytes)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($files)): ?>
                <tr><td colspan="4">Nu ai niciun fișier adăugat.</td></tr>
            <?php else: ?>
                <?php foreach ($files as $file): ?>
                    <tr class="file-row" data-id="<?php echo $file['id']; ?>" data-filename="<?php echo htmlspecialchars($file['filename']); ?>">
                        <td><?php echo $file['id']; ?></td>
                        <td><?php echo htmlspecialchars($file['filename']); ?></td>
                        <td><?php echo htmlspecialchars($file['filepath']); ?></td>
                        <td><?php echo $file['size']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="index.php?page=<?php echo $page - 1; ?>">&laquo; Înapoi</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="index.php?page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="index.php?page=<?php echo $page + 1; ?>">Înainte &raquo;</a>
        <?php endif; ?>
    </div>

    <div class="popular-box">
        <strong>Cel mai popular fișier vizualizat în această sesiune:</strong> 
        <span id="popular-file">Se calculează...</span>
    </div>

    <script src="script.js"></script>
</body>
</html>