<?php
    include("../functions/orderListfunctions.php");
    $database = new Connection();
    $conn = $database->conn;

    $cartID = $_SESSION["cartID"];
    $userID = $_SESSION['USER_ID'];

  
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = $_POST['items'];
        $items = json_decode($json, true);
        $cashReceived = isset($_POST['cashReceived']) ? floatval($_POST['cashReceived']) : 0;
        $cashReturn = isset($_POST['cashReturn']) ? floatval($_POST['cashReturn']) : 0;
    
        if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($items as $item) {
                    $productName = $item['name'];
                    $quantity = $item['quantity'];
                    $itemId = $item['itemId'];
        
                    $selectData = "SELECT * FROM product WHERE PROD_ID = '$itemId'";
                    $selectDataRun = mysqli_query($conn, $selectData);
        
                    if ($row = mysqli_fetch_array($selectDataRun)) {
                        $currentQuantity = $row['PROD_REMAINING_QUANTITY'];
                        $currentQuantitySold = $row['PROD_SOLD'];
        
                        if ($currentQuantity >= $quantity) {

                                $updateCart = "UPDATE online_order SET ORDER_STATUS_ID = 8,  RECEIVED_AMOUNT = '$cashReceived', CHANGE_AMOUNT = '$cashReturn', EMPLOYEE_ID = '$userID' WHERE OL_CART_ID = '$cartID'";
                                mysqli_query($conn, $updateCart);    

                                $updateQuantitySold = $currentQuantitySold + $quantity;
        
                                // Use a prepared statement to update the product quantity
                                $updateProduct = "UPDATE product SET PROD_SOLD = ? WHERE PROD_ID = ?";
                                $stmt = $conn->prepare($updateProduct);
                                $stmt->bind_param("ii", $updateQuantitySold, $itemId);
        
                                if ($stmt->execute()) {
                                    $stmt->close();
                                } else {
                                    echo "Failed to update product quantity";
                                }
                        } else {
                            echo "Negative Stock";
                        }
                    } else {
                        echo 'No selected items found.';
                    }
                }

                $orderID = "SELECT ONLINE_ORDER_ID FROM online_order WHERE OL_CART_ID  = '$cartID'";
                $orderIDrun = mysqli_query($conn, $orderID);
                
                if($row =  mysqli_fetch_array($orderIDrun)){
                    $orderIDCart = $row['ONLINE_ORDER_ID'];

                    $sendNotifQuery = "INSERT INTO notifications (OL_ORDER_ID, NOTIF_MESSAGE) VALUES ('$orderIDCart', 'Thank you for the purchase!')";
                    mysqli_query($conn, $sendNotifQuery);
                }
        
                $responseData = [
                    "status" => "success",
                    "message" => "Items added to the cart."
                ];
        
                echo json_encode($responseData);
            } else {
                echo "Failed to create cart";
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data.']);
        }        
?>