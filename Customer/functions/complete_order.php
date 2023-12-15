<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a database connection, replace with your actual connection code
    require_once '../../Admin/functions/dbConfig.php';
    $database = new Connection();
    $conn = $database->conn;

    // Get the order ID from the POST parameters
    $cartId = isset($_POST['ol_cart_id']) ? $_POST['ol_cart_id'] : null;
    $orderType = isset($_POST['order_type']) ? $_POST['order_type'] : null;
    $shippingFee = isset($_POST['shipping_fee']) ? $_POST['shipping_fee'] : null;

    
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

