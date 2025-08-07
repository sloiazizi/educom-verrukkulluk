<?php
class RecipeInfo
{
    private $connection;
    private $user;

    public function __construct($connection, $userObject)
    {
        $this->connection = $connection;
        $this->user = $userObject;
    }
    private function fetchUser($user_id) // private because right side 
    {
        return $this->user->fetchUser($user_id);
    }

    public function fetchRecipeInfo($recipe_id, $record_type)
    {
        $sql = "SELECT * FROM recipeinfo WHERE recipe_id = ? AND record_type = ?";
        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("is", $recipe_id, $record_type);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = []; 

        while ($row = $result->fetch_assoc()) {
            if ($record_type === 'C' || $record_type === 'F') {
                $user_info = $this->fetchUser($row['user_id']); //smexy way c:
                $data[] = [...$row, ...$user_info];
            } else {
                $data[] = $row;
            }
        }

        return $data;
    }
}
