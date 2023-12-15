<?php
require '../../Customer/functions/loginStaff.php';

$forgotPass = new customerLogin();

if(isset($_POST["Search"])){
    $email = $_POST["email"];

   $result =  $forgotPass->forgotPass($email);

    if($result === customerLogin::EMAIL_EMPTY_FIELDS){
        $error_message = "Kindly, fill in all the inputs";
    }else if($result === customerLogin::REGISTRATION_EMAIL_NOTSAME){
        $error_message = "Kindly, fill in the inputs or your email does not exist";
    }else if($result === customerLogin::REGISTRATION_SUCCESS){
        header("location: ../../Customer/customer_html/customer_security.php");
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

    <title>CBites Customer Register</title>
    <link rel="stylesheet" href="../../Customer/customer_css/customer_verifyemail.css">
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
        <div class="wavey1">
            <img src="../../Icons/customer-wavey1.svg" alt="" width="550px">
        </div>
        <div class="wavey2">
            <img src="../../Icons/customer-wavey2.svg" alt="" width="270px">
        </div>

            <div class="register-box">
                <form action="" method="POST">
                    <div class="verify-text">
                        <p style="font-size: 36px; font-weight: calc(1000); color: #F8B62E; margin-bottom: -5px;">Verify Email</p>
                        <p style="margin-bottom: -5px;">Kindly input your email address for your</p>
                        <p>account to be validated</p>
                    </div>
                    <div class="verify-input">
                            <input type="text" placeholder="Enter your email" class="email" name="email">
                    </div>
                        <div class="error error-message animate__animated animate__pulse">
                                <?php echo $error_message ?>
                        </div>
                    <div class="verify-button">
                        <button type="submit" class="search" name="Search">Verify Email</button>
                    </div>
                </form>
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