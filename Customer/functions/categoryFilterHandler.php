<?php
require_once '../../Admin/functions/dbConfig.php';
$database = new Connection();
$isCategoryResult = false;

function displayProduct($row)
{
?>
    <div class="col-6 col-lg-2 col-md-3 col-sm-4 p-sm-2 p-1 mb-1">
        <a href="../customer_php/customer-view-item.php?productId=<?php echo $row->PROD_ID; ?>" class="view-item-link" data-product-id="<?php echo $row->PROD_ID; ?>">
            <div class="card justify-content-center d-flex border" style="overflow:hidden;">
                <div class="row itemimage-con" style="overflow:hidden; border-radius: 10px 10px 0px 0px;">
                    <img src="<?php echo $row->PROD_PIC; ?>" id="itemImage" alt="Food Item">
                </div>
                <div class="card-body py-sm-2 py-1">
                    <div class="row px-0">
                        <div class="col-12 px-1 py-0">
                            <h5 class="card-title py-0"><?php echo $row->PROD_NAME; ?></h5>
                            <div class="p-0 align-items-center foodPrice">
                                ₱<?php echo number_format($row->PROD_SELLING_PRICE, 2); ?>
                            </div>
                            <div class="container-fluid d-flex p-0 deets-con">
                                <div class="d-flex p-0 justify-content-start ratingContainer">
                                    <p class="ratingText"><i class="star fa-solid fa-star"></i>
                                        <?php echo $row->ORDER_RATING;
                                        ?>
                                    </p>
                                </div>
                                <div class="d-flex num-sold">
                                    <?php echo $row->PROD_SOLD; ?> sold
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
<?php
}

function filterProducts($database, $categoryID, $minimum_price, $maximum_price, $selected_rating, $page, $limit)
{
    $offset = ($page - 1) * $limit; // Calculate the offset for pagination

    $query = "SELECT
                product.PROD_ID,
                product.PROD_NAME,
                product.PROD_SELLING_PRICE,
                product.PROD_SOLD,
                product.PROD_PIC,
                COALESCE(ROUND(AVG(online_order.ORDER_RATING), 1), 'N/A') AS ORDER_RATING
              FROM
                online_order
              INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
              INNER JOIN online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
              RIGHT JOIN product ON online_cart_item.PROD_ID = product.PROD_ID
              ";

    $paramTypes = "";
    $bindParams = [];

    if (!empty($categoryID)) {
        $query .= " JOIN prod_category c ON product.CATEGORY_ID = c.CATEGORY_ID";
        $query .= " AND product.CATEGORY_ID = ?";
        $paramTypes .= "i";
        $bindParams[] = $categoryID;
    }

    if (!empty($minimum_price) && !empty($maximum_price)) {
        if (strpos($query, 'WHERE') === false) {
            $query .= " WHERE product.PROD_SELLING_PRICE BETWEEN ? AND ?";
        } else {
            $query .= " AND product.PROD_SELLING_PRICE BETWEEN ? AND ?";
        }
        $paramTypes .= "dd";
        $bindParams[] = $minimum_price;
        $bindParams[] = $maximum_price;
    } elseif (!empty($categoryID)) {
        if (strpos($query, 'WHERE') === false) {
            $query .= " WHERE 1";
        }
    }

    $query .= " GROUP BY product.PROD_ID";

    // Use HAVING clause for filtering by selected rating
    if (!empty($selected_rating)) {
        $query .= " HAVING FLOOR(COALESCE(ROUND(AVG(online_order.ORDER_RATING), 2), 0)) = ?";
        $paramTypes .= "d";
        $bindParams[] = $selected_rating;
    }

    $query .= " LIMIT ?, ?";
    $paramTypes .= "ii";
    $bindParams[] = $offset;
    $bindParams[] = $limit;

    $stmt = $database->conn->prepare($query);

    if ($stmt === false) {
        // Handle error
        die('Error in preparing the statement: ' . $database->conn->error);
    }

    if (!empty($paramTypes)) {
        $stmt->bind_param($paramTypes, ...$bindParams);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $total_row = mysqli_num_rows($result);
    $output = '';

    if ($total_row > 0) {
        while ($row = $result->fetch_object()) {
            displayProduct($row);
        }
    } else {
        $output = '<h3>No Product Found</h3>';
    }

    return $output;
}

function searchProduct($database, $searchValue, $page, $limit)
{
    $query = "SELECT
                product.PROD_ID,
                product.PROD_NAME,
                product.PROD_SELLING_PRICE,
                product.PROD_SOLD,
                product.PROD_PIC,
                COALESCE(ROUND(AVG(online_order.ORDER_RATING), 1), 'N/A') AS ORDER_RATING
              FROM
                online_order
              INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
              INNER JOIN online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
              RIGHT JOIN product ON online_cart_item.PROD_ID = product.PROD_ID
              WHERE
                product.PROD_NAME LIKE ?
              GROUP BY product.PROD_ID
              LIMIT ?, ?";

    $offset = ($page - 1) * $limit;

    $stmt = $database->conn->prepare($query);

    if ($stmt === false) {
        // Handle error
        die('Error in preparing the statement: ' . $database->conn->error);
    }

    $searchParam = "%{$searchValue}%";
    $stmt->bind_param("sii", $searchParam, $offset, $limit);

    $stmt->execute();
    $result = $stmt->get_result();
    $total_row = mysqli_num_rows($result);
    $output = '';

    if ($total_row > 0) {
        while ($row = $result->fetch_object()) {
            displayProduct($row);
        }
    } else {
        $output = '<h3>No Product Found</h3>';
    }

    return $output;
}

if (isset($_POST["action"]) || isset($_POST["categoryID"])) {
    $minimum_price = $_POST["minimum_price"] ?? null;
    $maximum_price = $_POST["maximum_price"] ?? null;
    $rating = $_POST["rating"] ?? null;
    $categoryID = isset($_POST["categoryID"]) ? $_POST["categoryID"] : null;
    $page = isset($_POST["page"]) ? $_POST["page"] : 1;

    $limit = 18; // Number of items per page

    if (isset($_POST["action"])) {
        if ($_POST["action"] === 'search') {
            $searchValue = $_POST['searchValue'];

            // Call the function to search products
            $output = searchProduct($database, $searchValue, $page, $limit);
            echo $output;
        } else {
            $output = filterProducts($database, $categoryID, $minimum_price, $maximum_price, $rating, $page, $limit);
            echo $output;
        }
    } elseif (isset($_POST["categoryID"])) {
        $isCategoryResult = true;

        if ($categoryID == 0) {
            // Display all products without filtering by category
            $output = filterProducts($database, null, $minimum_price, $maximum_price, $rating, $page, $limit);
            echo $output;
        } else {
            $output = filterProducts($database, $categoryID, $minimum_price, $maximum_price, $rating, $page, $limit);
            echo $output;
        }
    }
}
?>