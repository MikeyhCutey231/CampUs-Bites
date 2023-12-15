<?php 
     require '../functions/loginStaff.php';

     $dbConnection = new Connection();
     $conn = $dbConnection->conn;
      
     $changePass = new customerLogin();
     
     if(isset($_POST['create'])){
         $password = $_POST['password'];
         $username = $_POST['username'];
         $fname = $_SESSION['user_info']['fname'];
         $mname = $_SESSION['user_info']['mname'];
         $lname = $_SESSION['user_info']['lname'];
         $suffix = $_SESSION['user_info']['suffix'];
         $phoneNum = $_SESSION['user_info']['phoneNumber'];
         $gender = $_SESSION['user_info']['gender'];
         $email = $_SESSION['user_info']['usepEmail'];
         $landmark = $_SESSION['user_info']['usepLandmark'];

         $result = $changePass->passwordErrorHandler($username, $password, $fname, $mname, $lname, $suffix, $phoneNum, $gender, $email, $landmark);
 
         $error_message = null;  
         if($result == customerLogin::REGISTRATION_EMPTY_FIELDS){
             $error_message = "Input fields are empty";
         }else if($result == customerLogin::REGISTRATION_NOTSAME){
             $error_message = "Password you have input is not the same";
         }else if($result == customerLogin::REGISTRATION_PASSWORD_LENGTH){
             $error_message = "Password should be longer than 8 character";
         }else if($result == customerLogin::REGISTRATION_PASSWORD_CASE){
             $error_message = "Include atleast 1 uppercase letter";
         }else if($result == customerLogin::REGISTRATION_PASSWORD_SPECIAL_CHAR){
             $error_message = "Include atleast 1 special character";
         }else if($result == customerLogin::REGISTRATION_SUCCESS){         
             header("location: customer_login.php");
         }
     }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <title>CBites Customer Register</title>
    <link rel="stylesheet" href="../../Customer/customer_css/customerCreateAcc.css">


    <style>
        .error{
            display: none;
        }
    </style>

    <?php
        if($error_message != null){
            ?><style>.error{display: block; }</style> <?php
        }else{
            ?><style>.error{display: hidden;}</style> <?php
        }
    
    ?>
</head>
<body>
    <div class="wrapper">
        <div class="wavey1">
            <img src="../../Icons/register_wavey1.svg" alt="" width="420px">
        </div>
        <div class="wavey2">
            <img src="../../Icons/register_wavey2.svg" alt="" width="250px">
        </div>

        <form action="" method="post">
        <div class="header-content">
            <p class="reg">Create Account</p>
            <p class="univ">University of Southeastern Philippines Tagum Unit</p>
            <p class="ecanteen">E-Canteen Service System</p>
        </div>
        <div class="register-box">
                <div class="first-row">
                    <div class="fname">
                        <input type="text" id="username" name="username" placeholder="Type your username">
                    </div>
                    <div class="mname">
                        <input type="text" id="mname" name="password" placeholder="Type your password">
                    </div>

                    <div class="error error-message animate__animated animate__pulse" id="verificationResult">
                        <?php echo $error_message ?>
                    </div>
                </div>
               
                    <div class="register-button">
                        <button type="submit" name="create" style="cursor:pointer;">Create Account</button>
                    </div>
                </form>
        </div>
    </div>


</body>
</html>