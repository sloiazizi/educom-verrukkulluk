<?php
// First ensure the "Autoloader" from vendor is included:
require_once("./vendor/autoload.php");
// Connect Twig:
$loader = new \Twig\Loader\FilesystemLoader("./templates");
// FOR PRODUCTION:
// $twig = new \Twig\Environment($loader), ["cache" => "./cache/cc"]);
// FOR DEVELOPMENT:
$twig = new \Twig\Environment($loader, ["debug" => true]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

// Next step, do something with your data. Fetch or so
require_once("lib/recipe.php");
require_once("lib/user.php");
require_once("lib/db.php");
// Initialize database connection and user
$database = new Database();
$connection = $database->getConnection();
$user = new User($connection);
$recipe = new Recipe($connection, $user);
$recipe_id = isset($_GET["recipe_id"]) ? (int)$_GET["recipe_id"] : null;
$action = isset($_GET["action"]) ? $_GET["action"] : "homepage";
switch($action) {
    case "homepage": {
        $data = $recipe->fetchRecipes(); // Fetch all recipes
        $template = 'homepage.html.twig';
        $title = "Recipe Homepage";
        break;
    }
    case "detail": {
        if ($recipe_id) {
            $data = $recipe->fetchRecipe($recipe_id); // Fetch single recipe
            $template = 'detail.html.twig';
            $title = "Recipe Details";
        } else {
            // If no recipe_id provided, redirect to homepage
            $data = $recipe->fetchRecipes();
            $template = 'homepage.html.twig';
            $title = "Recipe Homepage";
        }
        break;
    }
    default: {
        $data = $recipe->fetchRecipes();
        $template = 'homepage.html.twig';
        $title = "Recipe Homepage";
        break;
    }
}
$templateFile = $twig->load($template);
// And show that stuff!
echo $templateFile->render(["title" => $title, "data" => $data]);