<?php 
include("../../Admin/functions/dbConfig.php");
 
 class activeOrdersData{
    public $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getOrders() {
        $employeeData = array();
    
        $query = "SELECT
        online_order.ONLINE_ORDER_ID,
        online_order.OL_CART_ID,
        users.U_FIRST_NAME,
        users.U_MIDDLE_NAME,
        users.U_LAST_NAME,
        users.U_CAMPUS_AREA,
        online_order.SHIPPING_FEE,
        SUM(online_cart_item.OL_SUBTOTAL) AS totalSubtotal
        FROM
            online_order
        INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
        INNER JOIN users ON ol_cart.CUSTOMER_ID = users.USER_ID
        INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
        WHERE
            online_order.ORDER_STATUS_ID = 1 AND online_order.OL_ORDER_TYPE_ID = 1
        GROUP BY
            online_order.OL_CART_ID,
            users.U_FIRST_NAME,
            users.U_MIDDLE_NAME,
            users.U_LAST_NAME,
            users.U_CAMPUS_AREA,
            online_order.SHIPPING_FEE;";
    
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($onlineOrderId,$cartID, $firstName, $middleName, $lastName, $campusArea, $shippingFee, $cartTotal);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $employeeData[] = [
                    'ONLINE_ORDER_ID' => $onlineOrderId,
                    'OL_CART_ID' => $cartID,
                    'U_FIRST_NAME' => $firstName,
                    'U_MIDDLE_NAME' => $middleName,
                    'U_LAST_NAME' => $lastName,
                    'U_CAMPUS_AREA' => $campusArea,
                    'SHIPPING_FEE' => $shippingFee,
                    'totalSubtotal' => $cartTotal,
                ];
            }
    
            $stmt->close();
        }
    
        return $employeeData;
    }
    
    public function renderOrders($employeeData) {
        if (empty($employeeData)) {
            echo '<tr><td colspan="6" style="border:1px solid white;">No Active Orders Available</td></tr>';
        } else {
            foreach ($employeeData as $row) {
                echo '<tr>';
                echo '<td>' . $row['ONLINE_ORDER_ID'] . '</td>';
                echo '<td>' . $row['U_FIRST_NAME'] . ' ' .  $row['U_MIDDLE_NAME'] . ' ' . $row['U_LAST_NAME'] . '</td>';
                echo '<td>' . $row['U_CAMPUS_AREA'] . '</td>';
                echo '<td>' . $row['SHIPPING_FEE'] . '</td>';
                echo '<td>' . $row['totalSubtotal'] . '</td>';
                echo '<td><button class="viewOrder" value="' . $row['OL_CART_ID'] . '">View Order</button></td>';
                echo '</tr>';
            }
        }
    }


    public function orderHeaderDetails(){
        $orderData = array();
        $oldCartID = $_SESSION["olCartId"];
    
        $viewOrder = "SELECT online_order.ONLINE_ORDER_ID , online_order.OL_CART_ID, DATE_FORMAT(online_order.DATE_CREATED, '%M %e, %Y') AS FORMATTED_DATE, ol_order_status.STATUS_NAME FROM online_order
        INNER JOIN ol_order_status ON online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
        WHERE online_order.OL_CART_ID = '$oldCartID'";
    
        $stmt = $this->conn->prepare($viewOrder);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($onlineOrderId, $cartID, $date, $statusName);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $orderData[] = [
                    'ONLINE_ORDER_ID' => $onlineOrderId,
                    'OL_CART_ID' => $cartID,
                    'FORMATTED_DATE' => $date,
                    'STATUS_NAME' => $statusName,
                ];
            }
    
            $stmt->close();
        }
    
        return $orderData;
    }


    public function renderOrdersDetails($orderData) {
        if (empty($orderData)) {
            echo '<tr><td colspan="6" style="border:1px solid white;">No Active Orders Available</td></tr>';
        } else {
            foreach ($orderData as $row) {
                
                echo '<div class="topOrderInfo">';
                echo '<p>Order#' . $row['ONLINE_ORDER_ID'] . '</p>';
                echo '<button type="button" name="claimBtn" id="claimBtn" value="'. $row['OL_CART_ID'] .'">Claim Order</button>';
                echo '</div>';
                
                
                echo '<div class="bottomOrderInfo">';
                echo '<p class="orderStatus">' . $row['STATUS_NAME'] . '</p>';
                echo '<p style="margin-left: 20px;">' . $row['FORMATTED_DATE'] . '</p>';
                echo '</div>';
            }
            
        }
    }

    public function productCartDetails(){
        $orderData = array();
        $oldCartID = $_SESSION["olCartId"];
    
        $viewItemDetails = "SELECT product.PROD_NAME, product.PROD_DESC, product.PROD_SELLING_PRICE, online_cart_item.OL_PROD_QUANTITY, online_cart_item.OL_SUBTOTAL, product.PROD_PIC FROM
        online_cart_item INNER JOIN product ON online_cart_item.PROD_ID = product.PROD_ID WHERE online_cart_item.OL_CART_ID = '$oldCartID'";
    
        $stmt = $this->conn->prepare($viewItemDetails);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($productName, $prodDesc, $prodPrice, $prodQuantity, $prodSubTotal, $prodPIC);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $orderData[] = [
                    'PROD_NAME' => $productName,
                    'PROD_DESC' => $prodDesc,
                    'PROD_SELLING_PRICE' => $prodPrice,
                    'OL_PROD_QUANTITY' => $prodQuantity,
                    'OL_SUBTOTAL' => $prodSubTotal,
                    'PROD_PIC' => $prodPIC,
                ];
            }
    
            $stmt->close();
        }
    
        return $orderData;
    }



    public function renderCartProdDetails($orderData) {
        if (empty($orderData)) {
            echo '<tr><td colspan="6" style="border:1px solid white;">No Active Orders Available</td></tr>';
        } else {
            foreach ($orderData as $row) {
                echo '<tr>';
                echo '<td>';
                echo '<div class="product-container">';
                echo '<img src="../../Icons/' . $row['PROD_PIC'] . '" alt="" class="foodImage">';
                echo '<div class="below-content">';
                echo '<p class="food-title">' . $row['PROD_NAME'] . '</p>';
                echo '<div class="card-category">';
                echo '<p style="margin-right: 5px;">Cooked Food</p>';
                echo '<img src="../../Icons/circle.svg" alt="" style="margin-right: 5px;">';
                echo '<p>Filipino Cuisine</p>';
                echo '</div>';
                echo '<p style="font-size: 10px; margin-top: 10px; color: #B4B4B4;">Description</p>';
                echo '<div class="description">' . $row['PROD_DESC'] . '</div>';
                echo '</div>';
                echo '</div>';
                echo '</td>';
            
                echo '<td class="orderPrice">₱ ' . $row['PROD_SELLING_PRICE'] . '</td>';
                echo '<td>' . $row['OL_PROD_QUANTITY'] . '</td>';
                echo '<td class="orderTotalPrice">₱ ' . $row['OL_SUBTOTAL'] . '</td>';
                echo '</tr>';
            }
            
        }
    }


    public function paymentProdDetails(){
        $orderData = array();
        $oldCartID = $_SESSION["olCartId"];
    
        $viewPay = "SELECT
            partialTotal,
            TotalItem,
            SHIPPING_FEE,
            (SHIPPING_FEE + partialTotal) AS Total
        FROM (
            SELECT
                SUM(online_cart_item.OL_SUBTOTAL) AS partialTotal,
                COUNT(online_cart_item.OL_CART_ID) AS TotalItem,
                online_order.SHIPPING_FEE
            FROM
                online_order
            INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
            WHERE
                online_order.OL_CART_ID = '$oldCartID'
        ) AS subquery;
        ";
    
        $stmt = $this->conn->prepare($viewPay);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($partialTotal, $totalItem, $shippingFee, $Total);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $orderData[] = [
                    'partialTotal' => $partialTotal,
                    'TotalItem' => $totalItem,
                    'SHIPPING_FEE' => $shippingFee,
                    'Total' => $Total,
                ];
            }
    
            $stmt->close();
        }
    
        return $orderData;
    }


    public function renderPaymentDetails($orderData) {
        if (empty($orderData)) {
            echo '<tr><td colspan="6" style="border:1px solid white;">No Active Orders Available</td></tr>';
        } else {
            foreach ($orderData as $row) {
                echo '<div class="subTotal">';
                echo '<p>Subtotal (' . $row['TotalItem'] . ' Items)</p>';
                echo '<p>₱ ' . $row['partialTotal'] . '</p>';
                echo '</div>';

                echo '<div class="deliveryFee">';
                echo '<p>Delivery Fee</p>';
                echo '<p>₱ ' . $row['SHIPPING_FEE'] . '</p>';
                echo '</div>';

                echo '<div class="totalPrice">';
                echo '<p>Total Price</p>';
                echo '<p>₱ ' . $row['Total'] . '</p>';
                echo '</div>';
            }
            
        }
    }


    public function customerInformation(){
        $orderData = array();
        $oldCartID = $_SESSION["olCartId"];
    
        $viewCustomerDetails = "SELECT users.U_FIRST_NAME, users.U_MIDDLE_NAME, users.U_LAST_NAME, users.U_PHONE_NUMBER, users.U_GENDER, users.U_CAMPUS_AREA, COUNT(online_cart_item.OL_CART_ID) AS totalCart,
        users.U_PICTURE
        FROM online_order 
        INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
        INNER JOIN users ON ol_cart.CUSTOMER_ID = users.USER_ID
        INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID 
        WHERE online_order.OL_CART_ID = '$oldCartID'";
    
        $stmt = $this->conn->prepare($viewCustomerDetails);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($fname, $mname, $lname, $phoneNum, $gender, $campusArea, $totalCart, $userPic);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $orderData[] = [
                    'U_FIRST_NAME' => $fname,
                    'U_MIDDLE_NAME' => $mname,
                    'U_LAST_NAME' => $lname,
                    'U_PHONE_NUMBER' => $phoneNum,
                    'U_GENDER' => $gender,
                    'U_CAMPUS_AREA' => $campusArea,
                    'totalCart' => $totalCart,
                    'U_PICTURE' => $userPic,
                ];
            }
    
            $stmt->close();
        }
    
        return $orderData;
    }



    public function renderCustomerInfo($orderData) {
        if (empty($orderData)) {
            echo '<tr><td colspan="6" style="border:1px solid white;">No Active Orders Available</td></tr>';
        } else {
            foreach ($orderData as $row) {
                echo '<p class="customerText-head">Customer</p>';
                echo '<div class="customer-profile">';
                echo '<img src="../../Icons/'. $row['U_PICTURE'] .'" alt="">';
                echo '<div class="customer-name">';
                echo '<p class="username">' . $row['U_FIRST_NAME'] . ' ' . $row['U_MIDDLE_NAME'] . ' ' . $row['U_LAST_NAME'] . '</p>';
                echo '</div>';
                echo '</div>';

                echo '<div class="amountOrder">';
                echo '<img src="../../Icons/shopping-bag.svg" alt="">';
                echo '<p>' . $row['totalCart'] . ' Items</p>';
                echo '</div>';

                echo '<div class="contact-info">';
                echo '<p class="contactText-head">Contact Info</p>';
                echo '<div class="phone-num">';
                echo '<p>Phone Number</p>';
                echo '<p>' . $row['U_PHONE_NUMBER'] . '</p>';
                echo '</div>';
                echo '<div class="email">';
                echo '<p>Gender</p>';
                echo '<p>' . $row['U_GENDER'] . '</p>';
                echo '</div>';
                echo '</div>';

                echo '<div class="shipping-info">';
                echo '<p class="shippingText-head">Shipping Address</p>';
                echo '<div class="shippingAdd">';
                echo '<p>Campus Address</p>';
                echo '<p>' . $row['U_CAMPUS_AREA'] . '</p>';
                echo '</div>';
                echo '</div>';
            }
            
        }
    }

    

}

?>


