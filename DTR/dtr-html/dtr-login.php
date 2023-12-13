<?php
    include("../functions/logModalHandler.php");

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        $currentDate = date("Y-m-d");
        $current_time = date('Y-m-d H:i:s');
        
        if (isset($_POST["yesBtn"])) {
            $checkUserlogsql = "SELECT * FROM emp_dtr WHERE EMP_DTR_DATE = '$currentDate' AND EMPLOYEE_ID  = '$username'";
            $checkUserRun = mysqli_query($conn, $checkUserlogsql);
        
            if (mysqli_num_rows($checkUserRun) > 0) {
                // User has logged in at least once on the same day
                $checkTimeOut = "SELECT * FROM emp_dtr WHERE EMPLOYEE_ID = '$username' AND EMP_DTR_TIME_OUT IS NULL";
                $timeOutrun = mysqli_query($conn, $checkTimeOut);
        
                if ($timeOutrun) {
                    $row = mysqli_fetch_assoc($timeOutrun);
                    if ($row["EMP_DTR_TIME_OUT"] == NULL && $row['EMPLOYEE_ID'] == $username) {
                        // Update the time out for the first login
                        
                        // Calculate overtime
                        $logoutTime = $current_time; // Replace with the actual column that stores log out time
                        $logoutHour = date('H:i', strtotime($logoutTime));
                        $logoutTimeThreshold = '19:00'; // 7:00 PM
        
                        if ($logoutHour <= $logoutTimeThreshold) {
                            $basicSalary = 1000; // Replace with the user's basic salary
                            $overtimeHours = (strtotime($logoutTime) - strtotime('17:00:00')) / 3600;
                            $overtimeHours = min($overtimeHours, 2);
                            $overtimePay = ($basicSalary / 8) * $overtimeHours;


                            $_SESSION['ovetime'] = $overtimeHours;
                            $updateUser = "UPDATE emp_dtr SET EMP_DTR_TIME_OUT = '$current_time', EMP_DTR_STATUS = 'Present', EMP_DTR_OVERTIME = '$overtimeHours' WHERE EMP_DTR_DATE = '$currentDate' AND EMP_DTR_TIME_OUT IS NULL";
                            $updateUserRun = mysqli_query($conn, $updateUser);
                            $_SESSION['date'] = $username;
                        }
                    } else {
                        // User has already used 2 logins
                        $_SESSION['date'] = $username;
                    }
                }
            } else {
                // User has not logged in today
                $_SESSION['date'] = $username;
                $insertUserdate = "INSERT INTO emp_dtr (EMPLOYEE_ID, EMP_DTR_DATE, EMP_DTR_TIME_IN) VALUES ('$username', '$currentDate', '$current_time');";
                $insertRunuser = mysqli_query($conn, $insertUserdate);
            }
        
            header('location: dtr-staff-attendance.php');
        }
        
        
        


        if (isset($_POST["noBtn"])) {
            $checkUserlogsql = "SELECT * FROM emp_dtr WHERE EMP_DTR_DATE = '$currentDate' AND EMPLOYEE_ID  = '$username'";
            $checkUserRun = mysqli_query($conn, $checkUserlogsql);
        
            if (mysqli_num_rows($checkUserRun) > 0) {
                // User has logged in at least once on the same day
                $checkTimeOut = "SELECT * FROM emp_dtr WHERE EMPLOYEE_ID = '$username' AND EMP_DTR_TIME_OUT IS NULL";
                $timeOutrun = mysqli_query($conn, $checkTimeOut);
        
                if ($timeOutrun) {
                    $row = mysqli_fetch_assoc($timeOutrun);
                    if ($row["EMP_DTR_TIME_OUT"] == NULL && $row['EMPLOYEE_ID'] == $username) {
                        // Update the time out for the first login
                        $updateUser = "UPDATE emp_dtr SET EMP_DTR_TIME_OUT = '$current_time', EMP_DTR_STATUS = 'Present' WHERE EMPLOYEE_ID = '$username' AND EMP_DTR_TIME_OUT IS NULL";
                        $updateUserRun = mysqli_query($conn, $updateUser);
                        $_SESSION['date'] = $username;
                    } else {
                        // User has already used 2 logins
                        $_SESSION['date'] = $username;
                    }
                }
            } else {
                // User has not logged in today
                $_SESSION['date'] = $username;
                $insertUserdate = "INSERT INTO emp_dtr (EMPLOYEE_ID, EMP_DTR_DATE, EMP_DTR_TIME_IN) VALUES ('$username', '$currentDate', '$current_time');";
                $insertRunuser = mysqli_query($conn, $insertUserdate);
            }
        
            header('location: dtr-login.php');
        }
        
         
           
        

        // $dateSql = "SELECT * FROM emp_dtr WHERE EMP_DTR_DATE = '$currentDate'";
        // $dateSqlrun = mysqli_query($conn, $dateSql);

        // $row = mysqli_fetch_array($dateSqlrun);
    
        // if($currentDate == $row["EMP_DTR_DATE"]){
        //     $username = "date exist";
        // }else{
        //     $username = "date not exist";
        // }




        // Date and Time 
        // login count = 2 - 1 (first login)
        // login count = 1

        // if(currentDate == databaseDate){
        //     if(user == already exist){
        //         checks the log count
        //             if(loginCount == 1){
        //                 if(user == first login) store database time in and date then login count = 1
        //                 else if 
        //                 (user = second login) update database time out, time thne logout count = 0

        //             }else if(loginCount == 0){
        //                 print you have reached the maximum login
        //             }
        //     }else{
        //         if(loginCount == 1){
        //                 if(user == first login) store database time in and date then login count = 1
        //                 else if 
        //                 (user = second login) update database time out, time thne logout count = 0

        //             }else if(loginCount == 0){
        //                 print you have reached the maximum login
        //         }
        //     }
        // }else if(currentDate != databaseDate){
        //     if(loginCount == 1){
        //                 if(user == first login) store database time in and date then login count = 1
        //                 else if 
        //                 (user = second login) update database time out, time thne logout count = 0

        //             }else if(loginCount == 0){
        //                 print you have reached the maximum login
        //         }
        // }
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
                        <header class="titleLogin">Yawa BITES</header>
                        <h5 class="mb-5">Daily Time Records</h5>
                        
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

                        <div class="signin">
                            <span><a href="#" class="forgotpass">Forgot Password?</a></span>
                        </div>

                        <div class="error error-message animate__animated animate__pulse" style="text-align: left;">
                            <?php echo $error_message ?>
                        </div>


                        <div class="login">
                            <button type="button" class="submit" name="submit" id="loginButton">LOGIN</button>
                        </div>
                 </div>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="" method="post">
                <div class="modal-content text-center p-4">
                    <div class="modal-body">

                        <div class="d-flex justify-content-center align-items-center">
                            <p class="msg">Do you want to view Daily Time Records? <?php echo $username ?></p>
                        </div>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-footer justify-content-center">
                            <button type="submit" name="yesBtn" class="btnY">Yes</button>
                            <button type="submit" name="noBtn" class="btnN">No</button>
                        </div>
                    </form>
                </div>
            </form>
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


   

         $(document).ready(function() {
            $("#loginButton").click(function() {
                var username = $("#emailField").val();
                var password = $("#passwordField").val();
                var errorElement = $(".error");

                errorElement.css({
                    fontSize: "14px",
                    marginTop: "8px",
                    marginBottom: "14px !important",
                    opacity: 1 
                });

                $.ajax({
                    type: "POST",
                    url: "../functions/logModalHandler.php", 
                    data: {
                        username: username,
                        password: password
                    },
                    
                    success: function(response) {
                       if(response === "Kindly, fill in all the inputs"){
                            errorElement.text(response);
                            errorElement.show();

                  
                            setTimeout(function() {
                                errorElement.css("opacity", 0);
                            }, 2000);
                            setTimeout(function() {
                                errorElement.hide();
                            }, 2500); 

                        } else if(response === "Kindly input your proper credentials"){
                            errorElement.text(response);
                            errorElement.show();

                  
                            setTimeout(function() {
                                errorElement.css("opacity", 0);
                            }, 2000);
                            setTimeout(function() {
                                errorElement.hide();
                            }, 2500); 

                        } else if (response === "success") {
                            $("#myModal").modal("show");

                        } 
                    }
                });
            });
        });
    </script>
        
</body>
</html>