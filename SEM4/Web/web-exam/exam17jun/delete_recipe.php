<?php
require_once 'auth.php';
require_once 'db.php';

// Ne asigurăm că cererea este de tip POST și conține un ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_id'])) {
    $recipeId = (int)$_POST['recipe_id'];

    try {
        $conn->beginTransaction();

        // 1. Ștergem mai întâi toți pașii asociați acestei rețete
        $stmtSteps = $conn->prepare("DELETE FROM RecipeStep WHERE recipeId = :id");
        $stmtSteps->execute([':id' => $recipeId]);

        // 2. Ștergem rețeta efectivă
        $stmtRecipe = $conn->prepare("DELETE FROM Recipe WHERE id = :id");
        $stmtRecipe->execute([':id' => $recipeId]);

        $conn->commit();
        
        // Redirect înapoi pe index cu un mesaj de succes
        header("Location: index.php?msg=recipe_deleted");
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        die("Eroare la ștergerea rețetei: " . $e->getMessage());
    }
}

// Dacă scriptul este accesat direct (GET), dăm redirect
header("Location: index.php");
exit();
?>