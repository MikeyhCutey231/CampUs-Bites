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
    <link rel="stylesheet" href="../../Admin/admin_css/admin-profile.css">
</head>
<body>
    <div class="wrapper">

        <?php include 'sidebar.php'; ?>

        <div class="right-container">
            <div class="head-content">
                <div class="Menu-name">
                    <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                    <p style="margin-bottom: 0;">Profile</p>
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
                                $adminEmail = $usersData[0]['email'];
                                $adminID = $usersData[0]['user_id'];
                                $adminPhonenum = $usersData[0]['phoneNum'];
                                $adminFullname = $usersData[0]['fullname'];
                                $userrole = $usersData[0]['userRole'];
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
            <div class="container-fluid col-sm-12 main-content">
                <div class="row g-lg-2  justify-content-between">

                    <!--profile-->
                    <div class="col-lg-3">
                        <div class="px-4 py-5 border profile">
                            <div class="mb-2 text-center admin-picture" style="object-fit: contain">
                                <form id="profileForm" action="../../Admin/functions/update_profile_picture.php" method="post" enctype="multipart/form-data">
                                    <div class="text-center admin-selectPic">
                                        <img id="profileImg" src="../../Icons/<?php echo $profilePic; ?>" style="height: 175px; width: 175px; border-radius: 4px; object-fit: cover;">
                                    </div>
                                    <div class="text-center admin-name">
                                        <h5><b><?php echo "$adminFullname"; ?></b></h5>
                                    </div>
                                    <?php
                                    if ($userDataManager->isManager()) {
                                        echo '<div class="text-center admin-position">Manager</div>';
                                    } else {
                                        echo '<div class="text-center admin-position">Administrator</div>';
                                    }
                                    ?>

                                    <div class="text-center admin-selectPic">
                                        <input type="file" id="uploadBtn" name="profilePic[]" accept="image/*" onchange="previewImage()">
                                        <input type="text" name="adminID" value="<?php echo $adminID; ?>" hidden="hidden">
                                        <label for="uploadBtn">Choose File</label>
                                    </div>
                                </form>
                                <script>
                                    function previewImage() {
                                        var input = document.getElementById('uploadBtn');
                                        var img = document.getElementById('profileImg');
                                        var reader = new FileReader();

                                        reader.onload = function (e) {
                                            img.src = e.target.result;
                                        };

                                        reader.readAsDataURL(input.files[0]);
                                        updateProfilePicture();
                                    }

                                    function updateProfilePicture() {
                                        var form = document.getElementById('profileForm');
                                        var formData = new FormData(form);

                                        fetch(form.action, {
                                            method: 'POST',
                                            body: formData
                                        })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.status === 'success') {
                                                    location.reload();
                                                    console.log(data.message);
                                                } else {
                                                    console.error(data.message);
                                                }
                                            })
                                            .catch(error => console.error('Fetch error:', error));
                                    }
                                </script>

                                <div class="text-center admin-backButton">
                                    <a href="admin-dashboard.php"><button class="btn back-btn">Back</button></a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <!--profile details-->
                    <div class=" col-lg-9 ">
                        <div class="container-fluid g-4  border profile-deets">
                            <div class="row py-3 px-4 d-flex justify-content-between" style="border-bottom: 1px solid black">
                                <div class="col-lg-6 col-12  px-3 d-flex align-items-center acc-info">
                                   Account Info
                                </div>
                                <div class="col-lg-3 ml-auto justify-content-center d-flex align-items-center">
                                    <button type="button" class="btn justify-content-center align-items-center  edit-profile" data-bs-toggle="modal" data-bs-target="#editprofile">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#9C1421" class="bi bi-pen custom-icon" viewBox="0 0 16 16">
                                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
                                        </svg>

                                        &nbsp; Edit Profile
                                    </button>
                                </div>


                                <div class="col-lg-3 px-l-3 px-l-2 justify-content-center d-flex align-items-center">
                                    <button type="button" class="btn justify-content-center align-items-center  edit-password"  data-bs-toggle="modal" data-bs-target="#editpassword">
                                        <svg xmlns="http://www.w3.org/2000/svg"  width="14" height="14" fill="#9C1421" class=" bi bi-pen custom-icon" viewBox="0 0 16 16">
                                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
                                        </svg>
                                        &nbsp; Edit Password
                                    </button>
                                </div>

                            </div>
                            <div class="row py-4 px-5 justify-content-between">
                               <form>
                                <div class="mb-2 mt-2">
                                    <label for="username" class="form-label"><span style="color:#9C1421">*</span>Username</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                            </svg>
                                        </span>
                                        <input type="input" class="form-control" id="username" placeholder="<?php echo $adminUsername ?>" disabled>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label for="email" class="form-label"><span style="color:#9C1421">*</span>Email</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                            </svg>
                                        </span>
                                        <input type="input" class="form-control" id="email" placeholder="<?php echo $adminEmail?>" disabled>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label for="password" class="form-label"><span style="color:#9C1421">*</span>Password</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                                <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                            </svg>
                                        </span>
                                        <input type="password" class="form-control" id="password" placeholder="••••••••" disabled>
                                    </div>

                                </div>

                                <div class="mb-2">
                                    <label for="phoneNum" class="form-label"><span style="color:#9C1421">*</span>Phone Number</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                            </svg>
                                        </span>
                                        <input type="input" class="form-control" id="phoneNum" placeholder="<?php echo $adminPhonenum ?>" disabled>
                                    </div>

                                </div>
                            </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
