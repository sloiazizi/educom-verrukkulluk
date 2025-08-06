<?php 

// Aanpassen naar je eigen omgeving
define("USER", "root");
define("PASSWORD", "");
define("DATABASE", "db");
define("HOST", "localhost");

class database {

    private $connection;

    public function __construct() {
       $this->connection = mysqli_connect(HOST,                                          
                                         USER, 
                                         PASSWORD,
                                         DATABASE );
    }

    public function getConnection() {
        return($this->connection);
    }

}
