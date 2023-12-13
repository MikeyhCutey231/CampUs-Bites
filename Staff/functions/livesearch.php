<?php
include("../functions/menuListfunctions.php");
$database = new Connection();
$conn = $database->conn;

if (isset($_POST['name'])) {
    $new = new MenuList($conn);
    $searchName = $_POST['name'];
    
    // Call searchMenuList only when $searchName is not empty
    if (!empty($searchName)) {
        $searchItem = $new->searchMenuList($searchName);
        if (!empty($searchItem)) {
            foreach ($searchItem as $row) {
                ?> <div class="card-item">
                <div class="top-image">
                    <img src="../../Icons/<?php echo $row['PROD_PIC'] ?>" alt="" class="foodImage">
                    <div class="stockContainer">
                        <div class="stockTextHead">
                            <img src="../../Icons/packagegreen.svg" alt="">
                            <p>Stock</p>
                        </div>
                        <h3><?php echo $row['PROD_TOTAL_QUANTITY'] ?></h3>
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
                     <button type="submit" class="Cart" value="<?php echo $row['PROD_ID'] ?>"  data-stock="<?php echo $row['PROD_TOTAL_QUANTITY'] ?>">Add to cart</button>
                </div>
            </div>
            <?php
            }
        } else {
            echo "No data";
        }
    } else {
        // If $searchName is empty, get all products
        $searchItem = $new->getAllProducts();
    }


}
?>


<script>
       $(document).ready(function() {
            var prodNoFound = $(".prodNoFound");
            updateTotals(); // Call this function when the page loads

            $('.Cart').each(function() {
                var stock = parseInt($(this).data('stock'));
                if (stock <= 0) {
                    $(this).prop('disabled', true);
                }
            });

            $('button.Cart').click(function() {
                var categoryID = $(this).attr('value');
                
                $.ajax({
                    type: "POST",
                    url: "../functions/checkOutHandler.php",
                    data: { categoryID: categoryID },
                    success: function(data) {
                        var itemName = $(data).find('.cartItem-title').text();
                        if (!isItemInCart(itemName)) {
                            $('.item-cart').append(data);
                            prodNoFound.hide();
                            // Update totals when a new item is added
                            updateTotals();
                        } else {
                            // Notify the user that the item is already in the cart
                            // alert('Item is already in the cart.');
                        }

                        $('.Cart').each(function() {
                            var stock = parseInt($(this).data('stock'));
                            var quantity = parseInt($(this).siblings('.add-item').find('.quantity-input').val());
                            if (quantity >= stock) {
                                $(this).prop('disabled', true);
                            }
                        });
                    }
                });
            });

            $('.item-cart').on('click', '.remove-item', function() {
                $(this).closest('.item1').remove(); // Remove the item from the cart
                updateTotals(); // Update totals after removal
            });

            function isItemInCart(itemName) {
                var isInCart = false;

                // Check if the item is already in the cart
                $('.item1').each(function () {
                    var name = $(this).find('.cartItem-title').text();
                    if (name === itemName) {
                        isInCart = true;
                        return false; // Exit the loop early
                    }
                });

                return isInCart;
            }


            function updateTotals() {
                var subTotal = 0;

                // Loop through each item in the cart
                $('.item1').each(function() {
                    var priceText = $(this).find('.cartItem-price').text(); // Get the text content
                    var price = parseFloat(priceText.match(/₱([\d.]+)/)[1]); // Extract the price
                    var quantity = parseInt($(this).find('.quantity-input').val());
                    subTotal += price * quantity;

                });

                // Calculate the total
                var total = subTotal;

                // Update the subtotal and total elements
                $('#subtotal').text('₱ ' + subTotal.toFixed(2));
                $('#total').text('₱ ' + total.toFixed(2));
            }
        });

    </script>



