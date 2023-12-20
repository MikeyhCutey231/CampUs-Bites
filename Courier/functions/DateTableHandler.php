<?php

include("../../Admin/functions/dbConfig.php");

class DateHandler{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function filterDate($startDate, $endDate){
        $userID = $_SESSION['USER_ID'];

        $query = "SELECT
        partialTotal,
        SHIPPING_FEE,
        (SHIPPING_FEE + partialTotal) AS Total,
        fname,
        mname,
        lname,
        cartID,
        dateCreate,
        COURIER_ID
    FROM (
        SELECT
            SUM(online_cart_item.OL_SUBTOTAL) AS partialTotal,
            online_order.SHIPPING_FEE,
            users.U_FIRST_NAME AS fname,
            users.U_MIDDLE_NAME AS mname,
            users.U_LAST_NAME AS lname,
            online_order.OL_CART_ID AS cartID,
            DATE(online_order.DATE_CREATED) AS dateCreate,
            online_order.COURIER_ID
        FROM
            online_order
        INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
        INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
        INNER JOIN users ON ol_cart.CUSTOMER_ID = users.USER_ID
        INNER JOIN ol_order_status ON online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
        WHERE
            DATE(online_order.DATE_CREATED) BETWEEN ? AND ?
            AND online_order.COURIER_ID = '$userID'
            AND online_order.ORDER_STATUS_ID = 4
            AND online_order.OL_ORDER_TYPE_ID  = 1
        GROUP BY
            online_order.COURIER_ID,
            online_order.OL_CART_ID
        ORDER BY online_order.DATE_CREATED ASC
    ) AS subquery;
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);

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


    public function getData() {
        $userID = $_SESSION['USER_ID'];
        $employeeData = array();
    
        $query = "SELECT
                partialTotal,
                SHIPPING_FEE,
                (SHIPPING_FEE + partialTotal) AS Total,
                fname,
                mname,
                lname,
                cartID,
                OnlinecartID,
                dateCreate,
                COURIER_ID
            FROM (
                SELECT
                    SUM(online_cart_item.OL_SUBTOTAL) AS partialTotal,
                    online_order.SHIPPING_FEE,
                    users.U_FIRST_NAME AS fname,
                    users.U_MIDDLE_NAME AS mname,
                    users.U_LAST_NAME AS lname,
                    online_order.OL_CART_ID AS cartID,
                    online_order.ONLINE_ORDER_ID AS OnlinecartID,
                    DATE(online_order.DATE_CREATED) AS dateCreate,
                    online_order.COURIER_ID
                FROM
                    online_order
                INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
                INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
                INNER JOIN users ON ol_cart.CUSTOMER_ID = users.USER_ID
                INNER JOIN ol_order_status ON online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
                WHERE
                    online_order.COURIER_ID = '$userID' AND online_order.ORDER_STATUS_ID = 4 AND online_order.OL_ORDER_TYPE_ID  = 1
                GROUP BY
                    online_order.COURIER_ID,
                    online_order.OL_CART_ID
                ORDER BY online_order.DATE_CREATED ASC
            ) AS subquery
            ";
    
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($partialTotal, $shippingFee, $total, $fname, $mname, $lname, $cartID, $OnlinecartID, $dateCreate, $courierID);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $employeeData[] = [
                    'partialTotal' => $partialTotal,
                    'SHIPPING_FEE' => $shippingFee,
                    'Total' => $total,
                    'fname' => $fname,
                    'mname' => $mname,
                    'lname' => $lname,
                    'cartID' => $cartID,
                    'OnlinecartID' => $OnlinecartID,
                    'dateCreate' => $dateCreate,
                    'COURIER_ID' => $courierID,
                ];
            }
    
