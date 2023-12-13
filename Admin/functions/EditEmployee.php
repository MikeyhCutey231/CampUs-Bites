<?php
include('config.php');
class EditEmployee
{
private $conn;
    public function __construct() {
        $database = new Database();
        $this->conn = $database->conn;
    }

}