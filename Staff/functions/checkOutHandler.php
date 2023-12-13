<?php

if (isset($_POST["categoryID"])) {
    $itemId = $_POST["categoryID"]; // The item ID received from the button click

    // Fetch item details based on $itemId from your database
    $itemDetails = getItemDetails($itemId);

    if ($itemDetails) {
        // Generate HTML for the added item
        $html = '<div class="item1" style="background-color: white;">
            <img src="../../Icons/' . $itemDetails['PROD_PIC'] . '" alt="">
            <div class="item-content">
                <p class="cartItem-title">' . $itemDetails['PROD_NAME'] . '</p>
                <p class="cartItem-price">Standard Price: ₱' . $itemDetails['PROD_SELLING_PRICE'] . '</p>
                <p class="cartItem-ID" style="display: none;"">' . $itemId . '</p>
                <p class="cartQuantity" style="display: none;"">' . $itemDetails['PROD_TOTAL_QUANTITY'] . '</p>

            </div>
            <button class="remove-item" style="right: 0px; top: 0px; font-size: 8px; position: absolute; border: none;"><i class="fa-solid fa-trash"></i></button>
            <div class="add-item">
                <button class="add">+</button>
                <input type="text" value="1" class="quantity-input">
                <button class="minus">-</button>
            </div>
        </div>
        ';

        echo $html;
    } else {
        echo "Item not found."; // Handle the case when the item is not found in your database
    }
}

// Function to get item details from the database (Replace with your database query)
function getItemDetails($itemId) {
    include("../../Admin/functions/dbConfig.php");
    $database = new Connection();
    $conn = $database->conn;

    $query = "SELECT PROD_NAME, PROD_SELLING_PRICE, PROD_TOTAL_QUANTITY, PROD_PIC FROM product WHERE PROD_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false; // Item not found
    }
}
?>

</script>
