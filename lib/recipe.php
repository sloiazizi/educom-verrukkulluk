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
        } else {// en anders doe je het per recipe_id c:
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
            $price = $ingredient['price'];
            $amount = $ingredient['amount'];
            $unit = strtolower($ingredient['unit']);
            
            switch ($unit) {
                case 'per stuk':
                case 'per pak':
                case 'per fles':
                case 'per bosje':
                    $factor = 1;
                    break;
                case 'per 100g':
                    $factor = 0.01;
                    break;
                case 'per kg':
                    $factor = 0.001;
                    break;
                default:
                    $factor = 1; 
            }
            $itemPrice = $price * $amount * $factor;
            $total += $itemPrice;
        }
        
        return round($total * 4, 2);
    }

    private function calcCaloriesPer4people($recipe_id)
    {
        $ingredients = $this->fetchIngredients($recipe_id);
        
        if (!$ingredients) {
            return 0;
        }
        
        $totalCalories = 0;
        
        foreach ($ingredients as $ingredient) {
            $totalCalories += $ingredient['calories'];
        }

        return $totalCalories * 4;
    }
}