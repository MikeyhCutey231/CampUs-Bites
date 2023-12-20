<?php
include_once '../../Customer/functions/view-item.php';
require_once '../../Admin/functions/dbConfig.php';



// Set session variables
$database = new Connection();
$conn = $database->conn;

$productClass = new Product($conn);
$defaultProductId = 0;

if (isset($_GET["productId"]) && !empty($_GET["productId"])) {
    $defaultProductId = $_GET["productId"];
    $prodDetails = $productClass->getProductDetails($defaultProductId);
    $_SESSION['product_id'] = $_GET["productId"];
} else {
    // Handle the case where productId is not provided
    echo "No product ID provided!";
    exit;
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <!-- Link to Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Link to css and js -->
    <link rel="stylesheet" href="../customer_css/customer-view-item.css">
    <link rel="stylesheet" href="../customer_css/customer-navbar-nosearch.css">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>



<body>
    <!-- NAVBAR -->
    <div class="wrapper">
        <?php include 'customer_navbar_nosearch.php' ?>
    </div>


    <!-- Category Container -->
    <div class="content ">
        <div class="container-fluid  align-items-center justify-content-between mb-sm-3 mb-2 item-panel">
            <div class="row p-lg-3 p-md-2 justify-content-between d-flex align-items-center item-con">
                <div class="col-12 col-sm-4  col-lg-3 p-lg-2 p-md-1 p-sm-2 p-0 item-pic-container" style="overflow: hidden;" data-prodPic-unique="<?php echo $prodDetails['PROD_PIC'] ?>">
                    <img src="../../Icons/<?php echo isset($prodDetails['PROD_PIC']) ? $prodDetails['PROD_PIC'] : ''; ?>" alt="item-pic" class="item-picture">
                </div>

                <p id="pID" style="display: none;" value="<?php echo $defaultProductId ?>" data-prodID-unique="<?php echo $defaultProductId ?>"><?php echo $defaultProductId ?></p>


                <div class="col-12 col-sm-8 col-md-8 col-lg-9 p-lg-2 p-md-1 p-sm-2 p-1 justify-content-between align-items-center item-deets-container">
                    <div class="container-fluid py-1 py-sm-0  align-items-center item-header-con">
                        <div class="col-12">
                            <h5 class="item-name align-items-center d-flex" data-productName-unique="<?php echo $prodDetails['PROD_NAME'] ?>">
                                <?php echo isset($prodDetails['PROD_NAME']) ? $prodDetails['PROD_NAME'] : 'Product Name Here'; ?>
                            </h5>
                        </div>
                        <div class="col-12 py-0 align-items-center item-price-con">
                            <div class="item-price" data-price-unique="<?php echo $prodDetails['PROD_SELLING_PRICE'] ?>">
                                ₱<?php echo isset($prodDetails['PROD_SELLING_PRICE']) ? $prodDetails['PROD_SELLING_PRICE'] : 'Price Here'; ?>
                            </div>
                        </div>
                        <div class="col-12 item-sold-rate mb-1 ">
                            <div class="container-fluid p-0 mb-1 mb-sm-0 item-deets-con">

                                <p class="item-ratingText"><i class="item-star fa-solid fa-star"></i>
                                    <?php echo isset($prodDetails['ORDER_RATING']) ? $prodDetails['ORDER_RATING'] : 'Rating Here'; ?>
                                </p>

                                <div class="p-0  d-flex px-1 item-num-sold">
                                    - &nbsp; <?php echo isset($prodDetails['PROD_SOLD']) ? $prodDetails['PROD_SOLD'] : '0'; ?> sold
                                </div>
                            </div>
                        </div>
                        <div class="col-12 py-0 align-items-center border line-divider"> </div>
                    </div>

                    <div class="container-fluid align-items-center mt-sm-2 mt-md-0 item-details-con">
                        <div class="row py-lg-1 py-0">
                            <div class="col-sm-2 col-3 py-1 align-top description">
                                Description:
                            </div>
                            <div class="col-sm-10 col-9  py-1 py-sm-2 description-deets">
                                <?php echo isset($prodDetails['PROD_DESC']) ? $prodDetails['PROD_DESC'] : 'Description Here'; ?>
                            </div>
                        </div>
                        <div class="row py-1">
                            <div class="col-sm-2 col-3 py-sm-1 py-0 align-top quantity">
                                Quantity:
                            </div>

                            <div class="col-sm-10 col-9 px-0 py-0 quantity-deets">
                                <div class="amount-container">
                                    <button class="minus-btn align-center" onclick="decrement()">-</button>
                                    <input type="text" name="quantity" id="quantity" value="0" readonly>
                                    <button class="plus-btn" onclick="increment()">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="row py-1">
                            <div class="col-sm-2 col-3 py-0 stocks">
                                Stocks:
                            </div>
                            <div class="col-sm-10 col-9  px-0 d-flex align-items-center stocks-deets">
                                <?php echo isset($prodDetails['PROD_REMAINING_QUANTITY']) ? $prodDetails['PROD_REMAINING_QUANTITY'] : 'Available Items Here'; ?> available items
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid mt-lg-2 mt-1 d-flex px-2 justify-content-end item-details-con">

                        <div id="btnBuyNow">
                            <button type="button" name="buyNow" id="btn-buyNow">Buy Now</button>
                        </div>

                        <a href="#" id="btnAddCart"><input type="button" name="addtocart" id="btn-addCart" value="Add to Cart"></a>
                    </div>

                </div>
            </div>
        </div>

        <script>
            var quantityInput = document.getElementById('quantity');
            var availableStock = <?php echo isset($prodDetails['PROD_REMAINING_QUANTITY']) ? $prodDetails['PROD_REMAINING_QUANTITY'] : 0; ?>;

            // Set the default quantity to 1
            quantityInput.value = 1;

            function increment() {
                var currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity < availableStock) {

                    quantityInput.value = currentQuantity + 1;
                } else {
                    alert("Sorry, there are not enough items in stock.");
                }
            }

            function decrement() {
                var currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity > 1) {
                    quantityInput.value = currentQuantity - 1;
                }
            }
        </script>

        <script>
            $(document).ready(function() {
                $("#btnAddCart").click(function() {

                    // Get quantity value
                    var quantity = $("#quantity").val();
                    var availableStock = <?php echo $prodDetails['PROD_REMAINING_QUANTITY']; ?>;

                    if (parseInt(quantity) <= availableStock) {

                    // Make an AJAX request to the PHP script
                    $.ajax({
                        type: "POST",
                        url: "../functions/process-add-cart.php",
                        contentType: 'application/json',
                        data: JSON.stringify({
                            productId: <?php echo $defaultProductId; ?>, // Send the product ID
                            quantity: quantity,
                            // Add other data if needed
                        }),
                        success: function(response) {
                            if (response && response.status === 'success') {
                                // Handle success
                                console.log(response);
                                location.reload();
                                // You can redirect to a new page or perform other actions based on the response
                            } else {
                                // Handle error
                                console.error("Error:", response);
                            }
                        },
                        error: function(error) {
                            console.error("Error:", error);
                            // Handle the error (if needed)
                        }
                    });
                    }
                    else {
                    alert("Sorry not enough stocks");
                }

                });
            });
        </script>


        <script>
            // Attach click event handler to the button
            $("#btn-buyNow").click(function() {
                var quantityInput = document.getElementById('quantity');
                var availableStock = <?php echo $prodDetails['PROD_REMAINING_QUANTITY']; ?>;

                if (parseInt(quantityInput.value) <= availableStock) {
                    var prodPic = $(".item-pic-container").data("prodpicUnique");
                    var prodName = $(".item-name").data("productnameUnique");
                    var prodPrice = $(".item-price").data("priceUnique");
                    var quantity = $("#quantity").val();
                    var prodID = $("#pID").text();

                    $.ajax({
                        type: "POST",
                        url: "../../Customer/functions/buyNowHandler.php",
                        data: {
                            prodPic: prodPic,
                            prodName: prodName,
                            prodPrice: prodPrice,
                            quantity: quantity,
                            prodID: prodID
                        },
                        success: function(response) {
                            console.log(response);
                            if (response && response.status === 'success') {
                                window.location.href = "customer-checkout2.php" +
                                    "?prodPic=" + encodeURIComponent(prodPic) +
                                    "&prodName=" + encodeURIComponent(prodName) +
                                    "&prodPrice=" + encodeURIComponent(prodPrice) +
                                    "&quantity=" + encodeURIComponent(quantity) +
                                    "&prodID=" + encodeURIComponent(prodID);
                            } else {
                                console.error("Error:", response);
                            }
                        },
                    });
                } else {
                    alert("Sorry not enough stocks");
                }
            });
        </script>






        <script>
            function buyNowOrder() {
                // Assuming you have the order ID available, replace 123 with the actual order ID
                var cartId = <?php echo json_encode($cartId); ?>;
                var orderType = document.querySelector('.selectedOrderType');
                var shippingfee = <?php echo json_encode($shippingTotal); ?>;


                if (orderType = "DELIVERY") {
                    orderType = 1;
                } else orderType = 2


                // Make an AJAX request to update ol_cart_status to "dead"
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../functions/complete_order.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Send the order ID as a parameter
                var data = "ol_cart_id=" + cartId +
                    "&order_type=" + orderType +
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


        <!--Ratings-->
        <div class="container-fluid  align-items-center justify-content-between mb-lg-5 mb-md-4 mb-3 ratings-panel">
            <div class="row p-lg-3 p-md-2 justify-content-between d-flex align-items-center ">
                <div class="col-12 px-2 px-md-0 py-lg-2 py-1 mb-sm-2 mb-0 justify-content-between align-items-center rate-header">
                    <div class="container-fluid d-flex p-0 mb-1 mb-sm-0">
                        <div class="rating-title  align-items-center d-flex ">Item Reviews </div>
                        <div class="rating-dash align-items-center d-flex px-1 px-md-2 "><img src="dash.png" alt=""></div>
                        <div class=" align-items-center d-flex"><i class="rating-star-all fa-solid fa-star"></i></div>
                        <div class="ratingText-all align-items-center d-flex"> <?php echo isset($prodDetails['ORDER_RATING']) ? $prodDetails['ORDER_RATING'] : 'Rating Here'; ?></div>

                        <div class="rating-outOf px-1 align-items-center d-flex ">out of 5</div>
                    </div>
                </div>
                <div class="rating-body">
                    <?php
                    $ratings = $productClass->getProductRatings($defaultProductId);
                    foreach ($ratings as $relatedRating) :
                    ?>
                        <div class="col-12 px-2 px-md-0 py-1 mb-1 mt-1 rate-each">
                            <div class="container-fluid p-0 mb-2">
                                <div class="align-items-center d-flex">

                                    <?php
                                    // Assuming ORDER_RATING is a numeric value
                                    for ($i = $relatedRating['orderRating']; $i > 0; $i--) :
                                    ?>
                                        <i class="rating-star fa-solid fa-star<?= ($i <= $relatedRating['orderRating']) ? ' filled' : '' ?>"></i>
                                    <?php endfor; ?>

                                </div>
                                <div class="by-name">by <?= substr($relatedRating['userName'], 0, 1) . str_repeat("*", strlen($relatedRating['userName']) - 2) . substr($relatedRating['userName'], -1) ?></div>
                                <div class="rating-date align-items-center d-flex"><?= $relatedRating['dateCreated'] ?></div>
                                <div class="rating-comment align-items-center px-0 py-1 d-flex justify-content-between"><?= $relatedRating['orderComment'] ?></div>
                            </div>
                        </div>
                    <?php endforeach;

                    ?>
                </div>
                <div class="col-12 px-2 px-md-0  mb-2 mt-lg-3 mt-md-2 mt-sm-1 ">
                    <div class="container-fluid p-0">
                        <div class="see-more p-0 d-flex align-items-center justify-content-center see-more-toggle">Show all</div>
                    </div>
                </div>


            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Define the number of ratings to initially show and the step size for each "See more" click
                var ratingsToShowInitially = 15;
                var stepSize = 5;

                // Hide ratings beyond the initial count
                $('.rate-each:gt(' + (ratingsToShowInitially - 1) + ')').hide();

                // Handle "Show all" click
                $('.see-more-toggle').on('click', function() {
                    // Toggle visibility of all ratings except the initial ones
                    $('.rate-each:gt(' + (ratingsToShowInitially - 1) + ')').toggle();

                    // Toggle the text of the "See more" div
                    $(this).text(function(i, text) {
                        return text === "Show all" ? "See less" : "Show all";
                    });
                });
            });
        </script>

        <!--RELATED ITEMS-->
        <h5 class="px-1 related">Related Items</h5>
        <?php
        $selectedProductId = $defaultProductId;

        // Function to get the category ID for the selected product
        function getCategoryID($conn, $selectedProductId)
        {
            $query = "SELECT CATEGORY_ID FROM product WHERE PROD_ID = ?";

            $stmt = $conn->prepare($query);

            if ($stmt === false) {
                die('Error in preparing the statement: ' . $conn->error);
            }

            $stmt->bind_param("i", $selectedProductId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['CATEGORY_ID'];
            }

            return null;
        }

        // Get category ID for the selected product
        $selectedCategoryId = getCategoryID($conn, $selectedProductId);

        // Initialize $output before using it
        $output = '';

        // Check if category ID is available

        if ($selectedCategoryId !== null) {
            $relatedProductsQuery = "SELECT
            product.PROD_ID,
            product.PROD_NAME,
            product.PROD_SELLING_PRICE,
            product.PROD_SOLD,
            product.PROD_PIC,
            product.PROD_REMAINING_QUANTITY,
            COALESCE(ROUND(AVG(online_order.ORDER_RATING), 1), 'N/A') AS ORDER_RATING
                FROM online_order
                INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
                INNER JOIN online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
                RIGHT JOIN product ON online_cart_item.PROD_ID = product.PROD_ID
                LEFT JOIN users ON ol_cart.CUSTOMER_ID = users.USER_ID 
                WHERE product.CATEGORY_ID = ? AND product.PROD_ID != ?
                GROUP BY product.PROD_ID LIMIT 15 ";
            $stmt = $conn->prepare($relatedProductsQuery);

            if ($stmt === false) {
                die('Error in preparing the statement: ' . $conn->error);
            }

            $stmt->bind_param("ii", $selectedCategoryId, $selectedProductId);
            $stmt->execute();
            $result = $stmt->get_result();

            echo '<div class="row">'; // Start the row container

            while ($row = $result->fetch_object()) {
                echo '
            <div class="col-6 col-lg-2 col-md-3 col-sm-4 p-sm-2 p-1 mb-1">
                <a href="customer-view-item.php?productId=' . $row->PROD_ID . '" class="view-item-link" data-product-id="' . $row->PROD_ID . '">
                    <div class="card justify-content-center d-flex border" style="overflow:hidden;">
                        <div class="row itemimage-con" style="overflow:hidden; border-radius: 10px 10px 0px 0px;">
                            <img src="../../Icons/' . $row->PROD_PIC . '" id="itemImage" alt="Food Item" >
                        </div> 
                        <div class="card-body py-sm-2 py-1">
                            <div class="row px-0"> 
                                <div class="col-12 px-1 py-0 ">
                                    <h5 class="card-title py-0">' . $row->PROD_NAME . '</h5>
                                    <div class="p-0 align-items-center foodPrice">₱' . $row->PROD_SELLING_PRICE . '</div>
                                    <div class="container-fluid d-flex  p-0 deets-con">
                                        <div class="d-flex p-0 justify-content-start ratingContainer">
                                            <p class="ratingText"><i class="star fa-solid fa-star"></i> ' . $row->ORDER_RATING . '</p>
                                        </div>
                                        <div class="container-fluid d-flex px-1 num-sold"> -' . $row->PROD_SOLD . ' sold</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>';
            }


            echo '</div>'; // End the row container
        } else {
            echo '<div class="col-12"><h3>Category ID not found for the selected product.</h3></div>';
        }
        ?>


    </div>
    </div>


    <script>
        $(document).ready(function() {

            $dProductId = <?php echo $defaultProductId; ?>;
            // Function to update content based on the selected product
            function updateProductDetails(productId) {
                $.ajax({
                    type: "GET",
                    url: "../functions/get_product_details.php",
                    data: {
                        productId: productId
                    },
                    dataType: "json",
                    success: function(response) {
                        // Update content with the received details
                        $("#item-picture").attr("src", response.details.PROD_PIC);
                        $(".item-name").text(response.details.PROD_NAME);
                        $(".item-price").text("₱" + response.details.PROD_SELLING_PRICE);
                        $(".description-deets").text(response.details.PROD_DESC);
                        $(".stocks-deets").text(response.details.PROD_REMAINING_QUANTITY + " available items");

                        // Update other details accordingly

                        // Display ratings
                        displayRatings(response.ratings);
                    },
                    error: function(error) {
                        console.log("Error fetching product details:", error);
                    }
                });
            }

            // Function to display ratings in HTML
            function displayRatings(ratings) {
                var html = '';

                // Iterate through the ratings array
                for (var i = 0; i < ratings.length; i++) {
                    var rating = ratings[i];

                    // Build HTML for each rating
                    html += '<div class="col-12 px-2 px-md-0 py-1 mb-1 mt-1 rate-each">';
                    html += '<div class="container-fluid p-0 mb-2">';
                    html += '<div class="align-items-center d-flex">';

                    // Assuming ORDER_RATING is a numeric value
                    for (var j = rating.orderRating; j > 0; j--) {
                        html += '<i class="rating-star fa-solid fa-star' + (j <= rating.orderRating ? ' filled' : '') + '"></i>';
                    }

                    html += '</div>';
                    html += '<div class="by-name">by ' + rating.userName + '</div>';
                    html += '<div class="rating-date align-items-center d-flex">' + rating.dateCreated + '</div>';
                    html += '<div class="rating-comment align-items-center px-0 py-1 d-flex justify-content-between">' + rating.orderComment + '</div>';
                    html += '</div>';
                    html += '</div>';
                }

                // Update the HTML content with the generated ratings HTML
                $(".rate-body").html(html);
            }

            // Load default item details (you can set the default productId)
            updateProductDetails(dProductId);


            // Event handler for related product click
            $(".view-item-link").on("click", function(event) {
                event.preventDefault();
                var productId = $(this).data("product-id");
                updateProductDetails(productId);
            });
        });
    </script>




</body>

</html>