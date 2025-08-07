<?php
class User
{
    private $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function fetchUser($user_id)
    {
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result); // Fetch op associative array manier
        }
        return false; // Return false if no user is found
    }
}
