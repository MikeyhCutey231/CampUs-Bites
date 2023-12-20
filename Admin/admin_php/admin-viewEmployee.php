<?php

require '../../Admin/functions/UserData.php';

$loggedUser = $_SESSION['USER_ID'];
$userDataInstance = new UserData();
$usersData = $userDataInstance->getUserData($loggedUser);

if (!isset($_SESSION['USER_ID'])) {
    header("Location: admin-login.php");
    exit();
}

?>

<?php
require '../../Admin/functions/LiveSearchFunction.php';
$customerTableSorter = new TableSorter('#searchresult', '.filterDropdown');
$customerTableSorter->addSortingScript();
$searchHandler = new SearchHandler();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $searchHandler->performSearch($searchTerm);
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>CampUs Bites</title>
    <link rel="stylesheet" href="../../Admin/admin_css/admin-viewemployee.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        // Logout after 15 minutes of inactivity (for testing purposes)
        var inactivityTime = 5 * 60 * 1000;
        var logoutTimer;

        function resetTimer() {
            clearTimeout(logoutTimer);
            logoutTimer = setTimeout(logout, inactivityTime);
        }

        function logout() {
            // Clear session or perform other logout actions

            // Use replaceState to replace the current URL with the logout.php URL
            history.replaceState(null, null, 'logout.php');

            // Redirect to the logout page
            window.location.href = 'logout.php';
        }

        $(document).ready(function () {
            // Attach events to reset the timer when there is user activity
            $(document).on('mousemove keypress', resetTimer);

            // Initial setup of the timer
            resetTimer();
        });
    </script>
