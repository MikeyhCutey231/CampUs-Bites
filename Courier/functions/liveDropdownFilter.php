<?php
    include("../functions/orderDetailsHandler.php");
    $database = new Connection();
    $conn = $database->conn;

    if (isset($_POST['request'])) {
        $new = new orderDetails($conn);
        $orderStatus = $_POST['request'];
        
        if ($orderStatus == 0) {
            $status = $new->filterOrderList([2, 3]);
        } else {
            $status = $new->filterOrderList($orderStatus);
        }
    
        if (!empty($status)) {
            foreach ($status as $row) {
                $cartID = $row["OL_CART_ID"];

                ?> <div class="order-card">
                <div class="top-order">
                        <div class="order-numberCont">
                            <p class="orderNum">Order#<?php echo $row['ONLINE_ORDER_ID'] ?></p>
                            <p class="date"><?php echo $row['FORMATTED_DATE'] ?></p>
                        </div>
                        <div class="order-status">
                            <?php
                            $statusClass = ($row['ORDER_STATUS_ID'] == 3) ? 'status-yellow' : '';
                            ?>
                            <p class="status <?php echo $statusClass ?>"><?php echo $row['STATUS_NAME'] ?></p>
                        </div>
                </div>
                <div class="middle-order">
                    <?php
                        $orderDetailSql = "SELECT online_cart_item.OL_PROD_QUANTITY, product.PROD_NAME, product.PROD_SELLING_PRICE, product.PROD_PIC ,prod_category.CATEGORY_NAME
                        FROM online_cart_item
                        INNER JOIN product ON online_cart_item.PROD_ID = product.PROD_ID
                        INNER JOIN prod_category ON product.CATEGORY_ID = prod_category.CATEGORY_ID
                        WHERE online_cart_item.OL_CART_ID  = '$cartID'";
                        $orderDetailSqlResult = mysqli_query($conn, $orderDetailSql);

                        while($row1 = mysqli_fetch_array($orderDetailSqlResult)){
                            ?>
                                 <div class="order-items">
                                    <img src="../../Icons/<?php echo $row1['PROD_PIC'] ?>" alt=""class="foodImage" style="border-radius: 8px;">
                                    <div class="item-content">
                                        <p class="foodTitle"><?php echo $row1['PROD_NAME']?></p>
                                        <div class="item-category">
                                            <p style="margin-right: 5px;"><?php echo $row1['CATEGORY_NAME']?></p>
                                            <img src="../../Icons/circle.svg" alt="" style="margin-right: 5px;">
                                            <p>Filipino Cuisine</p>
                                        </div>
                                        <div class="price-Quantiy">
                                            <p class="item-price">Standard Price: ₱<?php echo $row1['PROD_SELLING_PRICE'] ?></p>
                                            <p style="color: black; font-weight: calc(1000); font-size: 8px;">Qty <?php echo $row1['OL_PROD_QUANTITY'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php 

                        }

                    ?>
                </div>
                <div class="lower-order"></div>

                <button type="button" class="viewOrder" value="<?php echo $cartID ?>">View Order</button>
                </div>
            <?php
            
            }

        } else {
            echo '<p style="padding-left: 40px; padding-top: 20px;">No Data Available</p>';
        }
    }

?>


<script>
        $(document).ready(function() {
            $('.viewOrder').click(function() {
                var cartID = $(this).val();

                $.ajax({
                    type: "POST",  // Change the request type if needed
                    url: "../functions/activCartIDHandler.php",  // Replace with the path to your PHP file
                    data: { cartID: cartID },
                    success: function(response) {
                        // Handle the response from the server
                        window.location.href = "viewOrderlist.php";
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>