            $stmt->close();
        }
    
        return $employeeData;
    }
    
    public function renderEmployeeTable($employeeData) {
        if (empty($employeeData)) {
            echo '<tr><td colspan="5" style="font-size: 20px; padding: 50px; background-color: white;">You currently have no Transaction History.</td></tr>';
        } else {
            foreach ($employeeData as $row) {
                ?>
                <tr>
                    <td><?php echo $row['dateCreate'] ?></td>
                    <td><?php echo $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'] ?></td>
                    <td>Order Number# <?php echo $row['OnlinecartID'] ?></td>
                    <td>₱ <?php echo $row['Total'] ?></td>
                    <td>
                        <button class="vwEmpProfile viewOrder" value="<?php echo $row['cartID'] ?>">
                            <h6 class="text-center m-auto">View Order Record</h6>
                        </button>
                    </td>
                </tr>
                <?php
            }
        }
    }
    


    public function searchMenuList($searchKeyword) {
        $userID = $_SESSION['USER_ID'];

        $query = "SELECT
        partialTotal,
        SHIPPING_FEE,
        (SHIPPING_FEE + partialTotal) AS Total,
        fname,
        mname,
        lname,
        cartID,
        dateCreate,
        COURIER_ID
    FROM (
        SELECT
            SUM(online_cart_item.OL_SUBTOTAL) AS partialTotal,
            online_order.SHIPPING_FEE,
            users.U_FIRST_NAME AS fname,
            users.U_MIDDLE_NAME AS mname,
            users.U_LAST_NAME AS lname,
            online_order.OL_CART_ID AS cartID,
            DATE(online_order.DATE_CREATED) AS dateCreate,
            online_order.COURIER_ID
        FROM
            online_order
        INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
        INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
        INNER JOIN users ON ol_cart.CUSTOMER_ID = users.USER_ID
        INNER JOIN ol_order_status ON online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
        WHERE
            online_order.COURIER_ID = ? AND online_order.ORDER_STATUS_ID = 4 AND online_order.OL_ORDER_TYPE_ID  = 1 AND
            (online_order.OL_CART_ID LIKE ? OR DATE(online_order.DATE_CREATED) LIKE ?
            OR CONCAT(users.U_FIRST_NAME, ' ', users.U_MIDDLE_NAME, ' ', users.U_LAST_NAME) LIKE ?)
        GROUP BY
            online_order.COURIER_ID,
            online_order.OL_CART_ID
        ORDER BY online_order.DATE_CREATED ASC
    ) AS subquery;
    ";
    
        $stmt = $this->conn->prepare($query);
    
        $searchKeywordParam = "%" . $searchKeyword . "%"; // Add % to create a LIKE query
    
        // Assuming $userID is the courier ID
        $stmt->bind_param("ssss", $userID, $searchKeywordParam, $searchKeywordParam, $searchKeywordParam);
    
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $orderList = array();
    
            while ($row = $result->fetch_assoc()) {
                $orderList[] = $row;
            }
    
            return $orderList;
        } else {
            // Handle the error
            return null;
        }
    }
    



    public function getAllProducts() {
        $userID = $_SESSION['USER_ID'];
        
        $query = "SELECT ol_cart.CART_DATE_CREATED, users.U_FIRST_NAME, users.U_MIDDLE_NAME, users.U_LAST_NAME, ol_cart.OL_CART_ID, (ol_cart.CART_TOTAL + online_order.SHIPPING_FEE) AS Total
        FROM online_order 
        INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
        INNER JOIN ol_order_status ON online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
        INNER JOIN users ON ol_cart.CUSTOMER_ID = users.USER_ID
        WHERE COURIER_ID = '$userID' AND STATUS_NAME = 'Delivered' AND online_order.OL_ORDER_TYPE_ID  = 1
        ORDER BY ol_cart.CART_DATE_CREATED ASC";
    
        $result = $this->conn->query($query);
    
        // Check if there are any rows returned
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Output the HTML structure for each product
                echo '<tr>';
                echo '<td>' . $row['CART_DATE_CREATED'] . '</td>';
                echo '<td>' . $row['U_FIRST_NAME'] . ' ' . $row['U_MIDDLE_NAME'] . ' ' . $row['U_LAST_NAME'] . '</td>';
                echo '<td>Order Number# ' . $row['OL_CART_ID'] . '</td>';
                echo '<td>₱ ' . $row['Total'] . '</td>';
                echo '<td>';
                echo '<button class="vwEmpProfile viewOrder" value="' . $row['OL_CART_ID']. '">';
                echo '<h6 class="text-center m-auto">View Order Record</h6>';
                echo '</button>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<p style="padding-left: 40px; padding-top: 20px;">No Data Available</p>';
        }
    }
    
    
}

?>