</head>
<body>
    <div class="wrapper">

        <?php include 'sidebar.php'; ?>

        <div class="right-container">
            <div class="head-content">
                <div class="Menu-name">
                    <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                    <p style="margin-bottom: 0;">View Employee</p>
                </div>
                <!-- Main Search Bar -->
                <div class="head-searchbar">
                    <img src="../../Icons/search.svg" alt="" width="20px" style="margin-top: 12px;">
                    <form method="post">
                        <input type="text" name="search" placeholder="Search here...">
                    </form>
                </div>
                <div class="usep-texthead">
                    <img src="../../Icons/useplogo.png" alt="" width="30px" height="30px">
                    <p style="margin-bottom: 0px; margin-top: 3px; margin-left: 10px; font-weight: 600;">UseP (Tagum Unit)</p>
                </div>
                <a href="admin-profile.php" style="color: black;">
                    <div class="user-profile">
                        <div class="admin-profile">
                            <?php
                            if (!empty($usersData)) {
                                $profilePic = $usersData[0]['profilePic'];
                                echo '<img src="../../Icons/' . $profilePic . '" style="height: 38px; width: 40px; border-radius: 5px; object-fit: cover;">';
                            }
                            ?>
                        </div>
                        <div class="admin-detail">
                            <?php
                            if (!empty($usersData)) {
                                $adminUsername = $usersData[0]['username'];
                                echo '<p style="margin-bottom: 0px; font-weight: 700;">' . $adminUsername . '</p>';

                                require_once '../../Admin/functions/user.php';
                                $userDataManager = new User();
                                if ($userDataManager->isManager()) {
                                    echo '<p style="margin-bottom: 0px; font-size: 6px; background-color: #9C1421; color: white; padding: 3px; border-radius: 3px;">Manager</p>';
                                } else {
                                    echo '<p style="margin-bottom: 0px; font-size: 6px; background-color: #9C1421; color: white; padding: 3px; border-radius: 3px;">Administrator</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php
            if (isset($_GET['employee_id'])) {
                $employee_id = $_GET['employee_id'];

                $employeeData = $userDataInstance->getEmployeeData($employee_id);

                $targetEmployee = null;
                foreach ($employeeData as $employee) {
                    if ($employee['employee_id'] == $employee_id) {
                        $targetEmployee = $employee;
                        break;
                    }
                }

                if ($targetEmployee) {
                    $fullname = $targetEmployee['employee_fname'];

                    if (!empty($targetEmployee['employee_mname'])) {
                        $middleInitial = $targetEmployee['employee_mname'][0] . '.';
                        $fullname .= ' ' . $middleInitial;
                    }

                    $fullname .= ' ' . $targetEmployee['employee_lname'];

                    echo '<h5 class="settingUsername">' . $fullname . '</h5>';

                    $roleCode = $targetEmployee['role_code'];

                    $jobInfo = $userDataInstance->getPositionByRoleCode($roleCode);

                } else {
                    echo 'Employee not found.';
                }
            } else {
                echo 'Employee ID not provided in the URL.';
            }

            ?>

            <?php $roleName = $jobInfo;
            $basicSalary = $userDataInstance->getBasicSalaryByRole($roleName); ?>
            <div class="main-content">
                <div class="userSettings-container">
                    <?php
                    echo '<img src="../../Icons/' . $targetEmployee['employee_profPic'] . '" class="userProfile" ">';
                    ?>
                    <h5 class="settingUsername"><?php echo $fullname ?></h5>
                    <p class="userJob"><?php echo $roleName ?></p>

                    <button class="edit-info" data-bs-toggle="modal" data-bs-target="#editInfo"><img src="../../Icons/edit-3.svg" alt="" style="padding-right: 10px; margin-top: -3px;">Edit Info</button>

                    <?php
                    if ($targetEmployee['employee_acc_status'] == 'Active') {
                        $buttonLabel = 'Disable Account';
                        $buttonClass = 'disable-account';
                        $buttonDataTarget = '#disableAccount';
                        $buttonColor = '';
                    } else {
                        $buttonLabel = 'Enable Account';
                        $buttonClass = 'enable-account';
                        $buttonDataTarget = '#enableAccount';
                        $buttonColor = 'red';
                    }
                    ?>

                    <button class="<?php echo $buttonClass; ?>"
                            data-bs-toggle="modal"
                            data-bs-target="<?php echo $buttonDataTarget; ?>"
                            style="background-color: <?php echo $buttonColor; ?>">
                        <?php echo $buttonLabel; ?>
                    </button>

                    <button class="changepass" onclick="goBack()">Go Back</button>
                </div>
                <div class="userInformation-container">
                    <div class="personalInformation-container">
                        <div class="pInformation-head">
                           <h4>Personal Information</h4>
                        </div>
                        <div class="pInformation-content">
                            <div class="cardDetails">
                                <div class="userFname">
                                    <p style="margin-right: 40px;">First Name</p>
                                    <p style="font-weight: bold;"><?php echo $targetEmployee['employee_fname']?></p>
                                </div>

                                <div class="userGender">
                                    <p class="gender" style="margin-right: 68px;">Gender</p>
                                    <p style="font-weight: bold;"><?php echo $targetEmployee['employee_gender']?></p>
                                </div>

                            </div>

                            <div class="cardDetails">
                                <div class="userMname">
                                    <p style="margin-right: 28px;">Middle Name</p>
                                    <p style="font-weight: bold;"><?php echo $targetEmployee['employee_mname']?></p>
                                </div>

                                <div class="userPhonenum">
                                    <p style="margin-right: 23px;">Phone Number</p>
                                    <p style="font-weight: bold;"><?php echo $targetEmployee['employee_phoneNum'] ?></p>
                                </div>
                            </div>

                            <div class="cardDetails">
                                <div class="userLname">
                                    <p class="lname" style="margin-right: 47px;">Last Name</p>
                                    <p style="font-weight: bold;"><?php echo $targetEmployee['employee_lname']?></p>
                                </div>

                                <div class="userEmail">
                                    <p class="emails" style="margin-right: 83px;">Email</p>
                                    <p class="realusermail" style="font-weight: bold;"><?php echo $targetEmployee['employee_email']?></p>
                                </div>
                            </div>


                            <div class="cardDetails">
                                <div class="userSuffix">
                                    <p class="suffix" style="margin-right: 78px;">Suffix</p>
                                    <p style="font-weight: bold;"><?php echo $targetEmployee['employee_suffix']?></p>
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
                                <p style="margin-right: 54px;">Employee Id</p>
                                <p style="font-weight: bold;"><?php echo $targetEmployee['employee_id']?></p>
                            </div>

                            <div class="userPosition">
                                <p class="staffposition" style="margin-right: 166px;">Position</p>
                                <p  style="font-weight: bold;"><?php echo $roleName?></p>
                            </div>
                        </div>

                        <div class="cardDetails">

                            <div class="userBasicSalary">
                                <p class="basicsalary" style="margin-right: 10px;">Basic Salary per day</p>
                                <p  style="font-weight: bold;">₱ <?php echo $basicSalary ?> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

      <!-- Modal -->
    <div class="modal fade" id="editInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="../../Admin/functions/update_employee.php" method="POST" enctype="multipart/form-data">
                        <input type="text" name="userid" value="<?php echo $userid=$usersData[0]['user_id']; ?>" hidden="hidden">
                        <input type="hidden" name="employee_id" value="<?php echo $targetEmployee['employee_id']?>">
                        <input type="hidden" name="username" value="<?php echo $targetEmployee['username']?>">
                        <div class="editLeft-container">
                            <div class="editLeft-container">
                                <div class="image-container">
                                    <img id="image-preview" src="<?php echo '../../Icons/' . $targetEmployee['employee_profPic']; ?>" alt="" style="width:100%;height:100%;overflow:hidden" >

                                </div>
                                <input type="file" value="" id="uploadBtn" class="uploadBtn" name="image[]" accept="image/*" onchange="previewImage(event)">
                                <label for="uploadBtn" style=" width: 205px;">Choose File</label>
                            </div>
                        </div>

                        <div class="editRight-container">
                            <div class="modalPersonalInfo">
                                <h4>Personal Information</h4>
                            </div>

                            <div class="modalFirstRowInfo">
                                <div class="modalLname">
                                    <p>* Last Name</p>
                                    <input type="text" name="lastName" id="lastName" placeholder="<?php echo $targetEmployee['employee_lname'] ?>" >
                                </div>

                                <div class="modalFname">
                                    <p>* First Name</p>
                                    <input type="text" name="firstName" placeholder="<?php echo $targetEmployee['employee_fname']?>" >
                                </div>

                                <div class="modalMname">
                                    <p>* Middle Name</p>
                                    <input type="text" name="middleName" placeholder="<?php echo $targetEmployee['employee_mname']?>" >
                                </div>

                                <div class="modalSuffix">
                                    <p>* Suffix</p>
                                    <select name="suffix">
                                        <option value=" " <?php if ($targetEmployee['employee_suffix'] == " ") echo 'selected'; ?>></option>
                                        <option value="I" <?php if ($targetEmployee['employee_suffix'] == "I") echo 'selected'; ?>>I</option>
                                        <option value="II" <?php if ($targetEmployee['employee_suffix'] == "II") echo 'selected'; ?>>II</option>
                                        <option value="III" <?php if ($targetEmployee['employee_suffix'] == "III") echo 'selected'; ?>>III</option>
                                        <option value="IV" <?php if ($targetEmployee['employee_suffix'] == "IV") echo 'selected'; ?>>IV</option>
                                        <option value="J.D" <?php if ($targetEmployee['employee_suffix'] == "J.D") echo 'selected'; ?>>J.D</option>
                                        <option value="Jr" <?php if ($targetEmployee['employee_suffix'] == "Jr") echo 'selected'; ?>>Jr.</option>
                                        <option value="Sr" <?php if ($targetEmployee['employee_suffix'] == "Sr") echo 'selected'; ?>>Sr.</option>
                                    </select>
                                </div>
                            </div>

                            <div class="modalSecondRowInfo">
                                <div class="modalGender">
                                    <p>* Gender</p>
                                    <select id="gender" name="gender">
                                        <option value="Male" <?php if ($targetEmployee['employee_gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                        <option value="Female" <?php if ($targetEmployee['employee_gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                        <option value="Others" <?php if ($targetEmployee['employee_gender'] == 'Others') echo 'selected'; ?>>Others</option>
                                    </select>
                                </div>

                                <div class="modalPhonenumber">
                                    <p>* Phone Number</p>
                                    <input id="numericInput" name="phone_number" placeholder="<?php echo $targetEmployee['employee_phoneNum']?>" type="text"  minlength="11" maxlength="11">
                                </div>

                                <div class="modalEmail">
                                    <p>* Email</p>
                                    <input type="email" name="email" placeholder="<?php echo $targetEmployee['employee_email']?>" >
                                </div>

                            </div>

                            <div class="modalFifthRowInfo">
                                <h4>Job Information</h4>
                            </div>
                            <div class="modalSixRowInfo">
                                <div class="modalPosition">
                                    <label for="position">* Position</label><br>
                                    <select id="position" name="position" required>
                                        <option value="emp_cshr" <?php if ($targetEmployee['role_code'] == 'emp_cshr') echo 'selected'; ?>>Cashier</option>
                                        <option value="emp_cook" <?php if ($targetEmployee['role_code'] == 'emp_cook') echo 'selected'; ?>>Cook</option>
                                        <option value="emp_asst_cook" <?php if ($targetEmployee['role_code'] == 'emp_asst_cook') echo 'selected'; ?>>Assistant Cook</option>
                                        <option value="cour" <?php if ($targetEmployee['role_code'] == 'cour') echo 'selected'; ?>>Courier</option>
                                        <option value="emp_srvr" <?php if ($targetEmployee['role_code'] == 'emp_srvr') echo 'selected'; ?>>Server</option>
                                    </select>
                                </div>


                                <div class="modalBasicSalary">
                                    <p>* Basic Salary</p>
                                    <input type="text" name="basic_salary" id="numericInput2" placeholder="<?php echo $basicSalary ?>" onblur="formatNumber(this)" >
                                    <div class="dollarsign">
                                        <p>₱</p>
                                    </div>
                                </div>
                            </div>



                            <div class="modalEigth">
                                <input type="submit"  value="Update Profile" class="modalUpdate">
                                <button class="closeModalProfile" data-bs-dismiss="modal" type="button">Close</button>
                            </div>
                        </div>
                    </form>
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
                <p>By disabling your account you will no longer have access of this.</p>

                <div class="modalDisablebtn">
                    <button
                            id="disableButton"
                            data-status="Deactivated";
                            style="background-color: #9C1421; border: none; box-shadow: 0px 0px 10px rgba(0, 0, 0, 25%); color: white;"
                    >
                        Disable
                    </button>
                    <button style="background-color: white; border: none; color: black;  box-shadow: 0px 0px 10px rgba(0, 0, 0, 25%);" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade" id="enableAccount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h6>Are you sure you want to enable your account?</h6>
                    <p style="margin-top: 3px;">By enabling this account, this user can now access his or her account.</p>

                    <div class="modalEnablebtn">
                        <button
                                id="enableButton"
                                data-status = "Active";
                        >
                            Enable
                        </button>
                        <button class="noButton" style="background-color: white; border: none; color: black;  box-shadow: 0px 0px 10px rgba(0, 0, 0, 25%);" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        document.getElementById('disableButton').addEventListener('click', function () {
            const employeeId = <?php echo $targetEmployee['employee_id']; ?>;
            const username = '<?php echo $targetEmployee['username']; ?>';
            const adminId = <?php echo $usersData[0]['user_id']; ?>;
            const status = this.getAttribute('data-status');
console.log(status)
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'updateStatus.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = xhr.responseText;
                    if (response === 'success') {
                        const statusElement = document.querySelector('#enableButton');
                        console.log(statusElement)
                        statusElement.textContent = status;
                        statusElement.style.color = status === 'Deactivated' ? 'red' : 'green';
                        console.log('naabot diri ang code')
                        location.reload();
                    } else {
                        alert('Error updating status: ' + response);
                    }
                }
            };
            xhr.send('employeeId=' + employeeId + '&status=' + status + '&adminId=' + adminId + '&username=' + username);
        });

        document.getElementById('enableButton').addEventListener('click', function () {
            var employeeId = <?php echo $targetEmployee['employee_id']; ?>;
            const username = '<?php echo $targetEmployee['username']; ?>';
            const adminId = <?php echo $usersData[0]['user_id']; ?>;
            var status = this.getAttribute('data-status');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'updateStatus.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = xhr.responseText;
                    if (response === 'success') {
                        var statusElement = document.querySelector('#disableButton');
                        statusElement.textContent = status;
                        statusElement.style.color = status === 'Active' ? 'red' : 'green';
                        location.reload();
                    } else {
                        alert('Error updating status: ' + response);
                    }
                }
            };
            xhr.send('employeeId=' + employeeId + '&status=' + status + '&adminId=' + adminId + '&username=' + username);
        });

    </script>




    <!-- Javascript -->
    <script src="../../Admin/admin_js/admin.js"></script>
    <script>
          function goBack() {
            window.history.back();
        }
          document.addEventListener("DOMContentLoaded", function () {
              var urlParams = new URLSearchParams(window.location.search);
              var employeeName = urlParams.get("employee_name");

              var settingUsername = document.querySelector(".settingUsername");
              if (settingUsername) {
                  settingUsername.textContent = employeeName;
              }
          });
          function previewImage(event) {
              const input = event.target;
              const preview = document.getElementById('image-preview');
              const container = document.getElementById('image-container');

              const file = input.files[0];
              const reader = new FileReader();

              reader.onloadend = function () {
                  preview.src = reader.result;
                  container.style.display = 'block';
              }

              if (file) {
                  reader.readAsDataURL(file);
              } else {
                  preview.src = '';
                  container.style.display = 'none';
              }
          }
    </script>
</body>
</html>