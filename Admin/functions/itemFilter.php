<?php
include_once('ProductData.php');

$sortOption = $_POST['sortOption'];

// Fetch product data based on the selected option
$productDataInstance = new ProductData();
$productData = $productDataInstance->getProductData($sortOption);

// Output the updated product cards
if (!empty($productData)) {
    foreach ($productData as $product) {
        $status = $product['prodStatus'];
                            $textColor = ($status === 'Disabled') ? '#FF0000' : '#3DC53A';

                            $notificationCircle = ($product['prodQuantity'] <= 5) ? '<div class="notification-circle"><p>' . $product['prodQuantity'] . '</p></div>' : '';

                            echo '<div class="card-main">
            <button class="card-1">
                <div class="Cardtop-content">

                    <img src="upload/' . $product['prod_pic'] . '" alt="" style="width: 80px; height: 60px; object-fit: contain" class="foodImage">
                    <p class="foodName">' . $product['prodName'] . '</p>
                    <p class="status" style="color: ' . $textColor . ';">' . $status . '</p>
                </div>
                <div class="Cardbottom-content">
                    <div class="topBottomcard-content">
                        <img src="/Icons/clipboard.svg" alt="" width="18px">
                        <p>Item ID: ' . $product['prod_id'] . '</p>
                    </div>
                    <div class="bottomBottomcard-content">
                        <img src="/Icons/package.svg" alt="">
                        <p>' . $product['prod_remaining'] . ' stock available</p>
                        ' . $notificationCircle . '
                    </div>
                </div>
            </button>

            <a href="admin-viewItem.php?itemID=' . $product['prod_id'] . '" style="color: white;"><button class="restock">View Item</button></a>
        </div>';
    }
} else {
    echo "No products found.";
}
