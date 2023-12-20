<?php
// Include the database connection file
require_once '../../Admin/functions/dbConfig.php';


class Product{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getProductDetails($productId){
        $query = "SELECT
            product.PROD_ID,
            product.PROD_NAME,
            product.PROD_SELLING_PRICE,
            product.PROD_SOLD,
            product.PROD_PIC,
            product.PROD_REMAINING_QUANTITY,
            product.PROD_DESC,
            COALESCE(ROUND(AVG(online_order.ORDER_RATING), 1), 'N/A') AS ORDER_RATING
        FROM online_order
        INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
        INNER JOIN online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
        RIGHT JOIN product ON online_cart_item.PROD_ID = product.PROD_ID
        LEFT JOIN users ON ol_cart.CUSTOMER_ID = users.USER_ID 
        WHERE product.PROD_ID = ?
        GROUP BY product.PROD_ID";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in SQL query: " . $this->conn->error);
        }

        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();

        return $product;
    }


    function getProductRatings($productId){
        $query = "SELECT
            COALESCE(ROUND(AVG(online_order.ORDER_RATING), 1), 'N/A') AS ORDER_RATING,
            users.U_USER_NAME,
            online_order.DATE_CREATED,
            online_order.ORDER_COMMENT
            FROM
                online_order
            INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
            INNER JOIN online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
            RIGHT JOIN product ON online_cart_item.PROD_ID = product.PROD_ID
            LEFT JOIN users ON ol_cart.CUSTOMER_ID = users.USER_ID
            WHERE product.PROD_ID = ? AND ORDER_RATING != 'N/A'
            GROUP BY
            product.PROD_ID, online_order.ORDER_COMMENT, users.U_USER_NAME, online_order.DATE_CREATED";
        
        $stmt = $this->conn->prepare($query);
    
        if (!$stmt) {
            die("Error in SQL query: " . $this->conn->error);
        }
    
        $stmt->bind_param("i", $productId);
        $stmt->execute();
    
        if ($stmt->errno) {
            die("Error executing query: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $ratingsData = array();
    
        foreach ($result as $row) {
            // Calculate the length for str_repeat, ensuring it's not negative
            $length = max(strlen($row['U_USER_NAME']) - 2, 0);
        
            $ratingDetails = array(
                'orderRating' => $row['ORDER_RATING'],
                'userName' => substr($row['U_USER_NAME'], 0, 1) . str_repeat("*", $length) . substr($row['U_USER_NAME'], -1),
                'dateCreated' => $row['DATE_CREATED'],
                'orderComment' => $row['ORDER_COMMENT']
            );
        
            $ratingsData[] = $ratingDetails;
        }
    
        // Close the statement
        $stmt->close();
    
        // Return the ratings data as JSON
        return $ratingsData;
    }



    function displayProductRatings($ratings){
        
        foreach ($ratings as $row):
            ?>
            <div class="col-12 px-2 px-md-0 py-1 mb-1 mt-1 rate-each">
                <div class="container-fluid p-0 mb-2">
                    <div class="align-items-center d-flex">

                        <?php
                        // Assuming ORDER_RATING is a numeric value
                        for ($i = $row['orderRating']; $i > 0; $i--):
                            ?>
                            <i class="rating-star fa-solid fa-star<?= ($i <= $row['orderRating']) ? ' filled' : '' ?>"></i>
                        <?php endfor; ?>

                    </div>
                    <div class="by-name">by <?= substr($row['userName'], 0, 1) . str_repeat("*", strlen($row['userName']) - 2) . substr($row['userName'], -1) ?></div>
                    <div class="rating-date align-items-center d-flex"><?= $row['dateCreated'] ?></div>
                    <div class="rating-comment align-items-center px-0 py-1 d-flex justify-content-between"><?= $row['orderComment'] ?></div>
                </div>
            </div>
        <?php endforeach; 

    }
}

// Initialize the Product class
$productClass = new Product((new Connection())->conn);

// Handle AJAX request for fetching product details
$inputData = json_decode(file_get_contents("php://input"), true);

if (isset($inputData["productId"])) {
    $productId = $inputData["productId"];

    $productDetails = $productClass->getProductDetails($productId);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($productDetails);
    exit;
}
