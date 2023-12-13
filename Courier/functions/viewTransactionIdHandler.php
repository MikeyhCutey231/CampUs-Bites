<?php
    if (isset($_POST["olCartId"])) {
        $olCartId = $_POST["olCartId"];
        echo "Received student_ID: " . $olCartId;
        session_start();
        $_SESSION["olCartId"] = $olCartId;
        header("location: ../courier_html/courier-viewTransaction.php");
    } else {
        echo "No data received";
    }
 ?>