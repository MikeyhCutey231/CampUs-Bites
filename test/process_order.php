<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 'yawa';

// Create a connection
$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Get order details from the form
$item_name = $_POST['item_name'];
$quantity = intval($_POST['quantity']);
$item_price = floatval($_POST['item_price']);

// Insert order into the database
$sql = "INSERT INTO hi (product_name, prod_quantity, prod_price, order_date) VALUES ('$item_name', $quantity, $item_price, NOW())";

if ($connection->query($sql) === TRUE) {
    $order_id = $connection->insert_id;
    echo "Order added successfully. <a href='receipt_pdf.php?order_id=$order_id'>Generate Receipt</a>";
} else {
    echo "Error: " . $sql . "<br>" . $connection->error;
}

// Close the database connection
$connection->close();
?>
