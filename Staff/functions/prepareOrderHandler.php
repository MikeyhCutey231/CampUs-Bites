<?php
    include("../functions/orderListfunctions.php");
    $database = new Connection();
    $conn = $database->conn;

    $cartID = $_SESSION["cartID"];
    $userID = $_SESSION['USER_ID'];

    if(isset($_POST['olCartId'])){
        $olcartID = $_POST['olCartId'];

        $sendNotifQuery = "INSERT INTO notifications (OL_ORDER_ID, NOTIF_MESSAGE) VALUES ('$olcartID', 'Your order has been prepared!, Kindly pick-up your order.')";
        mysqli_query($conn, $sendNotifQuery);

        $updateQuery = "UPDATE online_order SET ORDER_STATUS_ID = 7 WHERE OL_CART_ID = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $cartID);

        if ($stmt->execute()) {
            $stmt->close();

            $responseData = [
                "status" => "success",
                "message" => "Items added to the cart."
            ];

            echo json_encode($responseData);
        } else {
            echo "Error updating online_order: " . mysqli_error($conn);
        }
    }
?>