<?php
require_once 'Article.php';

class Ingredients {
    private $connection;
    private $article;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->article = new Article($connection); 
    }

    public function fetchIngredients($recipe_id) {    // <<<<<<<<<<<<<<<<<  PUBLIC functie zoals in ASD
        $sql = "SELECT * FROM ingredients WHERE recipe_id = ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $recipe_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $ingredients = mysqli_fetch_all($result, MYSQLI_ASSOC);

            foreach ($ingredients as &$ingredient) { // Loop door de ingrediënten >> Haal artikelgegevens op voor elk ingrediënt >> en voeg deze toe aan het ingrediënt 
                $article = $this->fetchArticle($ingredient['article_id']);
                if ($article) {
                    $ingredient['article_name'] = $article['name'];
                    $ingredient['price'] = $article['price'];
                    $ingredient['unit'] = $article['unit'];
                }
            }
            return $ingredients;
        }
        return null;
    }

    private function fetchArticle($article_id) {// <<<<<<<<<<<<<<<<<   PRIVATE functie zoals in ASD 
        return $this->article->fetchArticle($article_id); 
    }
}