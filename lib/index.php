<?php
require_once("lib/db.php");
require_once("lib/article.php");
require_once("lib/user.php");
require_once("lib/cuisinetype.php");
require_once("lib/ingredients.php");
require_once("lib/recipeinfo.php");

/// INIT
$db = new Database();
$connection = $db->getConnection();

$art = new Article($connection);
$user = new User($connection);
$ct = new CuisineType($connection);
$ingredients = new Ingredients($connection);
$recipeInfo = new RecipeInfo($connection, $user);

/// VERWERK
$dataArticle       = $art->fetchArticle(8);
$dataUser          = $user->fetchUser(1);
$dataCuisineType   = $ct->fetchCuisineType(4);
$dataIngredients   = $ingredients->fetchIngredientsWithArticle(1);

$dataRecipePrep       = $recipeInfo->getRecipeInfo(1, 'P');
$dataRecipeComments   = $recipeInfo->getRecipeInfo(1, 'C');
$dataRecipeFavourites = $recipeInfo->getRecipeInfo(1, 'F');
$dataRecipeRatings    = $recipeInfo->getRecipeInfo(1, 'R');

/// RETURN
echo "<pre>";
var_dump(
    $dataRecipePrep,
    $dataRecipeComments,
    $dataRecipeFavourites,
    $dataRecipeRatings
);
echo "</pre>";
