<?php
require_once 'db.php';
require_once 'auth.php';
requireLogin();

// Procesăm datele doar dacă formularul a fost trimis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $developer = trim($_POST['developer'] ?? '');
    $projects = $_POST['projects'] ?? []; // Default un array gol, nu string gol

    $stmtCheck = $conn->prepare('SELECT id FROM SoftwareDeveloper WHERE name = ?');
    $stmtCheck->execute([$developer]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $dev_id = $row['id'];

        // PREGĂTIM INTEROGĂRILE O SINGURĂ DATĂ (în afara buclei)
        $stmtSelectProj = $conn->prepare('SELECT id, members FROM Project WHERE name = ?');
        $stmtInsertProj = $conn->prepare('INSERT INTO Project(ProjectManagerID, name, members) VALUES(?, ?, ?)');
        $stmtUpdateProj = $conn->prepare('UPDATE Project SET members = ? WHERE id = ?');

        foreach($projects as $proj) {
            $proj = trim($proj);
            if (empty($proj)) continue; // Sărim peste proiectele cu nume gol

            $stmtSelectProj->execute([$proj]);
            $foundProject = $stmtSelectProj->fetch(PDO::FETCH_ASSOC);

            if ($foundProject) {
                // Dacă proiectul există, concatenăm noul membru (dacă nu e gol anterior)
                $currentMembers = $foundProject['members'];
                $newMembers = empty($currentMembers) ? $developer : $currentMembers . ',' . $developer;
                
                // Opțional: Poți adăuga un if aici să verifici cu strpos() dacă dev-ul există deja în listă

                // Salvăm în baza de date
                $stmtUpdateProj->execute([$newMembers, $foundProject['id']]);
            } else {
                // Dacă proiectul nu există, inserăm numele real ($proj)
                $stmtInsertProj->execute([$dev_id, $proj, $developer]);
            }
        }
        echo "Operațiune realizată cu succes!";
    } else {
        echo "Dev name does not exist in the database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <br>
    <a href="index.php">go back</a>
</body>
</html>