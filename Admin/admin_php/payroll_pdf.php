<?php
require "../../vendor/autoload.php";
require_once("../../Admin/functions/dbConfig.php");


use Dompdf\Dompdf;
use Dompdf\Options;

$EmpName = $_POST["fullName"];
$Position = $_POST["position"];
$Rate = $_POST["RPH"];
$NDays = $_POST["NDW"];
$OvertimeRate = $_POST["overtimeRate"];
$HoursOver = $_POST["totalOT"];
$sss = $_POST["SSS"];
$PI = $_POST["PagIbig"];
$PH = $_POST["Philhealth"];
$prepName = $_POST["adminUsername"];
$grossPay = $_POST["grossSalary"];
$netPay = $_POST["netSalary"];
$totalDeductions = $_POST["totalDeduc"];
$STotal1 = $_POST["subtotal"];
$STotal2 = $_POST["subtotalOT"];
$period = $_POST["payrollPeriod"];

// Initialize Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('isFontSubsettingEnabled', true);
$options->set('isCssFloatEnabled', true);

$dompdf = new Dompdf($options);
$database = new Connection();
$connection = $database->conn;

// Define your HTML content for the payslip
$html = file_get_contents("MySlip.html");

// Replace placeholders with calculated values
$html = str_ireplace(["{{ EName }}","{{ PayrollPeriod }}", "{{ EPosition }}", "{{ PI }}", "{{ sss }}", "{{ Name1 }}", "{{ GrossPay }}", "{{ NetPay }}",                 "{{ TotalDeductions }}", "{{ Rate }}", "{{ NDW }}", "{{ ORH }}", "{{ THO }}", "{{ PHILHEALTH }}", "{{ STotal1 }}", "{{ STotal2 }}"],
                     [$EmpName, $period,$Position, $PI, $sss, $prepName, $grossPay, $netPay, $totalDeductions, $Rate, $NDays, $OvertimeRate,
                    $HoursOver, $PH, $STotal1, $STotal2], $html);

// Load the HTML into Dompdf
$dompdf->loadHtml($html);

// Set paper size and orientation (e.g., A4 portrait)
$dompdf->setPaper('A4', 'landscape');

// Render the PDF (default to file)
$dompdf->render();

// Stream the PDF to the browser
$dompdf->stream('payslip.pdf');
?>