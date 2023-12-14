<?php

include '../../Admin/functions/UserData.php';
include '../functions/loginUser.php';
$userID = $_SESSION['USER_ID'];
$userData = new UserData();
$usersData = $userData->getUserData($userID);

$userDTRData = new LoginUser();
if (!isset($_SESSION['USER_ID'])) {
    header("Location: dtr-login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../dtr-css/dtr-staff-attendance.css">
    <title>CampUs Bites DTR</title>
</head>
<body>
<div class="wrapper">
    <div class="left-container" id="sidebar">
        <div class="sidebar-logo">
            <div class="main-logo"></div>
            <div class="sidelogo-text">
                <div style="position: relative;">
                    <a href="" style="color: #099F6A"></a>
                    <p class="" style="color: #099F6A">Bites</p>
                </div>
                <div style="position: absolute; right: 20px; cursor: pointer;" class="close-btn d-md-none d-md-block"><img src="/Icons/x-circle.svg" alt=""></div>
            </div>
        </div>

        <ul class="sidebar-nav">
            <li class="sidebar-items">
                <a href="" class="sidebar-link dtr">
                    <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.5835 2.375H6.3335C7.17335 2.375 7.9788 2.70863 8.57267 3.3025C9.16653 3.89636 9.50016 4.70181 9.50016 5.54167V16.625C9.50016 15.9951 9.24994 15.391 8.80454 14.9456C8.35914 14.5002 7.75505 14.25 7.12516 14.25H1.5835V2.375Z" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.4167 2.375H12.6667C11.8268 2.375 11.0214 2.70863 10.4275 3.3025C9.83363 3.89636 9.5 4.70181 9.5 5.54167V16.625C9.5 15.9951 9.75022 15.391 10.1956 14.9456C10.641 14.5002 11.2451 14.25 11.875 14.25H17.4167V2.375Z" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    <p>Daily Time Record</p>
                </a>
            </li>

                <li class="sidebar-items">
                    <button type="submit" name="signout" style="width: 100%; margin-top:10px; border: none;">
                        <a href="dtr-logout.php" class="sidebar-link">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.33428 17.1132H4.07464C3.64239 17.1132 3.22784 16.9415 2.92219 16.6358C2.61654 16.3302 2.44482 15.9156 2.44482 15.4834V4.07464C2.44482 3.64239 2.61654 3.22784 2.92219 2.92219C3.22784 2.61654 3.64239 2.44482 4.07464 2.44482H7.33428" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13.0386 13.8537L17.1131 9.77914L13.0386 5.70459" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17.1129 9.77881H7.33398" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p>Signout</p>
                        </a>
                    </button>
                </li>
            <div class="usep-footercard">
                <img src="../../Icons/useplogo.png" alt="" width="50px">
                <p style="font-size: 11px; margin-bottom: -5px; font-weight: bold; margin-top: 5px;">University of Southeastern</p>
                <p style="font-size: 11px; font-weight: bold;">Philippines (TU)</p>

                <p style="font-size: 12px; margin-bottom: -5px; color: #099F6A;">University Property</p>
                <p  style="font-size: 12px; color: #099F6A;">E-Canteen</p>

                <p style="font-size: 12px; margin-bottom: -20px; color: #099F6A; font-weight: 900;">Learn More</p>
                <p style="margin-bottom: 0px; color: #099F6A;">___________</p>
            </div>
        </ul>
    </div>
<?php
if (!empty($usersData)) {
    $profilePic = $usersData[0]['profilePic'];
    $fname = $usersData[0]['fname'];
    $lname = $usersData[0]['lname'];
    $phoneNum = $usersData[0]['phoneNum'];
    $email = $usersData[0]['email'];
    $user_id = $usersData[0]['user_id'];
    $gender = $usersData[0]['gender'];
    $roleName = $usersData[0]['role_code'];
}
$basicSalary = $userData->getBasicSalaryByRole($roleName); ?>
    <div class="right-container">
        <div class="head-content">
            <div class="Menu-name">
                <button class="btn px-1 py-0 open-btn d-md-none d-md-block"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                <p style="margin-bottom: 0; color: #099F6A">Daily Time Record</p>
            </div>
            <div class="usep-texthead">
                <img src="../../Icons/useplogo.png" alt="" width="30px" height="30px">
                <p style="margin-bottom: 0px; margin-top: 3px; margin-left: 10px; font-weight: 600;">UseP (Tagum Unit)</p>
            </div>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="row" >
                    <div class="col-12 col-md-7 mt-3">
                        <div class="content-1 p-1 searchbar">
                            <div class="input-with-icon col-md-7">
                                <img src="../../Icons/searchgreen.svg" alt="search" style="margin-left: 10px">
                                <input type="text" placeholder="Search here...">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mt-3">

                                    <div class="content-2 p-1 data">
                                    <div class="upperleft mt-4">
                                        <img src="../../Admin/admin_php/upload/<?php echo $profilePic ?>" alt="Profile Image" class="rounded-circle">
                                        <p class="name mt-4"></p>
                                        <p class="position mt-4"></p>
                                    </div>
                                    
                                    <div class="contactInfo mt-4">
                                        <p class="contactHead">Contact Information</p>
                                        <div class="contactValues">
                                            <p class="contactBody">Phone Number <span class="contactValue"><?php echo $phoneNum?></span></p>
                                            <p class="contactBody">Email <span class="contactValue2"><?php echo $email ?></span></p>
                                        </div>
                                    </div>
                                    <div>
                                        <hr>
                                    </div>
                                    <div class="contactInfo">
                                        <p class="contactHead">Work Information</p>
                                        <div class="contactValues">
                                            <p class="contactBody">Employee ID <span class="contactValueId"><?php echo $userID ?></span></p>
                                            <p class="contactBody">Position <span class="contactValueStaff"></span></p>
                                            <p class="contactBody">Basic Salary <span class="contactValueSalary"></span></p>
                                        </div>
                                    </div>
                                    <div>
                                        <hr>
                                    </div>
                                    <div class="contactInfo">
                                        <p class="contactHead">Personal Information</p>
                                        <div class="contactValues">
                                            <p class="contactBody">First Name <span class="contactValueFname"><?php echo $fname ?></span></p>
                                            <p class="contactBody">Last Name <span class="contactValueLname"><?php echo $lname?></span></p>
                                            <p class="contactBody">Gender <span class="contactValueGender"><?php echo $gender ?></span></p>
                                        </div>
                                    </div>
                                    <br>


                                </div>
                    </div>
                </div>
                <div class="row custom-height">
                    <div class="col-12 col-md-7 mt-3">
                        <div class="content-3 p-1 dtrdata">
                            <div class="col-12 table-container" style="overflow-y: auto; max-height: 500px;"> <!-- Adjust max-height as needed -->
                                <form id="pdfForm" method="post" action="pdf_dtr.php">
                                    <input type="text" hidden="hidden" value="<?php echo $userID?>" name="userID">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col" style="font-weight: bold;">Date</th>
                                            <th scope="col" style="font-weight: bold;">Status</th>
                                            <th scope="col" style="font-weight: bold;">Time in</th>
                                            <th scope="col" style="font-weight: bold;">Time out</th>
                                            <th scope="col" style="font-weight: bold;">Hours Worked</th>
                                            <th scope="col" style="font-weight: bold;">Overtime</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php

                                        $userdatadtr = $userDTRData->getEmpDtrData($userID);
                                        foreach ($userdatadtr as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $row['EMP_DTR_DATE'] . '</td>';
                                            echo '<td>' . $row['EMP_DTR_STATUS'] . '</td>';
                                            date_default_timezone_set('Asia/Manila');
                                            echo '<td>' . $row['EMP_DTR_TIME_IN_FORMATTED'] . '</td>';
                                            echo '<td>' . $row['EMP_DTR_TIME_OUT_FORMATTED'] . '</td>';
                                            echo '<td>' . $row['EMP_TOTAL_HOURS_WORKED'] . '</td>';
                                            echo '<td>' . $row['EMP_DTR_OVERTIME_HOURS'] . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <button type="submit" class="printDTR">PRINT DTR</button>
                                </form>
                            </div>

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../dtr-js/dtr.js"></script>
</body>
</html>