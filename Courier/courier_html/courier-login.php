<?php
    require '../functions/courierLogin.php';
    error_reporting(0);

    $loginuser = new loginCourier();

    if(isset($_POST["submit"])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        $result = $loginuser->userLogin($username,$password);
        
        $error_message = null;

        if ($result === loginCourier::REGISTRATION_EMPTY_FIELDS) {
            $error_message = "Kindly, fill in all the inputs";
        } else if ($result === loginCourier::REGISTRATION_NOTSAME) {
            $error_message = "Kindly input your proper credentials";
        } else if($result === loginCourier::REGISTRATION_SUCCESS){
            header("location: courier-dashboard.php");
        }
    }

    if(!isset($_POST["submit"])){
        unset($_SESSION['code']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Include Bootstrap CSS -->
    <!-- CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Courier/courier_css/courier-login.css">

    <style>
        .error{
            display: none;
        }
    </style>

    <?php
        if($error_message != null){
            ?><style>.error{display: block; text-align: left;}</style> <?php
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
                        <div class="input-box text-center p-5 containerMiddle">
                            <img src="../../Icons/courierHead.png" class="person" style="width: 100px;">
                            <header>CAMPUS BITES</header>
                            <h5 class="mb-5">Courier</h5>
                            <div class="input-field mt-5 corusername">
                                <input type="text" class="input" id="emailField" name="username">
                                <label for="email">Username</label>
                                <img src="../../Icons/usergreen.png" class="input-icon">
                                <i class="fa-solid fa-check" id="emailImage" width="16px" style="display: none;"></i>
                            </div>

                            <div class="input-field mt-5 corPass">
                                <input type="password" class="input" id="passwordField" name="password">
                                <label for="pass">Password</label>
                                <img src="../../Icons/lock.png" class="input-icon">
                                <div class="password-toggle">
                                     <i class="fa-solid fa-eye-slash" id="passwordIcon" style="display: none;"></i>
                                </div>
                            </div>

                            <div class="signin">
                                <span><a href="courier-findacc.php" class="forgotpass">Forgot Password?</a></span>
                            </div>

                            <div class="error error-message animate__animated animate__pulse">
                                <?php echo $error_message ?>
                            </div>


                            <div class="login">
                                <button class="submit" name="submit" data-toggle="modal" data-target="#myModal">LOGIN</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>


<script>
        const passwordField = document.getElementById('passwordField');
        const passwordToggle = document.querySelector('.password-toggle i');
    
        passwordToggle.addEventListener('click', function() {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggle.classList.remove('fa-eye-slash');
                passwordToggle.classList.add('fa-eye');
            } else {
                passwordField.type = 'password';
                passwordToggle.classList.remove('fa-eye');
                passwordToggle.classList.add('fa-eye-slash');
            }
        });

        passwordField.addEventListener('input', function() {
            if (passwordField.value.trim() !== '') {
                passwordIcon.style.display = 'block';
            } else {
                passwordIcon.style.display = 'none';
            }
        });


        const emailField = document.getElementById('emailField');
        const emailImage = document.getElementById('emailImage');

        emailField.addEventListener('input', function() {
            if (emailField.value.trim() !== '') {
                emailImage.style.display = 'block';
            } else {
                emailImage.style.display = 'none';
            }
        });


        function hideErrorMessage() {
            const errorElement = document.querySelector('.error-message');
            errorElement.style.opacity = '0';
            setTimeout(() => {
                errorElement.style.display = 'none';
            }, 500);
        }

        const errorElement = document.querySelector('.error-message');
        if (errorElement.style.opacity === '1' || errorElement.innerText.trim() !== '') {
            setTimeout(hideErrorMessage, 2000);
        }
    </script>
</body>
</html>
