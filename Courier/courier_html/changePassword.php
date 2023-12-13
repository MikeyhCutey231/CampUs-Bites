<?php  
    require '../functions/courierLogin.php';
    error_reporting(0);

    $id = $_SESSION['Staff_ID'];

    $changePass = new loginCourier();
    
    if(isset($_POST['saveChangesButton'])){
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        $result = $changePass->changeLoginForgot($newPassword, $confirmPassword, $id);

        $error_message = null;  
        if($result == loginCourier::REGISTRATION_EMPTY_FIELDS){
            $error_message = "Input fields are empty";
        }else if($result == loginCourier::REGISTRATION_NOTSAME){
            $error_message = "Password you have input is not the same";
        }else if($result == loginCourier::REGISTRATION_PASSWORD_LENGTH){
            $error_message = "Password should be longer than 8 character";
        }else if($result == loginCourier::REGISTRATION_PASSWORD_CASE){
            $error_message = "Include atleast 1 uppercase letter";
        }else if($result == loginCourier::REGISTRATION_PASSWORD_SPECIAL_CHAR){
            $error_message = "Include atleast 1 special character";
        }else if($result == loginCourier::REGISTRATION_SUCCESS){         
            header("location: courier-login.php");
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
    <!-- CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Courier/courier_css/changePass.css">

    
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
                <div class="login-form">
                    <div class="input-box text-center p-5 loginTitle">
                        <img src="../../Icons/Layer_1%20(1).png" class="person" style="width: 100px;">
                        <header>CAMPUS BITES</header>
                        <h5 >Courier</h5>
                    </div>
                    <form action="" method="post">
                <div class="right">
                    <div class="container-login">
                            <div class="modal-header" style="border:none;">
                                <h4 class="modal-title" id="exampleModalLabel"><b>Edit Password</b></h4>
                            </div>
                            <div class="modal-body">
                                <div style="color: #727272; margin-bottom: 10px; margin-top: 15px;">
                                    <p style="font-size: 14px; font-weight: 700;">In order to protect your account. make sure your password</b></p>
                                    <ul style="font-size: 12px; margin-top: 0px;">
                                        <li>Is longer than 8 character</li>
                                        <li>It should include capital and lower case letters</li>
                                        <li>It should include as well as at least one special character</li>
                                    </ul>
                                </div>

                                <div class="mb-2">
                                    <label for="email" class="form-label"><span style="color:#9C1421">*</span>New Password</label>
                                    <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                        <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                    </svg>
                                </span>
                                        <input type="input" class="form-control" id="newPassword" placeholder="Insert new password" name="newPassword" value="<?php echo $newPassword ?>">
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label for="phoneNum" class="form-label"><span style="color:#9C1421">*</span>Confirm Password</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                                <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                            </svg>
                                        </span>
                                        <input type="input" class="form-control" id="confirmPassword" placeholder="Confirm password" name="confirmPassword">
                                    </div>
                                </div>
                                
                                <div class="error error-message animate__animated animate__pulse">
                                    <?php echo $error_message ?>
                                </div>
                            </div>
                            <div class="modal-footer" style="border:none;">
                                <button type="submit" class="btn-save" name="saveChangesButton">Change Password</button>
                            </div>
                    </div>
                </div>
            </form>

                    </div>

            </div>
        </div>
</div>
 
</body>
</html>
