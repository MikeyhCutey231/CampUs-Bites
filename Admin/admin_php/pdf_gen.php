<?php
require "../../vendor/autoload.php";
require_once("../../Admin/functions/dbConfig.php");

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

// Retrieve sale data from the AJAX request
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$categoryValue = $_POST['categoryValue'];
$productValue = $_POST['productValue'];

// Validate and sanitize input data (customize as needed)
$fromDate = htmlspecialchars($fromDate);
$toDate = htmlspecialchars($toDate);
$categoryValue = htmlspecialchars($categoryValue);
$productValue = htmlspecialchars($productValue);

// Database connection
$database = new Connection();
$connection = $database->conn;

// Build SQL query
$sql = "SELECT * FROM salesreport WHERE 1";

// Add conditions for date filtering if not empty
if (!empty($fromDate) && !empty($toDate)) {
    $sql .= " AND TRANSACTION_DATE BETWEEN '$fromDate' AND '$toDate'";
}

// Add conditions for category and product filtering if they are not empty
if (!empty($categoryValue)) {
    $sql .= " AND CATEGORY_ID = '$categoryValue'";
}

if (!empty($productValue)) {
    $sql .= " AND PROD_NAME = '$productValue'";
}

$result = $connection->query($sql);

$html = '<h1 style="text-align: center; font-family: ;">University of Southeastern Philippines</h1>';
$html .= '<p style="text-align: center; display: ;">Tagum-Unit Canteen</p>';
$html .= '<h3 style="text-align: center;">Reports</h3>';
$html .= '<p style="text-align: right;">Date: ' . date("Y-m-d") . '</p>';

// Table headers
$html .= '<table style="width: 100%; border-collapse: collapse; text-align: center;">';
$html .= '<tr style="background-color: #333; color: #fff;">';
$html .= '<th style="padding: 10px; font-size: 15px;">Date</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Product Name</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Cost Per Unit</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Selling Price</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Gross Margin</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Product Sold No.</th>';
$html .= '<th style="padding: 10px; font-size: 15px;">Subtotal</th>';
$html .= '</tr>';

$totalSubtotal = 0;

// Iterate through the result set
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['TRANSACTION_DATE'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['PROD_NAME'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['PROD_CAPITAL_PRICE'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['PROD_SELLING_PRICE'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['GROSS_MARGIN'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['POS_PROD_QUANTITY'] . '</td>';
        $html .= '<td style="padding: 10px; font-size: 13px;">' . $row['SUBTOTAL'] . '</td>';
        $totalSubtotal += (float)str_replace(['$', ','], '', $row['SUBTOTAL']);
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

$dompdf->stream("Sales_Report.pdf");
?>
