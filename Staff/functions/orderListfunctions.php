<?php
include("../../Admin/functions/dbConfig.php");

class EmployeeInfo{
    public $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

   public function getEmployeeData($employeeId){
        $sql = "SELECT users.USER_ID, users.U_FIRST_NAME, users.U_LAST_NAME, users.U_MIDDLE_NAME, users.U_SUFFIX, users.U_GENDER, users.U_PHONE_NUMBER, users.U_EMAIL, users.U_PICTURE, emp_job_info.EMP_BASIC_SALARY FROM users 
        inner join user_roles on users.USER_ID = user_roles.USER_ID
        inner join roles on user_roles.ROLE_CODE = roles.ROLE_CODE
        inner join emp_job_info on roles.ROLE_NAME = emp_job_info.EMP_POSITION
        WHERE users.USER_ID  = '$employeeId'";

        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            return mysqli_fetch_assoc($result);
        } else {
            return false;
        }
   }


   public function orderListData(){
    $userID = $_SESSION['USER_ID'];
    $listOders = array();

    $OrderList = "SELECT online_order.OL_CART_ID, DATE_FORMAT(online_order.DATE_CREATED, '%M %e, %Y') AS FORMATTED_DATE, ol_order_status.STATUS_NAME FROM online_order INNER JOIN ol_order_status ON online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
    INNER JOIN ol_order_type ON online_order.OL_ORDER_TYPE_ID = ol_order_type.OL_ORDER_TYPE_ID
    WHERE ol_order_type.OL_ORDER_TYPE_ID = 2 AND ol_order_status.STATUS_NAME = 'Pending'";
    
    $stmt = $this->conn->prepare($OrderList);
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($cartID, $formatDate, $statusName);  // Ayaw hilabti ni pula rana pero woking ni
        
        while ($stmt->fetch()) {
            $listOders[] = [
                'OL_CART_ID' => $cartID,
                'FORMATTED_DATE' => $formatDate,
                'STATUS_NAME' => $statusName,
            ];
        }

        $stmt->close();
    }

    return $listOders;
}


public function renderOrderList($orderData) {

    if (empty($orderData)) {
        echo '<tr><td colspan="6" style="border:1px solid white;">No Active Orders Available</td></tr>';
    } else {
        foreach ($orderData as $row) {
            $cartID = $row["OL_CART_ID"];

            echo '<div class="order-card">';
            echo '<div class="top-order">';
            echo '<div class="order-numberCont">';
            echo '<p class="orderNum">Order#' . $row['OL_CART_ID'] . '</p>';
            echo '<p class="date">' . $row['FORMATTED_DATE'] . '</p>';
            echo '</div>';
            echo '<div class="order-status">';
            echo '<p class="status">' . $row['STATUS_NAME'] . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="middle-order">';
        
            $orderDetailSql = "SELECT online_cart_item.OL_PROD_QUANTITY, product.PROD_NAME, product.PROD_SELLING_PRICE, product.PROD_PIC, prod_category.CATEGORY_NAME
            FROM online_cart_item
            INNER JOIN product ON online_cart_item.PROD_ID = product.PROD_ID
            INNER JOIN prod_category ON product.CATEGORY_ID = prod_category.CATEGORY_ID
            WHERE online_cart_item.OL_CART_ID = ?";

                // Assuming $cartID is the cart ID value
                $stmt = $this->conn->prepare($orderDetailSql);
                $stmt->bind_param('s', $cartID);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row1 = $result->fetch_assoc()) {
                echo '<div class="order-items">';
                echo '<img src="../../Icons/' . $row1['PROD_PIC'] . '" alt="" class="foodImage" style="border-radius: 8px;">';
                echo '<div class="item-content">';
                echo '<p class="foodTitle">' . $row1['PROD_NAME'] . '</p>';
                echo '<div class="item-category">';
                echo '<p style="margin-right: 5px;">' . $row1['CATEGORY_NAME'] . '</p>';
                echo '<img src="../../Icons/circle.svg" alt="" style="margin-right: 5px;">';
                echo '<p>Filipino Cuisine</p>';
                echo '</div>';
                echo '<div class="price-Quantiy">';
                echo '<p class="item-price">Standard Price: ₱' . $row1['PROD_SELLING_PRICE'] . '</p>';
                echo '<p style="color: black; font-weight: calc(1000); font-size: 8px;">Qty ' . $row1['OL_PROD_QUANTITY'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                }
        
            echo '</div>';
            echo '<div class="lower-order"></div>';
            echo '<button type="button" class="viewOrder" value="' . $cartID . '">View Order</button>';
            echo '</div>';
        }            
        
    }
}


