<?php
require_once '../functions/notifications.php';
require_once  '../functions/getProfile.php';
require_once("../../Admin/functions/dbConfig.php");


$database = new Connection();
$conn = $database->conn;

$customerID = $_SESSION['Customer_ID'];


if (isset($_GET['mark_as_read'])) {
    $notification = new Notification();
    $notification->markNotificationsAsRead($customerID);
}


$notificationsFunctions = new Notification();
$notifications = $notificationsFunctions->getNotificationsForCustomer($customerID);

$customer_details = new GetProfile();
$customer_profile = $customer_details->getProfile($customerID);
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
    <link rel="stylesheet" href="../customer_css/customer-notification.css">
</head>
<body>  
    <div class="wrapper">
        <div class="container-fluid py-md-3 justify-content-between banner ">
            <div class="row justify-content-between align-items-center  px-lg-5 px-md-4 px-sm-3 px-2  d-flex banner-row">

                <div class="col-lg-5 col-md-4 col-sm-1 col-1 p-0 d-flex align-items-center justify-content-start title-logo">
                    
                        <div class="logo-con" style="height: 50px; width: 46px;">
                            <img src="campus.png" alt="">
                        </div>

                        <div class="arrow-con"  onclick="window.history.back()" >
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                            </svg>
                        </div>
                    
                    <a href="customer-menu.php">
                        <h1 class="title">Campus Bites <?php echo $customerID ?></h1>
                    </a>
                </div>
                <div class="col-lg-7 col-md-8 col-sm-11 col-11 p-0 d-flex align-items-center justify-content-end">

                       
                <?php
                        $profile = "SELECT U_PICTURE FROM users WHERE USER_ID = '$customerID'";
                        $profRes = mysqli_query($conn, $profile);

                        while($row = mysqli_fetch_assoc($profRes)){
                            ?>  
                                 <a href="customer-profile.php" class="p-0 d-flex align-items-center ">
                                    <div class=" d-flex align-items-center justify-content-center p-0 profile">
                                        <img class="pofile-pic" src="../userPics/<?php echo $row['U_PICTURE'] ?>" style="height: 100%; width: 100%; background-color: white;">
                                    </div>
                                </a>
                            <?php
                        }
                    ?>
                   
                </div>
                <div class=" col-md-2 col-sm-3 col-5  d-flex justify-content-between align-items-center icon-con">
                    
                </div>
            </div>
        </div>


        <div class="main-content">
            

            <div class=" notification-title  px-0 px-sm-0">
                Notifications
            </div>
               
            <div class="container-fluid  p-0  mt-md-3 mt-2 justify-content-between notif-details">
                    <!-- diri ibutang ang profile details -->
                    

                    <?php foreach ($notifications as $notification): ?>
                        <div class="container d-flex px-0 py-1 notif-row">
                            <div class="d-flex py-1 align-items-center deliver-con">
                                <?php
                                // Determine the deliver-icon based on the OL_ORDER_TYPE_ID
                                $deliverIcon = '';
                                switch ($notification['OL_ORDER_TYPE_ID']) {
                                    case 1:
                                        $deliverIcon = '../images/deliver-Notif.png';
                                        break;
                                    case 2:
                                        $deliverIcon = '../images/pick-upNotif.png';
                                        break;
                                    // Add more cases as needed
                                    default:
                                        $deliverIcon = 'default-deliver-icon.png';
                                }
                                ?>
                                <img class="deliver-icon" src="<?php echo $deliverIcon; ?>" alt="">
                            </div>
                            <div class="px-2 py-2 p-0">
                                <div class="notif-title"><?php echo $notification['STATUS_NAME']; ?></div>
                                <div class="notif-deets">
                                    <?php echo $notification['NOTIF_MESSAGE']; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
            </div>

    


        </div>
       
        
    </div>



       
    <!-- Javascript -->
    <script src="admin.js"></script>
</body>
</html>