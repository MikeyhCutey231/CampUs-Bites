<?php
 require '../../Admin/functions/user.php';
error_reporting(0);

 $error_message = null;
if(isset($_POST["Search"])){
    $email = $_POST["email"];


   
   $forgotPass = new User();
   $forgotPass->forgotPass($email);

   $result =  $forgotPass->forgotPass($email);

    if($result === User::EMAIL_EMPTY_FIELDS){
        $error_message = "Kindly, fill in all the inputs";
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

    <title>CampUs Bites - Login</title>
    <link rel="stylesheet" href="../admin_css/admin-forgotpass.css">

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
            <h1 class="head-title">CampUs <?php echo $_SESSION['USER_ID'] ?></h1>
            <h4 class="sub-head">Feeding minds, one bite at a time.</h4>
        </div>
        <div class="right">
            <div class="container-login">
                <div class="login-head">
                    <p style="font-size: 22px; color: #9C1421; margin-bottom: -5px; font-weight: calc(1000);">Find your Account</p>
                </div>

               <form action="" method="post">
                        <div class="find-account">
                            <p style="margin-bottom: 0px;">Please enter your email to search for your account.</p>
                            <input type="text" placeholder="Enter email" style="border:1px solid #B4B4B4;" name="email">
                            <div class="error error-message animate__animated animate__pulse">
                                <?php echo $error_message ?>
                            </div>
                        </div>

                        <div class="buttons">
                            <button type="button" class=" cancel">Cancel</button>
                            <button type="submit" class="search" name="Search">Search</button>
                    </div>
               </form>
            </div>
        </div>
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
            setTimeout(hideErrorMessage, 1000);
        }
    </script>   
</body>
</html>