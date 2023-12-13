<?php
    if (isset($_POST["positionEmpID"])) {
        $positionEmpID = $_POST["positionEmpID"];
        echo "Received student_ID: " . $positionEmpID;
        session_start();
        $_SESSION["positionEmpID"] = $positionEmpID;
        header("location: ../admin_php/viewpayroll.php");
    } else {
        echo "No data received";
    }
 ?>

