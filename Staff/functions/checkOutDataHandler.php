<?php
    include("../functions/menuListfunctions.php");
    $database = new Connection();
    $conn = $database->conn;

    if (!isset($_SESSION['selected_items'])) {
        $_SESSION['selected_items'] = [];
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = $_POST['items'];
        $items = json_decode($json, true);
        $overAllTotal = isset($_POST['overAllTotal']) ? floatval($_POST['overAllTotal']) : 0;
        $cashReceived = isset($_POST['cashReceived']) ? floatval($_POST['cashReceived']) : 0;
        $cashReturn = isset($_POST['cashReturn']) ? floatval($_POST['cashReturn']) : 0;
    
        if (json_last_error() === JSON_ERROR_NONE) {
            $currentDate = date('Y-m-d');
        
            // Create the cart entry
            $createCart = "INSERT INTO pos_cart (POS_CART_DATE_CREATED, POS_CART_TOTAL) VALUES ('$currentDate', '$overAllTotal')";
            if (mysqli_query($conn, $createCart)) {
                // Get the ID of the newly created cart
                $cartID = mysqli_insert_id($conn);
        
                foreach ($items as $item) {
                    $productName = $item['name'];
                    $quantity = $item['quantity'];
                    $subtotal = $item['subtotal'];
                    $itemId = $item['itemId'];
                    $staffId = $_SESSION['USER_ID'];
        
                    $selectData = "SELECT * FROM product WHERE PROD_ID = '$itemId'";
                    $selectDataRun = mysqli_query($conn, $selectData);
        
                    if ($row = mysqli_fetch_array($selectDataRun)) {
                        $currentQuantity = $row['PROD_TOTAL_QUANTITY'];
                        $currentQuantitySold = $row['PROD_SOLD'];
        
                        if ($currentQuantity >= $quantity) {
                            $query = "INSERT INTO pos_cart_item(POS_CART_ID, PROD_ID, POS_PROD_QUANTITY, POS_SUBTOTAL) VALUES ('$cartID', '$itemId', '$quantity', '$subtotal')";
                            
                            if (mysqli_query($conn, $query)) {
                                // Update the product quantity
                                $updateQuantity = $currentQuantity - $quantity;
                                $updateQuantitySold = $currentQuantitySold + $quantity;
        
                                // Use a prepared statement to update the product quantity
                                $updateProduct = "UPDATE product SET PROD_TOTAL_QUANTITY = ?, PROD_SOLD = ? WHERE PROD_ID = ?";
                                $stmt = $conn->prepare($updateProduct);
                                $stmt->bind_param("iii", $updateQuantity, $updateQuantitySold, $itemId);
        
                                if ($stmt->execute()) {
                                    $stmt->close();
                                } else {
                                    echo "Failed to update product quantity";
                                }
                            } else {
                                echo "Failed to insert cart item";
                            }
                        } else {
                            echo "Negative Stock";
                        }
                    } else {
                        echo 'No selected items found.';
                    }
                }
        
                $posOrder = "INSERT INTO pos_order(USER_ID, POS_CART_ID, RECEIVED_AMOUNT, CHANGE_AMOUNT) VALUES ('$staffId', '$cartID', '$cashReceived', '$cashReturn')";
                mysqli_query($conn, $posOrder);
        
                $responseData = [
                    "status" => "success",
                    "message" => "Items added to the cart.",
                    "cartID" => $cartID // Add the cartID to the response data
                ];
        
                echo json_encode($responseData);
            } else {
                echo "Failed to create cart";
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data.']);
        }        
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data or not logged in.']);
    }
?>