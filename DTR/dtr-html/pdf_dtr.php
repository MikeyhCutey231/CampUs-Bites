<?php
require "../../vendor/autoload.php";
require_once("../../Admin/functions/dbConfig.php");

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

// Check if userID is provided in the form submission
if (isset($_POST['userID'])) {
    $userID = $_POST['userID'];

    $database = new Connection();
    $connection = $database->conn;

    // Fetch employee data based on userID
    $sql = "SELECT U_FIRST_NAME, U_LAST_NAME, U_MIDDLE_NAME FROM users WHERE USER_ID = $userID";
    $result = $connection->query($sql);

    if ($result) {
        $employeeData = $result->fetch_assoc();
        $fullName = $employeeData['U_FIRST_NAME'] . ' ' . $employeeData['U_MIDDLE_NAME'] . ' ' . $employeeData['U_LAST_NAME'];
        // Fetch attendance data
        $sqlAttendance = "SELECT emp_dtr_date, emp_dtr_time_in, emp_dtr_time_out, emp_dtr_overtime_hours FROM emp_dtr WHERE employee_id = $userID"; // Adjust the query according to your database schema
        $resultAttendance = $connection->query($sqlAttendance);

        // Check for SQL errors
        if (!$resultAttendance) {
            die("Error in SQL query: " . $connection->error);
        }

        // Close the database connection
        $connection->close();

        if ($resultAttendance->num_rows > 0) {
            $attendanceData = $resultAttendance->fetch_all(MYSQLI_ASSOC);

            $html = file_get_contents("tab.html");

            // Replace placeholders with actual data
            $html = str_replace("{employee_name}", $fullName, $html);
            $html = str_replace("{day}", date("Y-m-d"), $html);

            // Create an empty variable to hold replacements
            $replacements = '';

            foreach ($attendanceData as $row) {
                $replacements .= '<tr>';
                $replacements .= '<td rowspan="1">' . $row['emp_dtr_date'] . '</td>';
                $replacements .= '<td colspan="2">' . $row['emp_dtr_time_in'] . '</td>';
                $replacements .= '<td colspan="2">' . $row['emp_dtr_time_out'] . '</td>';
                $replacements .= '<td colspan="2">' . $row['emp_dtr_overtime_hours'] . '</td>';
                $replacements .= '</tr>';
            }

            $html = str_replace("{attendance_data}", $replacements, $html);

            $dompdf->loadHtml($html);

            // Set paper size and orientation (e.g., A4 portrait)
            $dompdf->setPaper('A4', 'portrait');

            // Render the PDF (default to file)
            $dompdf->render();

            // Stream the PDF to the browser
            $dompdf->stream('dtr.pdf');
        } else {
            echo "No attendance data found.";
        }
    } else {
        echo "Error fetching employee data: " . $connection->error;
    }
} else {
    echo "UserID not provided in the form.";
}
?>
