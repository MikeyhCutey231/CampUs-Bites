<?php
require_once("../../Admin/functions/dbConfig.php");
include '../../Admin/functions/Admin_Manager_logs.php';
$logger = new Admin_Manager_logs();
$database = new Connection();

if (isset($_POST['customerId']) && isset($_POST['status']) && isset($_POST['adminId']) && isset($_POST['customer_username'])) {
    $userId = $_POST['adminId'];
    $customer_username = $_POST['customer_username'];
    $customerId = $_POST['customerId'];
    $Status = $_POST['status'];

    if ($Status !== 'Active' && $Status !== 'Deactivated') {
        echo 'Invalid new status';
        exit;
    }

    $sql = "UPDATE users SET U_STATUS = ? WHERE USER_ID = ?";
    $stmt = $database->conn->prepare($sql);
    $stmt->bind_param("si", $Status, $customerId);

    if ($stmt->execute()) {
        $logger->logStatusCustomer($userId, $customerId, $customer_username);
        echo 'success';
    } else {
        echo 'Error updating status: ' . $database->conn->error;
    }

    $stmt->close();
}
elseif (isset($_POST['employeeId']) && isset($_POST['status']) && isset($_POST['adminId']) && isset($_POST['username'])) {
    $username = $_POST['username'];
    $employeeId = $_POST['employeeId'];
    $userId = $_POST['adminId'];
    $Status = $_POST['status'];

    if ($Status !== 'Active' && $Status !== 'Deactivated') {
        echo 'Invalid new status';
        exit;
    }

    $sql = "UPDATE users SET U_STATUS = ? WHERE USER_ID = ?";
    $stmt = $database->conn->prepare($sql);
    $stmt->bind_param("si", $Status, $employeeId);

    if ($stmt->execute()) {
        $logger->logStatusEmployee($userId, $employeeId, $username);
        echo 'success';
    } else {
        echo 'Error updating status: ' . $database->conn->error;
    }

    $stmt->close();
}
    else {
    echo 'Invalid request';
}
?>
