<?php
require_once '../../Admin/functions/dbConfig.php';

$database = new Connection();
$conn = $database->conn;
$customerId = $_SESSION['Customer_ID'];

$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Function to check if an order is alive based on OL_CART_ID
function isOrderAlive($conn, $olCartId)
{
    $checkAliveOrderQuery = "SELECT OL_CART_ID FROM ol_cart WHERE OL_CART_ID = ? AND OL_CART_STATUS = 'alive'";
    $stmt = $conn->prepare($checkAliveOrderQuery);
    $stmt->bind_param("i", $olCartId);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

// Function to check if a customer exists in the users table
function customerExists($conn, $customerId)
{
    $checkCustomerQuery = "SELECT USER_ID FROM users WHERE USER_ID = ?";
    $stmt = $conn->prepare($checkCustomerQuery);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;
}

// Function to create a new order and return the OL_CART_ID
function createOrder($conn, $customerId)
{
    $insertOrderQuery = "INSERT INTO ol_cart (CUSTOMER_ID, OL_CART_STATUS) VALUES (?, 'alive')";
    $stmt = $conn->prepare($insertOrderQuery);
    $stmt->bind_param("i", $customerId);

    if ($stmt->execute()) {
        return $stmt->insert_id;
    } else {
        die("Error creating order: " . $stmt->error);
    }
}

// Function to get product details from the database
function getProductDetails($conn, $productId)
{
    $selectProductQuery = "SELECT * FROM product WHERE prod_id = ?";
    $stmt = $conn->prepare($selectProductQuery);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        die("Product not found with ID: " . $productId);
    }
}

// Function to insert an order item into the online_cart_item table
function insertOrderItem($conn, $olCartId, $productId, $quantity, $subtotal)
{
    // Check if the product quantity is greater than 0
    if ($quantity > 0) {
        // Check if the remaining quantity is greater than 0
        $checkRemainingQuantityQuery = "SELECT PROD_REMAINING_QUANTITY FROM product WHERE PROD_ID = ?";
        $checkRemainingQuantityStmt = $conn->prepare($checkRemainingQuantityQuery);
        $checkRemainingQuantityStmt->bind_param("i", $productId);
        $checkRemainingQuantityStmt->execute();
        $checkRemainingQuantityResult = $checkRemainingQuantityStmt->get_result();

        if ($checkRemainingQuantityResult->num_rows > 0) {
            $remainingQuantityRow = $checkRemainingQuantityResult->fetch_assoc();
            $remainingQuantity = $remainingQuantityRow['PROD_REMAINING_QUANTITY'];

            if ($remainingQuantity > 0) {
                // Insert the order item
                $insertOrderItemQuery = "INSERT INTO online_cart_item (OL_CART_ID, PROD_ID, OL_PROD_QUANTITY, OL_SUBTOTAL) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insertOrderItemQuery);

                if (!$stmt) {
                    die("Error preparing statement: " . $conn->error);
                }

                $bindResult = $stmt->bind_param("iiid", $olCartId, $productId, $quantity, $subtotal);

                if (!$bindResult) {
                    die("Error binding parameters: " . $stmt->error . " Query: " . $insertOrderItemQuery);
                }

                $executeResult = $stmt->execute();

                if ($executeResult) {
                    return $olCartId;
                } else {
                    die("Error executing statement: " . $stmt->error . " Query: " . $insertOrderItemQuery);
                }
            } else {
                die("Cannot add to cart. Product with ID $productId has no remaining quantity.");
            }
        } else {
            die("Error checking remaining quantity: " . $checkRemainingQuantityStmt->error);
        }
    } else {
        die("Invalid quantity. Quantity must be greater than 0.");
    }
}


// Function to get the OL_CART_ID of an alive order for a customer
function getAliveOrder($conn, $customerId)
{
    $selectAliveOrderQuery = "SELECT OL_CART_ID FROM ol_cart WHERE CUSTOMER_ID = ? AND OL_CART_STATUS = 'alive'";
    $stmt = $conn->prepare($selectAliveOrderQuery);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['OL_CART_ID'];
    } else {
        return false;
    }
}

if (!empty($data['productId']) && !empty($data['quantity'])) {
    $productId = $data['productId'];
    $quantity = $data['quantity'];

    if (customerExists($conn, $customerId)) {
        $existingOrderId = getAliveOrder($conn, $customerId);

        if ($existingOrderId) {
            $olCartId = $existingOrderId;
        } else {
            $olCartId = createOrder($conn, $customerId);
        }

        if (isOrderAlive($conn, $olCartId)) {
            $prodDetails = getProductDetails($conn, $productId);

            // Check if the product already exists in the cart
            $existingCartItemQuery = "SELECT OL_PROD_QUANTITY FROM online_cart_item WHERE OL_CART_ID = ? AND PROD_ID = ?";
            $existingCartItemStmt = $conn->prepare($existingCartItemQuery);
            $existingCartItemStmt->bind_param("ii", $olCartId, $productId);
            $existingCartItemStmt->execute();
            $existingCartItemResult = $existingCartItemStmt->get_result();

            if ($existingCartItemResult->num_rows > 0) {
                // Product already exists, update OL_PROD_QUANTITY
                $existingCartItemRow = $existingCartItemResult->fetch_assoc();
                $newQuantity = $existingCartItemRow['OL_PROD_QUANTITY'] + $quantity;

                // Update OL_PROD_QUANTITY for the existing item
                $updateQuantityQuery = "UPDATE online_cart_item SET OL_PROD_QUANTITY = ? WHERE OL_CART_ID = ? AND PROD_ID = ?";
                $updateQuantityStmt = $conn->prepare($updateQuantityQuery);
                $updateQuantityStmt->bind_param("iii", $newQuantity, $olCartId, $productId);
                $updateQuantityStmt->execute();
            } else {
                // Product does not exist in the cart, insert new item
                $quantity = intval($quantity);
                $subtotal = $quantity * $prodDetails['PROD_SELLING_PRICE'];
                insertOrderItem($conn, $olCartId, $productId, $quantity, $subtotal);
            }

            $response = [
                'status' => 'success',
                'message' => 'Item added to cart successfully.'
            ];

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Order is not alive.'
            ];

            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Customer does not exist.'
        ];

        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Invalid data.'
    ];

    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
