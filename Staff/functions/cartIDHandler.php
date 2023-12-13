<?php
    if (isset($_POST["onsiteOrderID"], $_POST["filterValue"])) {
        $onsiteOrderID = $_POST["onsiteOrderID"];
        $filterValue = $_POST['filterValue'];
        echo "Received student_ID: " . $onsiteOrderID;
        session_start();
        $_SESSION["onsiteOrderID"] = $onsiteOrderID;
        $_SESSION["filterValue"] = $filterValue;
        header("location: ../staff_html/viewOrderHistory.php");
    } else {
        echo "No data received";
    }
 ?>

