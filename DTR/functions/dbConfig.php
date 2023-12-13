<?php 
    class Connection {
        public $host = "localhost";
        public $password = "root";
        public $username = "root";
        public $dbName = "campusbites";
    
        public $conn;
    
        public function __construct() {
            try {
                $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbName);
                session_start();
                if ($this->conn->connect_error) {
                    throw new Exception("Database Connection Failed: " . $this->conn->connect_error);
                }
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }

?>