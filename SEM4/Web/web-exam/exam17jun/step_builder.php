<?php
require_once 'db.php';
require_once 'auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_title'])) {
    $_SESSION['recipe_title'] = trim($_POST['recipe_title']);
}

if (!isset($_SESSION['recipe_steps'])) {
    $_SESSION['recipe_steps'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 2. Acțiunea de ADAUGARE pas nou
    if (isset($_POST['action']) && $_POST['action'] === 'add_step') {
        $desc = trim($_POST['description'] ?? '');
        $ingreds = $_POST['ingredients'] ?? []; // Acesta va fi un array
        
        if ($desc !== '' && !empty($ingreds)) {
            $_SESSION['recipe_steps'][] = [
                'description' => $desc,
                'ingredients' => $ingreds
            ];
        }
    }

    // 3. Acțiunea de ȘTERGERE pas
    if (isset($_POST['action']) && $_POST['action'] === 'remove_step') {
        $index = (int)$_POST['step_index'];
        
        if (isset($_SESSION['recipe_steps'][$index])) {
            unset($_SESSION['recipe_steps'][$index]); // Șterge pasul
            
            // TRUCUL PENTRU RENUMEROTARE: array_values reface indecșii de la 0, 1, 2...
            $_SESSION['recipe_steps'] = array_values($_SESSION['recipe_steps']);
        }
    }
    
    // Refresh pagina (Pattern-ul Post-Redirect-Get) pentru a preveni re-trimiterea la F5
    header("Location: step_builder.php");
    exit();
}

// Extragem toate ingredientele din DB pentru a le afișa în formularul de adăugare
$stmt = $conn->query("SELECT id, name FROM Ingredient");
$allIngredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Build your Recipe: <?php echo htmlspecialchars($_SESSION['recipe_title'] ?? ''); ?></h2>

    <h3>Current Steps:</h3>
    <?php if (empty($_SESSION['recipe_steps'])): ?>
        <p>No steps added yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($_SESSION['recipe_steps'] as $index => $step): ?>
                <li>
                    <strong>Step <?php echo $index + 1; ?>:</strong> 
                    <?php echo htmlspecialchars($step['description']); ?>
                    
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="remove_step">
                        <input type="hidden" name="step_index" value="<?php echo $index; ?>">
                        <button type="submit" style="color:red; margin-left: 10px;">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <hr>

    <h3>Add a New Step</h3>
    <form method="POST">
        <input type="hidden" name="action" value="add_step">
        
        <label>Description:</label><br>
        <textarea name="description" required rows="3" cols="40"></textarea><br><br>
        
        <label>Select Ingredients (hold CTRL to select multiple):</label><br>
        <select name="ingredients[]" multiple required style="height: 100px; width: 200px;">
            <?php foreach ($allIngredients as $ing): ?>
                <option value="<?php echo $ing['id']; ?>">
                    <?php echo htmlspecialchars($ing['name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <button type="submit">Add Step</button>
    </form>

    <br><br>

    <?php if (!empty($_SESSION['recipe_steps'])): ?>
        <form action="review.php" method="GET">
            <button type="submit" style="padding: 10px; background-color: green; color: white;">Finish Building</button>
        </form>
    <?php endif; ?>
</body>
</html>