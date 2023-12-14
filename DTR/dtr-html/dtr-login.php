<?php

require '../functions/loginUser.php';

$loginuser = new LoginUser();
$error_message = '';

if(isset($_POST["submit"])){
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = $loginuser->userLogin($username, $password);

    if ($result === LoginUser::REGISTRATION_EMPTY_FIELDS) {
        $error_message = "Kindly, fill in all the inputs";
    } else if ($result === LoginUser::REGISTRATION_NOTSAME) {
        $error_message = "Kindly input your proper credentials";
    } else if ($result === LoginUser::REGISTRATION_DEACTIVATED) {
        $error_message = "User account is deactivated.";
    }else if ($result === LoginUser::REGISTRATION_NOTSTAFF) {
        $error_message = "User account is not staff.";
    }else if ($result === LoginUser::REGISTRATION_LIMIT) {
        $error_message = "You have timed out already for today.";
    } else if ($result === LoginUser::REGISTRATION_SUCCESS) {

        $_SESSION['USER_ID'] = $loginuser->getUserID();
        header("location: dtr-staff-attendance.php");
        exit();
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add Bootstrap CSS (for styling) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Add jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <!-- Add Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        .error-message {
            display: <?php echo isset($error_message) && !empty($error_message) ? 'block' : 'none'; ?>;
            font-size: 12px;
            background-color: #edb4bc;
            color: rgb(73, 73, 73);
            padding: 8px;
            padding-left: 10px;
            border-radius: 2px;
            width: 88%;
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }
    </style>


    <link rel="stylesheet" href="../../DTR/dtr-css/dtr-login.css">
    <title>Campus Bites</title>
    
</head>
<body>
<div class="wrapper">
    <div class="container main">
        <div class="row">
            <div class="col-md-6 d-flex ">
                <img src="../../Icons/Vector%2046.png" class="mainVector">
                <img src="../../Icons/Layer_1.png" alt="greenIcon" class="layer1">
            </div>
            <div class="col-md-6 right d-flex justify-content-center align-items-center">
                    <div class="input-box text-center p-5">
                        <img src="../../Icons/Layer_1%20(1).png" class="person">
                        <header class="titleLogin">CAMPUS BITES</header>
                        <h5 class="mb-5">Daily Time Records</h5>
                        <form method="post" action="">
                            <div class="input-field">
                                <input type="text" id="emailField" class="input" name="username">
                                <label>Username</label>
                                <img src="../../Icons/userGreen.png" class="input-icon">
                                <i class="fa-solid fa-check" id="emailImage" width="16px" style="display: none;"></i>
                            </div>

                            <div class="input-field password">
                                <input type="password" id="passwordField" class="input" name="password">
                                <label>Password</label>
                                <img src="../../Icons/lock.png" class="input-icon">
                                <div class="password-toggle">
                                    <i class="fa-solid fa-eye-slash" id="passwordIcon" style="display: none;"></i>
                                </div>
                            </div>
                            <div class="login">
                                <p class="error-message"><?php echo $error_message ?></p>
                                <button type="submit" class="submit" name="submit" id="loginButton" data-bs-target="#myModal">LOGIN</button>
                            </div>
                        </form>

                 </div>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap Modal -->
    <!--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="" method="post">
                <div class="modal-content text-center p-4">
                    <div class="modal-body">

                        <div class="d-flex justify-content-center align-items-center">
                            <p class="msg">Do you want to view Daily Time Records?</p>
                        </div>
                    </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" name="yesBtn" class="btnY">Yes</button>
                            <button type="submit" name="noBtn" class="btnN">No</button>
                        </div>
                </div>
            </form>
        </div>
    </div>-->


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