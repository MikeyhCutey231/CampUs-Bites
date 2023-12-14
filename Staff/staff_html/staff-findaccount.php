<?php
 require '../functions/loginStaff.php';
 error_reporting(0);
 

if(isset($_POST["Search"])){
    $email = $_POST["email"];

   $forgotPass = new  LoginStaff();

   $result =  $forgotPass->forgotPass($email);

    if($result === LoginStaff::EMAIL_EMPTY_FIELDS){
        $error_message = "Kindly, fill in all the inputs";
    }else if($result === loginStaff::REGISTRATION_EMAIL_NOTSAME){
        $error_message = "Kindly, fill in the inputs or your email does not exist";
    }else if($result === loginStaff::REGISTRATION_SUCCESS){
        header("location: ../../Staff/staff_html/staff-securitycode.php");
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

    <title>CBites Customer Login</title>
    <link rel="stylesheet" href="../staff_css/staff-findaccount.css">

    <style>
        .error{
            display: none;
        }
    </style>

    <?php
        if($error_message != null){
            ?><style>.error{display: block;}</style> <?php
        }else{
            ?><style>.error{display: hidden;}</style> <?php
        }
    
    ?>
</head>
<body>
    <div class="wrapper">
        <div class="left">
            <div class="info-logo">
                <p class="campus">CampUs Bites</p>
                <p class="usep">University of Southeastern Philippines Tagum Unit</p>
                <p class="ecanteen">E-Canteen Service System</p>
            </div>
            <div class="login-container">
                <form action="" method="POST">
                    <p 
                        style="font-size: 25px;
                         font-weight: bold;
                         margin-bottom: 0px;
                         padding: 20px; 
                         color: #0F947E;
                         border-bottom: 1px solid rgb(177, 177, 177);
                         padding-left: 120px;">
                        Find your account
                    </p>

                    <div class="find-account">
                            <p style="margin-bottom: 3px; margin-left: 30px;">Please enter your email to search for your account.</p>
                            <input type="text" placeholder="Enter email" style="border:1px solid #B4B4B4;" name="email">
                            <div class="error error-message animate__animated animate__pulse">
                                <?php echo $error_message ?>
                            </div>

                    </div>

                    <button type="submit" class="search" name="Search">Search</button>
                    <button type="button" class="back">Cancel</button>
                </form>
            </div>
        </div>
        <div class="cwavey-logo">
            <img src="../../Icons/stafflogo-login.svg" alt="" class="logo" width="625px">
        </div>
    </div>

    <script>
         function hideErrorMessage() {
            const errorElement = document.querySelector('.error-message');
            errorElement.style.opacity = '0';
            setTimeout(() => {
                errorElement.style.display = 'none';
            }, 500);
        }

        const errorElement = document.querySelector('.error-message');
        if (errorElement.style.opacity === '1' || errorElement.innerText.trim() !== '') {
            setTimeout(hideErrorMessage, 1000);
        }
    </script>   
</body>
</html>