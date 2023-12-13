<?php
include("../../Admin/functions/dbConfig.php");
require '../../vendor/autoload.php';

$database = new Connection();
$conn = $database->conn;

if (isset($_GET['cartID']) && isset($_GET['items'])) {
    $cartID = $_GET['cartID'];
    $itemsJson = $_GET['items'];
    $items = json_decode(urldecode($itemsJson), true); // Decode the JSON string
} else {
    // Handle invalid cartID or missing parameter
    echo "Invalid cartID or missing parameter";
}

use Dompdf\Dompdf;
use Dompdf\Options;

// Initialize Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('isFontSubsettingEnabled', true);

$dompdf = new Dompdf($options);

$sql = "SELECT pos_cart_item.POS_CART_ID, product.PROD_NAME, pos_cart_item.POS_PROD_QUANTITY, pos_cart_item.POS_SUBTOTAL 
        FROM pos_cart_item 
        INNER JOIN product ON pos_cart_item.PROD_ID = product.PROD_ID 
        WHERE pos_cart_item.POS_CART_ID = '$cartID'";
$result = $conn->query($sql);

if ($result) {
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=open sans">
        <style>
            body {
                text-align: Center;
                font-family: "Open Sans";
            }
            .Top{
                display: block;
            }
            .Top1{
                float: left;
                width: 50%;
                font-size:13px; 
                text-align: left;
            }
            .Top2{
                float: left;
                width: 50%;
                font-size:13px; 
                text-align: right;
            }
        </style>
    </head>
    <body>
        <p style="font-size:15px;">
            <strong style="font-size:18px;">University of Southeastern Philippines</strong><br/>
            (Tagum-unit Campus)
        </p>

        <p style="font-size:18px;"> <strong> Campus Bites </strong></p>

        <div class="Top">
            <div class="Top1">
                <p> <strong>Date:</strong> ' . date('Y-m-d') . '</p>
            </div>
            <div class="Top2">
                <p> <strong>Cashier:'. $_SESSION['Staff_Name'] .'</strong></p>
                <p> <strong>Order no:' . $cartID . '</p>
            </div>
        </div>
        <p> 
        ---------------------------------------------------------<br/>    
        <strong style="font-size:15px;">
        RECEIPT
        </strong>
        <br/>
        ---------------------------------------------------------
        </p>
    
        <table style="width: 100%; border-collapse: collapse; text-align: center;">
            <tr style="font-size: 13px;">
                <th> QTY </th>
                <th> ITEM </th>
                <th> PRICE </th>
                <th> AMOUNT </th>
            </tr>';
    
    // Initialize the total variable
    $total = 0;

    // Iterate through the items array and generate HTML for each item
    foreach ($items as $item) {
        $itemName = $item['name'];
        $quantity = $item['quantity'];
        $originalPrice = $item['price'];
        $subtotal = $quantity * $originalPrice;
    
        // Add the current subtotal to the total
        $total += $subtotal;
    
        $html .= "
        <tr>
            <td> $quantity </td>
            <td> $itemName </td>
            <td> $originalPrice </td>
            <td> $subtotal </td>
        </tr>";
    }
    
    // Display the total
    $html .= '
        </table>
    
        <p style="text-align:right;">
        ---------------------------------------------------------<br/>
        <strong> Total: </strong> PHP ' . number_format($total, 2) . '
        <br/> 
        ---------------------------------------------------------</p>    
    
        <h1>THANK YOU!</h1>
    ';
    
    // Load the HTML into Dompdf
    $dompdf->loadHtml($html);
    
    // Set paper size and orientation (e.g., A4 portrait)
    $dompdf->setPaper(array(0, 0, 297.64, 419.53), 'portrait');
    
    // Render the PDF (default to file)
    $dompdf->render();
    
    // Stream the PDF to the browser
    $dompdf->stream('receipt.pdf');
} else {
    echo "Error in SQL query: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
