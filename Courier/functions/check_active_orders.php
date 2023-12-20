<?php
include("../../Admin/functions/dbConfig.php");

$dbConnection = new Connection();
$conn = $dbConnection->conn;

$userID = $_SESSION['USER_ID'];

$query = "SELECT COUNT(*) AS activeOrders
FROM online_order
INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
WHERE online_order.ORDER_STATUS_ID = 1 AND online_order.OL_ORDER_TYPE_ID = 1 AND ol_cart.CUSTOMER_ID != '$userID';
";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $activeOrders = $row['activeOrders'];
    echo $activeOrders;
} else {
    echo "0";
}
?>
