<?php
require_once 'auth.php';
require_once 'db.php';
requireLogin();

// Verificăm dacă există o rețetă în curs de construire
if (empty($_SESSION['recipe_title']) || empty($_SESSION['recipe_steps'])) {
    header("Location: index.php");
    exit();
}

// 1. Extragem toate ingredientele din DB pentru a le putea citi numele și caloriile
$stmt = $conn->query("SELECT id, name, caloriesPer100g FROM Ingredient");
$ingredientsData = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $ingredientsData[$row['id']] = $row;
}

// 2. Calculăm caloriile și pregătim datele pentru afișare
$totalCalories = 0;
$displaySteps = [];

// Variabile noi pentru cerința cu procentul
$foodGroupCounts = [];
$totalUsages = 0;

foreach ($_SESSION['recipe_steps'] as $step) {
    $stepIngredientNames = [];
    
    foreach ($step['ingredients'] as $ingId) {
        if (isset($ingredientsData[$ingId])) {
            $ingName = $ingredientsData[$ingId]['name'];
            $stepIngredientNames[] = $ingName;
            $totalCalories += $ingredientsData[$ingId]['caloriesPer100g'];
            
            // Extragem prefixul (ex: din "DAIRY-Butter" luăm "DAIRY")
            $parts = explode('-', $ingName);
            if (count($parts) > 1) {
                $foodGroup = $parts[0];
                
                // Creștem contorul pentru această grupă și numărul total de utilizări
                $foodGroupCounts[$foodGroup] = ($foodGroupCounts[$foodGroup] ?? 0) + 1;
                $totalUsages++;
            }
        }
    }
    
    $displaySteps[] = [
        'description' => $step['description'],
        'ingredient_names' => $stepIngredientNames
    ];
}

// Verificăm dacă o grupă depășește 60%
$dominantFoodGroup = null;
if ($totalUsages > 0) {
    foreach ($foodGroupCounts as $group => $count) {
        $percentage = $count / $totalUsages;
        if ($percentage > 0.60) {
            $dominantFoodGroup = $group;
            break; // Doar una poate fi peste 60%, deci ne oprim
        }
    }
}
// 3. Procesarea butoanelor Confirm / Discard
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if ($_POST['action'] === 'discard') {
        unset($_SESSION['recipe_title']);
        unset($_SESSION['recipe_steps']);
        header("Location: index.php");
        exit();
    }
    
    if ($_POST['action'] === 'confirm') {
        try {
            // Începem tranzacția pentru a salva în ambele tabele
            $conn->beginTransaction();
            
            // Inserăm rețeta (presupunem că ID-ul userului este în $_SESSION['user_id'])
            $stmtRecipe = $conn->prepare("INSERT INTO Recipe (userId, title, totalCalories) VALUES (:userId, :title, :cals)");
            $stmtRecipe->execute([
                ':userId' => $_SESSION['user_id'],
                ':title' => $_SESSION['recipe_title'],
                ':cals' => $totalCalories
            ]);
            
            // Luăm ID-ul rețetei abia inserate
            $newRecipeId = $conn->lastInsertId();
            
            // Pregătim inserarea pașilor
            $stmtStep = $conn->prepare("INSERT INTO RecipeStep (recipeId, stepNumber, description, ingredientIds) VALUES (:rId, :stepNum, :desc, :ingIds)");
            
            foreach ($_SESSION['recipe_steps'] as $index => $step) {
                // Transformăm array-ul de ID-uri [1, 4, 7] în string-ul "1,4,7"
                $ingredientIdsString = implode(',', $step['ingredients']);
                $stepNumber = $index + 1;
                
                $stmtStep->execute([
                    ':rId' => $newRecipeId,
                    ':stepNum' => $stepNumber,
                    ':desc' => $step['description'],
                    ':ingIds' => $ingredientIdsString
                ]);
            }
            
            // Validăm tranzacția
            $conn->commit();
            
            // Curățăm sesiunea
            unset($_SESSION['recipe_title']);
            unset($_SESSION['recipe_steps']);
            
            // Redirect cu succes
            header("Location: index.php?msg=recipe_saved");
            exit();
            
        } catch (PDOException $e) {
            $conn->rollBack();
            $error = "Eroare la salvarea rețetei: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<body>
    <h2>Review & Confirm Recipe</h2>
    
    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <h3>Title: <?php echo htmlspecialchars($_SESSION['recipe_title']); ?></h3>
    <p><strong>Total Calories:</strong> <?php echo $totalCalories; ?> kcal</p>

    <h3>Title: <?php echo htmlspecialchars($_SESSION['recipe_title']); ?></h3>
    <p><strong>Total Calories:</strong> <?php echo $totalCalories; ?> kcal</p>

    <?php if ($dominantFoodGroup): ?>
        <p style="color: darkorange; font-weight: bold;">
            This recipe is heavily <?php echo htmlspecialchars($dominantFoodGroup); ?>-based.
        </p>
    <?php endif; ?>

    <h4>Steps:</h4>
    <ul>
        <?php foreach ($displaySteps as $index => $ds): ?>
            <li>
                <strong>Step <?php echo $index + 1; ?>:</strong> <?php echo htmlspecialchars($ds['description']); ?><br>
                <em>Ingredients: <?php echo htmlspecialchars(implode(', ', $ds['ingredient_names'])); ?></em>
            </li>
        <?php endforeach; ?>
    </ul>

    <hr>
    
    <form method="POST" style="display:inline;">
        <input type="hidden" name="action" value="confirm">
        <button type="submit" style="background-color: green; color: white; padding: 10px;">Confirm & Save</button>
    </form>

    <form method="POST" style="display:inline;">
        <input type="hidden" name="action" value="discard">
        <button type="submit" style="background-color: red; color: white; padding: 10px;">Discard</button>
    </form>
    
</body>
</html>