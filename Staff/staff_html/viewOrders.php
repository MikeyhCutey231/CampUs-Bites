<?php
    include("../functions/orderListfunctions.php");
    $database = new Connection();
    $conn = $database->conn;

   $cartID = $_SESSION["cartID"];
   $userID = $_SESSION['USER_ID'];

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

    <title>Staff POS | Order List</title>
    <link rel="stylesheet" href="../../Staff/staff_css/viewOrder.css">
</head>
<body>
    <div class="wrapper">

    <div class="left-container" id="sidebar">
            <div class="sidebar-logo">
                <div class="main-logo"></div>
                <div class="sidelogo-text">
                    <div style="position: relative;">
                        <a href="">CampUs</a>
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
                    <p style="margin-bottom: 0;">Orders List</p>
                </div>
                <!-- <div class="head-searchbar">
                    <img src="/Icons/searchgreen.svg" alt="">
                    <input type="text" name="fname" placeholder="Search here...">
                </div> -->
                <div class="usep-texthead">
                    <img src="/Icons/useplogo.png" alt="" width="30px" height="30px">
                    <p style="margin-bottom: 0px; margin-top: 3px; margin-left: 10px; font-weight: 600;">UseP (Tagum Unit)</p>
                </div>
                <a href="staff-Profile.php" style="color: black;">
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
               <div class="mainLeft-container">
                    <div class="orderInfo-container">
                            <?php
                                $orderData = new EmployeeInfo($conn);
                                $data = $orderData->orderHeaderDetails();
                                $orderData->renderOrdersDetails($data);
                            ?>
                    </div>
                    <div class="items-container">
                        <table>
                            <tr>
                                <th style="padding-left: 30px !important;">Products</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th style="padding-left: 40px;">Total Price</th>
                            </tr>
                            <?php
                                $orderData = new EmployeeInfo($conn);
                                $data = $orderData->productCartDetails();
                                $orderData->renderCartProdDetails($data);
                            ?>
                        </table>
                    </div>
                    <div class="payment-container">
                        <p class="paymentText">Payment Summary</p>
                        <div class="paymentSum-container">
                            <?php
                                $orderData = new EmployeeInfo($conn);
                                $data = $orderData->paymentProdDetails();
                                $orderData->renderPaymentDetails($data);
                            ?>
                        </div>
                    </div>
               </div>
               <div class="mainRight-container">
                    <div class="customer-container">
                        <p class="customerText-head">Customer</p>
                            <?php
                                $orderData = new EmployeeInfo($conn);
                                $data = $orderData->customerInformation();
                                $orderData->renderCustomerInfo($data);
                            ?>
                    </div>


                    <!-- <div class="courier-container">
                        <p class="customerText-head">Courier</p>
                        <div class="customer-profile">
                            <img src="/Icons/cutemikey.svg" alt="">
                            <div class="customer-name">
                                <p class="username">Michael C. Labastida</p>
                                <p class="course">ID Number# A421521</p>
                            </div>
                        </div>

                        <div class="contact-info">
                            <p class="contactText-head">Contact Info</p>
                            <div class="phone-num">
                                <p>Phone Number</p>
                                <p>09301813358</p>
                            </div>
                            <div class="email">
                                <p>Email</p>
                                <p>mclabastida00045@gmail.com</p>
                            </div>
                          
                        </div>

                        <div class="shipping-info">
                            <p class="shippingText-head">Total Earnings</p>

                            <div class="totalItem">
                                <p>Total Item to Deliver</p>
                                <p>PECC Gymnasium</p>
                            </div>

                            <div class="Date">
                                <p>Date</p>
                                <p>January 18, 2020</p>
                            </div>

                            <div class="Deliveryfee">
                                <p>Delivery Fee</p>
                                <p>₱15.00</p>
                            </div>
                        </div>
                    </div> -->
               </div>


               <div class="modal fade" id="checkOutmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="border: 0;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Order Confirmation</h1>
                    </div>
                    <div class="modal-body">
                    <div class="modalContainer1">
                        <p>Total Amount</p>
                        <h3 class="modalTotal">₱ 0.00</h3>
                    </div>

                    <div class="modalContainer2">
                        <p>Cash Received</p>
                        <input type="text" name="" id="numberInput" placeholder="Enter cash you have received"> 
                    </div>

                    <div class="modalContainer3">
                        <p>Cash Return</p>
                        <h3 class="cashReturn">₱ 0.00</h3>
                    </div>
                    </div>
                    <div class="modal-footer" style="border: 0;">
                    <button type="button" id="completePayment" class="completePayment" data-status-id="<?php echo $cartID ?>">Complete Payment</button>
                    <button class="cancel"  data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Javascript -->
    <script src="../../Staff/staff_js/staff.js"></script>
    <script>
        numberInput.addEventListener('blur', () => {
        // Get the user's input
        const inputValue = numberInput.value;

        // Check if the input is a valid number
        if (!isNaN(inputValue)) {
            // Round the number to 2 decimal places
            const roundedValue = parseFloat(inputValue).toFixed(2);

            // Update the input field with the rounded value
            numberInput.value = roundedValue;
        }
    });
    </script>
    <script>
    $(document).ready(function () {
        $(".claimBtn").click(function () {
            var statusID = $(this).data("status-id");
            var olCartId = $(this).data("ol-cart-id");
            var statusName = $(this).data("status-name");

            // Display the retrieved values
            console.log("Status ID: " + statusID + "\nOL_CART_ID: " + olCartId + "\nStatus Name: " + statusName);

            if(statusID == 1){
                    

                $.ajax({
                    url: '../functions/confirmOrderHandler.php', // Replace with the actual URL of the destination page
                    type: 'POST', // Use POST method
                    data: { olCartId: olCartId }, // Send the button value as data
                    success: function(response) {
                        // Handle the success response if needed
                        console.log('Data sent successfully:', response);
                        window.location.href = "staff-orderList.php";
                    },
                    error: function(error) {
                        // Handle the error if there is one
                        console.error('Error sending data:', error);
                    }
                });
            }else if(statusID == 6){
               

                $.ajax({
                    url: '../functions/prepareOrderHandler.php', // Replace with the actual URL of the destination page
                    type: 'POST', // Use POST method
                    data: { olCartId: olCartId }, // Send the button value as data
                    success: function(response) {
                        // Handle the success response if needed
                        console.log('Data sent successfully:', response);
                        window.location.href = "staff-orderList.php";
                    },
                    error: function(error) {
                        // Handle the error if there is one
                        console.error('Error sending data:', error);
                    }
                });
            }else if(statusID == 7){
                 $("#checkOutmodal .modalContainer1 .modalTotal").text($(this).val());
                    $("#checkOutmodal").modal('show');

                    // Add an event listener to the cash received input
                    $('#numberInput').on('input', function () {
                        var totalAmount = parseFloat($("#checkOutmodal .modalContainer1 .modalTotal").text().replace('₱', '').trim()) || 0;
                        var cashReceived = parseFloat($(this).val()) || 0;
                        var cashReturn = cashReceived - totalAmount;
                        cashReturn = cashReturn >= 0 ? cashReturn : 0; // Ensure cashReturn is non-negative
                        $('.cashReturn').text('₱ ' + cashReturn.toFixed(2));

                        // Disable/enable the "Complete Payment" button based on the condition
                        if (cashReceived < totalAmount) {
                            $('.completePayment').prop('disabled', true);
                        } else {
                            $('.completePayment').prop('disabled', false);
                    }
                });
            }
        });


            // $.ajax({
            //     url: 'orderConfirmationModal.php', // Replace with the actual URL of the destination page
            //     type: 'POST', // Use POST method
            //     data: { buttonValue: buttonValue }, // Send the button value as data
            //     success: function(response) {
            //         // Handle the success response if needed
            //         console.log('Data sent successfully:', response);
            //     },
            //     error: function(error) {
            //         // Handle the error if there is one
            //         console.error('Error sending data:', error);
            //     }
            // });
        });
