<?php
require '../../Admin/functions/dbConfig.php';
$database = new Connection();

if (isset($_POST['customerID']) && isset($_POST['status'])) {
    $customerID = $_POST['customerID'];
    $Status = $_POST['status'];

    if ($Status !== 'Active' && $Status !== 'Deactivated') {
        echo 'Invalid new status';
        exit;
    }

    $sql = "UPDATE users SET U_STATUS = ? WHERE USER_ID = ?";
    $stmt = $database->conn->prepare($sql);
    $stmt->bind_param("si", $Status, $customerID);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error updating status: ' . $database->conn->error;
    }

    $stmt->close();
} else {
    echo 'Invalid request';
}
?>
