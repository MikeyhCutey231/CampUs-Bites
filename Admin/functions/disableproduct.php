<?php

require_once("../../Admin/functions/dbConfig.php");
include '../../Admin/functions/Admin_Manager_logs.php';
$logger = new Admin_Manager_logs();

$database = new Connection();
$conn = $database->conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prod_id = $_POST["prodID"];
    $status = $_POST["prodStatus"];
    $pnameLogs = $_POST['pname'];
    $userId = $_POST["userid"];


    $sql = "";
    $confirmationMessage = "";

    if ($status === 'Disabled') {
        $sql = "UPDATE product SET PROD_STATUS = 'Available' WHERE PROD_ID = ?";
        $confirmationMessage = "Product enabled successfully.";
        $logger->logProductEnable($userId, $pnameLogs);

    } else {
        $sql = "UPDATE product SET PROD_STATUS = 'Disabled' WHERE PROD_ID = ?";
        $confirmationMessage = "Product disabled successfully.";
        $logger->logProductDisable($userId, $pnameLogs);

    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $prod_id);

    if ($stmt->execute()) {
        echo $confirmationMessage;
        header("Location: ../../Admin/admin_php/admin_itemInventory.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
