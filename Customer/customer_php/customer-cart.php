<?php
include '../includes/autoloader.inc.php';


$remover = new CartControl();
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];
    $remover->removeAll($customer_id);
}
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

    <!-- Link to css and js -->
    <link rel="stylesheet" href="../customer_css/customer-cart.css">
    <link rel="stylesheet" href="../customer_css/customer-navbar-nosearch.css">
</head>

<body>

    <!-- NAVBAR -->
    <div class="wrapper">
        <?php include 'customer_navbar_nosearch.php' ?>
    </div>

    <div class="content border justify-content-between ">
        <div class="container-fluid cart-container">
            <div class="row cart-title  px-2 px-sm-0">
                Cart
            </div>
            <div class="row cart-header py-sm-2 py-1 mb-1 mb-lg-2">
                <div class="col-md-6 col-4 p-sm-3 p-1 d-flex align-items-center">
                    Products
                </div>
                <div class="col-md-6 col-8 p-0  d-flex">
                    <div class="col-3 p-sm-2 p-1 d-flex align-items-center justify-content-center">
                        Unit Price
                    </div>
                    <div class="col-3 p-sm-2 p-1 d-flex align-items-center  justify-content-center">
                        Quantity
                    </div>
                    <div class="col-3 p-sm-2 p-1 d-flex align-items-center justify-content-center">
                        Total Price
                    </div>
                    <div class="col-3 p-sm-2 p-1 d-flex justify-content-center align-items-center">
                        <a href="customer-cart.php?customer_id=2" class="delete-all">
                            <div class="btn delete-all d-flex align-items-center justify-content-center p-0"> Delete All</div>
                        </a>
                    </div>
                </div>
            </div>

            <?php
            $showitems = new CartView();
            $showitems->showCartItems($_SESSION['Customer_ID']);

            ?>
        </div>
        <div class="check-out-con  py-lg-4 px-lg-5 py-sm-3 px-sm-4 py-2 px-3">
            <div class="container-fluid">

                <?php
                $cartTotal = new CartView();
                $cartTotal->showCartTotal($_SESSION['Customer_ID']);

                ?>

            </div>
        </div>

    </div>



</body>

</html>