public function filterOrderList($orderStatus) {
    $OrderList = "SELECT online_order.OL_CART_ID, 
                    DATE_FORMAT(online_order.DATE_CREATED, '%M %e, %Y') AS FORMATTED_DATE, 
                    ol_order_status.STATUS_NAME, ol_order_status.ORDER_STATUS_ID
                    FROM online_order
                    INNER JOIN ol_order_status ON ol_order_status.ORDER_STATUS_ID = online_order.ORDER_STATUS_ID
                    INNER JOIN ol_order_type ON online_order.OL_ORDER_TYPE_ID = ol_order_type.OL_ORDER_TYPE_ID
                    WHERE ol_order_type.OL_ORDER_TYPE_ID = 2 AND ol_order_status.ORDER_STATUS_ID = ?";

    $stmt = $this->conn->prepare($OrderList);

    if ($stmt) {
        $stmt->bind_param("s", $orderStatus);
        $stmt->execute();
        $result = $stmt->get_result();

        $orderData = array();

        while ($row = $result->fetch_assoc()) {
            $orderData[] = [
                'OL_CART_ID' => $row['OL_CART_ID'],
                'FORMATTED_DATE' => $row['FORMATTED_DATE'],
                'STATUS_NAME' => $row['STATUS_NAME'],
                'ORDER_STATUS_ID' => $row['ORDER_STATUS_ID'],
            ];
        }

        $stmt->close();
        return $orderData;
    } else {
        // Handle error if the statement preparation fails
        return [];
    }
}


public function searchOrderlist($searchName) {

    $OrderList = "SELECT online_order.OL_CART_ID, 
    DATE_FORMAT(online_order.DATE_CREATED, '%M %e, %Y') AS FORMATTED_DATE, 
    ol_order_status.STATUS_NAME, ol_order_status.ORDER_STATUS_ID
    FROM online_order
    INNER JOIN ol_order_type ON online_order.OL_ORDER_TYPE_ID = ol_order_type.OL_ORDER_TYPE_ID
    INNER JOIN ol_order_status ON ol_order_status.ORDER_STATUS_ID = online_order.ORDER_STATUS_ID
    WHERE ol_order_type.OL_ORDER_TYPE_ID = 2 AND online_order.OL_CART_ID LIKE ?";

    $stmt = $this->conn->prepare($OrderList);

    $searchNameParam = "%" . $searchName . "%";

    $stmt->bind_param("s", $searchNameParam);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $employeeData = array();
        
        while ($row = $result->fetch_assoc()) {
            $employeeData[] = $row;
        }
        
        return $employeeData;
    } else {
        // Handle the error
        return null;
    }
}


public function getAllOrders() {
    $OrderList = "SELECT online_order.OL_CART_ID,
    DATE_FORMAT(online_order.DATE_CREATED, '%M %e, %Y') AS FORMATTED_DATE, 
    ol_order_status.STATUS_NAME, ol_order_status.ORDER_STATUS_ID
    FROM online_order
    INNER JOIN ol_order_status ON ol_order_status.ORDER_STATUS_ID = online_order.ORDER_STATUS_ID
    WHERE ol_order_status.ORDER_STATUS_ID = 1 AND online_order.OL_ORDER_TYPE_ID = 2";

    $result = $this->conn->query($OrderList);

    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Output the HTML structure for each product
            $cartID = $row["OL_CART_ID"];

            echo '<div class="order-card">';
            echo '<div class="top-order">';
            echo '<div class="order-numberCont">';
            echo '<p class="orderNum">Order#' . $row['OL_CART_ID'] . '</p>';
            echo '<p class="date">' . $row['FORMATTED_DATE'] . '</p>';
            echo '</div>';
            echo '<div class="order-status">';
            $statusClass = ($row['ORDER_STATUS_ID'] == 3) ? 'status-yellow' : '';
            echo '<p class="status ' . $statusClass . '">' . $row['STATUS_NAME'] . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="middle-order">';
        
            $orderDetailSql = "SELECT online_cart_item.OL_PROD_QUANTITY, product.PROD_NAME, product.PROD_SELLING_PRICE, product.PROD_PIC ,prod_category.CATEGORY_NAME
                            FROM online_cart_item
                            INNER JOIN product ON online_cart_item.PROD_ID = product.PROD_ID
                            INNER JOIN prod_category ON product.CATEGORY_ID = prod_category.CATEGORY_ID
                            WHERE online_cart_item.OL_CART_ID  = '$cartID'";
            $orderDetailSqlResult = mysqli_query($this->conn, $orderDetailSql);
        
            while ($row1 = mysqli_fetch_array($orderDetailSqlResult)) {
                echo '<div class="order-items">';
                echo '<img src="../../Icons/' . $row1['PROD_PIC'] . '" alt="" class="foodImage" style="border-radius: 8px;">';
                echo '<div class="item-content">';
                echo '<p class="foodTitle">' . $row1['PROD_NAME'] . '</p>';
                echo '<div class="item-category">';
                echo '<p style="margin-right: 5px;">' . $row1['CATEGORY_NAME'] . '</p>';
                echo '<img src="../../Icons/circle.svg" alt="" style="margin-right: 5px;">';
                echo '<p>Filipino Cuisine</p>';
                echo '</div>';
                echo '<div class="price-Quantiy">';
                echo '<p class="item-price">Standard Price: ₱' . $row1['PROD_SELLING_PRICE'] . '</p>';
                echo '<p style="color: black; font-weight: calc(1000); font-size: 8px;">Qty ' . $row1['OL_PROD_QUANTITY'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        
            echo '</div>';
            echo '<div class="lower-order"></div>';
            echo '<button type="button" class="viewOrder" value="' . $cartID . '">View Order</button>';
            echo '</div>';
        }
    } else {
        echo "No data";
    }
}


