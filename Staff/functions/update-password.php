<?php 
    include("../../Admin/functions/dbConfig.php");

    $dbConnection = new Connection();
    $conn = $dbConnection->conn;

    $staffID = $_SESSION['Staff_ID'];

    if(isset($_POST["currentPassword"], $_POST["newPassword"], $_POST["confirmPassword"] )) {
        $currentPass = $_POST["currentPassword"];
        $newPassword = $_POST["newPassword"];
        $confirmPassword = $_POST["confirmPassword"];


   

        // Kuhaon ang current password sa database og tama ba
        $checkCurrentpass = "SELECT EMP_PASSWORD FROM emp_personal_info WHERE EMP_PASSWORD  = '$currentPass'";
        $runCurrenPass = mysqli_query($conn, $checkCurrentpass);

        $fetchPass = mysqli_fetch_assoc($runCurrenPass);
       if(empty($currentPass)) {
       }else{
            if($currentPass == $fetchPass["EMP_PASSWORD"]){

                if (strlen($newPassword) < 8) {
                    echo $currentPass = "Password should be at least 8 characters long";

                }else if(!preg_match("/[A-Z]/", $newPassword) || !preg_match("/[a-z]/", $newPassword)){
                    echo $currentPass = "Password should include both capital and lowercase letters";

                }else if(!preg_match("/[!@#\$%^&*()_+{}[\]:;<>,.?~]/", $newPassword)){
                    echo $currentPass  = "Password should include at least one special character";
                }else{
                    if($newPassword == $confirmPassword){
                        $updatePassword = "UPDATE emp_personal_info SET EMP_PASSWORD = '$confirmPassword' WHERE EMPLOYEE_ID = '$staffID'";
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