<?php
require_once("../../Admin/functions/dbConfig.php");
class Admin_Manager_logs {
    private $conn;
    public function __construct() {
        $database = new Connection();
        $this->conn = $database->conn;
    }
    public function loginLogs($userId) {
        $action = "Log in";
        $description = "User ID $userId logged in to the system";
        $this->logAction($userId, $action, $description);
    }
    public function logoutLogs($userId) {
        $action = "Log out";
        $description = "User ID $userId logged out of the system";
        $this->logAction($userId, $action, $description);
    }
    public function logProductAddition($userId, $productName) {
        $action = "Product Addition";
        $description = "User ID $userId added a new product: $productName";
        $this->logAction($userId, $action, $description);
    }

    // Log the action of updating a product
    public function logProductUpdate($userId, $productName) {
        $action = "Product Update";
        $description = "User ID $userId updated the product: $productName";
        $this->logAction($userId, $action, $description);
    }
    public function logProductDisable($userId, $productName) {
        $action = "Product Update";
        $description = "User ID $userId disabled the product: $productName";
        $this->logAction($userId, $action, $description);
    }
    public function logProductEnable($userId, $productName) {
        $action = "Product Update";
        $description = "User ID $userId enabled the product: $productName";
        $this->logAction($userId, $action, $description);
    }

    public function logAddEmployee($userId, $employeeID, $employeeName) {
        $action = "Add Employee";
        $description = "User ID $userId added an employee: $employeeName (ID: $employeeID)";
        $this->logAction($userId, $action, $description);
    }
    public function logUpdateEmployee($userId, $employeeID, $employeeName) {
        $action = "Update Employee";
        $description = "User ID $userId updated an employee: $employeeName (ID: $employeeID)";
        $this->logAction($userId, $action, $description);
    }
    public function logStatusEmployee($userId, $employeeID, $employeeName) {
        $action = "Change Employee Status";
        $description = "User ID $userId updated the status of an employee: $employeeName (ID: $employeeID)";
        $this->logAction($userId, $action, $description);
    }
    public function logStatusCustomer($userId, $customerID, $customerName) {
        $action = "Change Customer Status";
        $description = "User ID $userId updated the status of a customer: $customerName (ID: $customerID)";
        $this->logAction($userId, $action, $description);
    }

    public function logAction($userId, $action, $description) {
        date_default_timezone_set('Asia/Manila');
        $currentDateTime = date("Y-m-d H:i:s");

        $query = "INSERT INTO canteen_staff_logs (user_id, action, description, date) 
              VALUES ('$userId', '$action', '$description', '$currentDateTime')";
        $result = $this->conn->query($query);
        if (!$result) {
            die("Error: " . $this->conn->error);
        }
    }

    public function setDatabase($database)
    {
        $this->conn = $database->conn;
    }
}

?>
