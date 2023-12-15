<?php
require_once '../../Admin/functions/dbConfig.php';

$database = new Connection();
$conn = $database->conn;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the values from the AJAX request
    $orderId = isset($_POST['orderId']) ? $_POST['orderId'] : null;
    $rating = isset($_POST['rating']) ? $_POST['rating'] : null;
    $comment = isset($_POST['comment']) ? $_POST['comment'] : null;

    $sql = "UPDATE online_order SET  online_order.ORDER_RATING = '$rating', online_order.ORDER_COMMENT = '$comment', online_order.ORDER_STATUS_ID=4 WHERE online_order.ONLINE_ORDER_ID = '$orderId'";

    if ($conn->query($sql) === TRUE) {
        // Return success message or handle as needed
        $response = ['success' => true, 'message' => 'Rating and comment updated successfully'];
        echo json_encode($response);
    } else {
        // Return error message or handle as needed
        $response = ['success' => false, 'message' => 'Error updating rating and comment: ' . $conn->error];
        echo json_encode($response);
    }

    // Close the database connection
    $conn->close();
} else {
    // Handle non-POST requests
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
}
