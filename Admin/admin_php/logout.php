<?php
session_start();

if (isset($_SESSION['Admin_ID'])) {
    session_destroy();
}
header("Location: admin-login.php");
exit();
?>
