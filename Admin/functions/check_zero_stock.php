<?php
include("../../Admin/functions/dbConfig.php");

$dbConnection = new Connection();
$conn = $dbConnection->conn;

$query = "SELECT COUNT(*) AS low_stock_count FROM product WHERE PROD_REMAINING_QUANTITY <= 5";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $low_stock_count = $row['low_stock_count'];
    echo $low_stock_count;
} else {
    echo "0";
}
?>
