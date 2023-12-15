<?php
include("../../Admin/functions/dbConfig.php");
$customerID = $_SESSION['Customer_ID'];

include("../functions/menuListfunctions.php");
require_once '../classes/cart.class.php';
require_once '../functions/notifications.php';
require_once  '../functions/getProfile.php';

$database = new Connection();
$conn = $database->conn;

$countCart = new Cart();
$cartItem = $countCart->getTotalItem($customerID);


$countNotif = new Notification();
$notifTotal = $countNotif->countNotification($customerID);


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
    <title>Campus Bites</title>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Link to Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Link to js bootsrap 5.3.2 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <!-- Link to Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">



    <!-- RateYo JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>


    <!-- Link to css and js -->
    <link rel="stylesheet" href="../../Customer/customer_css/customer-menu.css">
</head>

<body>
    <!-- NAVBAR -->
    <div class="wrapper">
        <div class="container-fluid py-md-3 justify-content-between banner ">
            <div class="row justify-content-between align-items-center  px-lg-5 px-md-4 px-sm-3 px-2  d-flex banner-row">

                <div class="col-lg-5 col-md-4 col-sm-1 col-0 p-0 d-flex align-items-center justify-content-start title-logo">
                    <a href="customer-menu.php">
                        <div class="logo-con" style="height: 50px; width: 46px;">
                            <img src="campus.png" alt="">
                        </div>
                    </a>
                    <a href="customer-menu.php">
                        <h1 class="title">Campus Bites</h1>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="col-lg-7 col-md-8 col-sm-11 col-12 p-0 d-flex align-items-center justify-content-end">
                    <div class="input-group search-con">
                        <input type="text" class="form-control searchbar" id="search-product" name="input" placeholder="Search an item here...">
                        <span class="input-group-text d-flex align-items-center justify-content-center" style="background-color: #9C1421;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="white" class="bi bi-search search-icon" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                            </svg>
                        </span>
                    </div>


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
                            } ?>
                        </div>
                    </a>

                    <a href="customer-notification.html" class="p-0 d-flex align-items-center ">
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
                            <img class="pofile-pic" src="cute27.png" style="height: 100%; width: 100%;">
                        </div>
                    </a>

                </div>
                <div class=" col-md-2 col-sm-3 col-5  d-flex justify-content-between align-items-center icon-con">

                </div>
            </div>
        </div>
    </div>

    <!-- Search Script -->
    <script>
        // Add an event listener for the search input
        document.getElementById('search-product').addEventListener('input', function() {
            // Get the value from the search input
            var searchValue = this.value.trim();

            // Perform an AJAX request to filter products based on the search value
            $.ajax({
                type: 'POST',
                url: "../functions/categoryFilterHandler.php", // Replace with the correct path
                data: {
                    action: 'search',
                    searchValue: searchValue
                },
                success: function(response) {
                    // Update the product grid with the filtered products
                    $('#product-grid').html(response);
                }
            });
        });
    </script>

    <!-- Category Container -->
    <div class="content px-lg-5 px-md-4  px-3">
        <div class="categories-panel">
            <div class="mt-md-3 mt-2 p-0 d-flex align-items-center text-left">
                <div class="categoryTitle">Categories</div>
            </div>
            <div class="categories-carousel">
                <div class="icon"><i id="left" class="fa-solid fa-chevron-left"></i></div>
                <ul class="tabs-box px-md-1 p-0">

                    <button type="button">
                        <li class="tab active" id="prodName" value="0">
                            <img src="../../Icons/cooked.svg" alt="" width="25px" style="padding-right: 5px;">
                            All Products
                        </li>
                    </button>
                    <?php
                    $categoryList = new MenuList($conn);
                    $data = $categoryList->itemCategory();
                    $categoryList->renderCategoryTable($data);
                    ?>
                </ul>
                <div class="icon"><i id="right" class="fa-solid fa-chevron-right"></i></div>
            </div>
        </div>

        <script>
            const tabsBox = document.querySelector(".tabs-box"),
                allTabs = document.querySelectorAll(".tab"),
                arrowIcons = document.querySelectorAll(".icon i");

            let isDragging = false;


            const handleIcons = () => {
                let scrollVal = Math.round(tabsBox.scrollLeft);
                let maxScrollableWidth = tabsBox.scrollWidth - tabsBox.clientWidth;
                arrowIcons[0].parentElement.style.display = scrollVal > 0 ? "flex" : "none";
                arrowIcons[1].parentElement.style.display = maxScrollableWidth > scrollVal ? "flex" : "none";
            }

            arrowIcons.forEach(icon => {
                icon.addEventListener("click", () => {
                    tabsBox.scrollLeft += icon.id === "left" ? -350 : 350;
                    setTimeout(() => handleIcons(), 50);
                });
            });


            allTabs.forEach(tab => {
                tab.addEventListener("click", () => {
                    tabsBox.querySelector(".active").classList.remove("active");
                    tab.classList.add("active")
                });
            });

            const dragging = (e) => {
                if (!isDragging) return;
                tabsBox.classList.add("dragging");
                tabsBox.scrollLeft -= e.movementX;
                handleIcons();
            }

            const dragStop = () => {
                isDragging = false;
                tabsBox.classList.remove("dragging");
            }

            tabsBox.addEventListener("mousedown", () => isDragging = true);
            tabsBox.addEventListener("mousemove", dragging);
            document.addEventListener("mouseup", dragStop);
        </script>



        <!-- Main Content -->
        <div class="container-fluid mt-2  align-items-center  menu-con mb-5">
            <div class="row p-0 justify-content-end">
                <div class="col-12 px-sm-2 px-0 align-items-center justify-content-end filterContainer ">
                    <div class="dropdown">
                        <button type="button" class="filterButton btn btn-primary btn-warning">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.6661 3.37695H2.1416L9.1514 11.666V17.3966L12.6563 19.149V11.666L19.6661 3.37695Z" stroke="white" stroke-width="1.75245" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="filterText">Filter</span>
                        </button>
                        <div class="container  px-md-3 px-1 py-sm-2 py-1 dropdown-content border">
                            <div class="container-fluid">

                                <div class="row py-lg-3 py-2 px-0  justify-content-between" style="border-bottom: 1px solid #F0C419;">
                                    <div class="col-12">
                                        <h5 class="filterPrice">Price Range</h5>
                                        <div class="row ">

                                            <div class="col-6 px-1 ">
                                                <input class="priceInput form-control" type="text" name="min_price" placeholder="₱MIN">
                                            </div>
                                            <div class="col-6 px-1 ">
                                                <input class="priceInput form-control" type="text" name="max_price" placeholder="₱MAX">
                                            </div>
                                            <input type="hidden" id="hidden_minimum_price" value="0" />
                                            <input type="hidden" id="hidden_maximum_price" value="65000" />

                                            <div id="price_range" style="margin-top:10px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row py-lg-3 px-0  py-sm-2 py-1 justify-content-between">
                                <div class="col-12">
                                    <h5 class="filterPrice">Rating</h5>
                                    <div id="rating" name="rating"></div>
                                </div>
                            </div>

                            <div class="row mt-md-1 py-2 ">
                                <div class="col-12 d-flex justify-content-center">
                                    <button class="btnApply btn" name="btn-apply" style="background-color: #9C1421;">
                                        <p class="apply">APPLY</p>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let currentCategoryId = "";
        </script>
        <script>
            // JavaScript to toggle the active state of the dropdown
            const dropdownButton = document.querySelector('.filterButton');
            const dropdownContent = document.querySelector('.dropdown-content');

            dropdownButton.addEventListener('click', function() {
                // Toggle the "active" class to change the button's background color
                this.classList.toggle('active');

                // Toggle the display of the dropdown content
                dropdownContent.style.display = dropdownContent.style.display === 'none' ? 'block' : 'none';
            });
        </script>

        <script>
            $(document).ready(function() {
                $("#rating").rateYo({
                    rating: 0, // Initial rating value
                    starWidth: "20px", // Width of each star
                    ratedFill: "#FFD700", // Color of filled stars
                    numStars: 5, // Number of stars
                    precision: 0, // Number of decimal places for average rating
                    onSet: function(rating, rateYoInstance) {
                        // Handle the rated value (rating) here, e.g., send it to the server via AJAX
                        console.log("Rated: " + rating);
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                filter_data();

                function filter_data() {
                    $('#product-grid').html('<div id="loading" style="" ></div>');
                    var action = 'fetch_data';
                    var minimum_price = $('#hidden_minimum_price').val();
                    var maximum_price = $('#hidden_maximum_price').val();
                    var rating = $("#rating").rateYo("rating");
                    $.ajax({
                        url: "../functions/categoryFilterHandler.php",
                        method: "POST",
                        data: {
                            action: action,
                            minimum_price: minimum_price,
                            maximum_price: maximum_price,
                            rating: rating,
                            categoryID: currentCategoryId
                        },
                        success: function(data) {
                            $('#product-grid').html(data);
                        }
                    });
                }
                $('#price_range').slider({
                    range: true,
                    min: 1,
                    max: 300,
                    values: [1, 300],
                    step: 1,
                    stop: function(event, ui) {
                        $('#price_show').html(ui.values[0] + ' - ' + ui.values[1]);
                        $('#hidden_minimum_price').val(ui.values[0]);
                        $('#hidden_maximum_price').val(ui.values[1]);
                        $('input[name="min_price"]').val(ui.values[0]).prop('disabled', true);
                        $('input[name="max_price"]').val(ui.values[1]).prop('disabled', true);
                        filter_data();
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {

                $('li.tab').click(function() {
                    var categoryID = $(this).attr('value');
                    currentCategoryId = categoryID;

                    $.ajax({
                        type: "POST",
                        url: "../functions/categoryFilterHandler.php",
                        data: {
                            categoryID: categoryID
                        },
                        success: function(data) {
                            // Clear the existing content in the card-container
                            $('#product-grid').empty();
                            $('#product-grid').append(data);

                        }
                    });
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                // Function to handle pagination and filtering
                function handlePagination(page) {
                    // Get filter values
                    var minimum_price = $('input[name="min_price"]').val();
                    var maximum_price = $('input[name="max_price"]').val();
                    var rating = $("#rating").rateYo("rating");

                    // AJAX request to fetch filtered data for the selected page
                    $.ajax({
                        url: "../functions/categoryFilterHandler.php",
                        method: "POST",
                        data: {
                            action: 'fetch_data',
                            minimum_price: minimum_price,
                            maximum_price: maximum_price,
                            rating: rating,
                            categoryID: currentCategoryId,
                            page: page // Add the page parameter
                        },
                        success: function(data) {
                            // Update the filtered data in your HTML container
                            $('#product-grid').html(data);
                        },
                        error: function(xhr, status, error) {
                            // Handle errors if any
                            console.error(error);
                        }
                    });
                }

                // Apply button click event
                $('.btnApply').on('click', function() {
                    // Initial page should be 1 when applying filters
                    handlePagination(1);
                });

                // Your existing RateYo initialization code
                $("#rating").rateYo({
                    // RateYo initialization options
                });

                // Handle pagination click event
                $('.pagination a').click(function(e) {
                    e.preventDefault();

                    // Get the page number from the link
                    var page = $(this).attr('href').split('=')[1];

                    // Handle pagination and filtering
                    handlePagination(page);
                });
            });
        </script>

        <!-- Product Container -->
        <div class="row" style="margin-top:-40px;" id="product-grid">
            <?php
            require_once '../../Admin/functions/dbConfig.php';
            require_once '../functions/menuListFunctions.php';

            // Instantiate the MenuList object with a valid database connection
            $menuList = new MenuList($conn);

            // Get the current page from the URL parameters
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

            $limit = 18; // Set your desired limit here
            $offset = ($page - 1) * $limit;

            // Get the product list based on the category filter
            $categoryID = isset($_GET["categoryID"]) ? $_GET["categoryID"] : null;
            $products = $menuList->productList($page, $limit, $categoryID);

            // Display the products
            $menuList->renderProductList($products);
            ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php

                // Display the page numbers dynamically
                $totalProducts = $menuList->getTotalProducts();
                $totalPages = ($limit > 0) ? ceil($totalProducts / $limit) : 1;

                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . (isset($categoryID) ? '&categoryID=' . $categoryID : '') . '">' . $i . '</a></li>';
                }

                ?>
            </ul>
        </nav>

    </div>

    <script>
        // Function to handle category filtering
        function filterCategory(categoryID) {
            // Update the URL with the selected category
            history.pushState(null, null, "?category=" + categoryID);
            // Send an AJAX request to categoryFilterHandler.php with the selected category
            $.post("../functions/categoryFilterHandler.php", {
                categoryID: categoryID
            }, function(data) {
                // Update the product grid with the filtered results
                $("#product-grid").html(data);
            });
        }

        // Check if a category is selected in the URL when the page loads
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const categoryID = urlParams.get("category");
            if (categoryID !== null) {
                filterCategory(categoryID);
            }
        });

        // Handle category tab clicks
        $(".tabs-box li a").click(function(e) {
            e.preventDefault();
            const categoryID = $(this).attr("href").split("=")[1];
            filterCategory(categoryID);
        });
    </script>
    <script>
        $(document).ready(function() {
            // Handle click event on category links
            $('.tabs-box li').click(function(e) {
                e.preventDefault();

                // Remove the 'active' class from all tabs
                $('.tabs-box li').removeClass('active');

                // Add the 'active' class to the clicked tab
                $(this).addClass('active');

                // Update the styles based on the active state
                updateStyles();
            });

            // Function to update styles based on active state
            function updateStyles() {
                // Iterate through each tab and update styles
                $('.tabs-box li').each(function() {
                    var link = $(this).find('a');

                    if ($(this).hasClass('active')) {
                        // If the tab is active, set the font color to white
                        link.css('color', 'white');
                    } else {
                        // If the tab is inactive, set the font color to black
                        link.css('color', 'black');
                    }
                });
            }

            // Initial styles update
            updateStyles();
        });
    </script>


</body>

</html>