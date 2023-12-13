<?php 
include("../../Admin/functions/dbConfig.php");

$dbConnection = new Connection();
$conn = $dbConnection->conn;

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

    <title>CBites Customer Login</title>
    <link rel="stylesheet" href="../../Customer/customer_css/customer_security.css">
</head>
<body>
    <div class="wrapper">
        <div class="left">
            <div class="info-logo">
                 <?php 
                    // Echo the user information without assigning to the session
                    echo '<p class="campus">' . $_SESSION['user_info']['fname'] . ' ' . $_SESSION['user_info']['mname'] . ' ' . $_SESSION['user_info']['lname'] . ' ' . $_SESSION['user_info']['suffix'] . ' ' . $_SESSION['user_info']['phoneNumber'] . ' ' . $_SESSION['user_info']['gender'] . ' ' . $_SESSION['user_info']['usepEmail'] . ' ' . $_SESSION['user_info']['usepLandmark'] . '</p>';
                ?>
                <p class="usep">University of Southeastern Philippines Tagum Unit</p>
                <p class="ecanteen">E-Canteen Service System</p>
            </div>
            <div class="login-container">
                <form action="" method="get">
                    <p 
                        style="font-size: 25px;
                         font-weight: bold;
                         margin-bottom: 0px;
                         padding: 20px; 
                         color: #F8B62E;
                         border-bottom: 1px solid rgb(177, 177, 177);
                         padding-left: 120px;">
                        Enter security code
                    </p>

                    <p style="margin-bottom: 5px; color: #6C6C6C; margin-top: 20px; padding-left: 35px; line-height: 1.1;">Please check your email for a message with your <br> code. Your code is 6 characters long.</p>
                    <input type="email" name="" id="" placeholder="Enter your email">

                    <button type="button" class="search">Continue</button>
                    <button type="button" class="back">Back</button>
                </form>
            </div>
        </div>
        <div class="cwavey-logo">
            <img src="/Icons/clogin_logo.svg" alt="" class="logo" width="550px">
            <img src="/Icons/clogin_wavey.svg" alt="" width="702px">
        </div>
    </div>
</body>
</html>