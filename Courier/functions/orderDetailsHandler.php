<?php 
include("../../Admin/functions/dbConfig.php");
 
 class orderDetails{
    public $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function orderListData(){
        $userID = $_SESSION['USER_ID'];
        $listOders = array();
    
        $OrderList = "SELECT online_order.ONLINE_ORDER_ID, online_order.OL_CART_ID, 
        DATE_FORMAT(online_order.DATE_CREATED, '%M %e, %Y') AS FORMATTED_DATE, 
        ol_order_status.STATUS_NAME, ol_order_status.ORDER_STATUS_ID
        FROM online_order
        INNER JOIN ol_order_status ON ol_order_status.ORDER_STATUS_ID = online_order.ORDER_STATUS_ID
        WHERE COURIER_ID = '$userID' AND (ol_order_status.ORDER_STATUS_ID = '2' OR ol_order_status.ORDER_STATUS_ID = '3');";
        
        $stmt = $this->conn->prepare($OrderList);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($onlineOrderId, $cartID, $formatDate, $statusName, $statusID);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $listOders[] = [
                    'ONLINE_ORDER_ID' => $onlineOrderId,
                    'OL_CART_ID' => $cartID,
                    'FORMATTED_DATE' => $formatDate,
                    'STATUS_NAME' => $statusName,
                    'ORDER_STATUS_ID' => $statusID,
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
                echo '<p class="orderNum">Order#' . $row['ONLINE_ORDER_ID'] . '</p>';
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
            
        }
    }


    public function filterOrderList($orderStatus) {
        $userID = $_SESSION['USER_ID'];
    
        if (is_array($orderStatus)) {
            // Convert the array to a comma-separated string
            $statusString = implode(',', $orderStatus);
    
            $OrderList = "SELECT online_order.ONLINE_ORDER_ID, online_order.OL_CART_ID, 
            DATE_FORMAT(online_order.DATE_CREATED, '%M %e, %Y') AS FORMATTED_DATE, 
            ol_order_status.STATUS_NAME, ol_order_status.ORDER_STATUS_ID
            FROM online_order
            INNER JOIN ol_order_status ON ol_order_status.ORDER_STATUS_ID = online_order.ORDER_STATUS_ID
            WHERE COURIER_ID = '$userID' AND ol_order_status.ORDER_STATUS_ID IN ($statusString)";
                
        } else {
            // If $orderStatus is not an array, treat it as a single status value
            $OrderList = "SELECT online_order.ONLINE_ORDER_ID, online_order.OL_CART_ID, 
            DATE_FORMAT(online_order.DATE_CREATED, '%M %e, %Y') AS FORMATTED_DATE, 
            ol_order_status.STATUS_NAME, ol_order_status.ORDER_STATUS_ID
            FROM online_order
            INNER JOIN ol_order_status ON ol_order_status.ORDER_STATUS_ID = online_order.ORDER_STATUS_ID
            WHERE COURIER_ID = '$userID' AND ol_order_status.ORDER_STATUS_ID = ?";
        }
    
        $stmt = $this->conn->prepare($OrderList);
    
        if (is_array($orderStatus)) {
            // If $orderStatus is an array, bind the parameters individually
            $stmt->execute();
            $stmt->bind_result( $onlineOrderID,$olCartId, $formattedDate, $statusName, $orderStatusId);
            $orderData = array();
    
            while ($stmt->fetch()) {
                $orderData[] = [
                    'ONLINE_ORDER_ID' => $onlineOrderID,
                    'OL_CART_ID' => $olCartId,
                    'FORMATTED_DATE' => $formattedDate,
                    'STATUS_NAME' => $statusName,
                    'ORDER_STATUS_ID' => $orderStatusId,
                ];
            }
        } else {
            // If $orderStatus is not an array, bind the single parameter
            $stmt->bind_param("s", $orderStatus);
            $stmt->execute();
            $result = $stmt->get_result();
            $orderData = $result->fetch_all(MYSQLI_ASSOC);
        }
    
        $stmt->close();
        return $orderData;
    }



    public function searchOrderlist($searchName) {
        $userID = $_SESSION['USER_ID'];
    
        $OrderList = "SELECT online_order.OL_CART_ID, 
        DATE_FORMAT(online_order.DATE_CREATED, '%M %e, %Y') AS FORMATTED_DATE, 
        ol_order_status.STATUS_NAME, ol_order_status.ORDER_STATUS_ID
        FROM online_order
        INNER JOIN ol_order_status ON ol_order_status.ORDER_STATUS_ID = online_order.ORDER_STATUS_ID
        WHERE COURIER_ID = '$userID' AND online_order.OL_CART_ID LIKE ?";
    
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
        $userID = $_SESSION['USER_ID'];

        $OrderList = "SELECT online_order.OL_CART_ID, 
        DATE_FORMAT(online_order.DATE_CREATED, '%M %e, %Y') AS FORMATTED_DATE, 
        ol_order_status.STATUS_NAME, ol_order_status.ORDER_STATUS_ID
        FROM online_order
        INNER JOIN ol_order_status ON ol_order_status.ORDER_STATUS_ID = online_order.ORDER_STATUS_ID
        WHERE COURIER_ID = '$userID' AND (ol_order_status.ORDER_STATUS_ID = '2' OR ol_order_status.ORDER_STATUS_ID = '3')";
    
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
    
}

?>


