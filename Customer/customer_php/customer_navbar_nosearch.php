<?php
require_once '../classes/cart.class.php';
require_once '../functions/notifications.php';
require_once  '../functions/getProfile.php';

$customer_id =  $_SESSION['Customer_ID'];
$countCart = new Cart();
$cartItem = $countCart->getCartTotalItem($customer_id);


$countNotif = new Notification();
$notifTotal = $countNotif->countNotification($customer_id);


$notificationsFunctions = new Notification();
$notifications = $notificationsFunctions->getNotificationsForCustomer($customer_id);

$customer_details = new GetProfile();
$customer_profile = $customer_details->getProfile($customer_id);
$u_picture = $customer_profile['U_PICTURE'];
?>






<div class="container-fluid py-md-3 justify-content-between banner ">
    <div class="row justify-content-between align-items-center  px-lg-5 px-md-4 px-sm-3 px-2  d-flex banner-row">

        <div class="col-lg-5 col-md-4 col-sm-1 col-1 p-0 d-flex align-items-center justify-content-start title-logo">
            <a href="customer-menu.php">
                <div class="logo-con" style="height: 50px; width: 46px;">
                    <img src="campus.png" alt="">
                </div>
            </a>
            <div class="arrow-con" onclick="window.history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
            </div>


            <a href="customer-menu.php">
                <h1 class="title">Campus Bites</h1>
            </a>
        </div>
        <div class="col-lg-7 col-md-8 col-sm-11 col-11 p-0 d-flex align-items-center justify-content-end">
            <a href="customer-cart.php" class="p-0 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center py-md-2 p-sm-1 p-0 cart-con">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#9C1421" class="bi bi-cart2 cart" viewBox="0 0 16 16">
                        <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0" style="stroke-width: px" />
                    </svg>
                </div>

                <div class="cart-num align-items-center ">
                    <?php if ($cartItem >= 99) {
                        echo '99+';
                    } else {
                        echo $cartItem;
                    }  ?>
                </div>
            </a>

            <a href="customer-notification.php?mark_as_read=true" class="p-0 d-flex align-items-center ">
                <div class="d-flex align-items-center justify-content-center py-md-2 p-sm-1 p-0 notif-con">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#9C1421" class="bi bi-bell bell" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
                    </svg>
                </div>

                <div class="notif-num align-items-center ">
                    <?php if ($notifTotal >= 99) {
                        echo '99+';
                    } else {
                        echo $notifTotal;
                    } ?>
                </div>
            </a>


            <a href="customer-profile.php" class="p-0 d-flex align-items-center ">
                <div class=" d-flex align-items-center justify-content-center p-0 profile">
                    <img class="profile-pic" src="../userPics/<?php echo $u_picture ?>" style="height: 100%; width: 100%; background-color: white;">
                </div>
            </a>

        </div>

    </div>
</div>


<script>
    $(document).ready(function() {
        $('#notification-icon').on('click', function(e) {
            e.preventDefault();

            // Log to the console to check if the click event is triggered
            console.log('Notification icon clicked');

            $.ajax({
                type: 'GET',
                url: 'customer-notification.php?mark_as_read=true',
                success: function(response) {
                    console.log('Notifications marked as read.');
                    // Handle success, e.g., update the UI or do something else
                },
                error: function(error) {
                    console.error('Error marking notifications as read.');
                    // Handle error
                }
            });
        });
    });
</script>