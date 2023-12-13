<?php 
include("../../Admin/functions/dbConfig.php");
 
 class reportsData{
    public $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function reportDataSql(){
        $orderData = array();
        $userID = $_SESSION['USER_ID'];
    
        $viewOrder = "SELECT
                orderDate,
                orderCartID,
                SHIPPING_FEE,
                (SHIPPING_FEE + partialTotal) AS Total
            FROM (
                SELECT
                    DATE(online_order.DATE_CREATED) AS orderDate,
                    online_order.OL_CART_ID AS orderCartID,
                    SUM(online_cart_item.OL_SUBTOTAL) AS partialTotal,
                    online_order.SHIPPING_FEE
                FROM
                    online_order
                INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
                WHERE
                    online_order.COURIER_ID = '$userID'
                    AND online_order.OL_ORDER_TYPE_ID = 1
                    AND online_order.ORDER_STATUS_ID = 4
                GROUP BY
                    orderDate, orderCartID, online_order.SHIPPING_FEE
            ) AS subquery;
            ";
    
        $stmt = $this->conn->prepare($viewOrder);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($dateCreate, $cartID, $shippingFee, $totalAmount);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $orderData[] = [
                    'DATE_CREATED' => $dateCreate,
                    'OL_CART_ID' => $cartID,
                    'SHIPPING_FEE' => $shippingFee,
                    'TotalAmount' => $totalAmount,
                ];
            }
    
            $stmt->close();
        }
    
        return $orderData;
    }

    public function renderreportDataSql($orderData) {
        if (empty($orderData)) {
            echo '<tr><td colspan="6" style="border:1px solid white;">No Active Orders Available</td></tr>';
        } else {
            foreach ($orderData as $row) {
                echo '<tr>';
                echo '<td>' . $row['DATE_CREATED'] . '</td>';
                echo '<td># ' . $row['OL_CART_ID'] . '</td>';
                echo '<td>₱ ' . $row['TotalAmount'] . '</td>';
                echo '<td>₱ ' . $row['SHIPPING_FEE'] . '</td>';
                echo '</tr>';
            }
        }
    }

    public function overAllIncome(){
        $orderData = array();
        $userID = $_SESSION['USER_ID'];
    
        $viewOrder = "SELECT SUM(online_order.SHIPPING_FEE) FROM online_order
        WHERE online_order.COURIER_ID = '$userID' AND online_order.OL_ORDER_TYPE_ID = 1 AND online_order.ORDER_STATUS_ID = 4;";
    
        $stmt = $this->conn->prepare($viewOrder);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($income);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $orderData[] = [
                    'TotalAmount' => $income,
                ];
            }
    
            $stmt->close();
        }
    
        return $orderData;
    }


    public function renderoverAllIncome($orderData) {
        if (empty($orderData)) {
            echo '<tr><td colspan="6" style="border:1px solid white;">No Active Orders Available</td></tr>';
        } else {
            foreach ($orderData as $row) {
                echo '<h2 id="overAllIncome">₱ '. $row['TotalAmount'] .'</h2>';
            }
        }
    }


    public function todayIncome(){
        $orderData = array();
        $userID = $_SESSION['USER_ID'];
    
        $viewOrder = "SELECT SUM(online_order.SHIPPING_FEE) AS TotalAmount FROM online_order
        WHERE online_order.COURIER_ID = '$userID' AND online_order.OL_ORDER_TYPE_ID = 1 AND online_order.ORDER_STATUS_ID = 4 AND DATE(online_order.DATE_CREATED) = CURDATE()";
    
        $stmt = $this->conn->prepare($viewOrder);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($income);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $orderData[] = [
                    'TotalAmount' => $income,
                ];
            }
    
            $stmt->close();
        }
    
        return $orderData;
    }


    public function rendertodayIncome($orderData) {
        if (empty($orderData)) {
            echo '<tr><td colspan="6" style="border:1px solid white;">No Active Orders Available</td></tr>';
        } else {
            foreach ($orderData as $row) {
                if($row['TotalAmount'] == NULL){
                    echo '<h2 id="todayIncome">₱ 0.00</h2>';
                }else{
                    echo '<h2 id="todayIncome">₱ '. $row['TotalAmount'] .'</h2>';
                }
            }
        }
    }

    public function filterDate($startDate, $endDate){
        $userID = $_SESSION['USER_ID'];

        $query = "SELECT
                orderDate,
                orderCartID,
                SHIPPING_FEE,
                (SHIPPING_FEE + partialTotal) AS Total
            FROM (
                SELECT
                    DATE(online_order.DATE_CREATED) AS orderDate,
                    online_order.OL_CART_ID AS orderCartID,
                    SUM(online_cart_item.OL_SUBTOTAL) AS partialTotal,
                    online_order.SHIPPING_FEE
                FROM
                    online_order
                INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
                WHERE
                    online_order.COURIER_ID = '$userID'
                    AND online_order.OL_ORDER_TYPE_ID = 1 
                    AND online_order.ORDER_STATUS_ID = 4 
                    AND DATE(online_order.DATE_CREATED) BETWEEN ? AND ?
                GROUP BY
                    orderDate, orderCartID, online_order.SHIPPING_FEE
                ORDER BY
                    DATE(online_order.DATE_CREATED) DESC
            ) AS subquery;";


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





    public function todayDate($currentValue){
        $userID = $_SESSION['USER_ID'];

        $query = "SELECT
                orderDate,
                orderCartID,
                SHIPPING_FEE,
                (SHIPPING_FEE + partialTotal) AS Total
            FROM (
                SELECT
                    DATE(online_order.DATE_CREATED) AS orderDate,
                    online_order.OL_CART_ID AS orderCartID,
                    SUM(online_cart_item.OL_SUBTOTAL) AS partialTotal,
                    online_order.SHIPPING_FEE
                FROM
                    online_order
                INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
                WHERE
                    online_order.COURIER_ID = '$userID'
                    AND online_order.OL_ORDER_TYPE_ID = 1 
                    AND online_order.ORDER_STATUS_ID = 4 
                    AND DATE(online_order.DATE_CREATED) = ?
                GROUP BY
                    orderDate, orderCartID, online_order.SHIPPING_FEE
                ORDER BY
                    DATE(online_order.DATE_CREATED) DESC
            ) AS subquery;";


        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $currentValue);

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


    public function monthDate($thisMonth){
        $userID = $_SESSION['USER_ID'];

        $query = "SELECT
                orderDate,
                orderCartID,
                SHIPPING_FEE,
                (SHIPPING_FEE + partialTotal) AS Total
            FROM (
                SELECT
                    DATE(online_order.DATE_CREATED) AS orderDate,
                    online_order.OL_CART_ID AS orderCartID,
                    SUM(online_cart_item.OL_SUBTOTAL) AS partialTotal,
                    online_order.SHIPPING_FEE
                FROM
                    online_order
                INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
                WHERE
                    online_order.COURIER_ID = '$userID'
                    AND online_order.OL_ORDER_TYPE_ID = 1 
                    AND online_order.ORDER_STATUS_ID = 4 
                    AND YEAR(online_order.DATE_CREATED) = YEAR(CURDATE())
                    AND MONTH(online_order.DATE_CREATED) = MONTH(CURDATE())
                GROUP BY
                    orderDate, orderCartID, online_order.SHIPPING_FEE
                ORDER BY
                    DATE(online_order.DATE_CREATED) DESC
            ) AS subquery;";


        $stmt = $this->conn->prepare($query);

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


    public function yearDate($thisYear){
        $userID = $_SESSION['USER_ID'];

        $query = "SELECT
                orderDate,
                orderCartID,
                SHIPPING_FEE,
                (SHIPPING_FEE + partialTotal) AS Total
            FROM (
                SELECT
                    DATE(online_order.DATE_CREATED) AS orderDate,
                    online_order.OL_CART_ID AS orderCartID,
                    SUM(online_cart_item.OL_SUBTOTAL) AS partialTotal,
                    online_order.SHIPPING_FEE
                FROM
                    online_order
                INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
                WHERE
                    online_order.COURIER_ID = '$userID'
                    AND online_order.OL_ORDER_TYPE_ID = 1 
                    AND online_order.ORDER_STATUS_ID = 4 
                    AND YEAR(online_order.DATE_CREATED) = YEAR(CURDATE())
                GROUP BY
                    orderDate, orderCartID, online_order.SHIPPING_FEE
                ORDER BY
                    DATE(online_order.DATE_CREATED) DESC
            ) AS subquery;";


        $stmt = $this->conn->prepare($query);

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



    public function searchMenuList($searchKeyword) {
        $userID = $_SESSION['USER_ID'];
    
        $query = "SELECT
            orderDate,
            orderCartID,
            SHIPPING_FEE,
            (SHIPPING_FEE + partialTotal) AS Total
        FROM (
            SELECT
                DATE(online_order.DATE_CREATED) AS orderDate,
                online_order.OL_CART_ID AS orderCartID,
                SUM(online_cart_item.OL_SUBTOTAL) AS partialTotal,
                online_order.SHIPPING_FEE
            FROM
                online_order
            INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
            WHERE
                online_order.COURIER_ID = ?
                AND online_order.OL_ORDER_TYPE_ID = 1 
                AND online_order.ORDER_STATUS_ID = 4 
                AND DATE(online_order.DATE_CREATED) LIKE ?
                OR online_order.OL_CART_ID LIKE ?
            GROUP BY
                orderDate, orderCartID, online_order.SHIPPING_FEE
            ORDER BY
                DATE(online_order.DATE_CREATED) DESC
        ) AS subquery;";
    
        $stmt = $this->conn->prepare($query);
    
        $searchKeywordParam = "%" . $searchKeyword . "%"; // Add % to create a LIKE query
    
        // Assuming $userID is a string
        $stmt->bind_param("sss", $userID, $searchKeywordParam, $searchKeywordParam);
    
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
    

}



    
?>