<?php
    include("../functions/menuListfunctions.php");
    $database = new Connection();
    $conn = $database->conn;

    $currentDate = date('Y-m-d');

    if (!isset($_SESSION['USER_ID'])) {
        header("Location: staff-login.php");
        exit();
    }
    

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

    <title>Staff POS | Menu</title>
    <link rel="stylesheet" href="../staff_css/stafflocal-style.css">
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
                    <a href="../../Staff/staff_html/staff-logout.php" class="sidebar-link">
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
                    <button class="btn px-1 py-0 open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                    <p style="margin-bottom: 0;">Menu Item</p>
                </div>
                <div class="head-searchbar">
                    <img src="../../Icons/searchgreen.svg" alt="">
                    <input type="text" name="fname" placeholder="Search here..." id="searchItem">
                </div>
                <div class="usep-texthead">
                    <img src="../../Icons/useplogo.png" alt="" width="30px" height="30px">
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
                <div class="maincontent-1">
                 <div class="categories-carousel">
                   <div class="icon"><i id="left" class="fa-solid fa-caret-left"></i></div>
                   <ul class="tabs-box">
                   <button>
                        <li class="tab active" style="display: none;">
                            <img src="/Icons/cooked.svg" alt="" width="25px" style="padding-right: 5px;">
                            Food
                        </li>
                    <button>
                        <button type="button">
                            <li class="tab" id="prodName" value="0">
                            <img src="../../Icons/cooked.svg" alt="" width="25px" style="padding-right: 5px;">
                            All Products</li>
                        </button>
                        <?php 
                            $categoryList = new MenuList($conn);
                            $data = $categoryList->itemCategory();
                            $categoryList->renderCategoryTable($data);
                        ?>
                   </ul>
                   <div class="icon"><i id="right" class="fa-solid fa-caret-right"></i></div>
                </div>

                <div class="card-container">
                        <?php 
                            $itemList = new MenuList($conn);
                            $data = $itemList->productList();
                            $itemList->renderProductList($data);
                        ?>
                    </div>
                </div>

                
                <div class="maincontent-2">
                    <div class="checkouttext-head">
                        <p style="font-size: 20px; font-weight: calc(1000);">Checkout</p>
                        <p style="font-size: 12px; margin-top: 5px;"><?php echo  $currentDate ?></p>
                    </div>
                   
                    <div class="itemCartContainer">
                        <div class="item-cart">
                            <p class="prodNoFound" style="margin-left:10px; margin-top:10px;">No Item Currently</p>
                        </div>
                    </div>
                    
                    <div class="total-item">
                        <div class="total-container">
                            <div class="partial-totalcontainer">
                                <div class="subtotal-container">
                                    <p style="font-size: 12px;">Subtotal:</p>
                                     <p style="font-size: 12px; font-weight: bold;" id="subtotal">₱ 0.00</p>
                                </div>

                               
                            <div class="overall-total">
                                <p>Total</p>
                                <p id="total">₱ 0.00</p>
                            </div>

                        </div>

                        <div class="checkout-container">
                            <button type="button" class="checkout-btn">Checkout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
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
            <button class="completePayment">Complete Payment</button>
            <button class="cancel"  data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="../staff_js/staff.js"></script>

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
       $(document).ready(function(){
            $('#searchItem').on('input', function(){
                var searchValue = $(this).val();

                // Check if the search value is not empty
                if (searchValue.trim() !== '') {
                    // Make the AJAX call if the search value is not empty
                    $.post('../functions/livesearch.php', { name: searchValue }, function(data) {
                        $(".card-container").html(data);
                    });
                } else {
                    // If the search value is empty, display all products (adjust this part based on your requirements)
                    $.post('../functions/livesearch.php', { name: '' }, function(data) {
                        $(".card-container").html(data);
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('li.tab').click(function() {
                var categoryID = $(this).attr('value');

                $.ajax({
                    type: "POST",
                    url: "../functions/categoryFilterHandler.php",
                    data: { categoryID: categoryID},
                    success: function(data) {
                        // Clear the existing content in the card-container
                        $('.card-container').empty();

                        // Append the received data to the card-container
                        $('.card-container').append(data);
                    }
                });
            });
        });

    </script>

<script>
    $(document).ready(function () {
        var prodNoFound = $(".prodNoFound");
        updateTotals(); // Call this function when the page loads

        $('.Cart').each(function () {
            var stock = parseInt($(this).data('stock'));
            if (stock <= 0) {
                $(this).prop('disabled', true);
            }
        });

        $('button.Cart').click(function () {
            var categoryID = $(this).attr('value');

            $.ajax({
                type: "POST",
                url: "../functions/checkOutHandler.php",
                data: { categoryID: categoryID },
                success: function (data) {
                    // Check if the item is already in the cart
                    var itemName = $(data).find('.cartItem-title').text();
                    if (!isItemInCart(itemName)) {
                        $('.item-cart').append(data);
                        prodNoFound.hide();
                        // Update totals when a new item is added
                        updateTotals();
                    } else {
                        // Notify the user that the item is already in the cart
                        // alert('Item is already in the cart.');
                    }

                    $('.Cart').each(function () {
                        var stock = parseInt($(this).data('stock'));
                        var quantity = parseInt($(this).siblings('.add-item').find('.quantity-input').val());
                        if (quantity >= stock) {
                            $(this).prop('disabled', true);
                        }
                    });
                }
            });
        });

        $('.item-cart').on('click', '.remove-item', function () {
            $(this).closest('.item1').remove(); // Remove the item from the cart
            updateTotals(); // Update totals after removal
        });

        $('.item-cart').on('click', '.add', function () {
            var quantityInput = $(this).siblings('.quantity-input');
            var currentQuantity = parseInt(quantityInput.val(), 10);

            // Retrieve the stock value from the hidden field
            var stock = parseInt($(this).closest('.item1').find('.cartQuantity').text());

            // Check if adding one more exceeds the available stock
            if (currentQuantity + 1 <= stock) {
                // Increment the quantity
                quantityInput.val(currentQuantity + 1);

                // Update totals for the specific item
                updateTotals();
            } else {
                // alert('Cannot add more. Maximum stock reached.');
            }
        });

        $('.item-cart').on('click', '.minus', function () {
            var quantityInput = $(this).siblings('.quantity-input');
            var currentQuantity = parseInt(quantityInput.val(), 10);

            if (currentQuantity > 1) {
                // Decrement the quantity
                quantityInput.val(currentQuantity - 1);
                // Update totals for the specific item
                updateTotals();
            }
        });

        function isItemInCart(itemName) {
            var isInCart = false;

            // Check if the item is already in the cart
            $('.item1').each(function () {
                var name = $(this).find('.cartItem-title').text();
                if (name === itemName) {
                    isInCart = true;
                    return false; // Exit the loop early
                }
            });

            return isInCart;
        }

        function updateTotals() {
            var subTotal = 0;

            // Loop through each item in the cart
            $('.item1').each(function () {
                var priceText = $(this).find('.cartItem-price').text();
                var price = parseFloat(priceText.match(/₱([\d.]+)/)[1]);
                var quantity = parseInt($(this).find('.quantity-input').val());
                subTotal += price * quantity;
            });

            var total = subTotal;

            // Update the subtotal and total elements
            $('#subtotal').text('₱ ' + subTotal.toFixed(2));
            $('#total').text('₱ ' + total.toFixed(2));
        }
    });
</script>


    <!-- <script>
        $('.checkout-btn').on('click', function() {
        // Collect item data from the page
        var items = [];
        $('.item1').each(function() {
            var name = $(this).find('.cartItem-title').text();
            var quantity = $(this).find('.quantity-input').val();
            items.push(['name' => name, 'quantity' => quantity]);
        });

        // Send the data to the server using AJAX
        $.ajax({
            type: 'POST',
            url: 'store-selected-items.php', // Create this PHP file
            data: { items: JSON.stringify(items) },
            success: function(response) {
                // Redirect to the other PHP file where you want to display the selected items
                window.location.href = 'other-php-file.php';
            }
        });
    });
    </script> -->


    <script>
        $(document).ready(function () {
            // Initialize modal with total amount
            $('#checkOutmodal').on('show.bs.modal', function (e) {
                var totalAmount = $('#total').text();
                $('.modalTotal').text(totalAmount);

                // Add an event listener to the cash received input
                $('#numberInput').on('input', function () {
                    var cashReceived = parseFloat($(this).val()) || 0;
                    var cashReturn = cashReceived - parseFloat(totalAmount.replace('₱', '').trim());
                    cashReturn = cashReturn >= 0 ? cashReturn : 0; // Ensure cashReturn is non-negative
                    $('.modalContainer3 h3').text('₱ ' + cashReturn.toFixed(2));

                    // Disable/enable the "Complete Payment" button based on the condition
                    if (cashReceived < parseFloat(totalAmount.replace('₱', '').trim())) {
                        $('.completePayment').prop('disabled', true);
                    } else {
                        $('.completePayment').prop('disabled', false);
                    }
                });
            });

            // Trigger the modal on "Checkout" button click
            $('.checkout-btn').click(function () {
                $('#checkOutmodal').modal('show');
            });
        });
    </script>


    <script>
    $('.completePayment').on('click', function() {
        var items = [];
    var cashReceived = parseFloat($('#numberInput').val());
    var cashReturn = parseFloat($('.cashReturn').text().replace('₱ ', ''));
    var overAllTotal = 0;

    $('.item1').each(function() {
        var name = $(this).find('.cartItem-title').text();
        var quantity = parseInt($(this).find('.quantity-input').val());
        var price = parseFloat($(this).find('.cartItem-price').text().replace('Standard Price: ₱', ''));
        var subtotal = price * quantity;
        var itemId = $(this).find('.cartItem-ID').text();

        items.push({ name: name, quantity: quantity, price: price, subtotal: subtotal, itemId: itemId });

        overAllTotal += subtotal;
    });


        $.ajax({
        type: 'POST',
        url: '../functions/checkOutDataHandler.php',
        data: {
            items: JSON.stringify(items),
            overAllTotal: overAllTotal,
            cashReceived: cashReceived,
            cashReturn: cashReturn
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const cartID = response.cartID;

            window.location.href = '../functions/receipt_pdf.php?cartID=' + cartID + '&items=' + encodeURIComponent(JSON.stringify(items));

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
                url: "../functions/check_zero_stock.php", // Create a new PHP file for checking zero stock products
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