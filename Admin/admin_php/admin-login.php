<?php
require '../../Admin/functions/user.php';
include '../../Admin/functions/Admin_Manager_logs.php';
$logger = new Admin_Manager_logs();

$loginuser = new User();
$error_message = '';

if(isset($_POST["submit"])){
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = $loginuser->login($username, $password);

    if ($result === User::REGISTRATION_EMPTY_FIELDS) {
        $error_message = "Kindly, fill in all the inputs";
    } else if ($result === User::REGISTRATION_NOTSAME) {
        $error_message = "Kindly input your proper credentials";
    } else if ($result === User::REGISTRATION_DEACTIVATED) {
        $error_message = "User account is deactivated.";
    } else if ($result === User::REGISTRATION_SUCCESS) {
        $_SESSION['USER_ID'] = $loginuser->getAdminID();
        $logger->loginLogs($loginuser->getAdminID());
        header("location: admin-dashboard.php");
        exit();
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

    <title>Campus Bites - Login</title>
    <link rel="stylesheet" href="../admin_css/admin-login.css">


    <style>
        .error {
            display: <?php echo $error_message ? 'block' : 'none'; ?>;
            margin-bottom: 20px;
        }
    </style>

</head>
<body>
<div class="wrapper">
    <div class="left">
        <h1 class="head-title">Campus Bites</h1>
        <h4 class="sub-head">Feeding minds, one bite at a time.</h4>
    </div>
    <div class="right">
        <div class="container-login">
            <div class="login-head">
                <p style="font-size: 26px; color: #9C1421; margin-bottom: -5px; font-weight: 700;">Welcome Back!</p>
                <p style="font-size: 14px;">Sign in to your account</p>
            </div>
            <form action="" method="post">
                <div class="login-middle">
                    <div class="email">
                        <p>Email</p>
                        <div class="email-input">
                            <input type="text" id="emailField" placeholder="Enter your USeP email" name="username" style="font-size: 13px;">
                            <img src="../../Icons/check.svg" alt="" id="emailImage" width="16px" style="display: none;">
                        </div>
                    </div>
                    <div class="password">
                        <p>Password</p>
                        <div class="password-input">
                            <input type="password" id="passwordField" name="password" placeholder="Enter your password" style="font-size: 13px;">
                            <div class="password-toggle">
                                <i class="fa-solid fa-eye-slash" id="passwordIcon" style="display: none;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="login-button">
                        <div class="error error-message animate__animated animate__pulse">
                            <?php echo $error_message ?>
                        </div>

                        <input type="submit" value="Login" name="submit">
                        <div class="create-account">
                            <a href="admin-forgotpass.php" >Forgot Password?</a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="login-footer">
                <a href="">Terms of use | Privacy policy</a>
            </div>
        </div>
    </div>
</div>

<!-- Javascript -->
<script src="/Admin/admin_js/admin.js"></script>
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


