<?php
class Recipe
{
    private $connection;
    private $ingredients;
    private $user;
    private $cuisineType;  

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->ingredients = new Ingredients($connection);
        $this->user = new User($connection);
        $this->cuisineType = new CuisineType($connection);
    }

        public function fetchRecipe($recipe_id)
        {
            $sql = "SELECT * FROM recipe WHERE id = ?";
            $stmt = mysqli_prepare($this->connection, $sql);
            mysqli_stmt_bind_param($stmt, "i", $recipe_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 0) {
                return null; // Recipe not found
            }

            $recipe = mysqli_fetch_assoc($result);

            // now we fetch the data van eerdere scripts
            $recipe['ingredients'] = $this->fetchIngredients($recipe_id);
            $recipe['user'] = $this->fetchUser($recipe['user_id']);
            $recipe['cuisine'] = $this->fetchCuisineType('C'); // 'C' = Cuisine
            $recipe['type'] = $this->fetchCuisineType('T'); // 'T' = Type

            return $recipe;
        }

    public function fetchIngredients($recipe_id)
    {
        return $this->ingredients->fetchIngredientsByRecipe($recipe_id);
    }

    public function fetchUser($user_id)
    {
        return $this->user->fetchUser($user_id);
    }

    public function fetchCuisineType($record_type)
    {
        return $this->cuisineType->fetchCuisineType($record_type);
    }
}
