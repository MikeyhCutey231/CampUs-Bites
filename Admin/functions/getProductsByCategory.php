<?php
include_once('../../config.php');

class ProductsByCategory {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conn;
    }

    public function getProductsByCategory($categoryId) {
        $categoryId = mysqli_real_escape_string($this->conn, $categoryId);

        $sql = "SELECT PROD_NAME FROM product WHERE CATEGORY_ID = '$categoryId'";
        $products = array();

        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $product = array(
                    'name' => $row["PROD_NAME"],
                );

                $products[] = $product;
            }

            mysqli_free_result($result);
        }

        return json_encode($products);
    }
}

// Check if the category ID is provided in the URL
if (isset($_GET['categoryId'])) {
    $categoryId = $_GET['categoryId'];

    // Create an instance of ProductData
    $productData = new ProductsByCategory();

    // Output JSON response with products based on the provided category ID
    header('Content-Type: application/json');
    echo $productData->getProductsByCategory($categoryId);
} else {
    // Handle error if category ID is not provided
    echo json_encode(array('error' => 'Category ID is not provided.'));
}
?>
