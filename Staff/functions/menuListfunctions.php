<?php 
    include("../../Admin/functions/dbConfig.php");
    include '../../vendor/autoload.php';

    use Dompdf\Dompdf;
    use Dompdf\Options;

    class MenuList{
        public $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }


        public function itemCategory() {
            $category = array();
        
            $query = "SELECT CATEGORY_ID, CATEGORY_NAME FROM prod_category";
        
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                $stmt->execute();
                $stmt->bind_result($categoryID, $categoryName);  // Ayaw hilabti ni pula rana pero woking ni
                
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
        
        
        public function renderCategoryTable($category) {
            foreach ($category as $row) {
             ?> 
                 <button type="button"><li class="tab" id="prodName" value="<?php echo $row['CATEGORY_ID']?>"><img src="../../Icons/cooked.svg" alt="" width="25px" style="padding-right: 5px;"><?php echo $row['CATEGORY_NAME'] ?></li>
             <?php
            }
             ?> </button> <?php
        }


        public function productList() {
            $category = array();
        
            $query = "SELECT product.PROD_ID, product.PROD_NAME, product.PROD_SELLING_PRICE, product.PROD_REMAINING_QUANTITY, prod_category.CATEGORY_NAME, product.PROD_PIC FROM product
             INNER JOIN prod_category ON product.CATEGORY_ID = prod_category.CATEGORY_ID";
        
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                $stmt->execute();
                $stmt->bind_result($prodID, $prodName, $prodSellingprice, $prodStock, $prodCategory, $prodPic);  // Ayaw hilabti ni pula rana pero woking ni
                
                while ($stmt->fetch()) {
                    $category[] = [
                        'PROD_ID' => $prodID,
                        'PROD_NAME' => $prodName,
                        'PROD_SELLING_PRICE' => $prodSellingprice,
                        'PROD_REMAINING_QUANTITY' => $prodStock,
                        'CATEGORY_NAME' => $prodCategory,
                        'PROD_PIC' => $prodPic,
                    ];
                }
        
                $stmt->close();
            }
        
            return $category;
        }
        
        public function renderProductList($category) {
            foreach ($category as $row) {
             ?> 
                 <div class="card-item">
                        <div class="top-image">
                            <img src="../../Icons/<?php echo $row['PROD_PIC'] ?>" alt="" class="foodImage">
                            <div class="stockContainer">
                                <div class="stockTextHead">
                                    <img src="../../Icons/packagegreen.svg" alt="">
                                    <p>Stock</p>
                                </div>
                                <h3 class="<?php echo ($row['PROD_REMAINING_QUANTITY'] <= 0) ? 'out-of-stock' : ''; ?>"><?php echo $row['PROD_REMAINING_QUANTITY'] ?></h3>
                            </div>
                        </div>
                        <div class="below-content">
                            <p class="food-title"><?php echo $row['PROD_NAME'] ?></p>
                                <div class="card-category">
                                     <p style="margin-right: 5px;"><?php echo $row['CATEGORY_NAME'] ?></p>
                                     <img src="../../Icons/circle.svg" alt="" style="margin-right: 5px;">
                                     <p>Filipino Cuisine</p>
                                </div>
                             <p class="item-price">Standard Price: <span style="font-weight: bold;"><?php echo "₱"."".$row['PROD_SELLING_PRICE'] ?></span></p>
                             <button type="submit" class="Cart" value="<?php echo $row['PROD_ID'] ?>"  data-stock="<?php echo $row['PROD_REMAINING_QUANTITY'] ?>">Add to cart</button>
                        </div>
                    </div>
             <?php
            }
             
        }


        public function searchMenuList($searchName) {
            $query = "SELECT product.PROD_ID, product.PROD_NAME, product.PROD_SELLING_PRICE, product.PROD_REMAINING_QUANTITY, prod_category.CATEGORY_NAME, product.PROD_PIC FROM product
            INNER JOIN prod_category ON product.CATEGORY_ID = prod_category.CATEGORY_ID
            WHERE product.PROD_NAME LIKE ?";
    
            $stmt = $this->conn->prepare($query);
    
            $searchNameParam = "%" . $searchName . "%"; // Add % to create a LIKE query
    
            $stmt->bind_param("s", $searchNameParam);
    
            if ($stmt->execute()) {
            $result = $stmt->get_result();
            $productList = array();
            
            while ($row = $result->fetch_assoc()) {
                $productList[] = $row;
            }
            
            return $productList;
            } else {
            // Handle the error
            return null;
            }
        }


        public function getAllProducts() {
            $query = "SELECT product.PROD_ID, product.PROD_NAME, product.PROD_SELLING_PRICE, product.PROD_REMAINING_QUANTITY, prod_category.CATEGORY_NAME, product.PROD_PIC FROM product
                INNER JOIN prod_category ON product.CATEGORY_ID = prod_category.CATEGORY_ID";
        
            $result = $this->conn->query($query);
        
            // Check if there are any rows returned
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Output the HTML structure for each product
                    echo '<div class="card-item">';
                    echo '<div class="top-image">';
                    echo '<img src="../../Icons/' . $row['PROD_PIC'] . '" alt="" class="foodImage">';
                    echo '<div class="stockContainer">';
                    echo '<div class="stockTextHead">';
                    echo '<img src="../../Icons/packagegreen.svg" alt="">';
                    echo '<p>Stock</p>';
                    echo '</div>';
                    echo '<h3>' . $row['PROD_REMAINING_QUANTITY'] . '</h3>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="below-content">';
                    echo '<p class="food-title">' . $row['PROD_NAME'] . '</p>';
                    echo '<div class="card-category">';
                    echo '<p style="margin-right: 5px;">' . $row['CATEGORY_NAME'] . '</p>';
                    echo '<img src="../../Icons/circle.svg" alt="" style="margin-right: 5px;">';
                    echo '<p>Filipino Cuisine</p>';
                    echo '</div>';
                    echo '<p class="item-price">Standard Price: <span style="font-weight: bold;">' . "₱" . $row['PROD_SELLING_PRICE'] . '</span></p>';
                    echo '<button type="submit" class="Cart" value="' . $row['PROD_ID'] . '"  data-stock="' . $row['PROD_REMAINING_QUANTITY'] . '">Add to cart</button>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "No data";
            }
        }
        
        


        // Ayaw lang sa ni eh deletee 

        // public function pasteData($overAllTotal, $cashReceived, $cashReturn){
        //     // $currentDate = date('Y-m-d');

        //     // $createCart = "INSERT INTO pos_cart (POS_CART_DATE_CREATED, POS_CART_TOTAL) VALUES ('$currentDate', '$overAllTotal')";
        //     // mysqli_query($this->conn, $createCart);
        
        //     // // Get the ID of the newly created cart
        //     // $cartID = mysqli_insert_id($this->conn);
            
        //     // if (isset($_SESSION['selected_items'])) {
        //     //     $selectedItems = $_SESSION['selected_items'];
                
        //     //     unset($_SESSION['selected_items']);

        //     //     // Display the selected items in your desired format
        //     //     foreach ($selectedItems as $item) {
        //     //         $productName = $item['name'];
        //     //         $quantity = $item['quantity'];
        //     //         $subtotal = $item['subtotal'];
        //     //         $itemId = $item['itemId'];
        //     //         $staffId = $_SESSION['Staff_ID'];
        
        //     //         // Check if updating the quantity would result in a negative value
        //     //         $selectData  = "SELECT * FROM product WHERE PROD_ID  = '$itemId'";
        //     //         $selecDatarun = mysqli_query($this->conn, $selectData);
        //     //         if ($row = mysqli_fetch_array($selecDatarun)) {

        //     //             $currentQuantity = $row['PROD_TOTAL_QUANTITY'];
        //     //             if ($currentQuantity >= $quantity) {
        //     //                 // You have enough stock to fulfill the order
        //     //                 $query = "INSERT INTO cart_item (CART_ID, PROD_ID, PROD_QUANTITY, POS_SUBTOTAL) VALUES ('$cartID', '$itemId', '$quantity', '$subtotal')";
        //     //                 if (mysqli_query($this->conn, $query)) {
        //     //                     // Update the product quantity
        //     //                     $updateQuan = $currentQuantity - $quantity;
        //     //                     $updateProudct = "UPDATE product SET PROD_TOTAL_QUANTITY = '$updateQuan' WHERE PROD_ID = '$itemId'";
        //     //                     mysqli_query($this->conn, $updateProudct);

        //     //                     $_SESSION['$cartID'] = $cartID;

        //     //                 } else {
        //     //                     echo "Fail Insertion";
        //     //                 }
        //     //             } else {
        //     //                 echo "Negative Stock";
        //     //             }
        //     //         } else {
        //     //             echo 'No selected items found.';
        //     //         }
        //     //     }

        //     //     $posOrder = "INSERT INTO pos_order(EMPLOYEE_ID, POS_CART_ID, RECEIVED_AMOUNT, CHANGE_AMOUNT) VALUES ('$staffId', '$cartID', '$cashReceived', '$cashReturn')";
        //     //     mysqli_query($this->conn, $posOrder);
        //     // } else {
        //     //     echo 'No selected items found.';
        //     // }
        // }

       
    }
?>


