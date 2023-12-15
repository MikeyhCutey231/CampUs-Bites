<?php


require_once '../../Admin/functions/dbConfig.php';



// Function to fetch and display order details based on order status
function displayOrderDetails($request)
{
    // Create an instance of the Database class
    $database = new Connection();
    $conn = $database->conn;

    $query = "SELECT online_order.ONLINE_ORDER_ID, ol_order_status.STATUS_NAME, online_order.OL_CART_ID, product.PROD_NAME, product.PROD_PIC, online_cart_item.OL_PROD_QUANTITY, product.PROD_SELLING_PRICE, online_cart_item.OL_SUBTOTAL
    FROM online_order
    INNER JOIN ol_order_status ON online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
    INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
    INNER JOIN online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
    INNER JOIN product ON online_cart_item.PROD_ID = product.PROD_ID
    WHERE online_order.OL_ORDER_TYPE_ID = 2";

    // Check if a specific order status is requested
    if (!empty($request) && $request !== '0') {
        $query .= " AND online_order.ORDER_STATUS_ID = '$request'";
    }

    $query .= " GROUP BY online_order.ONLINE_ORDER_ID, online_cart_item.PROD_ID ORDER BY online_order.DATE_CREATED DESC";

    $result = mysqli_query($conn, $query);

    if ($result) {
        $count = mysqli_num_rows($result);

        if ($count > 0) {
            // Initialize variables
            $currentOrderId = null;
            $orderTotals = [];

            // Loop through the results and display order details
            while ($row = mysqli_fetch_assoc($result)) {
                $orderId = $row['ONLINE_ORDER_ID'];
                $orderStatus = $row['STATUS_NAME'];
                $cartId = $row['OL_CART_ID'];
                $productName = $row['PROD_NAME'];
                $productPic = $row['PROD_PIC'];
                $quantity = $row['OL_PROD_QUANTITY'];
                $productPrice = $row['PROD_SELLING_PRICE'];
                $subtotal = $row['OL_SUBTOTAL'];

                // Calculate subtotal by multiplying price and quantity
                $subtotal = $productPrice * $quantity;

                // Initialize order total for the current order
                if (!isset($orderTotals[$orderId])) {
                    $orderTotals[$orderId] = 0;
                }

                // Update order total for the current order
                $orderTotals[$orderId] += $subtotal;

                // Display order details with the provided HTML structure
                if ($orderId != $currentOrderId) {
                    // Display order details only if it's a new order
                    if ($currentOrderId !== null) {
                        // Display order total after the loop for products
                        echo '<div class="row">';
                        echo '<div class="col-12">';
                        echo '<hr style="border-top: 1px solid #ddd; margin-top: 15px; margin-bottom: 15px;">'; // Add a horizontal line
                        echo '</div>';
                        echo '<div class="col-lg-11 col-md-10 col-9 align-items-center d-flex justify-content-end">';
                        echo '<div class="total-title">Order Total:</div>';
                        echo '</div>';
                        echo '<div class="col-lg-1 col-md-2 col-3 d-flex align-items-center justify-content-center">';
                        echo '<div class="total-con">₱' . number_format($orderTotals[$currentOrderId], 2) . '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>'; // Close the order details container
                    }

                    echo '<div class="container-fluid p-0 mt-2 align-items-center justify-content-center order-details shadow">'; // Add the "shadow" class here
                    echo '<div class="container d-block py-lg-3 px-lg-4 p-sm-3 px-4 py-2 order ">';
                    echo '<div class="row  px-0 py-2 ">';
                    echo '<div class="col-md-6 col-4  d-flex  p-0 d-flex align-items-center justify-content-start">';
                    echo '<div class="d-flex align-items-center justify-content-start order-no">';
                    echo 'Order #' . $orderId;
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="col-md-6 col-8 p-0 d-flex align-items-center justify-content-end">';
                    echo '<div class="d-flex justify-content-center align-items-center view">';
                    echo '<a href="customer-viewOrder.php?order_id=' . $orderId . '">VIEW ORDER | </a> &nbsp' . strtoupper($orderStatus) . '';

                    echo '</div>';



                    // Check the order status and set the button text and color accordingly
                    if ($orderStatus == 'Pending') {
                        echo '<div class=" d-flex justify-content-center align-items-center status ">';
                        echo '<button class="btn cancel-order-btn d-flex align-items-center justify-content-center p-0 status" data-order-id="' . $orderId . '" type="button" style="background-color: #9c1421; color: #fff;">';
                        echo 'CANCEL';
                        echo '</button>';
                        echo '</div>';
                    } else if ($orderStatus == 'To Rate') {
                        echo '<button class="btn d-flex align-items-center justify-content-center p-0 status order-received-btn" 
                        type="button" data-bs-toggle="modal" data-bs-target="#ratemodal" 
                        data-order-id="' . $orderId . '" onclick="setOrderId(' . $orderId . ')" style="background-color: #f1c827; color: #fff;">';
                        echo 'TO RATE';
                        echo '</button>';
                    }

                    echo '</div>';
                    echo '</div>';
                    $currentOrderId = $orderId; // Update the current order ID
                }

                // Display product details with the provided HTML structure
                echo '<div class="row py-1 py-sm-2 px-1 border item-details">';
                echo '<div class="col-md-1 col-sm-2 col-3 py-1 d-flex align-items-center justify-content-center">';
                echo '<div class="d-flex align-items-center justify-content-center">';
                echo '<img class="item-image" src="' . $productPic . '" alt="' . $productName . '">';
                echo '</div>';
                echo '</div>';
                echo '<div class="col-md-9 col-sm-7 col-6 py-3 p-1 d-block align-items-center justify-content-center">';
                echo '<div class="col-12 item-title">' . $productName . '</div>';
                echo '<div class="col-12 item-deets">Quantity: ' . $quantity . '</div>';
                echo '</div>';
                echo '<div class="col-md-2 col-sm-3 col-3 p-2 d-flex align-items-center justify-content-center">';
                echo '<div class="col-12 justify-content-center d-flex item-price">₱' . number_format($subtotal, 2) . '</div>';
                echo '</div>';
                echo '</div>';
            }

            // Display order total after the loop for the last order
            echo '<div class="row">';
            echo '<div class="col-lg-11 col-md-10 col-9 align-items-center d-flex justify-content-end">';
            echo '<div class="total-title">Order Total:</div>';
            echo '</div>';
            echo '<div class="col-lg-1 col-md-2 col-3 d-flex align-items-center justify-content-center">';
            echo '<div class="total-con">₱' . number_format($orderTotals[$currentOrderId], 2) . '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } else {
            echo 'No orders found for the selected status.';
        }
    } else {
        echo 'Error in the query: ' . mysqli_error($conn);
    }
}

// Check if 'request' is set in POST
if (isset($_POST['request'])) {
    // Call the function with the request parameter
    displayOrderDetails($_POST['request']);
} else {
    // Call the function without a specific order status (for "All Orders")
    displayOrderDetails('0');
}
