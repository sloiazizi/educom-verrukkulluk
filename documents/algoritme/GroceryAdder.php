<?php
require_once("lib/ingredients.php");
require_once("documents/algoritme/ShoppingListChecker.php");

class GroceryAdder
{
    private $connection;
    private $ingredients;
    private $checker;

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->ingredients = new Ingredients($connection);
        $this->checker = new ShoppingListChecker($connection);
    }

    public function addingGroceries($recipe_id, $user_id, $servings = 4)
    {
        $ingredients = $this->ingredients->fetchIngredients($recipe_id);
        
        if (!$ingredients) {
            return false;
        }

        foreach ($ingredients as $ingredient) {
            $article_id = $ingredient['article_id'];
            $needed_amount_per_person = $ingredient['amount']; 
            $needed_amount_total = $needed_amount_per_person * $servings;
            $package_size = $ingredient['package_size']; 
            
            $existingItem = $this->checker->articleOnList($article_id, $user_id);

            if ($existingItem) {
                // Add to existing total needed amount
                $new_total_amount = $existingItem['total_amount_needed'] + $needed_amount_total;
                $new_packages_needed = $this->calculatePackagesNeeded($new_total_amount, $package_size);
                
                $this->updateShoppingListItem($existingItem['id'], $new_packages_needed, $new_total_amount);
            } else {
                // Create new shopping list item
                $packages_needed = $this->calculatePackagesNeeded($needed_amount_total, $package_size);
                $this->addArticleToShoppingList($article_id, $user_id, $packages_needed, $needed_amount_total);
            }
        }

        return true;
    }

    private function calculatePackagesNeeded($total_amount_needed, $package_size)
    {
        if ($package_size == 1) { // oke so voor items "per stuk" (like veggies), package_size = 1
            return $total_amount_needed; //  exact amount nodig
        }
       
        return ceil($total_amount_needed / $package_size);
    }

    private function addArticleToShoppingList($article_id, $user_id, $packages_needed, $total_amount_needed)
    {
        $sql = "INSERT INTO shopping_list (article_id, user_id, packages_needed, total_amount_needed) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "iiid", $article_id, $user_id, $packages_needed, $total_amount_needed);
        return mysqli_stmt_execute($stmt);
    }

    private function updateShoppingListItem($shopping_list_id, $new_packages_needed, $new_total_amount)
    {
        $sql = "UPDATE shopping_list SET packages_needed = ?, total_amount_needed = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "idi", $new_packages_needed, $new_total_amount, $shopping_list_id);
        return mysqli_stmt_execute($stmt);
    }
}