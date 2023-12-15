<?php
require_once '../../Admin/functions/dbConfig.php';
require_once '../functions/check-out2.php';
$database = new Connection();
$conn = $database->conn;


$customer_id = $_SESSION['Customer_ID'];

$prodPic = isset($_GET["prodPic"]) ? urldecode($_GET["prodPic"]) : '';
$prodName = isset($_GET["prodName"]) ? urldecode($_GET["prodName"]) : '';
$prodPrice = isset($_GET["prodPrice"]) ? urldecode($_GET["prodPrice"]) : '';
$quantity = isset($_GET["quantity"]) ? urldecode($_GET["quantity"]) : '';
$prodID = isset($_GET["prodID"]) ? urldecode($_GET["prodID"]) : '';
$subtotal = $prodPrice * $quantity;
$orderTotal = $subtotal;


//shipping
$shippingPercentage = 0.05;
$shippingFixedPrice = 10;
$shippingTotal = $orderTotal * $shippingPercentage + $shippingFixedPrice;

$details = new Checkout();

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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <!-- Link to Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Link for dropdown -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Link to css and js -->
    <link rel="stylesheet" href="../customer_css/customer-checkout.css">
</head>

<body>
    <!-- NAVBAR -->
    <div class="wrapper">
        <?php include 'customer_navbar_nosearch.php' ?>
    </div>

    <div class="content  justify-content-between ">
        <div class="container-fluid checkout-con ">
            <div class="row checkout-title  px-2 px-sm-0">
                Checkout
            </div>
            <div class="row address-header px-sm-2 py-sm-2 p-1 mb-2 mb-lg-3">
                <div class="col-12  px-sm-2 d-flex  py-sm-2  px-1 align-items-center justify-content-start">

                    <div class="col-10 p-0">
                        <div class="dropdown p-0">
                            <button class="btn dropdown-toggle btn-ordertype justify-content-between d-flex" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Order type:
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" onclick="handleOrderType('delivery')" aria-selected="true">Delivery</a>
                                <a class="dropdown-item" href="#" onclick="handleOrderType('pickup')">Pick-up</a>


                            </div>
                        </div>
                    </div>
                    <div class="col-2 px-3 d-flex align-items-center justify-content-center">
                        <div class="selectedOrderType d-flex align-items-center justify-content-center">DELIVERY</div>

                    </div>
                </div>



                <div class="p-2 " id="deliverySection">
                    <div class="col-12  px-sm-2  py-sm-1 py-1 px-1 d-flex align-items-center justify-content-start delivery-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                        </svg>
                        Delivery Address
                    </div>

                    <div class="d-flex">
                        <div class="col-sm-10 col-9  py-0 px-2 d-flex align-items-center justify-content-between cust-address">
                            <form action="" method="">
                                <input type="address" id="address" name="address" value="<?php echo $details->getUserAddress($customer_id) ?>" disabled>
                        </div>
                        <div class="col-sm-2 col-3 px-md-0  p-sm-2 p-0 d-flex justify-content-center align-items-center">
                            <button class="btn edit-deets d-flex align-items-center justify-content-center p-0" id="editAddressBtn" type="button" data-bs-toggle="modal" data-bs-target="#editaddress">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                </svg>
                                Edit Address
                            </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="container-fluid item-container mb-md-4 mb-1 ">
            <div class="row  item-deets-header px-sm-3  py-sm-3 py-2 px-1">
                <div class="col-6 p-sm-2 p-1 d-flex align-items-center item-ordered">
                    Item ordered
                </div>
                <div class="col-2 p-sm-2 p-0 d-flex align-items-center justify-content-center">
                    <div class="unit-price">Unit Price</div>
                </div>
                <div class="col-2 p-sm-2 p-1 d-flex align-items-center  justify-content-center">
                    Quantity
                </div>
                <div class="col-2 p-sm-2 p-1 d-flex align-items-center justify-content-center">
                    Subtotal
                </div>
            </div>

            <div class="row item-con px-1 px-sm-3 px-sm-3 py-sm-2 py-1">
                <div class="col-sm-1 col-2 p-sm-2 p-1 d-flex  justify-content-center">
                    <img class="item-image" src="<?php echo $prodPic ?>" alt="">
                </div>
                <div class="col-sm-5 col-4  col-2 p-sm-2 p-1 d-flex align-items-center justify-content-start ">
                    <div class="item-name"><?php echo $prodName ?></div>
                </div>
                <div class="col-2 p-2  d-flex align-items-center justify-content-center">
                    <div class="item-price">₱<?php echo $prodPrice ?></div>
                </div>
                <div class="col-2 p-2  d-flex align-items-center justify-content-center">
                    <div class="item-quantity"><?php echo $quantity ?></div>
                </div>
                <div class="col-2 p-2  d-flex align-items-center justify-content-center">
                    <div class="item-subtotal">₱<?php echo number_format($subtotal, 2) ?></div>
                </div>
            </div>

            <div class="row subtotal-con px-sm-3 py-sm-3 p-2">
                <div class="col-10 p-2 d-flex align-items-center justify-content-end">
                    <div class="item-num">Order Total (<?php echo $quantity ?> item)</div>
                </div>
                <div class="col-2 p-2 d-flex align-items-center justify-content-center">
                    <div class="itemAll-subtotal">₱<?php echo number_format($subtotal, 2) ?></div>
                </div>
            </div>
        </div>

        <div class="container-fluid order-payment-con">
            <div class="row payment-header px-sm-3 py-sm-3 py-2 p-0">
                <div class="col-7 p-sm-2 p-1 d-flex align-items-center payment-title">
                    Payment Method
                </div>
                <div class="col-5 p-sm-2 p-1 d-flex align-items-center justify-content-end payment-mode">
                    Cash on Delivery
                </div>
            </div>

            <div class="row itemtotal-con px-sm-3 py-0 p-0">
                <div class="col-sm-10 col-9 py-sm-2 py-1 d-flex align-items-center justify-content-start">
                    <div class="totalProf-title">Product Subtotal:</div>
                </div>
                <div class="col-sm-2 col-3 d-flex align-items-center justify-content-end">
                    <div class="totalProd-value">₱<?php echo number_format($orderTotal, 2) ?> </div>
                </div>
            </div>

            <div class="row shippingtotal-con px-sm-3 p-0">
                <div class="col-sm-10 col-9 py-sm-2 py-1 d-flex align-items-center justify-content-start">
                    <div class="shipping-title">Shipping Total:</div>
                </div>
                <div class="col-sm-2 col-3  d-flex align-items-center justify-content-end">
                    <div class="shipping-value">₱<?php echo $shippingTotal ?></div>
                </div>
            </div>

            <div class="row total-con px-sm-3 p-0">
                <div class="col-sm-10 col-9 py-sm-2 py-1  d-flex align-items-center justify-content-start">
                    <div class="total-title">Total Payment:</div>
                </div>
                <div class="col-sm-2 col-3 py-sm-2 py-1 d-flex align-items-center justify-content-end">
                    <div class="total-value">₱<?php echo number_format($orderTotal + $shippingTotal, 2)  ?></div>
                </div>
            </div>

            <div class="row placeorder-con  px-sm-3 py-sm-3 py-2 px-0">
                <div class="col-12 px-sm-2 d-flex align-items-center justify-content-end">
                    <a href="#" id="placeOrderBtn" onclick="completeOrder()">
                        <div class="btn btn-placeholder d-flex align-items-center justify-content-center p-0">
                            Place Order
                        </div>
                    </a>
                </div>

            </div>
        </div>


    </div>


    </div>

    <!--Modal for edit-address-->
    <div class="modal fade" id="editaddress" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><b>Edit Address</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="address" class="form-label"><span style="color:#9C1421">*</span>Edit address inside the school</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentcolor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                                </svg>
                            </span>
                            <input type="input" class="form-control" id="newAddress" value="<?php echo $details->getUserAddress($customer_id) ?>">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-save" id="saveChangesBtn" onclick="saveChanges()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- javascripts-->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Add this script at the end of your HTML body -->

    <script>
        function handleOrderType(orderType) {
            var deliverySection = document.getElementById("deliverySection");
            var orderTypeButton = document.querySelector('.selectedOrderType');
            var totalPaymentElement = document.querySelector('.total-value');
            var paymentMode = document.querySelector('.payment-mode');
            var shippingTotal = document.querySelector(".shippingtotal-con");
            var newText = '';
            var paymode = '';

            if (orderType === "pickup") {
                // If the order type is "Pick-up", hide the delivery section
                deliverySection.style.display = "none";
                shippingTotal.style.display = "none";
                newText = 'PICK-UP';
                paymode = 'Cash on pick-up';


            } else if (orderType === "delivery") {
                // If the order type is "Delivery", show the delivery section
                newText = 'DELIVERY';

                deliverySection.style.display = "block";
                shippingTotal.style.display = "flex";
                paymode = 'Cash on delivery';

                paymode = 'Cash on delivery';

            }

            orderTypeButton.textContent = newText;
            paymentMode.textContent = paymode;

            // Update total payment based on the selected order type
            var orderTotal = parseFloat(<?php echo $orderTotal; ?>);
            var shippingTotal = parseFloat(<?php echo $shippingTotal; ?>);

            // Calculate new total payment based on the selected order type
            var newTotalPayment = orderType === 'pickup' ? orderTotal : orderTotal + shippingTotal;

            // Update the total payment element
            totalPaymentElement.innerHTML = '₱' + newTotalPayment.toFixed(2);
        }

        function editAddress() {
            // Implement the logic to handle the edit address functionality here
            alert("Edit Address clicked!");
        }
    </script>


    <script>
        function saveChanges() {
            // Get the new address from the input field
            var newAddress = document.getElementById('newAddress').value;
            // Make an AJAX request to update the user's address
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../functions/updateAddress.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Send the new address as a parameter
            xhr.send("newAddress=" + newAddress);

            // Handle the response from the server if needed
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // You can handle the response here if needed
                    console.log(xhr.responseText);
                    $('#editaddress').modal('hide');
                    // Reload the page
                    location.reload();
                    // Optionally, you can also update the displayed address on the page
                }
            };
        }
    </script>



    <script>
        function completeOrder() {

            var orderType = document.querySelector('.selectedOrderType');
            var shippingfee = <?php echo json_encode($shippingTotal); ?>;
            var customer_id = <?php echo json_encode($customer_id); ?>;
            var product_id = <?php echo json_encode($prodID); ?>;
            var quantity = <?php echo json_encode($quantity); ?>;

            // Assuming you have the order ID available, replace 123 with the actual order ID
            if (orderType.textContent === "DELIVERY") {
                orderType = 1;
            } else orderType = 2

            // Make an AJAX request to update ol_cart_status to "dead"
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../functions/complete_order2.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Send the order ID as a parameter
            var data = "customer_id=" + customer_id +
                "&order_type=" + orderType +
                "&product_id=" + product_id +
                "&quantity=" + quantity +
                "&shipping_fee=" + shippingfee;

            xhr.send(data);
            alert(data);


            // Handle the response from the server if needed
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // You can handle the response here if needed
                    console.log(xhr.responseText);
                }
            };
        }
    </script>



</body>

</html>