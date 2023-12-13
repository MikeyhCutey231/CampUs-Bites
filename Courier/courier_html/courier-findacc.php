<?php
 require '../functions/courierLogin.php';
 error_reporting(0);
 

if(isset($_POST["Search"])){
    $email = $_POST["email"];

   $forgotPass = new  loginCourier();
   $forgotPass->forgotPass($email);

   $result =  $forgotPass->forgotPass($email);

    if($result === loginCourier::EMAIL_EMPTY_FIELDS){
        $error_message = "Kindly, fill in all the inputs";
    }
 }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Courier/courier_css/courier-findacc.css">

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
<div class="main-wrapper">
    <img src="../../Icons/Vector%2046.png">
</div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 login-container">
                <img src="../../Icons/Layer_3%20(1).png" alt="Login Image" class="logo">
                <img src="../../Icons/driver-removebg-preview%201.png" alt="Login Image" class="login-image">
            </div>
            <div class="col-md-6 login-container">
                <form action="" method="post">
                <div class="login-form">
                    <div class="input-box text-center p-5 loginTitle">
                        <img src="../../Icons/Layer_1%20(1).png" class="person" style="width: 100px;">
                        <header>CAMPUS BITES</header>
                        <h5 >Courier</h5>
                    </div>

                        <div class="login-container1">
                            <form action="" method="get">
                                <p class="findT">
                                    Find your account
                                </p>

                                <p style="margin-bottom: 5px; color: #6C6C6C; margin-top: 20px; padding-left: 35px;">Please enter your email to search for your account.</p>
                                <input type="email" name="email" id="" placeholder="Enter your email">
            
                                <div class="error error-message animate__animated animate__pulse">
                                    <?php echo $error_message ?>
                                </div>
                                <div class="btns">
                                    <button type="submit" name="Search" class="back">Cancel</button>
                                    <button type="submit" class="search" name="Search">Search</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </form>

            </div>
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
