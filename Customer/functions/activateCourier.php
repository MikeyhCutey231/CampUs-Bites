<?php
include '../../Admin/functions/UserData.php';

$database = new Connection();
$conn = $database->conn;

// Check if the action parameter is set
if (isset($_POST['action']) && $_POST['action'] === 'activateCourier') {
    // Perform the SQL query to insert into user_roles
    $loggedUser = $_SESSION['Customer_ID']; // Assuming you have a user ID in the session
    $roleCode = 'cour';

    
    $sql = "INSERT INTO user_roles (ROLE_CODE, USER_ID) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die('Error in SQL query: ' . $conn->error);
    }

    $stmt->bind_param("si", $roleCode, $loggedUser);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['status' => 'success']);
    header('Content-Type: application/json'); 
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>
