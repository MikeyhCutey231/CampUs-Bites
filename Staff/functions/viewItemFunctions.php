<?php 
    include("../../Admin/functions/dbConfig.php");
    include '../../vendor/autoload.php';

    use Dompdf\Dompdf;
    use Dompdf\Options;

    class ProductDetails{
        public $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }


        public function itemCategory($productID) {
            $produdct = array();
        
            $query = "SELECT PROD_ID, PROD_NAME, PROD_DESC, PROD_SELLING_PRICE, PROD_SOLD, PROD_TOTAL_QUANTITY, 
            PROD_REMAINING_QUANTITY,PROD_DATE_ADD, PROD_TIME_ADDED, PRODPIC FROM product WHERE PROD_ID  = '$productID'";
        
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                $stmt->execute();
                $stmt->bind_result($ID, $producName, $productDesc, $productSellingPrice, $productSold, $productQuantity, $productRemainingQuan, $productDate, $prodTimeAdd, $prodPic);  // Ayaw hilabti ni pula rana pero woking ni
                
                while ($stmt->fetch()) {
                    $produdct[] = [
                        'PROD_ID' => $ID,
                        'PROD_NAME' => $producName,
                        'PROD_DESC' => $productDesc,
                        'PROD_SELLING_PRICE' => $productSellingPrice,
                        'PROD_SOLD' => $productSold,
                        'PROD_TOTAL_QUANTITY' => $productQuantity,
                        'PROD_REMAINING_QUANTITY' => $productRemainingQuan,
                        'PROD_DATE_ADD' => $productDate,
                        'PROD_TIME_ADDED' => $prodTimeAdd,
                        'PRODPIC' => $prodPic,
                    ];
                }
        
                $stmt->close();
            }
        
            return $produdct;
        }
        
        
        public function renderCategoryTable($produdct, $productID) {
            foreach ($produdct as $row) {
             ?> 
                    <div class="productName-container">
                        <h2 style="font-weight: calc(1000);"><?php echo $row['PROD_NAME'] ?></h2>
                        <p>ProductID #<?php echo $row['PROD_ID'] ?></p>
                    </div>

                    <div class="stock-container">
                        <div class="invetory-container">
                            <p>Inventory</p>
                            <p class="stockprice"><?php echo $row['PROD_TOTAL_QUANTITY'] ?> items</p>
                        </div>
                        <div class="itemAvi-container">
                            <p>Item Available</p>
                            <p class="aviPrice"><?php echo $row['PROD_REMAINING_QUANTITY'] ?>/100 items</p>
                        </div>
                    </div>

                    <div class="itemImg-container">
                        <img src="../../Icons/abobo.svg" alt="">
                    </div>

                    <div class="prices-container">
                        <div class="salesprice-container">
                            <p style="font-weight: bold; font-size: 14px;">Sales price:</p>
                            <div class="amountSold">
                                <p class="amountSoldTotalsale">₱<?php echo $row['PROD_SELLING_PRICE'] ?></p>
                                <div class="ratingSold">
                                    <p>2.5%</p>
                                    <img src="/Icons/arrowUp.svg" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="totalsale-container">
                            <p style="font-weight: bold; font-size: 14px;">Total Sold:</p>
                            <div class="amountSolds">
                                <p class="amountSoldTotalsale"><?php echo $row['PROD_SOLD'] ?></p>
                                <div class="ratingSold">
                                    <p>2.5%</p>
                                    <img src="/Icons/arrowUp.svg" alt="">
                                </div>
                            </div>
                            <?php
                                $totalQuery = "SELECT SUM(POS_SUBTOTAL) AS Total FROM pos_cart_item WHERE PROD_ID = '$productID'";
                                $totalQueryrun = mysqli_query($this->conn, $totalQuery); 

                                while( $row1 = mysqli_fetch_array($totalQueryrun) ) {
                                    ?>
                                        <p class="amountTotalamount">Total Amount: ₱<?php echo $row1['Total'] ?></p>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>

                    <div class="description-container">
                        <p class="desc">Description</p>
                        <p class="desc-info"><?php echo $row['PROD_DESC'] ?></p>
                    </div>

                    <div class="productRating-container">
                        <p class="pdrating">Product Rating</p>
                        <p style="font-size: 30px; padding-left: 30px; font-weight: calc(1000);">4.0</p>
                        <div class="starRating-container">
                            <i class="fa-solid fa-star" style="color: yellow; font-size: 20px;"></i>
                            <i class="fa-solid fa-star" style="color: yellow; font-size: 20px;"></i>
                            <i class="fa-solid fa-star" style="color: yellow; font-size: 20px;"></i>
                            <i class="fa-solid fa-star" style="color: yellow; font-size: 20px;"></i>
                            <i class="fa-solid fa-star" style="color: rgb(199, 199, 199); font-size: 20px;"></i>
                        </div>
               
             <?php
            }
             ?> </div> <?php
        }

    }
?>


