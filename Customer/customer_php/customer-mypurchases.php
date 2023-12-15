<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <title>Campus Bites</title>
    <link rel="stylesheet" href="../customer_css/customer-mypurchases.css">
</head>

<body>
    <div class="wrapper">
        <div class="wrapper">
            <?php include 'customer_navbar_nosearch.php' ?>
        </div>

        <div class="main-content ">

            <!-- sub navbar -->
            <div class="container-fluid d-flex p-0 profile-menu mb-md-3 mb-2 ">
                <a href="customer-profile.php" class="p-0">
                    <button class="btn py-md-1 p-0 d-flex  align-items-center justify-content-center btn-profile" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#9C1421" class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                        </svg>
                        My Profile
                    </button>
                </a>


                <div class="dropdown d-flex align-items-center justify-content-center btn-purchases ">

                    <button class="btn border-0 py-md-2 py-1 px-0 dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="height: 100%; width: 100%;    ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#9C1421" class="bi bi-box2-heart-fill" viewBox="0 0 16 16">
                            <path d="M3.75 0a1 1 0 0 0-.8.4L.1 4.2a.5.5 0 0 0-.1.3V15a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V4.5a.5.5 0 0 0-.1-.3L13.05.4a1 1 0 0 0-.8-.4h-8.5ZM8.5 4h6l.5.667V5H1v-.333L1.5 4h6V1h1zM8 7.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132" />
                        </svg>
                        Delivery
                    </button>

                    <ul class="dropdown-menu dropdowm-purchase" id="custom-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#" value="0" selected>All Orders</a></li>
                        <li><a class="dropdown-item" href="#" value="1">Pending</a></li>
                        <li><a class="dropdown-item" href="#" value="2">Claimed</a></li>
                        <li><a class="dropdown-item" href="#" value="3">On the way</a></li>
                        <li><a class="dropdown-item" href="#" value="4">Completed</a></li>
                        <li><a class="dropdown-item" href="#" value="5">Cancelled</a></li>

                    </ul>
                </div>


                <a href="customer-mypurchases-pickup.php" class="p-0">
                    <button class="btn py-md-1 p-0 d-flex  align-items-center justify-content-center btn-notification" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#9C1421" class="bi bi-box2-heart" viewBox="0 0 16 16">
                            <path d="M8 7.982C9.664 6.309 13.825 9.236 8 13 2.175 9.236 6.336 6.31 8 7.982" />
                            <path d="M3.75 0a1 1 0 0 0-.8.4L.1 4.2a.5.5 0 0 0-.1.3V15a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V4.5a.5.5 0 0 0-.1-.3L13.05.4a1 1 0 0 0-.8-.4h-8.5Zm0 1H7.5v3h-6zM8.5 4V1h3.75l2.25 3zM15 5v10H1V5z" />
                        </svg>
                        Pick-up
                    </button>
                </a>

            </div>

            <script>
                $(document).ready(function() {
                    // Set up click event handler for dropdown items
                    $(".dropdowm-purchase a").on('click', function() {
                        var value = $(this).attr('value');

                        $.ajax({
                            url: "../functions/myPurchases-filter.php",
                            type: "POST",
                            data: 'request=' + value,
                            success: function(data) {
                                $("#order-container").html(data);
                            }
                        });
                    });

                    // Trigger click event for "All Orders" on page load
                    $(".dropdowm-purchase a[value='0']").click();
                });
            </script>

            <script>
                // Function to load order details when "View Order" link is clicked
                function loadOrderDetails(orderId) {
                    $.ajax({
                        url: '../functions/order-details.php?order_id=' + orderId,
                        method: 'GET',
                        success: function(response) {
                            // Save the order ID in a session variable
                            sessionStorage.setItem('viewedOrderId', orderId);
                            // Redirect to the customer-viewOrder page
                            window.location.href = 'customer-viewOrder.php';
                        },
                        error: function(error) {
                            console.error('Error loading order details:', error);
                        }
                    });
                }

                // Attach click event to the "View Order" link
                $(document).on('click', '#view-order-link', function(e) {
                    e.preventDefault();
                    var orderId = $(this).data('order-id');
                    loadOrderDetails(orderId);
                });
            </script>





            <div class="row" id="order-container"></div>







        </div>
    </div>

    <script>
        // Add an event listener to the document to capture clicks on the order-received-btn class
        document.addEventListener('click', function(event) {
            // Check if the clicked element has the order-received-btn class
            if (event.target.classList.contains('order-received-btn')) {
                // Get the order_id from the data-order-id attribute
                var orderId = event.target.getAttribute('data-order-id');

                // Log or use the orderId as needed
                console.log('Order ID:', orderId);

                // You can also set the orderId in your modal using JavaScript
                // For example, if you have a modal element with an ID of 'ratemodal'
                // document.getElementById('ratemodal').setAttribute('data-order-id', orderId);
            }
        });
    </script>

    <!--Modal for rating-->
    <div class="modal fade" id="ratemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><b>Product Ratings</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <p id="rating-value">Rating: <span id="selected-rating">0</span></p>

                        <div class="rating border d-flex justify-content-center align-items-center">
                            <span class="star" onclick="rate(1)"></span>
                            <span class="star" onclick="rate(2)"></span>
                            <span class="star" onclick="rate(3)"></span>
                            <span class="star" onclick="rate(4)"></span>
                            <span class="star" onclick="rate(5)"></span>
                        </div>

                        <label for="rating-comment" class="form-label" id="labelforcomment" style="color:gray;">Product Comment</label>
                        <input type="input" class="form-control" id="rating-comment">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-submit" onclick="submitRating()">Submit</button>
                </div>
            </div>
        </div>
    </div>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add an event listener to the document to capture clicks on the cancel-order-btn class
            document.addEventListener('click', function(event) {
                // Check if the clicked element has the cancel-order-btn class
                if (event.target.classList.contains('cancel-order-btn')) {
                    // Get the order ID from the data-order-id attribute
                    var orderId = event.target.getAttribute('data-order-id');
                    console.log('Cancel button clicked for Order ID:', orderId);

                    // Call the cancelOrder function with the order ID
                    cancelOrder(orderId);
                }
            });
        });

        function cancelOrder(orderId) {
            console.log('Cancel order function called for order ID:', orderId);

            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Set up the AJAX request
            xhr.open('POST', '../functions/cancel_order.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            // Set up the callback function for when the request completes
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        // You can handle the response here if needed
                        console.log(xhr.responseText);

                        // Reload the page or update the order status dynamically
                        location.reload();
                    } else {
                        console.error('AJAX Error:', xhr.status, xhr.statusText);
                        console.log(xhr.responseText); // Log the responseText for more details
                    }
                }
            };

            // Prepare the data to be sent
            var data = 'order_id=' + encodeURIComponent(orderId);

            // Send the AJAX request
            xhr.send(data);
        }
    </script>




    <script>
        let currentRating = 0;
        let currentOrderId = null;

        // Function to set the order ID in the modal
        function setOrderId(orderId) {
            // Set the order ID in the data-order-id attribute of the modal trigger button
            $('.order-received-btn').attr('data-order-id', orderId);
            currentOrderId = orderId;
            console.log(currentOrderId);
        }

        // Function to get the order ID and open the modal
        $('.order-received-btn').click(function() {
            // Get the order ID from the data-order-id attribute
            var orderId = $(this).attr('data-order-id');
            console.log('Order ID:', orderId);

            // Set the order ID in the modal
            setOrderId(orderId);

            // Open the modal
            $('#ratemodal').modal('show');
        });

        // Function to submit the rating
        function submitRating() {
            // Get the order ID from the data-order-id attribute
            var orderId = $('.order-received-btn').attr('data-order-id');
            console.log('Submitting Rating for Order ID:', orderId);

            // Log the data to be sent
            console.log("Data to be sent:", {
                orderId: orderId,
                rating: currentRating,
                comment: $('#rating-comment').val()
            });

            // Disable the submit button to prevent multiple submissions
            $('.btn-submit').prop('disabled', true);

            // Make an AJAX request to submit the rating to the server
            $.ajax({
                url: '../functions/order-rating.php', // Replace with the actual path to your server-side script
                method: 'POST',
                data: {
                    orderId: orderId,
                    rating: currentRating,
                    comment: $('#rating-comment').val()
                },
                success: function(response) {
                    console.log(response); // Log the server response
                    location.reload();
                    // Handle the response or update the UI as needed
                },
                error: function(error) {
                    console.error('Error:', error);
                    // Handle errors if needed
                },
                complete: function() {
                    // Re-enable the submit button after the request is complete
                    $('.btn-submit').prop('disabled', false);
                    // Close the modal
                    $('#ratemodal').modal('hide');
                }
            });
        }

        function rate(star) {
            currentRating = star;
            updateStars();
            updateRatingValue();
            console.log("ds");
        }

        function updateStars() {
            const stars = document.querySelectorAll(".rating .star");
            stars.forEach((star, index) => {
                if (index < currentRating) {
                    star.classList.add("active");
                } else {
                    star.classList.remove("active");
                }
            });
        }

        function updateRatingValue() {
            document.getElementById("selected-rating").textContent = currentRating;
        }

        // Add an event listener to the modal close event
        $('#ratemodal').on('hidden.bs.modal', function() {
            // Reset the rating and order ID when the modal is closed
            currentRating = 0;
            currentOrderId = null;
            updateStars();
            updateRatingValue();

            $('#rating-comment').val('');
        });

        // Add an event listener to the modal submit button
        $('.btn-submit').on('click', function() {
            submitRating();
        });
    </script>


</body>

</html>