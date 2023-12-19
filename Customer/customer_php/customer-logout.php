<?php
session_start();

if (isset($_SESSION['Customer_ID'])) {
    session_unset();
    session_destroy();
}

header("Location: ../Customer/customer_php/customer_login.php");
exit();
?>
