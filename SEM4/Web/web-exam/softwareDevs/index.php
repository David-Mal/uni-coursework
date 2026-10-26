<?php
require_once 'db.php';
require_once 'auth.php';
requireLogin();
$username = trim($_SESSION['username'] ?? '');
$stmt = $conn->query('SELECT p.name as projname, sd.name as devname, p.description, p.members FROM SoftwareDeveloper sd JOIN Project p ON sd.id=p.ProjectManagerID');
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT p.name as projname, sd.name as devname, p.description, p.members FROM SoftwareDeveloper sd JOIN Project p ON sd.id=p.ProjectManagerID WHERE p.members LIKE ?');
$stmt->execute(["%$username%"]);
$userProjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtDevs = $conn->query('SELECT name, skills FROM SoftwareDeveloper');
$allDevelopers = $stmtDevs->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Software Devs App</title>
</head>
<body>
    <h2>Welcome, <?php echo $username ?>!</h2> <br>
    <a href="logout.php">Logout</a>
    <br><br>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>proj. Manager</th>
            <th>Description</th>
            <th>Members</th>
        </tr>
        <?php foreach($projects as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['projname'])?></td>
                <td><?php echo htmlspecialchars($row['devname'])?></td>
                <td><?php echo htmlspecialchars($row['description'])?></td>
                <td><?php echo htmlspecialchars($row['members'])?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <hr>
    <h3><?php echo $_SESSION['username'] ?>'s projects:</h3>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>proj. Manager</th>
            <th>Description</th>
            <th>Members</th>
        </tr>
        <?php foreach($userProjects as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['projname'])?></td>
                <td><?php echo htmlspecialchars($row['devname'])?></td>
                <td><?php echo htmlspecialchars($row['description'])?></td>
                <td><?php echo htmlspecialchars($row['members'])?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <hr>
    <p>Assign another dev to a list of projects:</p>
    <form method="POST" action="add.php">
        <label>Nume Developer:</label>
        <input type="text" name="developer" required>
        <br><br>

        <div id="containerproj">
            <div>
                <label>Proiect 1:</label>
                <input type="text" name="projects[]" required>
            </div>
        </div>
        
        <br>
        <button type="button" id="butonadd">Adaugă încă un proiect</button>
        <br><br>
        <button type="submit">Asignează Proiectele</button>
    </form>

    <hr>
<h3>Caută Developeri după Skill</h3>

<label for="skill-input">Skill căutat:</label>
<input type="text" id="skill-input" placeholder="ex: Rainbow flick">
<button type="button" id="btn-show-devs">Afișează și Filtrează</button>

<br><br>

<div id="container-developeri" style="display: none;">
    <h4>Rezultate:</h4>
    <ul id="lista-developeri">
        <?php foreach($allDevelopers as $dev): ?>
            <li class="dev-item" data-skills="<?= htmlspecialchars(strtolower($dev['skills'] ?? '')) ?>">
                <strong><?= htmlspecialchars($dev['name']) ?></strong> 
                (Skills: <?= htmlspecialchars($dev['skills'] ?? 'Nespecificat') ?>)
            </li>
        <?php endforeach; ?>
    </ul>
    <p id="mesaj-lipsa" style="display: none; color: red;">Nu s-au găsit developeri cu acest skill.</p>
</div>

<script>
    document.getElementById('btn-show-devs').addEventListener('click', function() {
        // 1. Afișăm containerul cu rezultate când se apasă butonul
        document.getElementById('container-developeri').style.display = 'block';

        // 2. Preluăm textul introdus de utilizator și îl transformăm în litere mici
        const skillCautat = document.getElementById('skill-input').value.toLowerCase().trim();
        
        // 3. Preluăm toate elementele <li> din listă
        const devItems = document.querySelectorAll('.dev-item');
        let gasitCelPutinUnul = false;

        // 4. Parcurgem fiecare developer în parte
        devItems.forEach(function(item) {
            // Citim atributul data-skills setat din PHP
            const skillsDeveloper = item.getAttribute('data-skills');

            // Verificăm dacă textul căutat se regăsește în skill-urile developerului
            // Dacă inputul este gol, afișăm toți developerii
            if (skillCautat === '' || skillsDeveloper.includes(skillCautat)) {
                item.style.display = 'list-item'; // Îl arătăm
                gasitCelPutinUnul = true;
            } else {
                item.style.display = 'none'; // Îl ascundem
            }
        });

        // 5. Afișăm un mesaj dacă niciun developer nu se potrivește
        const mesajLipsa = document.getElementById('mesaj-lipsa');
        if (gasitCelPutinUnul) {
            mesajLipsa.style.display = 'none';
        } else {
            mesajLipsa.style.display = 'block';
        }
    });
</script>

<script src="add_new_button.js"></script>

</body>
</html>