</script>



<script>
   $('.completePayment').on('click', function() {
        var items = [];
        var cashReceived = parseFloat($('#numberInput').val());
        var cashReturn = parseFloat($('.cashReturn').text().replace('₱ ', ''));

        $('.tableDatarow').each(function(index) {
            console.log('Processing element at index:', index);

            var name = $(this).find('.food-title').text();
            var quantity = $(this).find('.prodQuantity').text(); // Use text() instead of val()
            var price = parseFloat($(this).find('.orderPrice').text().replace('₱', ''));
            var itemId = $(this).find('.productIdContainer').data("proudct-id"); // Fix the typo here

            items.push({ name: name, quantity: quantity, price: price, itemId: itemId });
        });

        $.ajax({
        type: 'POST',
        url: '../functions/checkOutOrderHandler.php',
        data: {
            items: JSON.stringify(items),
            cashReceived: cashReceived,
            cashReturn: cashReturn
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                window.location.href = "staff-orderList.php";

            // Set a timer to refresh the page after 20 seconds
            setTimeout(function() {
                location.reload(); // This will reload the current page
            }, 5000); // 20000 milliseconds = 20 seconds
            } else {
            alert("Error: " + response.message);
            }
        }
        });
    });

</script>

<script>
        function updateNotificationCount() {
            $.ajax({
                url: "../functions/check_orderList.php", // Create a new PHP file for checking zero stock products
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