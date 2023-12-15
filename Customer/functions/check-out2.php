<?php
require_once '../../Admin/functions/dbConfig.php';

echo '<style>body { overflow-x: hidden; }</style>';

// Create an instance of the Database class
$database = new Connection();
$conn = $database->conn;
class Checkout{

function displayDetailedOrder($cartId, $conn)
{
    // Convert $cartId to an integer
    $cartId = intval($cartId);

    // Fetch order details from the database based on $cartId
    $query = "
        SELECT 
            ol_cart.OL_CART_ID, 
            users.U_CAMPUS_AREA,
            product.PROD_PIC, 
            product.PROD_NAME, 
            product.PROD_SELLING_PRICE, 
            online_cart_item.OL_PROD_QUANTITY, 
            online_cart_item.OL_SUBTOTAL
        FROM 
            ol_cart
        INNER JOIN 
            users ON ol_cart.CUSTOMER_ID = users.USER_ID
        INNER JOIN 
            online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
        INNER JOIN 
            product ON online_cart_item.PROD_ID = product.PROD_ID
        WHERE 
            ol_cart.OL_CART_ID = ?
    ";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        return ['error' => 'Error in SQL query preparation: ' . $conn->error];
    }

    // Bind parameters
    $bindResult = $stmt->bind_param('i', $cartId);

    if (!$bindResult) {
        return ['error' => 'Error binding parameters: ' . $stmt->error];
    }

    // Execute the query
    $executeResult = $stmt->execute();

    if (!$executeResult) {
        return ['error' => 'Error executing SQL query: ' . $stmt->error];
    }

    $result = [];

    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (empty($result)) {
        $result['error'] = 'No order found with the provided Cart ID.';
    }

    return $result;
}

function getUserAddress($customer_id) {

    $userId = intval($customer_id); 

    $database = new Connection();
    $conn = $database->conn;

    $sql = "SELECT users.U_CAMPUS_AREA FROM users WHERE users.USER_ID = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("i", $userId);

    // Execute the statement
    $stmt->execute();

    // Bind the result variable
    $stmt->bind_result($userCampusArea);

    // Fetch the result
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // Return the result
    return $userCampusArea;
}



 }
