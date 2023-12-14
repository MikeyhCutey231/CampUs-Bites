<?php
    include("../functions/orderListfunctions.php");
    $database = new Connection();
    $conn = $database->conn;

    $cartID = $_SESSION["cartID"];
    $userID = $_SESSION['USER_ID'];

    if(isset($_POST['olCartId'])){
        $olcartID = $_POST['olCartId'];

        $sendNotifQuery = "INSERT INTO notifications (OL_ORDER_ID, NOTIF_MESSAGE) VALUES ('$olcartID', 'Your order, #$olcartID, has been cancelled!, might be because product is out of stock')";
        mysqli_query($conn, $sendNotifQuery);

        $updateQuery = "UPDATE online_order SET EMPLOYEE_ID = ?, ORDER_STATUS_ID = 5 WHERE OL_CART_ID = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ii", $userID, $cartID);

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