<?php
include("../../Admin/functions/dbConfig.php");

$dbConnection = new Connection();
$conn = $dbConnection->conn;

$query = "SELECT COUNT(*) AS activeOrders FROM online_order WHERE ORDER_STATUS_ID = 1 AND OL_ORDER_TYPE_ID = 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $activeOrders = $row['activeOrders'];
    echo $activeOrders;
} else {
    echo "0";
}
?>
