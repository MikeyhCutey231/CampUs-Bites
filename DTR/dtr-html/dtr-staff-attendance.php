<?php
    include("../functions/logModalHandler.php");

   $userid = $_SESSION['date'];
    

   if(isset($_POST['signout'])){
        echo "click";
        unset($_SESSION['date']);
        header("location: dtr-login.php");
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
                    <a href="" style="color: #099F6A"><?php echo $_SESSION['ovetime']?></a>
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

            <form action="" method="post">
                <li class="sidebar-items">
                    <button type="submit" name="signout" style="width: 100%; margin-top:10px; border: none;">
                        <a href="" class="sidebar-link">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.33428 17.1132H4.07464C3.64239 17.1132 3.22784 16.9415 2.92219 16.6358C2.61654 16.3302 2.44482 15.9156 2.44482 15.4834V4.07464C2.44482 3.64239 2.61654 3.22784 2.92219 2.92219C3.22784 2.61654 3.64239 2.44482 4.07464 2.44482H7.33428" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13.0386 13.8537L17.1131 9.77914L13.0386 5.70459" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17.1129 9.77881H7.33398" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p>Signout</p>
                        </a>
                    </button>
                </li>
            </form>

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

    <div class="right-container">
        <div class="head-content">
            <div class="Menu-name">
                <button class="btn px-1 py-0 open-btn d-md-none d-md-block"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                <p style="margin-bottom: 0; color: #099F6A">Daily Time Record</p>
            </div>
            <div class="usep-texthead">
                <img src="/Icons/useplogo.png" alt="" width="30px" height="30px">
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
                        <?php 
                            $printEmpData = "SELECT emp_personal_info.EMPLOYEE_ID, emp_personal_info.EMP_FIRST_NAME, emp_personal_info.EMP_MIDDLE_NAME,
                            emp_personal_info.EMP_LAST_NAME, emp_personal_info.EMP_GENDER, emp_personal_info.EMP_PHONE_NUM, emp_personal_info.EMP_EMAIL,
                            emp_personal_info.EMP_EMERGENCY_NUM, emp_job_info.EMP_POSITION, emp_job_info.EMP_BASIC_SALARY FROM emp_personal_info INNER JOIN
                            emp_job_info ON emp_personal_info.EMP_JOB_ID = emp_job_info.EMP_JOB_ID WHERE emp_personal_info.EMPLOYEE_ID = '$userid'";

                            $printEmpDatarun = mysqli_query($conn, $printEmpData);

                            while($row = mysqli_fetch_array($printEmpDatarun)){
                                ?>
                                    <div class="content-2 p-1 data">
                                    <div class="upperleft mt-4">
                                        <img src="../../Icons/cutemikey.svg" alt="Profile Image" class="rounded-circle">
                                        <p class="name mt-4"><?php echo $row['EMP_FIRST_NAME'] . " ". $row['EMP_MIDDLE_NAME'] . " ". $row['EMP_LAST_NAME'] ?></p>
                                        <p class="position mt-4"><?php echo $row['EMP_POSITION'] ?></p>
                                    </div>
                                    
                                    <div class="contactInfo mt-4">
                                        <p class="contactHead">Contact Information</p>
                                        <div class="contactValues">
                                            <p class="contactBody">Phone Number <span class="contactValue"><?php echo $row['EMP_PHONE_NUM'] ?></span></p>
                                            <p class="contactBody">Emergency Number <span class="contactValue1"><?php echo $row['EMP_EMERGENCY_NUM'] ?></span></p>
                                            <p class="contactBody">Email <span class="contactValue2"><?php echo $row['EMP_EMAIL'] ?></span></p>
                                        </div>
                                    </div>
                                    <div>
                                        <hr>
                                    </div>
                                    <div class="contactInfo">
                                        <p class="contactHead">Work Information</p>
                                        <div class="contactValues">
                                            <p class="contactBody">Employee ID <span class="contactValueId"><?php echo $row['EMPLOYEE_ID'] ?></span></p>
                                            <p class="contactBody">Position <span class="contactValueStaff"><?php echo $row['EMP_POSITION'] ?></span></p>
                                            <p class="contactBody">Basic Salary <span class="contactValueSalary"><?php echo $row['EMP_BASIC_SALARY'] ?></span></p>
                                        </div>
                                    </div>
                                    <div>
                                        <hr>
                                    </div>
                                    <div class="contactInfo">
                                        <p class="contactHead">Personal Information</p>
                                        <div class="contactValues">
                                            <p class="contactBody">First Name <span class="contactValueFname"><?php echo $row['EMP_FIRST_NAME'] ?></span></p>
                                            <p class="contactBody">Last Name <span class="contactValueLname"><?php echo $row['EMP_LAST_NAME'] ?></span></p>
                                            <p class="contactBody">Gender <span class="contactValueGender"><?php echo $row['EMP_GENDER'] ?></span></p>
                                        </div>
                                    </div>
                                    <br>


                                </div>

                                <?PHP
                            }

                        ?>
                    </div>
                </div>
                <div class="row custom-height">
                    <div class="col-12 col-md-7 mt-3">
                        <div class="content-3 p-1 dtrdata">
                        <div class="col-12 table-container">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col" style="font-weight: bold;">Date</th>
                                    <th scope="col" style="font-weight: bold;">Status</th>
                                    <th scope="col" style=" font-weight: bold;">Time in</th>
                                    <th scope="col" style="font-weight: bold;">Time out</th>
                                    <th scope="col" style="font-weight: bold;">Overtime</th>

                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $printData = "SELECT * FROM emp_dtr WHERE EMPLOYEE_ID  = '$userid'";
                                        $printDatarun = mysqli_query($conn, $printData);
                                        while ($row = mysqli_fetch_array($printDatarun)){
                                            ?>
                                                <tr>
                                                    <th scope="row" class="date">
                                                        <?php echo $row['EMP_DTR_DATE'] ?>
                                                    </th>
                                                    <td class="stat"><?php echo $row['EMP_DTR_STATUS'] ?></td>
                                                    <td><?php echo $row['EMP_DTR_TIME_IN'] ?></td>
                                                    <td><?php echo $row['EMP_DTR_TIME_OUT'] ?></td>
                                                    <td><?php echo $row['EMP_DTR_OVERTIME'] ?></td>
                                
                                                </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
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