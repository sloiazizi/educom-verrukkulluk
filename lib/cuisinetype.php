<?php
class CuisineType
{
    private $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    // Fetch cuisine type --> record type (C or T)
    public function fetchCuisineType($cuisinetype_id)
    {
        $sql = "SELECT * FROM cuisinetype WHERE id = ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "s", $cuisinetype_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }
}
