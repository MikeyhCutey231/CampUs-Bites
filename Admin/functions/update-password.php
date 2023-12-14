<?php
require("../../Admin/functions/dbConfig.php");

$dbConnection = new Connection();
$conn = $dbConnection->conn;

if(isset($_POST["currentPassword"], $_POST["newPassword"], $_POST["confirmPassword"] )) {
    $userID = $_POST["userID"];
    $currentPass = $_POST["currentPassword"];
    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];

    $checkCurrentpass = "SELECT U_PASSWORD FROM users WHERE USER_ID = '$userID'";
    $runCurrenPass = mysqli_query($conn, $checkCurrentpass);

    $fetchPass = mysqli_fetch_assoc($runCurrenPass);

    if(empty($currentPass)) {
        echo "Please enter the current password.";
    } else {
        // Check if the entered password matches the hashed password
        if(password_verify($currentPass, $fetchPass["U_PASSWORD"])) {

            if (strlen($newPassword) < 8) {
                echo "Password should be at least 8 characters long";

            } else if(!preg_match("/[A-Z]/", $newPassword) || !preg_match("/[a-z]/", $newPassword)){
                echo "Password should include both capital and lowercase letters";

            } else if(!preg_match("/[!@#\$%^&*()_+{}[\]:;<>,.?~]/", $newPassword)){
                echo "Password should include at least one special character";
            } else {
                if($newPassword == $confirmPassword) {
                    // Hash the new password before updating
                    $hashedNewPassword = password_hash($confirmPassword, PASSWORD_DEFAULT);

                    $updatePassword = "UPDATE users SET U_PASSWORD = '$hashedNewPassword' WHERE USER_ID = '$userID'";
                    $updateRun = mysqli_query($conn, $updatePassword);

                    echo "Data changed";

                } else {
                    echo "New and Current password does not match";
                }
            }
        } else {
            echo "Invalid current password";
        }
    }
}
?>
