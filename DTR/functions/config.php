<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "root";
    private $database = "campusbites";
    public $conn;

    public function __construct() {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
?>
