<?php
    include("../../Admin/functions/dbConfig.php");
    error_reporting(0);
        
    $code = $_SESSION['code'];
    $userEmail =  $_SESSION['email'];

    if(isset($_POST['continue'])){
        $codeInput = $_POST['codeInput'];
        $error_message = null;

        if($codeInput == $code){
            header('location: admin-forgotChange.php');
        }else{
            $error_message = "Invalid Code";
        }
    }


    if(isset($_POST['cancel'])){
        header("location: admin-forgotpass.php"); 
        setcookie(session_name(), '', 1);
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

    <title>CampUs Bites - Login</title>
    <link rel="stylesheet" href="../admin_css/admin-securitycode.css">

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
            <h1 class="head-title">CampUs Bites</h1>
            <h4 class="sub-head">Feeding minds, one bite at a time.</h4>
        </div>
        <form action="" method="post">
            <div class="right">
                <div class="container-login">
                    <div class="login-head">
                        <p style="font-size: 22px; color: #9C1421; margin-bottom: -5px; font-weight: calc(1000);">Enter security code</p>
                    </div>

                    <div class="find-account">
                        <form action="" method="post">
                            <p style="margin-bottom: 0px; text-align: left; line-height: 1.1; padding-left: 50px;">Please check your email for a message with your <br> code. Your code is 6 characters long.</p>
                            <input type="text" placeholder="Enter 6 digit code" name="codeInput" id="codeInput" style="border:1px solid #B4B4B4;" value="<?php echo " " ?>">
                            <p class="error error-message animate__animated animate__pulse"><?php echo $error_message ?></p>
                        </form>
                    </div>

                    <div class="buttons">
                        <button type="submit" class="cancel" name="cancel">Cancel</button>
                        <button type="submit" class="search" name="continue">Continue</button>
                    </div>
                </div>
            </div>
        </form>
   </div>

    <!-- Javascript -->
    <script src="/Admin/admin_js/admin.js"></script>
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
            setTimeout(hideErrorMessage, 2000);
        }

        
        const codeInput = document.getElementById('codeInput');

        codeInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');

            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });
    </script>
</body>
</html>