<!----modals-->
    <form method="post" action="../../Admin/functions/update-profile.php">
        <div class="modal fade" id="editprofile" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><b>Edit Profile</b></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2 mt-2">
                            <label for="username" class="form-label"><span style="color:#9C1421">*</span>Username</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            </svg>
                        </span>
                                <input type="input" class="form-control" name="username" placeholder="<?php echo $adminUsername;  ?>">
                            </div>
                        </div>

                        <div class="mb-2 mt-2">
                            <label for="username" class="form-label"><span style="color:#9C1421">*</span>Full Name</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            </svg>
                        </span>
                                <input type="input" class="form-control" name="fullname" placeholder="<?php echo $adminFullname;  ?>">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="email" class="form-label"><span style="color:#9C1421">*</span>Email</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                            </svg>
                        </span>
                                <input type="input" class="form-control" name="email" placeholder="<?php echo $adminEmail;  ?>">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="phoneNum" class="form-label"><span style="color:#9C1421">*</span>Phone Number</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                            </svg>
                        </span>
                                <input type="input" class="form-control" name="phoneNum" placeholder="<?php echo $adminPhonenum;  ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn  btn-save">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--Modal for edit password-->
    <form method="post" action="">
        <div class="modal fade" id="editpassword" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><b>Edit Password</b></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div style="color: #727272; margin-bottom: -10px;">
                            <p style="font-size: 14px; font-weight: bold;">In order to protect your account. make sure your password</b></p>
                            <ul style="font-size: 12px; margin-top: -10px;">
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
                                <input type="input" id="adminID" hidden="hidden" value="<?php echo $adminID ?>" name="userID">
                                <input type="input" class="form-control" id="confirmPassword" placeholder="Confirm password" name="confirmPassword">
                            </div>
                        </div>
                    </div>

                    <div class="error error-message">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn  btn-save" id="saveChangesButton">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Javascript -->
    <script src="/Admin/admin_js/admin.js"></script>

    <script>
        $(document).ready(function () {
            $("#saveChangesButton").click(function () {
                const adminID = $("#adminID").val();
                const currentPassword = $("#currentPassword").val();
                const newPassword = $("#newPassword").val();
                const confirmPassword = $("#confirmPassword").val();
                const errorElement = $(".error");

                // Your AJAX request goes here
                $.ajax({
                    type: "POST",
                    url: "../../Admin/functions/update-password.php",
                    data: { userID:adminID, currentPassword: currentPassword, newPassword: newPassword, confirmPassword: confirmPassword },
                    success: function (response) {
                        errorElement.css({
                            color: "rgb(223, 59, 59)",
                            fontSize: "14px",
                            marginTop: "-5px",
                            marginBottom: "5px !important"
                        });

                        if (currentPassword.trim() === "" || newPassword.trim() === "" || confirmPassword.trim() === "") {
                            errorElement.text("Please fill in all the required fields.");
                        } else if (response === "Data changed") {
                            window.location.href = "admin-profile.php";
                        } else {
                            errorElement.show();
                            errorElement.text(response);
                        }
                    }
                });
            });
        });

    </script>
</body>
</html>