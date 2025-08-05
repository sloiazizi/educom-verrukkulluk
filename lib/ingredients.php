<?php
class Ingredients
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function fetchIngredientsByRecipe($recipe_id)
    {
        $sql = "SELECT * FROM ingredients WHERE recipe_id = ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $recipe_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return null;
    }

    public function fetchIngredientsWithArticle($recipe_id)
    {
        $sql = "SELECT ingredients.*, article.name, article.price, article.unit
                FROM ingredients
                JOIN article ON ingredients.article_id = article.id
                WHERE ingredients.recipe_id = ?";

        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $recipe_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return null;
    }
}
