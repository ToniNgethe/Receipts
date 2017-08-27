<?php
header('Content-type: application/json');
 class DB_Connect{

    private $conn;
    //connect to Database
    public function connect(){
      require_once 'Config.php';

       // Connecting to mysql database
       $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

       // return database handler
       return $this->conn;
    }

 }
?>
