<?php
class Article
{
    private $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function fetchArticle($article_id)
    {
        $sql = "SELECT * FROM article WHERE id = ?";
        $stmt = mysqli_prepare($this->connection, $sql); // van W3 chapter prepared statements
        mysqli_stmt_bind_param($stmt, "i", $article_id); //same
        mysqli_stmt_execute($stmt); //same
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result); // van W3 chapter php mySQLi + associative array
        }
        return null; //when article not found then it will return null 
    }
}
