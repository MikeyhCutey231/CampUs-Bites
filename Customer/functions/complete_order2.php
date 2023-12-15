<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a database connection, replace with your actual connection code
    require_once '../../Admin/functions/dbConfig.php';
    $database = new Connection();
    $conn = $database->conn;


    // Get the order ID from the POST parameters
    $customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : null;
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
    $customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : null;
    $orderType = isset($_POST['order_type']) ? $_POST['order_type'] : null;
    $shippingFee = isset($_POST['shipping_fee']) ? $_POST['shipping_fee'] : null;


    $cartId = createcart($customer_id, $conn);
    $cartItem = insertItem($conn, $cartId, $product_id, $quantity);

    
    if ($cartId) {
        $query = "INSERT INTO online_order (DATE_CREATED,OL_CART_ID, SHIPPING_FEE, OL_ORDER_TYPE_ID) VALUES (CURRENT_DATE(),?, ?,?)";
        $stmt = $conn->prepare($query);
        
        // Bind parameters
        $stmt->bind_param('idi', $cartId , $shippingFee,  $orderType  );
        
        // Execute the statement
        if ($stmt->execute()) {
            echo 'Data inserted successfully.';

                 // Update ol_cart_status to "dead" for the specified order ID
            $updateQuery = "UPDATE ol_cart SET ol_cart_status = 'dead' WHERE ol_cart_id = ?";
            $stmt1 = $conn->prepare($updateQuery);
            $stmt1->bind_param('i',  $cartId);

            if ($stmt1->execute()) {
                     echo 'Order completed successfully.';
                 } else {
                     echo 'Error updating order status.';
                 }

                 $stmt1->close();
        
        } else {
            echo 'Error inserting data.';
        }
        
        // Close the statement and the database connection
        $stmt->close();
        $conn->close();
       
       
    }
       
       
       
   
} else {
    // Handle other HTTP methods if needed
    http_response_code(405); // Method Not Allowed
    echo 'Method Not Allowed';
}


function createcart($customer_id, $conn){
    $query= "INSERT INTO ol_cart (CUSTOMER_ID, OL_CART_STATUS) VALUES (?, 'temp')";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customer_id);

    if ($stmt->execute()) {
        return $stmt->insert_id;
    } else {
        die("Error creating cart: " . $stmt->error);
    }

}

function insertItem($conn, $cartId, $prodID, $quantity)
{
    $query = "INSERT INTO online_cart_item (OL_CART_ID,PROD_ID,OL_PROD_QUANTITY) VALUES (?,?,?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $cartId, $prodID, $quantity);

    if ($stmt->execute()) {
        return $stmt->insert_id;
    } else {
        die("Error creating cart-item: " . $stmt->error);
    }
}

