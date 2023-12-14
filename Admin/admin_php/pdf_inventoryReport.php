<?php
require "../../vendor/autoload.php";
require_once("../../Admin/functions/dbConfig.php");

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

$database = new Connection();
$connection = $database->conn;

// Build SQL query
$sql = "SELECT * FROM product";

$result = $connection->query($sql);

$html = '<h1 style="text-align: center; font-family: ;">University of Southeastern Philippines</h1>';
$html .= '<p style="text-align: center; display: ;">Tagum-Unit Canteen</p>';
$html .= '<h3 style="text-align: center;">Inventory Reports</h3>';
$html .= '<p style="text-align: right;">Date: ' . date("Y-m-d") . '</p>';

// Table headers
$html .= '<table style="width: 100%; border-collapse: collapse; text-align: center;">';
$html .= '<tr style="background-color: #333; color: #fff;">';
$html .= '<th style="padding: 10px; font-size: 15px;">Date Added</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Product Code</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Product Name</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Capital Price</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Selling Price</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Stock</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">No. of Product Sold</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Total Sales</th>';
$html .= '</tr>';

$totalSubtotal = 0;

// Iterate through the result set
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['PROD_DATE_ADD'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['PROD_ID'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['PROD_NAME'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['PROD_CAPITAL_PRICE'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['PROD_SELLING_PRICE'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['PROD_TOTAL_QUANTITY'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['PROD_SOLD'] . '</td>';
        $subtotal = $row['PROD_SOLD'] * $row['PROD_SELLING_PRICE'];
        $html .= '<td style="padding: 10px; font-size: 13px;">' . number_format($subtotal, 2) . '</td>';
        $totalSubtotal += (float)str_replace(['$', ','], '', $subtotal);
        $html .= '</tr>';


    }
    $html .= '</table>';
} else {
    $html .= '<tr><td colspan="7">No data found.</td></tr></table>';
}

$html .= '<p style="text-align: right; margin-right: 20px; font-size: 18px;"><strong>Total: ' . number_format($totalSubtotal, 2) . '</strong></p>';

$connection->close();

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream("Inventory_Report.pdf");
?>
