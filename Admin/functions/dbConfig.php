<?php 
    session_start();
    date_default_timezone_set('Asia/Manila');
    
    class Connection {
        public $host = "localhost";
        public $password = "r+jnMm?Z9";
        public $username = "u657994792_micheal";
        public $dbName = "u657994792_campusbites";
    
        public $conn;
    
        public function __construct() {
            try {
                $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbName);
    
                if ($this->conn->connect_error) {
                    throw new Exception("Database Connection Failed: " . $this->conn->connect_error);
                }
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }

?>