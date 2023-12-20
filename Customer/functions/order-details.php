
    <?php
    require_once '../../Admin/functions/dbConfig.php';
    echo '<style>body { overflow-x: hidden; }</style>';
    // Create an instance of the Database class
    $database = new Connection();
    $conn = $database->conn;

    // Check if order_id is set in the URL
    if (isset($_GET['order_id'])) {
        // Sanitize input
        $orderId = $_GET['order_id'];
        // Call a function to display detailed order information
        displayDetailedOrder($orderId, $conn);
    } else {
        echo 'Order ID not provided.';
    }

    // Function to fetch and display detailed order information

    function displayDetailedOrder($orderId, $conn)
    {

        // Display Order Details
        $orderQuery = "SELECT 
    online_order.ONLINE_ORDER_ID, 
    ol_order_status.STATUS_NAME, 
    online_order.OL_CART_ID, 
    product.PROD_NAME, 
    product.PROD_PIC, 
    online_cart_item.OL_PROD_QUANTITY, 
    product.PROD_SELLING_PRICE, 
    online_cart_item.OL_SUBTOTAL
FROM 
    online_order
INNER JOIN 
    ol_order_status ON online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
INNER JOIN 
    ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
INNER JOIN 
    online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
INNER JOIN 
    product ON online_cart_item.PROD_ID = product.PROD_ID
WHERE 
    online_order.ONLINE_ORDER_ID = ?
ORDER BY online_cart_item.PROD_ID";

        // Prepare the statement for order details query
        $orderStmt = mysqli_prepare($conn, $orderQuery);

        if ($orderStmt === false) {
            die('Error in preparing the order details statement: ' . mysqli_error($conn));
        }

        // Bind the parameters for order details query
        mysqli_stmt_bind_param($orderStmt, "s", $orderId);

        // Execute the order details statement
        $executeOrderResult = mysqli_stmt_execute($orderStmt);

        if ($executeOrderResult === false) {
            die('Error in executing the order details statement: ' . mysqli_error($conn));
        }

        // Get the result for order details query
        $orderResult = mysqli_stmt_get_result($orderStmt);
        // Display Order Header and Details
        echo '<div class="content justify-content-between">';
        echo '<div class="container-fluid order-con px-1 py-0">';
        echo '<div class="row order-title px-2">';
        echo '<div class="col-8 col-sm-6 p-0 d-flex align-items-center orderdeets-title">';
        echo 'Order Details: <span> &nbsp; Order#' . $orderId . '</span>';
        echo '</div>';
        echo '<div class="col-4 col-sm-6 p-0 justify-content-end align-items-center d-flex p-0 shopmore">';
        echo '<a href="customer-menu.php">';
        echo 'Shop more';
        echo '</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';




        // Display Order Details
        if ($orderResult) {
            $orderCount = mysqli_num_rows($orderResult);

            if ($orderCount > 0) {
                // Display order details with the provided HTML structure
                echo '<div class="container-fluid item-container mb-lg-3 mb-2">';
                echo '<div class="row item-deets-header px-sm-3  py-sm-3 py-2 px-1">';
                echo '<div class="col-6 p-sm-2 p-1 d-flex align-items-center item-ordered">';
                echo 'Item ordered';
                echo '</div>';
                echo '<div class="col-2 p-sm-2 p-0 d-flex align-items-center justify-content-center">';
                echo '<div class="unit-price">Unit Price</div>';
                echo '</div>';
                echo '<div class="col-2 p-sm-2 p-1 d-flex align-items-center  justify-content-center">';
                echo 'Quantity';
                echo '</div>';
                echo '<div class="col-2 p-sm-2 p-1 d-flex align-items-center justify-content-center">';
                echo 'Subtotal';
                echo '</div>';
                echo '<div class="row" id="orders-container">';
                echo '</div>';


                while ($orderRow = mysqli_fetch_assoc($orderResult)) {
                    $orderStatus = $orderRow['STATUS_NAME'];
                    $productName = $orderRow['PROD_NAME'];
                    $productPic = $orderRow['PROD_PIC'];
                    $quantity = $orderRow['OL_PROD_QUANTITY'];
                    $productPrice = $orderRow['PROD_SELLING_PRICE'];
                    $subtotal = $orderRow['OL_SUBTOTAL'];

                    // Calculate subtotal by multiplying price and quantity
                    $subtotal = $productPrice * $quantity;

                    echo '<div class="row item-con px-1 px-sm-3 px-sm-3 py-sm-2 py-1">';
                    echo '<div class="col-sm-1 col-2 p-sm-2 p-1 d-flex  justify-content-center">';
                    echo '<img class="item-image" src="../../Icons/' . $productPic . '" alt="' . $productName . '">';
                    echo '</div>';
                    echo '<div class="col-sm-5 col-4 col-2 p-sm-2 p-1 d-flex align-items-center justify-content-start text-truncate">';
                    echo '<div class="item-name">' . $productName . '</div>';
                    echo '</div>';
                    echo '<div class="col-2 p-2 d-flex align-items-center justify-content-center">';
                    echo '<div class="item-price">₱' . number_format($productPrice, 2) . '</div>';
                    echo '</div>';
                    echo '<div class="col-2 p-2 d-flex align-items-center justify-content-center">';
                    echo '<div class="item-quantity">' . $quantity . '</div>';
                    echo '</div>';
                    echo '<div class="col-2 p-2 d-flex align-items-center justify-content-center">';
                    echo '<div class="item-subtotal">₱' . number_format($subtotal, 2) . '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
            } else {
                echo 'No detailed order information found for the provided order ID.';
            }
        } else {
            echo 'Error in the order details query: ' . mysqli_error($conn);
        }

        // Close the order details statement
        mysqli_stmt_close($orderStmt);

        // Fetch customer and order details
        $detailsQuery = "SELECT 
    users.U_FIRST_NAME, 
    users.U_LAST_NAME, 
    users.U_PHONE_NUMBER, 
    users.U_CAMPUS_AREA, 
    ol_order_status.STATUS_NAME,
    ol_order_type.OL_ORDER_TYPE_NAME, 
    online_order.DATE_CREATED, 
    online_order.ONLINE_ORDER_ID,
    online_order.SHIPPING_FEE AS 'DELIVERY FEE',
    SUM(online_cart_item.OL_SUBTOTAL) as 'ORDER TOTAL',
    online_order.SHIPPING_FEE + SUM(online_cart_item.OL_SUBTOTAL) as 'PAYMENT TOTAL'
FROM 
    online_order
INNER JOIN 
    ol_order_type ON online_order.OL_ORDER_TYPE_ID = ol_order_type.OL_ORDER_TYPE_ID
INNER JOIN 
    ol_order_status ON online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
INNER JOIN 
    ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
INNER JOIN 
    online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
INNER JOIN 
    users ON ol_cart.CUSTOMER_ID = users.USER_ID
WHERE 
    online_order.ONLINE_ORDER_ID = ?
GROUP BY online_order.ONLINE_ORDER_ID";

        // Prepare the statement for details query
        $detailsStmt = mysqli_prepare($conn, $detailsQuery);

        if ($detailsStmt === false) {
            die('Error in preparing the details statement: ' . mysqli_error($conn));
        }

        // Bind the parameters for details query
        mysqli_stmt_bind_param($detailsStmt, "s", $orderId);

        // Execute the details statement
        $executeResult = mysqli_stmt_execute($detailsStmt);

        if ($executeResult === false) {
            die('Error in executing the details statement: ' . mysqli_error($conn));
        }

        // Get the result for details query
        $detailsResult = mysqli_stmt_get_result($detailsStmt);

        if ($detailsResult) {
            $count = mysqli_num_rows($detailsResult);

            if ($count > 0) {
                // Fetch data from the first row as it will be the same for all rows
                $detailsRow = mysqli_fetch_assoc($detailsResult);



                // Display Additional Details
                echo '<div class="container-fluid checkout-con">';
                echo '<div class="row address-header border px-sm-2 py-sm-2 p-2 mb-2 mb-lg-3">';

                // Display Customer Details
                echo '<div class="col-12 col-md-6 border p-0 px-sm-2 py-2">';
                echo '<div class="col-12 py-2 px-2 px-sm-3 d-flex align-items-center justify-content-start delivery-title">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#9C1421" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">';
                echo '<path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />';
                echo '</svg>';
                echo 'Customer Details';
                echo '</div>';
                echo '<div class="d-flex">';
                echo '<div class="col-6 py-1 px-2 px-sm-3 del-deets">Customer Name:</div>';
                echo '<div class="col-6 py-1 px-2 px-sm-3 justify-content-end d-flex del-deets">' . $detailsRow['U_FIRST_NAME'] . ' ' . $detailsRow['U_LAST_NAME'] . '</div>';
                echo '</div>';
                echo '<div class="d-flex">';
                echo '<div class="col-6 py-1 px-2 px-sm-3 del-deets">Phone Number:</div>';
                echo '<div class="col-6 py-1 px-2 px-sm-3 justify-content-end d-flex del-deets">' . $detailsRow['U_PHONE_NUMBER'] . '</div>';
                echo '</div>';
                echo '<div class="d-flex">';
                echo '<div class="col-6 py-1 px-2 px-sm-3 del-deets">Address inside the school:</div>';
                echo '<div class="col-6 py-1 px-2 px-sm-3 justify-content-end d-flex del-deets">' . $detailsRow['U_CAMPUS_AREA'] . '</div>';
                echo '</div>';
                echo '</div>';

                // Display Additional Details
                echo '<div class="col-12 col-lg-6 p-0 px-sm-2 py-2 mt-2 mt-md-0 border">';
                echo '<div class="col-12 py-2 px-2 px-sm-3 d-flex align-items-center justify-content-start delivery-title">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#9C1421" class="bi bi-box-fill" viewBox="0 0 16 16">';
                echo '<path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001 6.971 2.789Zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z" />';
                echo '</svg>';
                echo 'Additional Details';
                echo '</div>';
                echo '<div class="d-flex">';
                echo '<div class="col-6 px-sm-3 py-1 px-2 del-deets">Status:</div>';
                echo '<div class="col-6 px-sm-3 py-1 px-2 justify-content-end d-flex status" style="color:black;">' . $detailsRow['STATUS_NAME'] . '</div>';
                echo '</div>';
                echo '<div class="d-flex">';
                echo '<div class="col-6 px-sm-3 py-1 px-2 del-deets">Order Type:</div>';
                echo '<div class="col-6 px-sm-3 py-1 px-2 justify-content-end d-flex del-deets fw-bold">' . $detailsRow['OL_ORDER_TYPE_NAME'] . '</div>';
                echo '</div>';
                echo '<div class="d-flex">';
                echo '<div class="col-6 px-sm-3 py-1 px-2 del-deets">Order Placed Date and Time:</div>';
                echo '<div class="col-6 px-sm-3 py-1 px-2 justify-content-end d-flex del-deets">' . $detailsRow['DATE_CREATED'] . '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>'; // Closing the outer container
                echo '</div>';

                // Display Payment Information
                echo '<div class="container-fluid order-payment-con">';
                echo '<div class="row payment-header px-sm-3 py-sm-3 py-2 p-0">';
                echo '<div class="col-7 p-sm-2 p-1 d-flex align-items-center payment-title">Payment Method</div>';
                echo '<div class="col-5 p-sm-2 p-1 d-flex align-items-center justify-content-end payment-mode">' . 'Cash on Delivery' /* Replace with fetched payment method */ . '</div>';
                echo '</div>';

                // Fetch and display Order Total, Delivery Fee, and Total Payment
                echo '<div class="row itemtotal-con px-sm-3 py-0 p-0">';
                echo '<div class="col-sm-10 col-9 py-sm-2 py-1 d-flex align-items-center justify-content-start">';
                echo '<div class="totalProf-title">Order Total:</div>';
                echo '</div>';
                echo '<div class="col-sm-2 col-3 d-flex align-items-center justify-content-end">';
                echo '<div class="totalProd-value">₱' . number_format($detailsRow['ORDER TOTAL'], 2) . '</div>';
                echo '</div>';
                echo '</div>';

                echo '<div class="row shippingtotal-con px-sm-3 p-0">';
                echo '<div class="col-sm-10 col-9 py-sm-2 py-1 d-flex align-items-center justify-content-start">';
                echo '<div class="shipping-title">Delivery fee:</div>';
                echo '</div>';
                echo '<div class="col-sm-2 col-3  d-flex align-items-center justify-content-end">';
                echo '<div class="shipping-value">₱' . number_format($detailsRow['DELIVERY FEE'], 2) . '</div>';
                echo '</div>';
                echo '</div>';

                echo '<div class="row total-con px-sm-3 p-0">';
                echo '<div class="col-sm-10 col-9 py-sm-2 py-1  d-flex align-items-center justify-content-start">';
                echo '<div class="total-title">Total Payment:</div>';
                echo '</div>';
                echo '<div class="col-sm-2 col-3 py-sm-3 py-1 d-flex align-items-center justify-content-end">';
                echo '<div class="total-value">₱' . number_format($detailsRow['PAYMENT TOTAL'], 2) . '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>'; // Closing the payment container

            } else {
                echo 'No detailed order information found for the provided order ID.';
            }
        } else {
            echo 'Error in the details query: ' . mysqli_error($conn);
        }


        // Close the details statement
        mysqli_stmt_close($detailsStmt);
    }
