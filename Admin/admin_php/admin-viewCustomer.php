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

    <title>CampUs Bites</title>
    <link rel="stylesheet" href="../../Admin/admin_css/admin-viewcustomer.css">
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
                    <p style="margin-bottom: 0;">View Customer</p>
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
            if (isset($_GET['customer_id'])) {
                $customer_id = $_GET['customer_id'];

                $customerData = $userDataInstance->getCustomerData();

                $targetCustomer = null;
                foreach ($customerData as $customer) {
                    if ($customer['customer_id'] == $customer_id) {
                        $targetCustomer = $customer;
                        break;
                    }
                }

                if ($targetCustomer) {
                    $fullname = $targetCustomer['customer_fname'];

                    if (!empty($targetCustomer['customer_mname'])) {
                        $middleInitial = $targetCustomer['customer_mname'][0] . '.';
                        $fullname .= ' ' . $middleInitial;
                    }

                    $fullname .= ' ' . $targetCustomer['customer_lname'];


                } else {
                    echo 'Customer not found.';
                }
            } else {
                echo 'Customer ID not provided in the URL.';
            }

            ?>
            <div class="main-content">
                <div class="userSettings-container">
                    <?php
                    echo '<img src="../../Icons/' . $targetCustomer['customer_profPic'] . '" class="userProfile" ">';
                    ?>
                    <h5 class="settingUsername"><?php echo $fullname ?></h5>
                    <p class="userJob">Customer</p>
                    <?php

                    $adminAccounts = $userDataInstance->getAdminAccounts();
                    $isCurrentUser = ($usersData[0]['user_id'] == $targetCustomer['customer_id']);
                    $isAdmin = false;

                    if (count($adminAccounts) > 0 && $adminAccounts[0]['user_id'] == $targetCustomer['customer_id']) {
                        $isAdmin = true;
                    }

                    if (!$isAdmin && !$isCurrentUser) {
                        if ($targetCustomer['customer_status'] == 'Active') {
                            $buttonLabel = 'Disable Account';
                            $buttonClass = 'disable-account';
                            $buttonDataTarget = '#disableAccount';
                            $buttonColor = '';
                        } else {
                            $buttonLabel = 'Enable Account';
                            $buttonClass = 'enable-account';
                            $buttonDataTarget = '#enableAccount';
                            $buttonColor = '';
                        }
                        ?>
                        <button class="<?php echo $buttonClass; ?>"
                                data-bs-toggle="modal"
                                data-bs-target="<?php echo $buttonDataTarget; ?>"
                                style="background-color: <?php echo $buttonColor; ?>">
                            <?php echo $buttonLabel; ?>
                        </button>
                    <?php } ?>


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
                                    <p class="fname" style="margin-right: 43px;">First Name</p>
                                    <p style="font-weight: bold;"><?php echo $targetCustomer['customer_fname']?></p>
                                </div>

                                <div class="userPhonenum">
                                    <p class="phNumber"  style="margin-right: 42px;">Phone Number</p>
                                    <p  style="font-weight: bold;"><?php echo $targetCustomer['customer_phoneNum']?></p>
                                </div>
                            </div>

                            <div class="cardDetails">
                                <div class="userMname">
                                    <p style="margin-right: 28px;">Middle Name</p>
                                    <p style="font-weight: bold;"><?php echo $targetCustomer['customer_mname']?></p>
                                </div>

                                <div class="userEmail">
                                    <p class="emails" style="margin-right: 100px;">Email</p>
                                    <p  class="realusermail" style="font-weight: bold;"><?php echo $targetCustomer['customer_email']?></p>
                                </div>
                            </div>

                            <div class="cardDetails">
                                <div class="userLname">
                                    <p class="lname" style="margin-right: 47px;">Last Name</p>
                                    <p style="font-weight: bold;"><?php echo $targetCustomer['customer_lname']?></p>
                                </div>

                                <div class="userAddress">
                                    <p class="addresses" style="margin-right: 84px;">Address</p>
                                    <p class="realaddresstext" style="font-weight: bold;"><?php echo $targetCustomer['customer_schoolLandmark']?></p>
                                </div>
                            </div>


                            <div class="cardDetails">
                                <div class="userSuffix">
                                    <p class="suffix" style="margin-right: 78px;">Suffix</p>
                                    <p style="font-weight: bold;"><?php echo $targetCustomer['customer_suffix']?></p>
                                </div>

                            </div>

                            <div class="cardDetails">
                                <div class="userGender">
                                    <p class="gender" style="margin-right: 68px;">Gender</p>
                                    <p style="font-weight: bold;"><?php echo $targetCustomer['customer_gender']?></p>
                                </div>
                            </div>
                          
                  
                        </div>
                    </div>
                    <div class="workInformation-container">
                        <div class="workInformation-head">
                            <h4>Customer Information</h4>
                        </div>

                        <div class="cardDetails" style="margin-top: 10px;">
                            <div class="userStudentID">
                                <p style="margin-right: 48px;">Student ID Number</p>
                                <p style="font-weight: bold;"><?php echo $targetCustomer['customer_id']?></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <p style="margin-top: 5px;">By enabling this account, this user can now access his or her account.</p>

                    <div class="modalEnablebtn">
                        <button
                                id="enableButton"
                                data-status = "Active";
                                style="background-color: #3DC53A; border: none; box-shadow: 0px 0px 10px rgba(0, 0, 0, 25%); color: white;"
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
            const customerId = <?php echo $targetCustomer['customer_id']; ?>;
            const customer_username = '<?php echo $targetCustomer['username']; ?>';
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
            xhr.send('customerId=' + customerId + '&status=' + status + '&adminId=' + adminId + '&customer_username=' + customer_username);
        });

        document.getElementById('enableButton').addEventListener('click', function () {
            const customerId = <?php echo $targetCustomer['customer_id']; ?>;
            const customer_username = '<?php echo $targetCustomer['username']; ?>';
            const adminId = <?php echo $usersData[0]['user_id']; ?>;
            const status = this.getAttribute('data-status');

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
            xhr.send('customerId=' + customerId + '&status=' + status + '&adminId=' + adminId + '&customer_username=' + customer_username);
        });

    </script>

    <!-- Javascript -->
    <script src="../../Admin/admin_js/admin.js"></script>
    <script>
          function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>