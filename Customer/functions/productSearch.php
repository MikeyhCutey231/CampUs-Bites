<?php
require_once '../../Admin/functions/dbConfig.php';

// Function to display products with pagination
function displayProducts($result, $displayInColumn = true)
{
    // Determine the container class based on the display preference
    $containerClass = $displayInColumn ? 'd-flex flex-wrap' : '';

    echo "<div class=\"$containerClass\">"; // Start the container

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            echo '
                <div class="col-6 col-lg-2 col-md-3 col-sm-4 p-sm-2 p-1 mb-1">
                    <a href="../customer_html/customer-view-item.php?productId=' . $row->PROD_ID . '" class="view-item-link" data-product-id="' . $row->PROD_ID . '">
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
                                            <div class="container-fluid d-flex px-1 num-sold">' . $row->PROD_SOLD . ' sold</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>';
        }
    } else {
        echo '<div class="col-12"><h3>No products found.</h3></div>';
    }

    echo '</div>'; // End the container
}

// Function to get the total number of products
function getTotalProductsCount()
{
    $database = new Connection();
    $conn = $database->conn;

    $query = "SELECT COUNT(*) as total FROM product";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}

// Function to display products with pagination
function displayProductsWithPagination($page, $perPage, $displayInColumn = true)
{
    $start = ($page - 1) * $perPage;

    $database = new Connection();
    $conn = $database->conn;

    $query = "SELECT * FROM product LIMIT $start, $perPage";
    $result = mysqli_query($conn, $query);

    displayProducts($result, $displayInColumn);
}

// Function to search products
function searchProducts($input, $displayInColumn = true)
{
    // Create an instance of the Database class
    $database = new Connection();
    $conn = $database->conn;

    $result = false;

    // Check if the search input is not empty
    if (!empty($input)) {
        $query = "SELECT product.PROD_ID, product.PROD_NAME, product.PROD_SELLING_PRICE, product.PROD_SOLD, product.PROD_PIC,
        COALESCE(ROUND(AVG(online_order.ORDER_RATING), 1), 'N/A') AS ORDER_RATING
        FROM online_order
        INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
        INNER JOIN online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
        RIGHT JOIN product ON online_cart_item.PROD_ID = product.PROD_ID
        WHERE PROD_NAME LIKE '{$input}%' GROUP BY product.PROD_ID   ";
         $result = mysqli_query($conn, $query);
    }

   

    displayProducts($result, $displayInColumn);
}

$perPage = 18;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$totalProducts = getTotalProductsCount();
$totalPages = ceil($totalProducts / $perPage);

if (isset($_POST['input'])) {
    $input = $_POST['input'];
    // Display products based on the search input
    searchProducts($input);
} else {
    // Display products with pagination
    displayProductsWithPagination($page, $perPage);
}
