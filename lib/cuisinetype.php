<?php
class CuisineType
{
    private $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    // Fetch cuisine type --> record type (C or T)
    public function fetchCuisineType($record_type)
    {
        $sql = "SELECT * FROM cuisinetype WHERE id = ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "s", $record_type);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }
}
