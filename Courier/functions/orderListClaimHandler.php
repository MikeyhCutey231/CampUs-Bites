<?php
    include("../functions/viewOrderListInfo.php");

    $database = new Connection();
    $conn = $database->conn;

    $userID = $_SESSION['USER_ID'];

    if (isset($_POST["orderID"])) {
        $orderID = $_POST["orderID"];

        $claimedOrder = "UPDATE online_order SET ORDER_STATUS_ID = '3', COURIER_ID  = '$userID' WHERE OL_CART_ID = '$orderID'";

        $orderIdquery = "SELECT * FROM online_order WHERE OL_CART_ID = '$orderID'";
        $orderIdqueryrun = mysqli_query($conn, $orderIdquery);


        while($row = mysqli_fetch_assoc($orderIdqueryrun)){
            $newOlOrderID = $row['ONLINE_ORDER_ID'];
            
            $insertNotif = "INSERT INTO notifications (OL_ORDER_ID, NOTIF_MESSAGE) VALUES ('$newOlOrderID', 'Your order is about to arrived!')";
            mysqli_query($conn, $insertNotif);

        }   

        if (mysqli_query($conn, $claimedOrder)) {
            
            unset($_SESSION["cartID"]);

            $response = array("status" => "success", "message" => "Order claimed successfully");
        } else {
            $response = array("status" => "error", "message" => "Error claiming order");
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit; 
    }
?>