public function orderHeaderDetails(){
    $orderData = array();
    $cartID = $_SESSION["cartID"];

    $viewOrder = "SELECT
        orderID,
        cartIDS,
        statusID,
        FORMATTED_DATE,
        statusName,
        partialTotal,
        TotalItem,
        SHIPPING_FEE,
        (SHIPPING_FEE + partialTotal) AS Total
    FROM (
        SELECT
            online_order.ONLINE_ORDER_ID AS orderID,
            online_order.OL_CART_ID AS cartIDS,
            online_order.ORDER_STATUS_ID AS statusID,
            DATE_FORMAT(online_order.DATE_CREATED, '%M %e, %Y') AS FORMATTED_DATE,
            ol_order_status.STATUS_NAME AS statusName,
            SUM(online_cart_item.OL_SUBTOTAL) AS partialTotal,
            COUNT(online_cart_item.OL_CART_ID) AS TotalItem,
            online_order.SHIPPING_FEE
        FROM
            online_order
        INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
        INNER JOIN ol_order_status ON online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
        WHERE
            online_order.OL_CART_ID = '$cartID'
    ) AS subquery;";

    $stmt = $this->conn->prepare($viewOrder);

    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($orderID, $cartIDS, $statusID, $FORMATTED_DATE, $statusName, $partialTotal, $TotalItem, $SHIPPING_FEE, $Total);
        
        while ($stmt->fetch()) {
            $orderData[] = [
                'ONLINE_ORDER_ID' => $orderID,
                'OL_CART_ID' => $cartIDS,
                'ORDER_STATUS_ID' => $statusID,
                'FORMATTED_DATE' => $FORMATTED_DATE,
                'STATUS_NAME' => $statusName,
                'partialTotal' => $partialTotal,
                'TotalItem' => $TotalItem,
                'SHIPPING_FEE' => $SHIPPING_FEE,
                'Total' => $Total,
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
            echo '<p>Order#' . $row['OL_CART_ID'] . '</p>';
            
            if ($row['ORDER_STATUS_ID'] == 1) {
                echo '<button type="submit" name="claimBtn" class="claimBtn" 
                data-status-id="' . 1 . '" 
                data-ol-cart-id="' . $row['ONLINE_ORDER_ID'] . '" 
                data-status-name="' . $row['STATUS_NAME'] . '" 
                value="₱ ' . $row['Total'] .'">Confirm Order</button>';
            }else if($row['ORDER_STATUS_ID'] == 6){
                echo '<button type="submit" name="claimBtn" class="claimBtn" 
                data-status-id="' . 6 . '" 
                data-ol-cart-id="' . $row['ONLINE_ORDER_ID'] . '" 
                data-status-name="' . $row['STATUS_NAME'] . '" 
                value="₱ ' . $row['Total'] .'">Order Prepared</button>';
            }else if($row['ORDER_STATUS_ID'] == 7){
                echo '<button type="submit" name="claimBtn" class="claimBtn" 
                data-status-id="' . 7 . '"
                data-ol-cart-id="' . $row['ONLINE_ORDER_ID'] . '" 
                data-status-name="' . $row['STATUS_NAME'] . '" 
                value="₱ ' . $row['Total'] .'">Ordered Pick-Up</button>';
            }else{
                
            }
            
            echo '</div>';
            
            echo '<div class="bottomOrderInfo">';
            $statusName = $row['STATUS_NAME'];
            $backgroundColor = ($statusName === 'On the way') ? '#A2C53A' : ''; // Set the background color to orange if the status is 'On the way'

            echo '<p class="orderStatus" style="background-color: ' . $backgroundColor . ';">' . $statusName . '</p>';
            echo '<p style="margin-left: 20px;">' . $row['FORMATTED_DATE'] . '</p>';
            echo '</div>';
        }
    }
}


