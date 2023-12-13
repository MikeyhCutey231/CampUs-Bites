<?php
require '../admin_functions/config.php';
$database = new Database();
$conn = $database->conn;

if (isset($_POST["currentPassword"], $_POST["newPassword"], $_POST["confirmPassword"])) {
    $currentPass = $_POST["currentPassword"];
    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];

    $hashedCurrentPassword = password_hash($currentPass, PASSWORD_DEFAULT);

    // Kuhaon ang current password sa database og tama ba
    $checkCurrentpass = "SELECT Password FROM admin_info";
    $runCurrenPass = mysqli_query($conn, $checkCurrentpass);

    $fetchPass = mysqli_fetch_assoc($runCurrenPass);
    if (empty($currentPass)) {
    } else {
        if (password_verify($currentPass, $fetchPass["Password"])) {

            if (strlen($newPassword) < 8) {
                echo $currentPass = "Password should be at least 8 characters long";

            } else if (!preg_match("/[A-Z]/", $newPassword) || !preg_match("/[a-z]/", $newPassword)) {
                echo $currentPass = "Password should include both capital and lowercase letters";

            } else if (!preg_match("/[!@#\$%^&*()_+{}[\]:;<>,.?~]/", $newPassword)) {
                echo $currentPass = "Password should include at least one special character";
            } else {
                if ($newPassword == $confirmPassword) {
                    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updatePassword = "UPDATE admin_info SET Password = '$hashedNewPassword'";
                    $updateRun = mysqli_query($conn, $updatePassword);

                    echo $currentPass = "Data changed";

                } else {
                    echo $currentPass = "New and Current password does not match";
                }
            }
        } else {
            echo $currentPass = "The current password you entered is incorrect";
        }
    }
}
?>
