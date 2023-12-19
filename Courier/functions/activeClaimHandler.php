<?php
include("../functions/activeOrdersInfo.php");

$database = new Connection();
$conn = $database->conn;

$userID = $_SESSION['USER_ID'];
header('Content-Type: application/json');

if (isset($_POST["orderID"])) {
    $orderID = $_POST["orderID"];

    
    $checkIfAssignedQuery = "SELECT COURIER_ID FROM online_order WHERE OL_CART_ID = '$orderID'";
    $checkIfAssignedResult = mysqli_query($conn, $checkIfAssignedQuery);

    $assignedCourierID = mysqli_fetch_assoc($checkIfAssignedResult)['COURIER_ID'];

    if (!empty($assignedCourierID)) {
        
        $response = array(
            "status" => "assigned",
            "message" => "Order has already been assigned to a courier."
        );
    } else {
        
        $claimedOrder = "UPDATE online_order SET ORDER_STATUS_ID = '2', COURIER_ID  = '$userID' WHERE OL_CART_ID = '$orderID'";
        mysqli_query($conn, $claimedOrder);

        
        unset($_SESSION["olCartId"]);

        $response = array(
            "status" => "updated",
            "message" => "Order claimed successfully."
        );
    }

    echo json_encode($response);
} else {
    echo "No data received";
}
?>
