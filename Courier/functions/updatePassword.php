<?php 
    include("../../Admin/functions/dbConfig.php");

    $dbConnection = new Connection();
    $conn = $dbConnection->conn;

    $userID = $_SESSION['USER_ID'];

    if(isset($_POST["currentPassword"], $_POST["newPassword"], $_POST["confirmPassword"] )) {
        $currentPass = $_POST["currentPassword"];
        $newPassword = $_POST["newPassword"];
        $confirmPassword = $_POST["confirmPassword"];


   

        // Kuhaon ang current password sa database og tama ba
        $checkCurrentpass = "SELECT U_PASSWORD FROM users WHERE U_PASSWORD  = '$currentPass'";
        $runCurrenPass = mysqli_query($conn, $checkCurrentpass);

        $fetchPass = mysqli_fetch_assoc($runCurrenPass);
       if(empty($currentPass)) {
       }else{
            if($currentPass == $fetchPass["U_PASSWORD"]){

                if (strlen($newPassword) < 8) {
                    echo $currentPass = "Password should be at least 8 characters long";

                }else if(!preg_match("/[A-Z]/", $newPassword) || !preg_match("/[a-z]/", $newPassword)){
                    echo $currentPass = "Password should include both capital and lowercase letters";

                }else if(!preg_match("/[!@#\$%^&*()_+{}[\]:;<>,.?~]/", $newPassword)){
                    echo $currentPass  = "Password should include at least one special character";
                }else{
                    if($newPassword == $confirmPassword){
                        $updatePassword = "UPDATE users SET U_PASSWORD = '$confirmPassword' WHERE USER_ID = '$userID'";
                        $updateRun = mysqli_query($conn, $updatePassword);
    
                        echo $currentPass =  "Data changed";
                        
                    }else{
                        echo $currentPass = "New and Current password does not match";
                    }
                }
            }
       }

    }

?>