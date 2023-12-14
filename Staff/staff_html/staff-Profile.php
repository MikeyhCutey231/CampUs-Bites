<?php
    include("../functions/orderListfunctions.php");
    $database = new Connection();
    $conn = $database->conn;


    if (!isset($_SESSION['USER_ID'])) {
        header("Location: staff-login.php");
        exit();
    }

    $staffID = $_SESSION['USER_ID'];
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
    <link rel="stylesheet" href="/country-select-js-master/build/css/countrySelect.css">
	<link rel="stylesheet" href="/country-select-js-master/build/css/demo.css">

    <title>Staff POS | Order List</title>
    <link rel="stylesheet" href="../staff_css/staff-profile.css">
</head>
<body>
    <div class="wrapper">
   
    <div class="left-container" id="sidebar">
            <div class="sidebar-logo">
                <div class="main-logo"></div>
                <div class="sidelogo-text">
                    <div style="position: relative;">
                        <a href="">Campus</a>
                        <p class="">Bites</p>
                    </div>
                    <div class="close-btn"><img src="../../Icons/x-circle.svg" alt=""></div>
                </div>
            </div>

            <ul class="sidebar-nav">
                <li class="sidebar-items" style="margin-top: 20px;">
                    <a href="staff-menu.php" class="sidebar-link"  id="invHover">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 3H8C9.06087 3 10.0783 3.42143 10.8284 4.17157C11.5786 4.92172 12 5.93913 12 7V21C12 20.2044 11.6839 19.4413 11.1213 18.8787C10.5587 18.3161 9.79565 18 9 18H2V3Z" stroke="#5F5F5F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 3H16C14.9391 3 13.9217 3.42143 13.1716 4.17157C12.4214 4.92172 12 5.93913 12 7V21C12 20.2044 12.3161 19.4413 12.8787 18.8787C13.4413 18.3161 14.2044 18 15 18H22V3Z" stroke="#5F5F5F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>    
                    <p>Menu Item</p>
                    </a>
                </li>


                <li class="sidebar-items">
                    <a href="staff-orderList.php" class="sidebar-link" id="invHover">
                        <div class="circle"> <p class="cirlcecolor" id="notificationCount">0</p></div>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.88995 1.62988L2.44495 4.88988V16.2999C2.44495 16.7322 2.61668 17.1468 2.92236 17.4525C3.22805 17.7581 3.64264 17.9299 4.07495 17.9299H15.4849C15.9172 17.9299 16.3318 17.7581 16.6375 17.4525C16.9432 17.1468 17.1149 16.7322 17.1149 16.2999V4.88988L14.6699 1.62988H4.88995Z" stroke="#5F5F5F" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2.44495 4.88965H17.1149" stroke="#5F5F5F" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.04 8.15039C13.04 9.015 12.6966 9.84419 12.0852 10.4556C11.4738 11.0669 10.6446 11.4104 9.78002 11.4104C8.91541 11.4104 8.08622 11.0669 7.47485 10.4556C6.86348 9.84419 6.52002 9.015 6.52002 8.15039" stroke="#5F5F5F" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                    <p>Orders</p>
                    </a>
                </li>

                
                <li class="sidebar-items">
                    <a href="staff-transactionHistory.php" class="sidebar-link" id="invHover">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_488_1719)">
                            <path d="M17.1149 6.51953V17.1145H2.44495V6.51953" stroke="#5F5F5F" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18.7449 2.44531H0.814941V6.52031H18.7449V2.44531Z" stroke="#5F5F5F" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.15002 9.78027H11.41" stroke="#5F5F5F" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_488_1719">
                            <rect width="19.56" height="19.56" fill="white"/>
                            </clipPath>
                            </defs>
                            </svg>
                        
                    <p>Order History</p>
                    </a>
                </li>

                <li class="sidebar-items">
                    <a href="../../Staff/staff_html/staff-logout.php" class="sidebar-link">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.33428 17.1132H4.07464C3.64239 17.1132 3.22784 16.9415 2.92219 16.6358C2.61654 16.3302 2.44482 15.9156 2.44482 15.4834V4.07464C2.44482 3.64239 2.61654 3.22784 2.92219 2.92219C3.22784 2.61654 3.64239 2.44482 4.07464 2.44482H7.33428" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.0386 13.8537L17.1131 9.77914L13.0386 5.70459" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.1129 9.77881H7.33398" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>                                                        
                    <p>Signout</p>
                    </a>
                </li>

                <div class="usep-footercard">
                    <img src="../../Icons/useplogo.png" alt="" width="50px">
                    <p style="font-size: 11px; margin-bottom: -5px; font-weight: bold; margin-top: 5px;">University of Southeastern</p>
                    <p style="font-size: 11px; font-weight: bold;">Philippines (TU)</p>
    
                    <p style="font-size: 12px; margin-bottom: -5px; color: #0F947E;">University Property</p>
                    <p  style="font-size: 12px; color: #0F947E;">E-Canteen</p>
    
                    <p style="font-size: 12px; margin-bottom: -20px; color: #0F947E; font-weight: 900;">Learn More</p>
                    <p style="margin-bottom: 0px; color: #0F947E;">___________</p>
                </div>
            </ul>
        </div>


        <div class="right-container">
             <div class="head-content">
                <div class="Menu-name">
                    <button class="btn px-1 py-0 open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                    <p style="margin-bottom: 0;">Profile</p>
                </div>
                <!-- <div class="head-searchbar">
                    <img src="../../Icons/searchgreen.svg" alt="">
                    <input type="text" name="fname" placeholder="Search here..." id="searchItem">
                </div> -->
                <div class="usep-texthead">
                    <img src="../../Icons/useplogo.png" alt="" width="30px" height="30px">
                    <p style="margin-bottom: 0px; margin-top: 3px; margin-left: 10px; font-weight: 600;">UseP (Tagum Unit)</p>
                </div>
                <a href="staff-Profile.php">
                    <div class="user-profile">
                        <?php 
                                $staffID = $_SESSION['USER_ID'];
                                $profile = "SELECT * FROM users WHERE USER_ID = '$staffID'";
                                $profileRun = mysqli_query($conn, $profile);

                            while($row = mysqli_fetch_array($profileRun)){
                                    ?>
                                        <img src="../../Icons/<?php echo $row['U_PICTURE'] ?>" alt="" class="admin-profile">
                                    <?php
                            }
                        ?>
                        <div class="admin-detail">
                            <p style="margin-bottom: 0px; font-weight: 700;">Michael</p>
                            <p style="margin-bottom: 0px; font-size: 7px; background-color: #0F947E; color: white; padding: 3px; border-radius: 3px;">Cashier</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="main-content">
            <?php
                $employeeInfo = new EmployeeInfo($conn);
                $employeeId = $staffID;
                $personalInfo = $employeeInfo->getEmployeeData($employeeId);

            if($personalInfo){
                ?>
                <div class="userSettings-container">
                    <img src="../../Icons/<?php echo $personalInfo['U_PICTURE'] ?>" alt="" class="userProfile">
                    <h5 class="settingUsername"><?php echo $personalInfo['U_FIRST_NAME'] ." ". $personalInfo['U_MIDDLE_NAME'] ." ". $personalInfo['U_LAST_NAME']?></h5>
                    <p class="userJob">Cashier</p>
                    
                    <input type="file" id="uploadBtn" accept="image/*" style="display: none;">
                    <label for="uploadBtn">Choose File</label>

                    <button class="changepass" data-bs-toggle="modal" data-bs-target="#editpassword">Change Password</button>


                    <button class="goBack" onclick="goBack()">Go Baack</button>
                </div>
                <div class="userInformation-container">
                    <div class="personalInformation-container">
                                    <div class="pInformation-head">
                                        <h4>Personal Information</h4>
                                    </div>
                                    <div class="pInformation-content">
                                        <div class="cardDetails">
                                            <div class="userFname">
                                                <p style="margin-right: 50px;">First Name</p>
                                                <p style="font-weight: bold;"><?php echo $personalInfo['U_FIRST_NAME']?></p>
                                            </div>

                                            <div class="userEmergencyNum">
                                                <p style="margin-right: 27px;">Emergency Number</p>
                                                <p  style="font-weight: bold;"><?php echo $personalInfo['U_PHONE_NUMBER'] ?></p>
                                            </div>
                                        </div>

                                        <div class="cardDetails">
                                            <div class="userMname">
                                                <p style="margin-right: 35px;">Middle Name</p>
                                                <p style="font-weight: bold;"><?php echo $personalInfo['U_MIDDLE_NAME'] ?></p>
                                            </div>

                                            <div class="userEmail">
                                                <p class="emails" style="margin-right: 115px;">Email</p>
                                                <p class="realusermail" style="font-weight: bold;"><?php echo $personalInfo['U_EMAIL'] ?></p>
                                            </div>
                                        </div>

                                        <div class="cardDetails">
                                            <div class="userLname">
                                                <p class="lname" style="margin-right: 52px;">Last Name</p>
                                                <p style="font-weight: bold;"><?php echo  $personalInfo['U_LAST_NAME'] ?></p>
                                            </div>
                                        </div>


                                        <div class="cardDetails">
                                            <div class="userSuffix">
                                                <p class="suffix" style="margin-right: 78px;">Suffix</p>
                                                <p style="font-weight: bold;"><?php echo $personalInfo['U_SUFFIX'] ?></p>
                                            </div>
                                        </div>

                                        <div class="cardDetails">
                                            <div class="userGender">
                                                <p class="gender" style="margin-right: 74px;">Gender</p>
                                                <p style="font-weight: bold;"><?php echo $personalInfo['U_GENDER'] ?></p>
                                            </div>
                                        </div>
                                    
                                        <div class="cardDetails">
                                            <div class="userPhonenum">
                                                <p style="margin-right: 28px;">Phone Number</p>
                                                <p style="font-weight: bold;"><?php echo $personalInfo['U_PHONE_NUMBER'] ?></p>
                                            </div>
                                         </div>

                                    </div>
                                </div>
                                <div class="workInformation-container">
                                    <div class="workInformation-head">
                                        <h4>Work Information</h4>
                                    </div>

                                    <div class="cardDetails" style="margin-top: 10px;">
                                        <div class="userEmployeeId">
                                            <p style="margin-right: 140px;">Employee Id</p>
                                            <p style="font-weight: bold;">#<?php echo $personalInfo['USER_ID'] ?></p>
                                        </div>

                                        <div class="userPosition">
                                            <p class="staffposition" style="margin-right: 166px;">Position</p>
                                            <p  style="font-weight: bold;">Cashier</p>
                                        </div>
                                    </div>

                                    <div class="cardDetails">
                                        <div class="userBasicSalary">
                                            <p class="basicsalary" style="margin-right: 88px;">Basic Salary per day</p>
                                            <p  style="font-weight: bold;">₱<?php echo $personalInfo['EMP_BASIC_SALARY'] ?></p>
                                        </div>
                                    </div>
                                <?php 
                            }
                        ?> 
                            
                    </div>
                </div>
            </div>
        </div>
    </div>



