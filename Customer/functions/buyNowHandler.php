<?php
require_once '../../Admin/functions/dbConfig.php';
require_once '../functions/view-item.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the data sent through AJAX
    $prodPic = $_POST["prodPic"];
    $prodName = $_POST["prodName"];
    $prodPrice = $_POST["prodPrice"];
    $quantity = $_POST["quantity"];
    $prodID = $_POST["prodID"];

    $response = array(
        "status" => "success",
        "message" => "Data received successfully.",
    );

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Handle cases where it's not a POST request
    echo "Invalid request method.";
}
?>
