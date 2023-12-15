<?php
require_once "cartcontrol.class.php";

$remove = new CartControl();
if (isset($_GET['prod_id']) && isset($_GET['cart_id'])) {
    $prod_id = $_GET['prod_id'];
    $cart_id = $_GET['cart_id'];
    $remove->removeItem($prod_id, $cart_id);
}

if (isset($_GET['productId']) && isset($_GET['newQuantity'])) {
    $prod_id = $_GET['productId'];
    $newQuantity = $_GET['newQuantity'];
    $remove->newQuantity($prod_id, $newQuantity);
}

class CartView extends Cart{    

    

    public function showCartItems($customer_id){
        $rows = $this->getCartItems($customer_id);
        

   

        foreach($rows as $row) {
            ?>
             <div class="row cart-item-con py-2">
                <div class="col-1 p-sm-2 p-1 d-flex  justify-content-center">
                    <img class="item-image" src="<?php echo $row['PROD_PIC']?>" alt="product picture">
                </div>
                <div class="col-md-5 col-3 p-sm-2 p-1 d-flex align-items-center justify-content-start ">
                    <div class="item-name"><?php echo $row['PROD_NAME']?></div>
                    
                </div>
                <div class="col-md-6 col-8 p-0 d-flex ">
                    <div class="col-3 p-2 d-flex align-items-center justify-content-center">
                    <div class="item-price"><?php echo $row['UNIT_PRICE']?></div>
                        
                    </div>
                    <div class="col-3 p-2 d-flex align-items-center justify-content-center">
                        <div class="amount-container">
                            <button class="minus-btn align-center" data-productid="<?php echo $row['PROD_ID']; ?>">-</button>
                            <input type="number" name="quantity" class="quantity-input"  value="<?php echo  $row['QUANTITY']?>" data-productid="<?php echo $row['PROD_ID']; ?>" disabled style="background-color: white;">
                            <button class="plus-btn" data-productid="<?php echo $row['PROD_ID']; ?>">+</button>
                       </div>
                    </div>

                    <div class="col-3 p-2 d-flex align-items-center justify-content-center">
                    <div class="item-total"><?php echo $row['SUBTOTAL']?></div>
                    </div>
                    <div class="col-3 p-2 d-flex align-items-center justify-content-center">
                        <a href="../classes/cartview.class.php?prod_id=<?php  echo $row['PROD_ID']; ?> &cart_id= <?php echo $row['CART_ID']; ?>" class="delete-btn">
                            <div class="btn delete-item d-flex align-items-center justify-content-center">Delete</div>
                        </a>
                    </div>
                </div>
            </div>


            
            <?php
        }
        ?>
            
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script>
                $(document).ready(function () {
                $(".plus-btn, .minus-btn").on("click", function () {
                    event.preventDefault();
                    var productId = $(this).data("productid");
                    var quantityInput = $(".quantity-input[data-productid='" + productId + "']");
                    var currentQuantity = parseInt(quantityInput.val());

                    if ($(this).hasClass("plus-btn")) {
                        // Increment quantity
                        quantityInput.val(currentQuantity + 1);
                    } else if ($(this).hasClass("minus-btn") && currentQuantity > 1) {
                        // Decrement quantity (if it's more than 1)
                        quantityInput.val(currentQuantity - 1);
                    }

                    

                  var xmlhttp = new XMLHttpRequest();
                    var url = "?productId=" + encodeURIComponent(productId) + "&newQuantity=" + encodeURIComponent(quantityInput.val());
                    xmlhttp.open("GET", url, true);
                    xmlhttp.send();

                    location.reload();

                });

                
            }
                );
                
            </script>
        <?php
    }

    public function showCartTotal($customer_id){
        $rows = $this->getCartTotal($customer_id);

    if (!empty($rows)) {
        $row = $rows[0]; // Assuming you only expect one result
        ?>
         <div class="row check-out-deets">
                    <div class="col-sm-10 col-8 p-1 d-flex align-items-center ">
                        <div class="total-item">Total (<?php echo $row['TOTAL_ITEMS']?> item):</div>
                        <div class="total-price">₱<?php echo (!empty($row['CART_TOTAL']) && $row['CART_TOTAL'] != 0) ? $row['CART_TOTAL'] : '0.00'; ?></div>

                    </div>
                    <div class="col-sm-2 col-4 p-0 d-flex align-items-center justify-content-end">
                        <a href="customer-checkout.php?cart_id=<?php echo $row['CART_ID']?>" data-cart-id="<?php echo $row['CART_ID']?>">
                            <div class="btn checkout-item d-flex align-items-center justify-content-center">Check out</div>
                        </a>
                    </div>
        </div>

        <script>
                $(document).ready(function() {
                    $(".checkout-item").on('click', function() {
                        var cartId = $(this).data('cartId'); // Corrected here

                        $.ajax({
                            url: "check-out.php",
                            type: "GET",
                            data: 'cart_id=' + cartId,
                            success: function(data) {
                                $("#item-container").html(data);
                                location.reload();
                            }
                        });
                    });
                });
            </script>

       

        <?php

    }
    }

    



}


?>