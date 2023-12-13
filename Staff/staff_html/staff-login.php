<?php
    require '../functions/loginStaff.php';
    
    $loginuser = new loginStaff();

    if(isset($_POST["submit"])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        $result = $loginuser->userLogin($username,$password);
        
        $error_message = null;

        if ($result === loginStaff::REGISTRATION_EMPTY_FIELDS) {
            $error_message = "Kindly, fill in all the inputs";
        } else if ($result === loginStaff::REGISTRATION_NOTSAME) {
            $error_message = "Kindly input your proper credentials";
        } else if($result === loginStaff::REGISTRATION_SUCCESS){
            header("location: staff-menu.php");
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

    <!-- CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <title>CBites Staff Login</title>
    <link rel="stylesheet" href="../staff_css/stafflogin.css">

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
            <form action="" method="post">
                <div class="login-container">
                    <form action="" method="get">
                        <p style="font-size: 34px; font-weight: calc(1000); margin-bottom: 0px; padding-top: 20px;">Welcome Staff!</p>

                        <div class="email">
                                <p>Employee Username</p>
                                <div class="email-input">
                                    <input type="text" id="emailField" name="username" style="font-size: 13px;">
                                    <i class="fa-solid fa-check" id="emailImage" width="16px" style="display: none;"></i>
                                </div>
                        </div>
                        <div class="password">
                                <p>Password</p>
                                <div class="password-input">
                                    <input type="password" id="passwordField" name="password" style="font-size: 13px;">
                                    <div class="password-toggle">
                                        <i class="fa-solid fa-eye-slash" id="passwordIcon" style="display: none;"></i>
                                    </div>
                                </div>
                        </div>


                        <a href="staff-findaccount.php"><p>Forgot password?</p></a>

                        <div class="error error-message animate__animated animate__pulse">
                                <?php echo $error_message ?>
                        </div>

                        <div class="loginBTN" style="margin-top: 8px;">
                            <button type="submit" name="submit">Login</button>
                        </div>
                    </form>
                </div>
            </form>
        </div>
        <div class="cwavey-logo">
            <img src="../../Icons/stafflogo-login.svg" alt="" class="logo" width="625px">
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