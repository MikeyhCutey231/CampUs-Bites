<?php
require_once '../../Admin/functions/dbConfig.php';
require_once '../functions/view-item.php';

$database = new Connection();
$conn = $database->conn;

$productClass = new Product($database->conn);

if (isset($_GET["productId"]) && !empty($_GET["productId"])) {
    $productId = $_GET["productId"];
    error_log("Product ID: " . $productId);
    
    // Get product details
    $prodDetails = $productClass->getProductDetails($productId);
    
    // Get product ratings
    $prodRatings = $productClass->getProductRatings($productId);
    
    // Combine both details and ratings into a single array
    $response = [
        "details" => $prodDetails,
        "ratings" =>  json_encode($prodRatings)
    ];

    // Return the combined data as JSON
    echo json_encode($response);
} else {
    echo json_encode(["error" => "No product ID provided"]);
}

?>