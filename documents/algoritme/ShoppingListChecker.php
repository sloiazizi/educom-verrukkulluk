<?php

class ShoppingListChecker
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function articleOnList($article_id, $user_id)
    {
        $sql = "SELECT * FROM shopping_list WHERE article_id = ? AND user_id = ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $article_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        
        return false;
    }

    public function fetchShoppingList($user_id)
    {
        $sql = "SELECT sl.*, a.name, a.unit, a.package_size, a.recipe_unit, a.price 
                FROM shopping_list sl 
                JOIN article a ON sl.article_id = a.id 
                WHERE sl.user_id = ? 
                ORDER BY a.name";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        
        return false;
    }

    public function displayShoppingList($user_id)
    {
        $list = $this->fetchShoppingList($user_id);
        
        if (!$list) {
            echo "<p>Your shopping list is empty.</p>";
            return;
        }
        
        echo "<h3>🛒 Shopping List</h3>";
        echo "<ul>";
        
        $total_cost = 0;
        
        foreach ($list as $item) {
            $item_cost = $item['packages_needed'] * $item['price'];
            $total_cost += $item_cost;
            
            echo "<li>";
            echo "<strong>" . htmlspecialchars($item['name']) . "</strong> - ";
            echo $item['packages_needed'] . " " . htmlspecialchars($item['unit']);
            echo " (needed: " . $item['total_amount_needed'] . " " . htmlspecialchars($item['recipe_unit']) . ")";
            echo " - €" . number_format($item_cost, 2);
            echo "</li>";
        }
        
        echo "</ul>";
        echo "<p><strong>Total estimated cost: €" . number_format($total_cost, 2) . "</strong></p>";
    }
}