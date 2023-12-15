<?php
// cancel_order.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../Admin/functions/dbConfig.php';

$database = new Connection();
$conn = $database->conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve order ID from the POST data
    $orderId = isset($_POST['order_id']) ? $_POST['order_id'] : '';



    // Update the order status to indicate cancellation
    $updateOrderStatusQuery = "UPDATE online_order SET ORDER_STATUS_ID = '5' WHERE ONLINE_ORDER_ID = $orderId";
    $resultOrderStatus = mysqli_query($conn, $updateOrderStatusQuery);

    if ($resultOrderStatus) {
        // Retrieve the product details from the canceled order
        $getProductDetailsQuery = "SELECT PROD_ID, OL_PROD_QUANTITY FROM online_cart_item WHERE OL_CART_ID = (SELECT OL_CART_ID FROM online_order WHERE ONLINE_ORDER_ID = $orderId)";
        $resultProductDetails = mysqli_query($conn, $getProductDetailsQuery);

        if ($resultProductDetails) {
            // Iterate through the products in the canceled order
            while ($row = mysqli_fetch_assoc($resultProductDetails)) {
                $productId = $row['PROD_ID'];
                $quantity = $row['OL_PROD_QUANTITY'];

                // Update the remaining quantity in the products table
                $updateProductQuantityQuery = "UPDATE product SET PROD_REMAINING_QUANTITY = PROD_REMAINING_QUANTITY + $quantity WHERE PROD_ID = $productId";
                $resultUpdateQuantity = mysqli_query($conn, $updateProductQuantityQuery);

                if (!$resultUpdateQuantity) {
                    // Rollback the transaction if an error occurs
                    mysqli_rollback($conn);
                    echo 'Error updating product quantity.';
                    error_log("Error updating product quantity. Query: $updateProductQuantityQuery");
                    exit;
                }
            }

            // Commit the transaction if everything is successful
            mysqli_commit($conn);
            echo 'Order canceled successfully';
        } else {
            // Rollback the transaction if an error occurs
            mysqli_rollback($conn);
            echo 'Error retrieving product details.';
            error_log("Error retrieving product details. Query: $getProductDetailsQuery");
        }
    } else {
        // Rollback the transaction if an error occurs
        mysqli_rollback($conn);
        echo 'Error updating order status.';
        error_log("Error updating order status. Query: $updateOrderStatusQuery");
    }
} else {
    // Handle the case where the request method is not POST
    echo 'Invalid request';
    error_log('Invalid request method. Expected POST.');
}
