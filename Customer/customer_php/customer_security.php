<?php 
    include("../../Admin/functions/dbConfig.php");
    error_reporting(0);    

    
    $code = $_SESSION['code'];
    $email = $_SESSION['email'];

    if(isset($_POST['continue'])){
        $codeInput = $_POST['codeInput'];
        $error_message = null;

        if($codeInput == $code){
            header('location: customer-forgotPassChange.php');
        }else{
            $error_message = "Invalid Code";
        }
    }


    if(isset($_POST['cancel'])){
        header("location: customer-forgotpass.php"); 
        setcookie(session_name(), '', 1);
        unset($_SESSION['code']);
        unset($_SESSION['email']);
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
    <link rel="stylesheet" href="../../Customer/customer_css/customer_security.css">

    <style>
        .error{
            display: none;
        }
    </style>

    <?php
        if($error_message != null){
            ?><style>.error{display: block; margin-bottom: 20px;}</style> <?php
        }else{
            ?><style>.error{display: hidden;}</style> <?php
        }
    
    ?>
</head>
<body>
    <div class="wrapper">
        <div class="left">
            <div class="info-logo">
                
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
                         color: #F8B62E;
                         border-bottom: 1px solid rgb(177, 177, 177);
                         padding-left: 120px;">
                        Enter security code
                    </p>

                    <p style="margin-bottom: 3px; margin-left: 30px;">Please check your email for a message with your code. <br> Your code is 6 characters long.</p>
                            <input type="text" placeholder="Enter email" style="border:1px solid #B4B4B4;" name="codeInput" id="codeInput">

                    <div class="error error-message animate__animated animate__pulse">
                                <?php echo $error_message ?>
                    </div>

                    <button type="submit" class="search" name="continue">Continue</button>
                    <button type="submit" class="back" name="cancel">Back</button>
                </form>
            </div>
        </div>
        <div class="cwavey-logo">
            <img src="../../Icons/clogin_logo.svg" alt="" class="logo" width="550px">
            <img src="../../Icons/clogin_wavey.svg" alt="" width="702px">
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