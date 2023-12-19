<?php
require '../../Admin/functions/UserData.php';


$loggedUser = $_SESSION['Customer_ID'];
$userDataInstance = new UserData();
$usersData = $userDataInstance->getUserData($loggedUser);

if (!isset($_SESSION['Customer_ID'])) {
    header("Location: admin-login.php");
    exit();
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

    <title>Campus Bites</title>
    <link rel="stylesheet" href="../customer_css/customer-profile.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'customer_navbar_nosearch.php' ?>

        <div class="main-content ">
                 <!-- sub navbar -->
            <div class="container-fluid d-flex p-0 profile-menu mb-md-3 mb-2 ">
                <a href="customer-profile.php" class="p-0">
                    <button class="btn py-md-1 p-0 d-flex  align-items-center justify-content-center btn-profile" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="white" class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                        </svg>
                        My Profile
                    </button>
                </a>

                <a href="customer-mypurchases.php" class="p-0">
                    <button class="btn py-md-1 p-0 d-flex  align-items-center justify-content-center btn-notification" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#9C1421" class="bi bi-box2-heart-fill" viewBox="0 0 16 16">
                    <path d="M3.75 0a1 1 0 0 0-.8.4L.1 4.2a.5.5 0 0 0-.1.3V15a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V4.5a.5.5 0 0 0-.1-.3L13.05.4a1 1 0 0 0-.8-.4h-8.5ZM8.5 4h6l.5.667V5H1v-.333L1.5 4h6V1h1zM8 7.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132"/>
                    </svg>
                        Delivery
                    </button>
                </a>

                <a href="customer-notification.php" class="p-0">
                    <button class="btn py-md-1 p-0 d-flex  align-items-center justify-content-center btn-notification" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#9C1421" class="bi bi-box2-heart" viewBox="0 0 16 16">
                        <path d="M8 7.982C9.664 6.309 13.825 9.236 8 13 2.175 9.236 6.336 6.31 8 7.982"/>
                        <path d="M3.75 0a1 1 0 0 0-.8.4L.1 4.2a.5.5 0 0 0-.1.3V15a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V4.5a.5.5 0 0 0-.1-.3L13.05.4a1 1 0 0 0-.8-.4h-8.5Zm0 1H7.5v3h-6zM8.5 4V1h3.75l2.25 3zM15 5v10H1V5z"/>
                    </svg>
                        Pick-up
                    </button>
                </a>

            </div>
            <div class="profile-details mb-5 mb-sm-3 border">
                <!-- diri ibutang ang profile details -->
                <div class="container-fluid ">
                    <div class="row profile-deets " >
                        <div class="col-lg-2 col-md-3 col-12 mb-2 mb-md-0 p-0">
                                <div class="px-md-4 py-md-4 px-sm-3 py-sm-3 py-3  myprofile ">


                                <form id="profileForm" action="../functions/update_profile_pic.php" method="post" enctype="multipart/form-data">
                                        <div class="container d-flex p-0n justify-content-center">
                                            <?php
                                            if (!empty($usersData)) {
                                                $customerID = $usersData[0]['user_id'];
                                                $profilePic = $usersData[0]['profilePic'];
                                                $email = $usersData[0]['email'];
                                                $password = $usersData[0]['password'];
                                                $phoneNum = $usersData[0]['phoneNum'];
                                                $fullname = $usersData[0]['fullname'];
                                                $fname = $usersData[0]['fname'];
                                                $lname = $usersData[0]['lname'];
                                                $mname = $usersData[0]['mname'];
                                                $userRole = $usersData[0]['userRole'];
                                                $campusArea = $usersData[0]['campusArea'];
                                                $suffix = $usersData[0]['suffix'];
                                                $gender = $usersData[0]['gender'];
                                                $currentStatus = $usersData[0]['status'];
                                            }
                                            ?>
                                            <img id="profileImg" src="../userPics/<?php echo $profilePic ?>" alt="Profile Picture" style="height: 175px; width: 175px; border-radius: 4px; object-fit: cover;">
                                        </div>
                                        <div class="container d-flex p-0 justify-content-center admin-selectPic">
                                             <input type="file" accept="image/*" id="uploadBtn" name="profilePic[]" style="display: none;" onchange="previewImage()">
                                                <input type="text" name="userID" value="<?php echo $customerID; ?>" hidden="hidden">
                                                <label for="uploadBtn" class="btn py-md-1 p-0 d-flex align-items-center justify-content-center btn-file">                                                Choose File
                                                </label>
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

                                    <div class="container d-flex p-0 justify-content-center">
                                        <a href="customer-logout.php">
                                        <button class="btn py-md-1 p-0 d-flex  align-items-center justify-content-center btn-logout" type="button" >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z"/>
                                                <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                                            </svg>
                                            Log out
                                        </button>
                                        </a>
                                    </div>
                                </div>
                        </div>

                        <div class="col-lg-10 col-md-9 col-12 p-0">
                            <div class="profile-deets-con ">
                                <div class="container-fluid  py-2 py-sm-0 px-0 profile-deets-header">
                                    
                                    <div class="col-lg-5 col-sm-3 col-12 px-lg-4 py-lg-3 px-md-3 py-md-4 p-1 d-flex align-items-center personal-title">
                                        Personal Info
                                    </div> 

                                    <div class="col-lg-7 col-sm-9 col-12  px-lg-4 py-lg-3 p-md-3 px-sm-3 py-sm-3 px-2 d-flex mt-1 mt-sm-0  align-items-center prof-button-con">
                                        <button class="btn py-md-1 p-0 d-flex  align-items-center justify-content-center btn-editprofile" type="button" data-bs-toggle="modal" data-bs-target="#editprofile" >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                            </svg>  
                                            Edit Profile
                                        </button>
                                        <button class="btn py-md-1 p-0 d-flex  align-items-center justify-content-center  btn-editpassword" type="button" data-bs-toggle="modal" data-bs-target="#editpassword" >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                            </svg> 
                                            Edit Password
                                        </button>
                                        <button class="btn py-md-1 p-0 d-flex align-items-center justify-content-center btn-deactivate" type="button" data-bs-toggle="modal" data-bs-target="<?php echo ($currentStatus === 'Active') ? '#deactivate' : '#activate'; ?>">
                                            <?php
                                            if ($currentStatus === 'Active') {
                                                echo 'Deactivate';
                                            } else {
                                                echo 'Activate';
                                            }
                                            ?>
                                        </button>

                                        <script>
                                            document.querySelector('.btn-deactivate').addEventListener('click', function () {
                                                const currentStatus = "<?php echo $currentStatus ?>";
                                                const button = this;

                                                if (currentStatus === 'Active') {
                                                    // If the current status is Active, update the button text to 'Deactivate' and set data-bs-target to '#deactivate'
                                                    button.textContent = 'Deactivate';
                                                    button.setAttribute('data-bs-target', '#deactivate');
                                                } else {
                                                    // If the current status is Deactivated, update the button text to 'Activate' and set data-bs-target to '#activate'
                                                    button.textContent = 'Activate';
                                                    button.setAttribute('data-bs-target', '#activate');
                                                }
                                            });
                                        </script>

                                    </div>

                                    
                                </div>
                                <div class="container-fluid px-lg-4 py-lg-3 p-md-3 px-sm-3 py-sm-3 px-2 py-2 mb-3 mb-sm-0 d-block  justify-content-center">
                                  

                                    <div class="col-12 py-sm-2 justify-content-between d-block d-sm-flex">
                                        <div class="col-12 col-sm-6 d-flex ">
                                            <div class="col-6 px-2 py-1 deets-title">First Name:</div>
                                            <div class="col-6 px-2 py-1 deets-details"><?php echo $fname ?></div>
                                        </div>
                                        <div class="col-12 col-sm-6 d-flex">
                                            <div class="col-6 px-2 py-1 deets-title">Middle Name:</div> 
                                            <div class="col-6 px-2 py-1 deets-details"><?php echo $mname ?></div>
                                        </div>
                                    </div>

                                    <div class="col-12 py-sm-2 justify-content-between d-block d-sm-flex">
                                        <div class="col-12 col-sm-6 d-flex ">
                                            <div class="col-6 px-2 py-1 deets-title">Last Name:</div>
                                            <div class="col-6 px-2 py-1 deets-details"><?php echo $lname ?></div>
                                        </div>
                                        <div class="col-12 col-sm-6 d-flex">
                                            <div class="col-6 px-2 py-1 deets-title">Suffix:</div> 
                                            <div class="col-6 px-2 py-1 deets-details"><?php echo $suffix ?></div>
                                        </div>
                                    </div>

                                    <div class="col-12 py-sm-2 justify-content-between d-block d-sm-flex">
                                        <div class="col-12 col-sm-6 d-flex ">
                                            <div class="col-6 px-2 py-1 deets-title"> Email:</div>
                                            <div class="col-6 px-2 py-1 deets-details"><?php echo $email ?></div>
                                        </div>
                                        <div class="col-12 col-sm-6 d-flex">
                                            <div class="col-6 px-2 py-1 deets-title">Gender:</div> 
                                            <div class="col-6 px-2 py-1 deets-details"><?php echo $gender ?></div>
                                        </div>
                                    </div>

                                    <div class="col-12 py-sm-2 justify-content-between d-block d-sm-flex">
                                        <div class="col-12 col-sm-6 d-flex ">
                                            <div class="col-6 px-2 py-1 deets-title">Phone Number:</div>
                                            <div class="col-6 px-2 py-1 deets-details"><?php echo $phoneNum ?></div>
                                        </div>
                                        <div class="col-12 col-sm-6 d-flex">
                                            <div class="col-6 px-2 py-1 deets-title">Address inside the school:</div> 
                                            <div class="col-6 px-2 py-1  deets-details"><?php echo $campusArea ?></div>
                                        </div>
                                    </div>      
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-0 Other_ACC">
                <div class=" p-3 col-lg-12">
                    Other accounts
                </div>      
                   <div class="container-fluid d-flex p-0 profile-menu mb-md-3 mb-2 switchContainer">
                        <div class="d-flex align-items-center">
                            <div class="ml-2">
                                <img id="profileImg" src="../userPics/<?php echo $profilePic ?>" alt="Profile Picture" class="oth-pic" style="height: 40px; width: 40px; border: transparent; border-radius: 50%; object-fit: cover;">   
                            </div>

                            <div class="ml-2 mr-3 userDetails">
                                <div class="prof-name"><?php echo $fname ?></div>
                                <div class="prof-role"><?php echo $userRole ?></div>
                            </div>
                        </div>

                        <div class="ms-auto d-flex align-items-center">
                            <button class="btn btn-primary ACC-BTN">
                                Activate Courier Account
                            </button>    
                        </div>
                    </div>
                </div>
        
            </div> 
        
        


    </div> 

    <form method="post" action="../../Customer/functions/update-customer.php">
        <div class="modal fade " id="editprofile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered ">
                <input type="text" value="<?php echo $customer_id ?>" hidden="hidden" name="user_ID">
                <div class="modal-content edit-profile-modal">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><b>Edit Profile</b></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" >

                        <div class="mb-2 mt-2">
                            <label for="firstname" class="form-label"><span style="color:#9C1421">*</span>First name</label>
                            <div class="input-group justify-content-center mb-3">
                        <span class="input-group-text justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill align-items-center" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            </svg>
                        </span>
                                <input type="input" class="form-control" id="fname" name="fname" placeholder="<?php echo $fname ?>">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="middlename" class="form-label"><span style="color:#9C1421">*</span>Middle name</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            </svg>
                        </span>
                                <input type="input" class="form-control" id="mname" name="mname" placeholder="<?php echo $mname ?>">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="lastname" class="form-label"><span style="color:#9C1421">*</span>Last name</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            </svg>
                        </span>
                                <input type="input" class="form-control" id="lname" name="lname" placeholder="<?php echo $lname ?>">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="suffix" class="form-label"><span style="color:#9C1421">*</span>Suffix</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            </svg>
                        </span>
                                <input type="input" class="form-control" id="suffix" name="suffix" placeholder="<?php echo $suffix ?>">
                            </div>
                        </div>


                        <div class="mb-2">
                            <label for="email" class="form-label"><span style="color:#9C1421">*</span>Email</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                            </svg>
                        </span>
                                <input type="input" class="form-control" id="email" name="email" placeholder="<?php echo $email ?>">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="gender" class="form-label"><span style="color:#9C1421">*</span>Gender</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gender-ambiguous" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.5 1a.5.5 0 0 1 0-1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-3.45 3.45A4 4 0 0 1 8.5 10.97V13H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V14H6a.5.5 0 0 1 0-1h1.5v-2.03a4 4 0 1 1 3.471-6.648L14.293 1H11.5zm-.997 4.346a3 3 0 1 0-5.006 3.309 3 3 0 0 0 5.006-3.31z"/>
                              </svg>
                        </span>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                                    <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                                    <option value="Others" <?php if ($gender == 'Others') echo 'selected'; ?>>Others</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="phoneNum" class="form-label"><span style="color:#9C1421">*</span>Phone Number</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                            </svg>
                        </span>
                                <input type="input" class="form-control" id="phoneNum" name="phoneNum" placeholder="<?php echo $phoneNum ?>">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="address" class="form-label"><span style="color:#9C1421">*</span>Address inside the school</label>
                            <div class="input-group mb-3">
                        <span class="input-group-text justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                              </svg>
                        </span>
                                <input type="input" class="form-control" id="address" name="area" placeholder="<?php echo $campusArea?>">
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
                                <input type="input" id="adminID" hidden="hidden" value="<?php echo $customerID ?>" name="userID">
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
    <!--Modal for deactivate-->
    <!-- Deactivate Account Modal -->
    <div class="modal fade" id="deactivate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content py-3">
                <div class="modal-header justify-content-center py-4 align-items-center" style="border: transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#9C1421" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                    </svg>
                </div>
                <div class="modal-body p-0 d-flex justify-content-center">
                    <h5 class="modal-title" id="exampleModalLabel"><b>Deactivate this Account?</b></h5>
                </div>
                <div class="modal-footer d-flex justify-content-center" style="border: transparent">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-save" id="deactivateButton">Deactivate</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activate Account Modal -->
    <div class="modal fade" id="activate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content py-3">
                <div class="modal-header justify-content-center py-4 align-items-center" style="border: transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#3DC53A" class="bi bi-check-circle" viewBox="0 0 16 16">
                        <path d="M11.742 4.344a.5.5 0 0 0-.871-.007L7.53 8.715 6.28 7.47a.5.5 0 0 0-.707 0l-.707.707a.5.5 0 0 0 0 .707l2.48 2.48a.5.5 0 0 0 .707 0l4.94-4.94a.5.5 0 0 0 .007-.707z"/>
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    </svg>
                </div>
                <div class="modal-body p-0 d-flex justify-content-center">
                    <h5 class="modal-title" id="exampleModalLabel"><b>Activate this Account?</b></h5>
                </div>
                <div class="modal-footer d-flex justify-content-center" style="border: transparent">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-save" id="activateButton">Activate</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('deactivateButton').addEventListener('click', function () {
            const customerID = <?php echo $customerID ?>;
            const status = 'Deactivated';

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'updateStatus.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = xhr.responseText;
                    if (response === 'success') {
                        // Update UI or perform any other actions on success
                        console.log('Account deactivated successfully');
                        location.reload(); // You can reload the page or perform other actions as needed
                    } else {
                        alert('Error updating status: ' + response);
                    }
                }
            };
            xhr.send('customerID=' + customerID + '&status=' + status);
        });

        document.getElementById('activateButton').addEventListener('click', function () {
            const customerID = <?php echo $customerID ?>;
            const status = 'Active';

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'updateStatus.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = xhr.responseText;
                    if (response === 'success') {
                        console.log('Account activated successfully');
                        location.reload();
                    } else {
                        alert('Error updating status: ' + response);
                    }
                }
            };
            xhr.send('customerID=' + customerID + '&status=' + status);
        });
    </script>

    <!-- Javascript -->
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
                        location.reload();
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