public function productCartDetails(){
    $orderData = array();
    $cartID = $_SESSION["cartID"];

    $viewItemDetails = "SELECT product.PROD_NAME, product.PROD_DESC, product.PROD_SELLING_PRICE, online_cart_item.OL_PROD_QUANTITY, online_cart_item.OL_SUBTOTAL, product.PROD_PIC, product.PROD_ID FROM
    online_cart_item INNER JOIN product ON online_cart_item.PROD_ID = product.PROD_ID WHERE online_cart_item.OL_CART_ID = '$cartID'";

    $stmt = $this->conn->prepare($viewItemDetails);
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($productName, $prodDesc, $prodPrice, $prodQuantity, $prodSubTotal, $prodPIC, $prodID);  // Ayaw hilabti ni pula rana pero woking ni
        
        while ($stmt->fetch()) {
            $orderData[] = [
                'PROD_NAME' => $productName,
                'PROD_DESC' => $prodDesc,
                'PROD_SELLING_PRICE' => $prodPrice,
                'OL_PROD_QUANTITY' => $prodQuantity,
                'OL_SUBTOTAL' => $prodSubTotal,
                'PROD_PIC' => $prodPIC,
                'PROD_ID' => $prodID,
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
            echo '<tr class="tableDatarow">';
            echo '<td>';
            echo '<div class="product-container">';
            echo '<div class="productIdContainer" style="display: hidden;" data-proudct-id="' . $row['PROD_ID'] . '" >'; echo '</div>';
            echo '<img src="../../Icons/'. $row['PROD_PIC'] . '" alt="" class="foodImage">';
            echo '<div class="below-content">';
            echo '<p class="food-title">' . $row['PROD_NAME'] . '</p>';
            echo '<div class="card-category">';
            echo '<p style="margin-right: 5px;">Cooked Food</p>';
            echo '<img src="/Icons/circle.svg" alt="" style="margin-right: 5px;">';
            echo '<p>Filipino Cuisine</p>';
            echo '</div>';
            echo '<p style="font-size: 10px; margin-top: 10px; color: #B4B4B4;">Description</p>';
            echo '<div class="description">' . $row['PROD_DESC'] . '</div>';
            echo '</div>';
            echo '</div>';
            echo '</td>';
        
            echo '<td class="orderPrice">₱ ' . $row['PROD_SELLING_PRICE'] . '</td>';
            echo '<td class="prodQuantity">' . $row['OL_PROD_QUANTITY'] . '</td>';
            echo '<td class="orderTotalPrice">₱ ' . $row['OL_SUBTOTAL'] . '</td>';
            echo '</tr>';
        }
        
    }
}


public function paymentProdDetails(){
    $orderData = array();
    $cartID = $_SESSION["cartID"];

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
            online_order.OL_CART_ID = '$cartID'
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
    $cartID = $_SESSION["cartID"];

    $viewCustomerDetails = "SELECT users.U_FIRST_NAME, users.U_MIDDLE_NAME, users.U_LAST_NAME, users.U_PHONE_NUMBER, users.U_GENDER, users.U_CAMPUS_AREA, COUNT(online_cart_item.OL_CART_ID) AS totalCart,
    users.U_PICTURE
    FROM online_order 
    INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
    INNER JOIN users ON ol_cart.CUSTOMER_ID = users.USER_ID
    INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID 
    WHERE online_order.OL_CART_ID = '$cartID'";

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
            echo '<div class="customer-profile">';
            echo '<img src="../../Icons/'. $row['U_PICTURE'] .'" alt="">';
            echo '<div class="customer-name">';
            echo '<p class="username">' . $row['U_FIRST_NAME'] . ' ' . $row['U_MIDDLE_NAME'] . ' ' . $row['U_LAST_NAME'] . '</p>';
            echo '<p class="customerTag">Customer</p>';
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
            echo '<div class="gender">';
            echo '<p>Gender</p>';
            echo '<p>' . $row['U_GENDER'] .'</p>';
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