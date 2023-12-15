<?php
require_once '../../Admin/functions/dbConfig.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Bites</title>

    <!-- Link to Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Link to js bootsrap 5.3.2 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgx` yol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <!-- Link to Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Link to css and js -->
    <link rel="stylesheet" href="../customer_css/customer-viewOrder.css">
</head>

<body>
    <!-- NAVBAR -->
    <div class="wrapper">
        <div class="container-fluid py-md-3 justify-content-between banner ">
            <div class="row justify-content-between align-items-center  px-lg-5 px-md-4 px-sm-3 px-2  d-flex banner-row">

                <div class="col-lg-5 col-md-4 col-sm-1 col-1 p-0 d-flex align-items-center justify-content-start title-logo">
                    <a href="customer-menu.html">
                        <div class="logo-con">
                            <img src="logoinspo.jpg" alt="">
                        </div>
                        <div class="arrow-con">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                            </svg>
                        </div>
                    </a>
                    <a href="customer-menu.html">
                        <h1 class="title">Campus Bites</h1>
                    </a>
                </div>
                <div class="col-lg-7 col-md-8 col-sm-11 col-11 p-0 d-flex align-items-center justify-content-end">
                    <div class="input-group  search-con ">
                        <input type="input" class="form-control searchbar" id="search" placeholder="Search an item here...">
                        <span class="input-group-text d-flex align-items-center justify-content-center p-0" style="background-color: #9C1421; ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="white" class="bi bi-search search-icon" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                            </svg>
                        </span>
                    </div>



                    <a href="customer-cart.html" class="p-0 d-flex align-items-center">
                        <div class="d-flex align-items-center justify-content-center py-md-2 p-sm-1 p-0 cart-con">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#9C1421" class="bi bi-cart2 cart" viewBox="0 0 16 16">
                                <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0" style="stroke-width: px" />
                            </svg>
                        </div>
                        <div class="cart-num align-items-center ">
                            12
                        </div>
                    </a>

                    <a href="customer-notification.html" class="p-0 d-flex align-items-center ">
                        <div class="d-flex align-items-center justify-content-center py-md-2 p-sm-1 p-0 notif-con">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#9C1421" class="bi bi-bell bell" viewBox="0 0 16 16">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
                            </svg>
                        </div>
                        <div class="notif-num align-items-center ">
                            12
                        </div>
                    </a>


                    <a href="customer-profile.html" class="p-0 d-flex align-items-center ">
                        <div class=" d-flex align-items-center justify-content-center p-0 profile">
                            <img class="pofile-pic" src="cute27.png" style="height: 100%; width: 100%;">
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="orders-container"> </div>
    <script>
        // Function to retrieve the viewed order ID from session and load details
        function loadViewedOrderDetails() {
            // Log existing session storage for debugging
            console.log('Session Storage:', sessionStorage);

            var viewedOrderId = sessionStorage.getItem('viewedOrderId');
            console.log('Viewed Order ID:', viewedOrderId); // Log viewed order ID

            if (viewedOrderId) {
                // Decrypt the viewedOrderId
                var decryptedOrderId = atob(viewedOrderId);

                $.ajax({
                    url: '../functions/order-details.php?order_id=' + encodeURIComponent(decryptedOrderId),
                    method: 'GET',
                    success: function(response) {
                        // Update the orders-container div with the response
                        $('#orders-container').html(response);
                        // Clear the viewedOrderId from session (optional)
                        sessionStorage.removeItem('viewedOrderId');
                    },
                    error: function(error) {
                        console.error('Error loading order details:', error);
                    }
                });
            } else {
                console.error('Viewed order ID not found in session.');
            }
        }

        // Make sure viewedOrderId is set in session storage
        // Make sure viewedOrderId is set in session storage
        var orderIdFromUrl = <?php echo isset($_GET['order_id']) ? json_encode($_GET['order_id']) : 'null'; ?>;
        if (orderIdFromUrl) {
            sessionStorage.setItem('viewedOrderId', btoa(orderIdFromUrl));
        }


        // Call the function to load viewed order details
        loadViewedOrderDetails();
    </script>



</body>

</html>