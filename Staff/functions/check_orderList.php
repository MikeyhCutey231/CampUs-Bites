<?php
include("../../Admin/functions/dbConfig.php");

$dbConnection = new Connection();
$conn = $dbConnection->conn;

$query = "SELECT COUNT(*) AS orders FROM online_order WHERE OL_ORDER_TYPE_ID = 2 AND ORDER_STATUS_ID  = 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $orders = $row['orders'];
    echo $orders;
} else {
    echo "0";
}
?>
