<?php

include '../../Admin/functions/Admin_Manager_logs.php';
$logger = new Admin_Manager_logs();

if (isset($_SESSION['USER_ID'])) {
    $logger->logoutLogs($_SESSION['USER_ID']);
    session_unset();
    session_destroy();
}
header("Location: staff-login.php");
exit();
?>
