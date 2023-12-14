<?php 
    include("../../Admin/functions/dbConfig.php");

    $dbConnection = new Connection();
    $conn = $dbConnection->conn;

    $staffID = $_SESSION['USER_ID'];

    if(isset($_POST["currentPassword"], $_POST["newPassword"], $_POST["confirmPassword"] )) {
        $currentPass = $_POST["currentPassword"];
        $newPassword = $_POST["newPassword"];
        $confirmPassword = $_POST["confirmPassword"];

        // Get the current hashed password from the database
        $checkCurrentpass = "SELECT U_PASSWORD FROM users WHERE USER_ID = '$staffID'";
        $runCurrenPass = mysqli_query($conn, $checkCurrentpass);

        $fetchPass = mysqli_fetch_assoc($runCurrenPass);

        if(empty($currentPass)) {
            echo $currentPass = "Incorrect current password";
        } else {
            // Verify the current password
            if (password_verify(trim($currentPass), $fetchPass["U_PASSWORD"])) {
                // Validate the new password
                if (strlen($newPassword) < 8) {
                    echo $currentPass = "Password should be at least 8 characters long";
                } else if(!preg_match("/[A-Z]/", $newPassword) || !preg_match("/[a-z]/", $newPassword)){
                    echo $currentPass = "Password should include both capital and lowercase letters";
                } else if(!preg_match("/[!@#\$%^&*()_+{}[\]:;<>,.?~]/", $newPassword)){
                    echo $currentPass  = "Password should include at least one special character";
                } else {
                    // Hash the new password
                    $hashedPassword = password_hash($confirmPassword, PASSWORD_DEFAULT);

                    // Update the hashed password in the database
                    $updatePassword = "UPDATE users SET U_PASSWORD = '$hashedPassword' WHERE USER_ID = '$staffID'";
                    $updateRun = mysqli_query($conn, $updatePassword);
    
                    echo $currentPass =  "Data changed";
                }
            } else {
                echo $currentPass = "Incorrect current password";
            }
        }
    }
?>
