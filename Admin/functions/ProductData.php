<?php
class ProductData{
    private $conn;

    public function __construct() {
        $database = new Connection();
        $this->conn = $database->conn;
    }

    // Fetch product data with optional sorting
    public function getProductData($sortOption = null) {
        switch ($sortOption) {
            case '1':
                $sql = "SELECT * FROM product ORDER BY PROD_ID";
                break;
            case '2':
                $sql = "SELECT * FROM product ORDER BY PROD_NAME";
                break;
            case '3':
                $sql = "SELECT * FROM product ORDER BY PROD_REMAINING_QUANTITY ASC";
                break;
            case '4':
                $sql = "SELECT * FROM product ORDER BY PROD_REMAINING_QUANTITY DESC";
                break;
            default:
                $sql = "SELECT * FROM product ORDER BY 
                        CASE 
                            WHEN PROD_REMAINING_QUANTITY <= 5 THEN 0  
                            ELSE 1
                        END";
                break;
        }

        $prodData = array();

        $retval = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($retval)) {
            $prod = array(
                'prod_id' => $row["PROD_ID"],
                'prod_pic' => $row["PROD_PIC"],
                'prodName' => $row["PROD_NAME"],
                'prodQuantity' => $row["PROD_TOTAL_QUANTITY"],
                'prodStatus' => $row["PROD_STATUS"],
                'prod_sold' => $row["PROD_SOLD"],
                'prod_remaining' => $row["PROD_REMAINING_QUANTITY"],
                'prod_price' => $row["PROD_SELLING_PRICE"],
                'prodDesc' => $row["PROD_DESC"],
                'prodCategory' => $row["CATEGORY_ID"],
                'prodDateCreated' => $row["PROD_DATE_ADD"],
                'totalSales' => $row["PROD_SALES"],
            );

            $prodData[] = $prod;
        }

