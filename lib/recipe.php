<?php
require_once 'recipeinfo.php';
require_once 'Ingredients.php';

class Recipe
{
    private $connection;
    private $user;

    public function __construct($connection, $userObject)
    {
        $this->connection = $connection;
        $this->user = $userObject;
    }

    public function fetchRecipe($recipe_id)
    {
        $sql = "SELECT * FROM recipe WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $recipe_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $recipeTotal = $result->fetch_assoc();
        if ($recipeTotal) {
            $user_id = $recipeTotal['user_id'];  // haal user_id from recipe data
            $user = $this->fetchUser($user_id);  // Fetch user data

            $comments = $this->fetchRecipeInfoComments($recipe_id);
            $favourites = $this->fetchRecipeInfoFavourites($recipe_id);
            $ratings = $this->fetchRecipeInfoRatings($recipe_id);
            $preparationSteps = $this->fetchRecipeInfoPreparationSteps($recipe_id);
            $ingredients = $this->fetchIngredients($recipe_id);

            $recipeTotal['comments'] = $comments;
            $recipeTotal['favourites'] = $favourites;
            $recipeTotal['ratings'] = $ratings;
            $recipeTotal['preparationSteps'] = $preparationSteps;
            $recipeTotal['ingredients'] = $ingredients;
            $recipeTotal['user'] = $user;

            // Add price and calories calculations for 4 people
            $recipeTotal['priceFor4'] = $this->calcPricePer4people($recipe_id);
            $recipeTotal['caloriesFor4'] = $this->calcCaloriesPer4people($recipe_id);


            return $recipeTotal;
        }

        return false;  // false when no recipe found
    }

    public function fetchRecipes($recipe_ids = null)
    {
        $recipes = [];

        if ($recipe_ids === null) { //hiermee kan ik zeggen fetch all recipes c:
            $sql = "SELECT id FROM recipe ORDER BY id DESC";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $recipe_id = $row['id'];
                $recipeData = $this->fetchRecipe($recipe_id);
                if ($recipeData) {
                    $recipes[] = $recipeData;
                }
            }
        } else { // en anders doe je het per recipe_id c:
            foreach ($recipe_ids as $id) {
                $recipeData = $this->fetchRecipe($id);
                if ($recipeData) {
                    $recipes[] = $recipeData;
                }
            }
        }

        return $recipes;
    }

    // ◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆   Private functions for recipe info ◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆
    private function fetchRecipeInfoComments($recipe_id)
    {
        $recipeInfo = new RecipeInfo($this->connection, $this->user);
        return $recipeInfo->fetchRecipeInfo($recipe_id, 'C');
    }

    private function fetchRecipeInfoFavourites($recipe_id)
    {
        $recipeInfo = new RecipeInfo($this->connection, $this->user);
        return $recipeInfo->fetchRecipeInfo($recipe_id, 'F');
    }

    private function fetchRecipeInfoRatings($recipe_id)
    {
        $recipeInfo = new RecipeInfo($this->connection, $this->user);
        return $recipeInfo->fetchRecipeInfo($recipe_id, 'R');
    }

    private function fetchRecipeInfoPreparationSteps($recipe_id)
    {
        $recipeInfo = new RecipeInfo($this->connection, $this->user);
        return $recipeInfo->fetchRecipeInfo($recipe_id, 'P');
    }

    // ◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆
    private function fetchIngredients($recipe_id)
    {
        $ingredients = new Ingredients($this->connection);
        return $ingredients->fetchIngredients($recipe_id);
    }

    private function fetchUser($user_id)
    {
        return $this->user->fetchUser($user_id);
    }


    // ◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆   Private functions for price and calories calculations ◆◆◆◆◆◆◆◆◆◆◆◆◆◆

    private function calcPricePer4people($recipe_id)
    {
        $ingredients = $this->fetchIngredients($recipe_id);

        if (!$ingredients) {
            return 0;
        }

        $total = 0;

        foreach ($ingredients as $ingredient) {
            $price_in_cents = $ingredient['price'] * 100;
            $needed_amount_per_person = $ingredient['amount'];
            $needed_amount_for_4 = $needed_amount_per_person * 4;
            $package_size = $ingredient['package_size'];

            if ($package_size == 1) {
                $packages_needed = $needed_amount_for_4;
            } else {
                $packages_needed = ceil($needed_amount_for_4 / $package_size);
            }

            $item_cost_in_cents = $price_in_cents * $packages_needed;
            $total += $item_cost_in_cents;
        }

        return round($total);
    }

    private function calcCaloriesPer4people($recipe_id)
    {
        $ingredients = $this->fetchIngredients($recipe_id);

        if (!$ingredients) {
            return 0;
        }

        $totalCalories = 0;

        foreach ($ingredients as $ingredient) {
            $calories_per_unit = $ingredient['calories'];
            $needed_amount = $ingredient['amount'];
            $package_size = $ingredient['package_size'];

            if ($package_size == 1) {
                $actual_calories = $calories_per_unit * $needed_amount;
            } else {
                $calories_per_gram_or_unit = $calories_per_unit / $package_size;
                $actual_calories = $calories_per_gram_or_unit * $needed_amount;
            }

            $totalCalories += $actual_calories;
        }

        return round($totalCalories * 4);
    }
}
