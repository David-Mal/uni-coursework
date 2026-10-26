<?php
require_once 'auth.php';
require_once 'db.php';
requireLogin();

$myRecipes = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT id, title, totalCalories FROM Recipe WHERE userId = :userId");
    $stmt->execute([':userId' => $_SESSION['user_id']]);
    $myRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title</title>
    <style>
        body { font-family: sans-serif; max-width: 480px; margin: 40px auto; }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']) ?>!</h2>
    <a href="logout.php">logout</a><br>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'recipe_saved'): ?>
            <p style="color: green; font-weight: bold;">Recipe saved successfully in the database!</p>
        <?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'recipe_deleted'): ?>
            <p style="color: blue; font-weight: bold;">Recipe and its steps were deleted.</p>
        <?php endif; ?>

        <hr>

    <h3>Your Saved Recipes</h3>
        <?php if (empty($myRecipes)): ?>
            <p>You haven't saved any recipes yet.</p>
        <?php else: ?>
            <table border="1" style="border-collapse: collapse; width: 100%; max-width: 600px;">
                <tr style="background-color: #f4f4f4;">
                    <th style="padding: 8px;">ID</th>
                    <th style="padding: 8px;">Recipe Title</th>
                    <th style="padding: 8px;">Total Calories</th>
                    <th style="padding: 8px;">Actions</th>
                </tr>
                <?php foreach ($myRecipes as $recipe): ?>
                    <tr>
                        <td style="padding: 8px; text-align: center;"><?php echo $recipe['id']; ?></td>
                        <td style="padding: 8px;"><?php echo htmlspecialchars($recipe['title']); ?></td>
                        <td style="padding: 8px; text-align: center;"><?php echo $recipe['totalCalories']; ?> kcal</td>
                        <td style="padding: 8px; text-align: center;">
                            <form action="delete_recipe.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                                <input type="hidden" name="recipe_id" value="<?php echo $recipe['id']; ?>">
                                <button type="submit" style="color: red;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

    <h3>Start building a recipe:</h3>

    <form action="step_builder.php" method="POST">
    <label for="title">Recipe Title:</label><br>
    <input type="text" id="title" name="recipe_title" required><br><br>
    
    <button type="submit">Next</button>
    </form>

    

    
</body>
</html>