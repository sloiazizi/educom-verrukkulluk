<?php
require_once("lib/db.php");
require_once("lib/recipe.php");
require_once("lib/user.php");

$db = new Database();
$connection = $db->getConnection();
$user = new User($connection);
$recipe = new Recipe($connection, $user);


$recipeIds = [2, 3]; 
$allRecipes = $recipe->fetchRecipes($recipeIds);

// Toon recepten - layout heb ik gepakt van reddit post hehe
foreach ($allRecipes as $recipeData) {
    echo "<h2>" . htmlspecialchars($recipeData['title']) . "</h2>";
    echo "<p>" . nl2br(htmlspecialchars($recipeData['long_description'] ?? $recipeData['short_description'])) . "</p>";
    echo "<p>Prijs voor 4 personen: €" . number_format($recipeData['priceFor4'], 2) . "</p>";
    echo "<p>Calorieën: " . intval($recipeData['caloriesFor4']) . " kcal</p>";
    echo "<hr>";
}