<!--Modal for edit password-->
<div class="modal fade" id="editpassword" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="border:none; padding-bottom: 0px;">
                        <h4 class="modal-title" id="exampleModalLabel"><b>Edit Password</b></h4>
                    </div>
                    <div class="modal-body">
                        <div style="color: #727272; margin-bottom: -10px;">
                            <p style="font-size: 14px; font-weight: bold;">In order to protect your account. make sure your password</b></p>
                            <ul style="font-size: 12px; margin-top: 0px;">
                                <li>Is longer than 8 character</li>
                                <li>It should include capital and lower case letters</li>
                                <li>It should include as well as at least one special character</li>
                            </ul>
                        </div>

                        <div class="mb-2">
                            <label for="username" class="form-label"><span style="color:#9C1421">*</span>Current Password</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                          <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                    </svg>
                                </span>
                                <input type="password" class="form-control" id="currentPassword" placeholder="Insert current password" name="currentPassword">
                            </div>
                            <p class="pass_error" style="color: red; font-size: 12px; margin-left: 55px; margin-top: -10px;"></p>

                        </div>

                        <div class="mb-2">
                            <label for="email" class="form-label"><span style="color:#9C1421">*</span>New Password</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                            </svg>
                        </span>
                                <input type="input" class="form-control" id="newPassword" placeholder="Insert new password" name="newPassword">
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
                        
                        <div class="error error-message">
                            <p></p>
                        </div>
                    </div>
                    <div class="modal-footer" style="border:none;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn-save" id="saveChangesButton">Save changes</button>
                    </div>
                </div>
            </div>
        </div>



 <!-- Disable account -->
 <div class="modal fade" id="disableAccount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h6>Are you sure you want to disable your account?</h6>
                    <p>By disabling your account account you will no longer have access of this.</p>

                    <div class="modalDisablebtn">
                        <button style="background-color: #0F947E; border: none;  box-shadow: 0px 0px 10px rgba(0, 0, 0, 25%);">Disable</button>
                        <button style="background-color: white; border: none; color: black;  box-shadow: 0px 0px 10px rgba(0, 0, 0, 25%);" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
  </div>

    <!-- Javascript -->
    <script src="../../Staff/staff_js/staff.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }

         $(document).ready(function () {
            $("#saveChangesButton").click(function () {
                var currentPassword = $("#currentPassword").val();
                var newPassword = $("#newPassword").val();
                var confirmPassword = $("#confirmPassword").val();
                var errorElement = $(".error");


                    // Your AJAX request goes here
                    $.ajax({
                        type: "POST",
                        url: "../functions/update-password.php",
                        data: { currentPassword: currentPassword, newPassword: newPassword, confirmPassword:confirmPassword },

                        success: function (response) {
                            
                            errorElement.css({
                                color: "rgb(223, 59, 59)",
                                fontSize: "14px",
                                marginTop: "-5px",
                                marginBottom: "5px !important"
                            });

                            errorElement.text(response);
                            

                            if (currentPassword.trim() === "" || newPassword.trim() === "" || confirmPassword.trim() === "") {
                               errorElement.text("Please fill in all the required fields.");
                             }else if (response === "Password should be at least 8 characters long"){
                                errorElement.show();
                             }else if (response === "Password should include both capital and lowercase letters"){
                                errorElement.show();
                             }else if (response === "Password should include at least one special character"){
                                errorElement.show();
                             } else if (response === "New and Current password does not match"){
                                errorElement.show();
                            }else if (response === "Data changed"){
                                window.location.href = "staff-profile.php";
                            }else{
                                errorElement.text("The current password you have input is incorrect");
                            }
                        }
                    });
            });
        });
    </script>

   

    <script>
    $(document).ready(function () {
        $('#uploadBtn').change(function () {
            var formData = new FormData();
            formData.append('profileImage', this.files[0]);

            $.ajax({
                url: '../functions/updateProfileHandler.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response === 'success') {
                        location.reload();
                    } else {
                        alert('Error: ' + response);
                    }
                },
                error: function (xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        });
    });
    </script>

    <script>
        function updateNotificationCount() {
            $.ajax({
                url: "../functions/check_zero_stock.php", // Create a new PHP file for checking zero stock products
                method: "GET",
                success: function (data) {
                    $("#notificationCount").text(data);
                },
            });
        }

        // Call the function when the page loads
        $(document).ready(function () {
            updateNotificationCount();

            // Set up an interval to update the notification count (e.g., every 5 minutes)
            setInterval(updateNotificationCount, 300000); // 300000 milliseconds = 5 minutes
        });
    </script>



    
</body>
</html>