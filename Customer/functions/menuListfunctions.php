<?php
require_once '../../Admin/functions/dbConfig.php';

class MenuList
{
    public $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function itemCategory()
    {
        $category = array();

        $query = "SELECT CATEGORY_ID, CATEGORY_NAME FROM prod_category";

        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($categoryID, $categoryName);

            while ($stmt->fetch()) {
                $category[] = [
                    'CATEGORY_ID' => $categoryID,
                    'CATEGORY_NAME' => $categoryName,
                ];
            }

            $stmt->close();
        }

        return $category;
    }

    public function renderCategoryTable($category)
    {
        foreach ($category as $row) {
?>
            <button type="button">
                <li class="tab" id="prodName" value="<?php echo $row['CATEGORY_ID'] ?>"><img src="../../Icons/cooked.svg" alt="" width="25px" style="padding-right: 5px;"><?php echo $row['CATEGORY_NAME'] ?></li>
            </button>
        <?php
        }
    }

    public function productList($page, $limit)
    {
        $category = array();
        $offset = ($page - 1) * $limit; // Calculate the offset for the SQL query
        
        $query = "SELECT
                    product.PROD_ID,
                    product.PROD_NAME,
                    product.PROD_SELLING_PRICE,
                    product.PROD_SOLD,
                    product.PROD_PIC,
                    COALESCE(ROUND(AVG(order_rating.ORDER_RATING), 1), 'N/A') AS ORDER_RATING
                FROM product
                LEFT JOIN (
                    SELECT
                        online_cart_item.PROD_ID,
                        COALESCE(ROUND(AVG(online_order.ORDER_RATING), 1), 'N/A') AS ORDER_RATING
                    FROM online_order
                    INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
                    INNER JOIN online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
                    GROUP BY online_cart_item.PROD_ID
                ) AS order_rating ON product.PROD_ID = order_rating.PROD_ID
                GROUP BY product.PROD_ID
                ORDER BY product.PROD_ID
                LIMIT $offset, $limit";
        
        $result = $this->conn->query($query);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                error_log(print_r($row, true));
                $category[] = [
                    'PROD_ID' => $row['PROD_ID'],
                    'PROD_NAME' => $row['PROD_NAME'],
                    'PROD_SELLING_PRICE' => $row['PROD_SELLING_PRICE'],
                    'PROD_SOLD' => $row['PROD_SOLD'],
                    'PROD_PIC' => $row['PROD_PIC'],
                    'ORDER_RATING' => $row['ORDER_RATING'],
                ];
            }
        } else {
            // Handle query error
            die('Error executing the query: ' . $this->conn->error);
        }
        
        return $category;
    }
    
    
    public function renderProductList($category)
    {
        foreach ($category as $row) {
        ?>
            <div class="col-6 col-lg-2 col-md-3 col-sm-4 p-sm-2 p-1 mb-1">
                <a href="../customer_html/customer-view-item.php">
                    <div class="card justify-content-center d-flex border" style="overflow:hidden;">
                        <div class="row itemimage-con" style="overflow:hidden; border-radius: 10px 10px 0px 0px;">
                            <img src="<?php echo $row['PROD_PIC']; ?>" id="itemImage" alt="Food Item">
                        </div>
                        <div class="card-body py-sm-2 py-1">
                            <div class="row px-0">
                                <div class="col-12 px-1 py-0">
                                    <h5 class="card-title py-0"><?php echo $row['PROD_NAME']; ?></h5>
                                    <div class="p-0 align-items-center foodPrice">
                                        ₱<?php echo number_format($row['PROD_SELLING_PRICE'], 2); ?>
                                    </div>
                                    <div class="container-fluid d-flex p-0 deets-con">
                                        <div class="d-flex p-0 justify-content-start border ratingContainer">
                                            <p class="ratingText" style="color: black;"><i class="star fa-solid fa-star"></i><?php echo $row['ORDER_RATING']; ?></p>
                                        </div>
                                        <div class="d-flex num-sold">
                                            <?php echo $row['PROD_SOLD']; ?> sold
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
    }
    public function getTotalProducts()
{
    $query = "SELECT COUNT(*) as total FROM product";
    $result = $this->conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        // Handle query error
        die('Error executing the query: ' . $this->conn->error);
    }
}

}

?>