        return $prodData;
    }
    public function getSalesReport() {
        $sql = "SELECT * FROM salesreport";

        $salesData = array();

        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $sales = array(
                    'transaction_date' => $row["TRANSACTION_DATE"],
                    'prod_name' => $row["PROD_NAME"],
                    'prod_capital_price' => $row["PROD_CAPITAL_PRICE"],
                    'prod_selling_price' => $row["PROD_SELLING_PRICE"],
                    'gross_margin' => $row["GROSS_MARGIN"],
                    'pos_prod_quantity' => $row["POS_PROD_QUANTITY"],
                    'subtotal' => $row["SUBTOTAL"],
                );

                $salesData[] = $sales;
            }

            mysqli_free_result($result);
        } else {
            return null;
        }

        return $salesData;
    }
    public function getSalesReportFilter($fromDate, $toDate, $selectedCategory, $selectedProduct) {
        // Build the base query
        $sql = "SELECT * FROM salesreport WHERE 1";

        // Add date range condition if provided
        if ($fromDate && $toDate) {
            $sql .= " AND TRANSACTION_DATE BETWEEN '$fromDate' AND '$toDate'";
        }

        // Add category filter condition if provided
        if ($selectedCategory) {
            $sql .= " AND CATEGORY_TO_FILTER = '$selectedCategory'";
        }

        // Add product filter condition if provided
        if ($selectedProduct) {
            $sql .= " AND TYPE_OF_PRODUCT = '$selectedProduct'";
        }

        $salesData = array();

        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $sales = array(
                    'transaction_date' => $row["TRANSACTION_DATE"],
                    'prod_name' => $row["PROD_NAME"],
                    'prod_capital_price' => $row["PROD_CAPITAL_PRICE"],
                    'prod_selling_price' => $row["PROD_SELLING_PRICE"],
                    'gross_margin' => $row["GROSS_MARGIN"],
                    'pos_prod_quantity' => $row["POS_PROD_QUANTITY"],
                    'subtotal' => $row["SUBTOTAL"],
                );

                $salesData[] = $sales;
            }

            mysqli_free_result($result);

            // Return the result as a JSON-encoded string
            return json_encode($salesData);
        } else {
            // Handle the case when the query fails
            $errorMessage = mysqli_error($this->conn);
            return json_encode(['error' => "Query failed: $errorMessage"]);
        }
    }



    public function getCategoryChartData() {
        $sql = "SELECT prod_category.category_name AS category, COUNT(product.PROD_ID) AS product_count
        FROM product
        JOIN prod_category ON product.category_id = prod_category.category_id
        WHERE product.PROD_SOLD > 0
        GROUP BY prod_category.category_name";

        $prodData = array();

        $retval = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($retval)) {
            $categoryData = array(
                'category' => $row["category"],
                'product_count' => $row["product_count"],
            );

            $prodData[] = $categoryData;
        }

        return json_encode($prodData);
    }
    public function getTopSoldProducts() {
        $sql = "SELECT
                product.PROD_NAME AS product_name,
                product.PROD_PIC AS product_pic,
                product.PROD_DATE_ADD AS prod_date,
                product.PROD_SELLING_PRICE AS prod_price,
                product.PROD_SOLD AS product_count
            FROM
                product
            WHERE
                product.PROD_SOLD > 0
            GROUP BY
                product.PROD_ID
            ORDER BY
                product_count DESC
            LIMIT 5";

        $topSoldProducts = array();

        $retval = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($retval)) {
            $productData = array(
                'product_name' => $row["product_name"],
                'prod_date' => $row["prod_date"],
                'prod_price' => $row["prod_price"],
                'product_count' => $row["product_count"],
                'product_pic' => $row["product_pic"],
            );

            $topSoldProducts[] = $productData;
        }

        return json_encode($topSoldProducts);
    }


    public function getReplenishData(){
        $sql = "SELECT * FROM prod_replenishment ORDER BY PROD_REPLENISH_ID DESC";
        $prodRep = array();

        $retval = mysqli_query($this->conn, $sql);

        while($row = mysqli_fetch_assoc($retval)){
            $productRep = array(
                'prod_rep_id' => $row["PROD_REPLENISH_ID"],
                'prod_id' => $row["PROD_ID"],
                'replenish_date' => $row["REPLENISH_DATE"],
                'replenish_time' => $row["REPLENISH_TIME"],
                'replenish_quantity' => $row["REPLENISH_QUANTITY"],
                'total_before_rep' => $row["TOTAL_BEFORE_REPLENISH"],
                'total_after_rep' => $row["TOTAL_AFTER_REPLENISH"],
                'prod_old_price' => $row["PROD_PRICE"],
                'prod_new_price' => $row["PROD_NEW_PRICE"],
            );
            $prodRep[] = $productRep;
        }
        return $prodRep;
    }

    // Fetch product data with pagination
    public function getProductDataWithPagination($limit, $offset) {
        $sql = "SELECT * FROM product ORDER BY PROD_REMAINING_QUANTITY ASC LIMIT ?, ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();

        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $prod = array(
                'prod_id' => $row["PROD_ID"],
                'prod_pic' => $row["PROD_PIC"],
                'prodName' => $row["PROD_NAME"],
                'prodQuantity' => $row["PROD_TOTAL_QUANTITY"],
                'prodStatus' => $row["PROD_STATUS"],
                'prod_sold' => $row["PROD_SOLD"],
                'prod_remaining' => $row["PROD_REMAINING_QUANTITY"],
                'prod_price' => $row["PROD_SELLING_PRICE"],
                'prodDesc' => $row["PROD_DESC"],
                'prodDateCreated' => $row["PROD_DATE_ADD"],
                'totalSales' => $row["PROD_SALES"],
            );
            $products[] = $prod;
        }

        $stmt->close();

        return $products;
    }

    // Get the total number of products
    public function getTotalProductsCount() {
        $sql = "SELECT COUNT(*) as count FROM product";
        $result = $this->conn->query($sql);

        $row = $result->fetch_assoc();

        return $row['count'];
    }
}
?>
