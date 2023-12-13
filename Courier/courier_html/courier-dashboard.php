<?php
    include("../../Admin/functions/dbConfig.php");
    $database = new Connection();
    $conn = $database->conn;

    $currentDate = date('Y-m-d');
    $userID = $_SESSION['USER_ID'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="height=device-height, width=device-width, initial-scale=1.0, minimum-scale=1.0, target-densitydpi=device-dpi">
 
    <!-- CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>Staff POS | Order List</title>
    <link rel="stylesheet" href="../../Courier/courier_css/courier-Dashboard.css">
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
                    <a href="courier-dashboard.php" class="sidebar-link"  id="invHover">
                        <svg width="21" height="21" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.451 10.2994C17.4413 12.0203 16.9215 13.6996 15.9573 15.125C14.9931 16.5505 13.6279 17.658 12.0343 18.3075C10.4407 18.9571 8.69028 19.1195 7.00438 18.7742C5.31847 18.4289 3.77281 17.5915 2.56286 16.3678C1.35291 15.144 0.533007 13.589 0.206836 11.8993C-0.119336 10.2096 0.0628736 8.4611 0.730422 6.87495C1.39797 5.2888 2.52088 3.93622 3.95714 2.98825C5.39341 2.04029 7.07852 1.53951 8.79939 1.54925L8.75014 10.2501L17.451 10.2994Z" fill="#5F5F5F"/>
                            <path d="M19.0745 8.65408C19.0803 7.52336 18.8633 6.40258 18.4359 5.35573C18.0085 4.30889 17.3791 3.35647 16.5836 2.55287C15.7881 1.74926 14.8422 1.1102 13.7997 0.672182C12.7573 0.23416 11.6388 0.0057526 10.5081 0L10.4643 8.61027L19.0745 8.65408Z" fill="#5F5F5F"/>
                            </svg>                                                       
                    <p>Dashboard</p>
                    </a>
                </li>


                <li class="sidebar-items">
                    <a href="courier-activeOrders.php" class="sidebar-link" id="invHover">
                        <div class="circle"> <p class="cirlcecolor" id="notificationCount">0</p></div>
                        <svg class="shoppingCart" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.90856 18.9994C8.38553 18.9994 8.77219 18.6157 8.77219 18.1423C8.77219 17.6689 8.38553 17.2852 7.90856 17.2852C7.43159 17.2852 7.04492 17.6689 7.04492 18.1423C7.04492 18.6157 7.43159 18.9994 7.90856 18.9994Z" stroke="#5F5F5F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.4095 18.9994C17.8865 18.9994 18.2732 18.6157 18.2732 18.1423C18.2732 17.6689 17.8865 17.2852 17.4095 17.2852C16.9326 17.2852 16.5459 17.6689 16.5459 18.1423C16.5459 18.6157 16.9326 18.9994 17.4095 18.9994Z" stroke="#5F5F5F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M1 1H4.45455L6.76909 12.4771C6.84807 12.8718 7.06438 13.2263 7.38015 13.4785C7.69593 13.7308 8.09106 13.8649 8.49636 13.8571H16.8909C17.2962 13.8649 17.6913 13.7308 18.0071 13.4785C18.3229 13.2263 18.5392 12.8718 18.6182 12.4771L20 5.28571H5.31818" stroke="#5F5F5F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>   
                    <p>Active Orders</p>
                    </a>
                </li>

                
                <li class="sidebar-items">
                    <a href="courier-OrderList.php" class="sidebar-link" id="invHover">
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
                        
                    <p>Claimed Orders</p>
                    </a>
                </li>


                <li class="sidebar-items">
                    <a href="courier-transactionHisto.php" class="sidebar-link" id="invHover">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 3H8C9.06087 3 10.0783 3.42143 10.8284 4.17157C11.5786 4.92172 12 5.93913 12 7V21C12 20.2044 11.6839 19.4413 11.1213 18.8787C10.5587 18.3161 9.79565 18 9 18H2V3Z" stroke="#5F5F5F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 3H16C14.9391 3 13.9217 3.42143 13.1716 4.17157C12.4214 4.92172 12 5.93913 12 7V21C12 20.2044 12.3161 19.4413 12.8787 18.8787C13.4413 18.3161 14.2044 18 15 18H22V3Z" stroke="#5F5F5F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                    <p>Transaction History</p>
                    </a>
                </li>

                <li class="sidebar-items">
                    <a href="courier-salesReport.php" class="sidebar-link">
                    <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.25 15.8332V7.9165" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9.5 15.8332V3.1665" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M4.75 15.8335V11.0835" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    <p>Income Report</p>
                    </a>
                </li>


                <li class="sidebar-items">
                    <a href="" class="sidebar-link">
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
                    <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                    <p style="margin-bottom: 0;">Dashboard</p>
                </div>
                <div class="head-searchbar">
                    <img src="../../Icons/searchgreen.svg" alt="">
                    <input type="text" name="fname" placeholder="Search here...">
                </div>
                <div class="usep-texthead">
                    <img src="../../Icons/useplogo.png" alt="" width="30px" height="30px">
                    <p style="margin-bottom: 0px; margin-top: 3px; margin-left: 10px; font-weight: 600;">UseP (Tagum Unit)</p>
                </div>
                <a href="courier-viewProfile.php" style="color: black;">
                                   <?php 
                                        $claimOrders = "SELECT U_FIRST_NAME, U_PICTURE FROM users WHERE USER_ID  = '$userID'";
                                        $claimOrdersRun = mysqli_query($conn, $claimOrders);

                                        while($row = mysqli_fetch_array($claimOrdersRun)){
                                            ?>
                                                 <div class="user-profile">
                                                    <img src="../../Icons/<?php echo $row['U_PICTURE'] ?>" alt="" class="admin-profile">
                                                    <div class="admin-detail">
                                                        <p style="margin-bottom: 0px; font-weight: 700;"><?php echo $row['U_FIRST_NAME'] ?></p>
                                                        <p style="margin-bottom: 0px; font-size: 7px; background-color: #0F947E; color: white; padding: 3px; border-radius: 3px;">Cashier</p>
                                                    </div>
                                                </div>
                                            <?php
                                        }

                                    
                                    ?>
                </a>
            </div>
            <div class="main-content">
                <div class="dash-topContent">
                    <div class="left-Container">
                        <div class="headGreen-Imagecntainer">
                            <div class="insideLeft-info">
                                <h3>Welcome, Michael!</h3>
                                <p>In CampUs Bites Delivery Courier, your task is to deliver numerous
                                    order to the students who ordered through us, you will be 
                                    paid based on how many orders you have taken.</p>
                                <h5>Enjoy your stay!</h5>
                            </div>
                            <div class="insideRight-info">
                                <img src="../../Icons/courierChardesign.svg" alt="" class="sideImage">
                                <img src="../../Icons/courierImage.svg" alt="" class="mainLogo">
                            </div>
                        </div>

                        <div class="courierRatings-info">
                            <div class="ratingsContainer">
                                <div class="topClaimorder">
                                    <div class="redCircleImage"></div>
                                    <img src="../../Icons/whiteshopping-bag.svg  " alt="" width="20px">
                                </div>
                                <div class="bottomClaimedorder">
                                    <?php 
                                        $claimOrders = "SELECT COUNT(*) AS Claimed FROM online_order WHERE COURIER_ID  = '$userID' AND OL_ORDER_TYPE_ID = 1 AND ORDER_STATUS_ID  BETWEEN 2 AND 3";
                                        $claimOrdersRun = mysqli_query($conn, $claimOrders);

                                        while($row = mysqli_fetch_array($claimOrdersRun)){
                                            ?>
                                                <h3><?php echo $row['Claimed'] ?></h3>
                                                <p>Claimed Orders</p>
                                            <?php
                                        }

                                    
                                    ?>
                                </div>
                            </div>

                            <div class="ratingsContainer" style="background-color: #FFF4DE;">
                                <div class="topClaimorder">
                                    <div class="redCircleImage" style="background-color: #F0C419;"></div>
                                    <img src="../../Icons/packagewhite.svg " alt="" width="20px">
                                </div>
                                <div class="bottomClaimedorder">
                                <?php 
                                        $claimOrders = "SELECT COUNT(*) AS Claimed FROM online_order WHERE COURIER_ID  = '$userID' AND ORDER_STATUS_ID = 4 AND OL_ORDER_TYPE_ID = 1";
                                        $claimOrdersRun = mysqli_query($conn, $claimOrders);

                                        while($row = mysqli_fetch_array($claimOrdersRun)){
                                            ?>
                                                <h3><?php echo $row['Claimed'] ?></h3>
                                                <p>Total Delivered</p>
                                            <?php
                                        }

                                    
                                    ?>
                                </div>
                            </div>

                            <div class="ratingsContainer" style="background-color: #DCFCE7;">
                                <div class="topClaimorder">
                                    <div class="redCircleImage" style="background-color: #A2C53A;"></div>
                                    <img src="../../Icons/bar-chartwhite.svg" alt="" width="20px">
                                </div>
                                <div class="bottomClaimedorder">
                                    <?php 
                                            $claimOrders = "SELECT COUNT(*) AS activeOrders FROM online_order WHERE ORDER_STATUS_ID = 1 AND OL_ORDER_TYPE_ID  = 1";
                                            $claimOrdersRun = mysqli_query($conn, $claimOrders);

                                            while($row = mysqli_fetch_array($claimOrdersRun)){
                                                ?>
                                                    <h3><?php echo $row['activeOrders'] ?></h3>
                                                    <p>Active Orders</p>
                                                <?php
                                            }

                                        
                                        ?>
                                </div>
                            </div>


                            <div class="ratingsContainer" style="background-color: #CEC8FC;">
                                <div class="topClaimorder">
                                    <div class="redCircleImage" style="background-color: #403D55;"></div>
                                    <img src="../../Icons/dollar-sign.svg" alt="" width="20px">
                                </div>
                                <div class="bottomClaimedorder">
                                    <?php
                                        $viewOrder = "SELECT SUM(online_order.SHIPPING_FEE) AS TotalAmount FROM online_order
                                        WHERE online_order.COURIER_ID = '$userID' AND online_order.OL_ORDER_TYPE_ID = 1 AND online_order.ORDER_STATUS_ID = 4 AND DATE(online_order.DATE_CREATED) = CURDATE()";
                                        $viewOrderrun = mysqli_query($conn, $viewOrder);

                                        while($row = mysqli_fetch_array($viewOrderrun)){
                                            $amountMoney = $row['TotalAmount'];

                                            if($amountMoney == 0){
                                                ?>
                                                 <h3>0</h3>
                                                <?php
                                            }else{
                                                ?>
                                                    <h3><?php echo $row['TotalAmount'] ?></h3>
                                                <?php
                                            }
                                            
                                        }

                                    ?>
                                    <p>Today's Income</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right-container1">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3326.9317751318126!2d125.83052320579006!3d7.419690574603784!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32f95590ac301e3f%3A0xb14a60fdfc593e5f!2sUSeP%20-%20School%20of%20Medicine!5e0!3m2!1sen!2sph!4v1697802681412!5m2!1sen!2sph" width="300" height="320" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="dash-bottomContent">
                    <div class="bottomContent-left">
                       <div class="transactionHistory-Top">
                            <div>
                                <h5>Transaction History</h5>
                                <p>All ordered products</p>
                            </div>
                            <div class="viewAlltransaction">
                                <a href="courier-transactionHisto.php">View All</a>
                            </div>
                       </div>

                       <div class="transactionHistory-bottom">
                            <?php 
                                $transactionDetails = "SELECT online_order.OL_CART_ID, ol_order_status.STATUS_NAME, online_order.SHIPPING_FEE, users.U_CAMPUS_AREA, users.U_USER_NAME FROM online_order
                                INNER JOIN ol_order_status ON online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
                                INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
                                INNER JOIN users ON ol_cart.CUSTOMER_ID = users.USER_ID
                                WHERE online_order.COURIER_ID = '$userID' AND online_order.ORDER_STATUS_ID = 4 AND OL_ORDER_TYPE_ID = 1;";

                                $transactionDetailsRun = mysqli_query($conn, $transactionDetails);

                                while($row = mysqli_fetch_array($transactionDetailsRun)){
                                    ?>
                                        <div class="transactionContent">
                                            <div class="locationImage">
                                                <div class="circleGreenlocation"></div>
                                                <img src="../../Icons/location.svg" alt="" >

                                                <div class="orderNum">
                                                    <h5>Order#<?php echo $row['OL_CART_ID'] ?></h5>
                                                    <p><?php echo $row['U_CAMPUS_AREA'] ?></p>
                                                </div>
                
                                            </div>

                                            <div class="profitEarned">
                                                <h5>Profit Earned</h5>
                                                <p>₱ <?php echo $row['SHIPPING_FEE'] ?></p>
                                            </div>


                                            <div class="DeliveryNum">
                                                <p><?php echo $row['STATUS_NAME'] ?></p>
                                            </div>
                                        </div>
                                    <?php
                                }


                            ?>  
                       </div>
                    </div>
                    <div class="bottomContent-right">
                        <div class="topProductrating-container">
                            <h5>Top Products Rating</h5>
                        </div>
                        <div class="productRatingtable">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Popularity</th>
                                    <th scope="col">Sales</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Example 20px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 10px;">
                                            <div class="progress-bar" style="width: 80%; background-color: #9C1421;"></div>
                                          </div>
                                    </td>
                                    <td>@mdo</td>
                                  </tr>
                                  <tr>
                                    <th scope="row">2</th>
                                    <td>Jacob</td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Example 20px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 10px;">
                                            <div class="progress-bar" style="width: 50%; background-color: #F0C419;"></div>
                                          </div>
                                    </td>
                                    <td>@fat</td>
                                  </tr>
                                  <tr>
                                    <th scope="row">3</th>
                                    <td>Jacob</td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Example 20px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 10px;">
                                            <div class="progress-bar" style="width: 20%; background-color: #A2C53A;"></div>
                                          </div>
                                    </td>
                                    <td>@fat</td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="../../Courier/courier_js/staff.js"></script>
    <script>
        function updateNotificationCount() {
            $.ajax({
                url: "../functions/check_active_orders.php", // Create a new PHP file for checking zero stock products
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