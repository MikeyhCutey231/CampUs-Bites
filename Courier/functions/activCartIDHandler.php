<?php
    if (isset($_POST["cartID"])) {
        $cartID = $_POST["cartID"];
        echo "Received student_ID: " . $cartID;
        session_start();
        $_SESSION["cartID"] = $cartID;
        header("location: ../courier_html/viewOrderlist.php");
    } else {
        echo "No data received";
    }
 ?>