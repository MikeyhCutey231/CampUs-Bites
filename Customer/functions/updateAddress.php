<?php
require_once '../../Admin/functions/dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the new address from the POST request
    $newAddress = $_POST["newAddress"];

    // Assuming you have a Connection class that handles your database connection
    $database = new Connection();
    $conn = $database->conn;

    // Update the user's address in the database
    $userId = intval($_SESSION['customer_id']); // Assuming the user ID is stored in the session

    $sql = "UPDATE users SET U_CAMPUS_AREA = ? WHERE USER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newAddress, $userId);
    $stmt->execute();
    $stmt->close();

    // You can return a success message or any other response if needed
    echo "Address updated successfully";
  
} else {
    // Handle other HTTP methods if needed
    echo "Invalid request method";
}
?>
