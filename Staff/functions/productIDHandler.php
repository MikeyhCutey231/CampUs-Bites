<?php
    if (isset($_POST["productID"])) {
        $productID = $_POST["productID"];
        echo "Received student_ID: " . $productID;
        session_start();
        $_SESSION["productID"] = $productID;
        header("location: ../staff_html/staff-viewItem.php");
    } else {
        echo "No data received";
    }
 ?>

