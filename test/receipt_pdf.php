<?php
require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Initialize Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('isFontSubsettingEnabled', true);

$dompdf = new Dompdf($options);

// Create a database connection (Replace with your actual database connection details)
$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 'yawa';

$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch data for a specific order (you may pass an order ID or other unique identifier)
$order_id = 4; // Change this to the actual order ID

$sql = "SELECT product_name, prod_quantity, prod_price FROM hi WHERE prod_id = $order_id";
$result = $connection->query($sql);

if ($result) {
    $order = $result->fetch_assoc();
    if ($order) {
        // Define your HTML content for the receipt
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
            <p> <strong>Cashier:</strong> MARVIN</p>
            <p> <strong>Order no:</strong> 8</p>
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
        
        $itemName = $order['product_name'];
        $quantity = $order['prod_quantity'];
        $price = $order['prod_price'];
        $subtotal = $quantity * $price;
        $amount = $subtotal;
        
        $html .= "
        <tr>
            <td> $quantity </td>
            <td> $itemName </td>
            <td> $price </td>
            <td> $amount </td>
        </tr>";
        
        $total = $subtotal;
        
        $html .= '
            </table>
        
            <p style="text-align:right;">
            ---------------------------------------------------------<br/>
            <strong> Total: </strong> PHP ' . number_format($total, 2) . '
            <br/> 
            ---------------------------------------------------------</p>    
        
            <h1>THANK YOU!</h1>
        
        </body>
        </html>';
        
        // Load the HTML into Dompdf
        $dompdf->loadHtml($html);
        
        // Set paper size and orientation (e.g., A4 portrait)
        $dompdf->setPaper(array(0, 0, 297.64, 419.53), 'portrait');
        
        // Render the PDF (default to file)
        $dompdf->render();
        
        // Stream the PDF to the browser
        $dompdf->stream('receipt.pdf');
    } else {
        echo "No data found for order ID: $order_id";
    }
} else {
    echo "Error in SQL query: " . $connection->error;
}

// Close the database connection
$connection->close();
?>
