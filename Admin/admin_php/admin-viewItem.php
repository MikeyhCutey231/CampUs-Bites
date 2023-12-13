<?php
require '../../Admin/functions/UserData.php';

$loggedUser = $_SESSION['USER_ID'];
$userDataInstance = new UserData();
$usersData = $userDataInstance->getUserData($loggedUser);

if (!isset($_SESSION['USER_ID'])) {
    header("Location: admin-login.php");
    exit();
}

?>

<?php
require '../../Admin/functions/LiveSearchFunction.php';
$customerTableSorter = new TableSorter('#searchresult', '.filterDropdown');
$customerTableSorter->addSortingScript();
$searchHandler = new SearchHandler();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $searchHandler->performSearch($searchTerm);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <!-- CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>CampUs Bites</title>
    <link rel="stylesheet" href="../../Admin/admin_css/admin-viewitem.css">
</head>
<body>
    <div class="wrapper">

        <?php include 'sidebar.php'; ?>

        <div class="right-container">
            <div class="head-content">
                <div class="Menu-name">
                    <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                    <p style="margin-bottom: 0;">View Item</p>
                </div>
                <!-- Main Search Bar -->
                <div class="head-searchbar">
                    <img src="../../Icons/search.svg" alt="" width="20px" style="margin-top: 12px;">
                    <form method="post">
                        <input type="text" name="search" placeholder="Search here...">
                    </form>
                </div>
                <div class="usep-texthead">
                    <img src="../../Icons/useplogo.png" alt="" width="30px" height="30px">
                    <p style="margin-bottom: 0px; margin-top: 3px; margin-left: 10px; font-weight: 600;">UseP (Tagum Unit)</p>
                </div>
                <a href="admin-profile.php" style="color: black;">
                    <div class="user-profile">
                        <div class="admin-profile">
                            <?php
                            if (!empty($usersData)) {
                                $profilePic = $usersData[0]['profilePic'];
                                echo '<img src="../../Icons/' . $profilePic . '" style="height: 38px; width: 40px; border-radius: 5px; object-fit: cover;">';
                            }
                            ?>
                        </div>
                        <div class="admin-detail">
                            <?php
                            if (!empty($usersData)) {
                                $adminUsername = $usersData[0]['username'];
                                echo '<p style="margin-bottom: 0px; font-weight: 700;">' . $adminUsername . '</p>';

                                require_once '../../Admin/functions/user.php';
                                $userDataManager = new User();
                                if ($userDataManager->isManager()) {
                                    echo '<p style="margin-bottom: 0px; font-size: 6px; background-color: #9C1421; color: white; padding: 3px; border-radius: 3px;">Manager</p>';
                                } else {
                                    echo '<p style="margin-bottom: 0px; font-size: 6px; background-color: #9C1421; color: white; padding: 3px; border-radius: 3px;">Administrator</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </a>
            </div>
            <div class="main-content">
                <?php
                include_once ('../../Admin/functions/ProductData.php');

                if (isset($_GET['itemID'])) {
                    $product_ID = $_GET['itemID'];

                    $productDataInstance = new ProductData();
                    $productData = $productDataInstance->getProductData();

                    $targetProduct = null;
                    foreach ($productData as $product) {
                        if ($product['prod_id'] == $product_ID) {
                            $targetProduct = $product;
                            break;
                        }
                    }
                }
                ?>

                <div class="left-content">
                    <div class="productName-container">
                        <div class="productName-left">
                            <h2 style="font-weight: bold;"><?php echo $targetProduct['prodName']; ?></h2>
                            <p>Code #<?php echo $targetProduct['prod_id'] ?></p>
                        </div>

                        <div class="productName-right">
                            <button type="button" class="editProduct" data-product-id="<?php echo $targetProduct['prod_id']; ?>" data-bs-toggle="modal" data-bs-target="#editModal">
                                <img src="../../Icons/edit-block.svg" alt="">
                                Edit
                            </button>
                            <button type="button" class="deleteProduct" data-product-id="<?php echo $targetProduct['prod_id']; ?>" data-bs-toggle="modal" data-bs-target="#deleteItem">
                                <img src="../../Icons/trash.svg" alt="">
                            </button>
                        </div>
                    </div>

                    <div class="stock-container">
                        <div class="invetory-container">
                            <p style="font-weight: normal;">Inventory</p>
                            <p class="stockprice"><?php echo $targetProduct['prodQuantity'] ?> items</p>
                        </div>
                        <div class="itemAvi-container">
                            <p style="font-weight: normal;">Item Available</p>
                            <p class="aviPrice"><?php echo $targetProduct['prod_remaining'] ?>/<?php echo $targetProduct['prodQuantity']?> items</p>
                        </div>
                        <div class="itemDate-container" style="margin-left: 80px; ">
                            <p style="font-weight: normal;">Date Created</p>
                            <p class="aviPrice" style="font-size: 18px;"><?php $dateStr = $targetProduct['prodDateCreated'];
                             $formattedDate = date('F j, Y', strtotime($dateStr));
                             echo $formattedDate ?></p>
                        </div>
                    </div>

                    <div class="itemImg-container">
                        <img src="../../Icons/<?php echo $targetProduct['prod_pic']; ?>" alt="">
                    </div>


                    <div class="prices-container">
                        <div class="salesprice-container">
                            <p style="font-weight: normal; font-size: 14px;">Sales price:</p>
                            <div class="amountSold">
                                <p class="amountSoldTotalsale">₱<?php echo $targetProduct['prod_price']?></p>
                                <div class="ratingSold">
                                    <p>2.5%</p>
                                    <img src="../../Icons/arrowUp.svg" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="totalsale-container">
                            <p style="font-weight: normal; font-size: 14px;">Total Sold:</p>
                            <div class="amountSolds">
                                <p class="amountSoldTotalsale"><?php echo $targetProduct['prod_sold']?></p>
                                <div class="ratingSold">
                                    <p>2.5%</p>
                                    <img src="../../Icons/arrowUp.svg" alt="">
                                </div>
                            </div>
                            <?php
                            $totalAmount = $targetProduct['totalSales'];
                            ?>
                            <p class="amountTotalamount">Total Amount: ₱<?php echo number_format($totalAmount, 2); ?></p>
                        </div>
                    </div>

                    <div class="description-container">
                        <p class="desc">Description</p>
                        <p class="desc-info"><?php echo $targetProduct['prodDesc']?></p>
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
                    </div>
               </div>
               <div class="right-content">
                    <div class="Historytop-container">
                        <div class="history">
                            <img src="../../Icons/rotate.svg" alt="">
                            <p>History</p>
                        </div>

                        <div class="searchbarHistory">
                            <img src="../../Icons/searchblack.svg" alt="">
                            <input type="text" placeholder="Search product history..">
                        </div>
                    </div>
                   <div class="Historybottom-container">
                       <?php

                       if (isset($_GET['itemID'])) {
                           $product_ID = $_GET['itemID'];

                           $productDataInstance = new ProductData();
                           $replenishData = $productDataInstance->getReplenishData();

                           $replenishHistory = array();
                           foreach ($replenishData as $replenish) {
                               if ($replenish['prod_id'] == $product_ID) {
                                   $replenishHistory[] = $replenish;
                               }
                           }

                           if (!empty($replenishHistory)) {
                               echo '<table class="table">';
                               echo '<thead>';
                               echo '<tr>';
                               echo '<th scope="col">Date Replenish</th>';
                               echo '<th scope="col">Replenish Quantity</th>';
                               echo '<th scope="col">Previous Quantity</th>';
                               echo '<th scope="col">Total Quantity</th>';
                               echo '<th scope="col">Price Update</th>';
                               echo '</tr>';
                               echo '</thead>';
                               echo '<tbody>';

                               foreach ($replenishHistory as $replenish) {
                                   echo '<tr>';
                                   echo '<th scope="row">' . $replenish['replenish_date'] . ' - ' . $replenish['replenish_time'] . '</th>';
                                    echo '<td>' . $replenish['replenish_quantity'] . '</td>';
                                   echo '<td>' . $replenish['total_before_rep'] . '</td>';
                                   echo '<td>' . $replenish['total_after_rep'] . '</td>';
                                     if ($replenish['prod_new_price'] == $replenish['prod_old_price']) {
                                        echo '<td>No changes</td>';
                                    } else {
                                        echo '<td>' . '₱' . number_format($replenish['prod_new_price'], 2) . '</td>';
                                    }

                                    echo '</tr>';
                               }

                               echo '</tbody>';
                               echo '</table>';
                           } else {
                               echo 'No replenishment history available for this product.';
                           }
                       }
                       ?>

                   </div>

               </div>
            </div>
        </div>
    </div>



    <!--Edit Modal -->
    <form method="post" action="../../Admin/functions/editProduct.php" enctype="multipart/form-data">
        <input type="text" name="userid" value="<?php echo $userid=$usersData[0]['user_id']; ?>" hidden="hidden">
        <input type="text" name="pname" value="<?php echo $targetProduct['prodName'] ?>" hidden="hidden">
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="Modalclose-btn"  data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                        <div class="left-side">
                            <div class="image-container">
                                <img id="image-preview" src="../../Icons/<?php echo $targetProduct['prod_pic']; ?>" alt="" style="width: 100%; height: 100%; overflow: hidden">
                            </div>
                            <input type="file"  name="productPic[]" id="uploadBtn" accept="image/*" onchange="previewImage(event)">
                            <label for="uploadBtn">Choose File</label>
                        </div>

                        <div class="right-side">
                            <p class="pdInformation">Product Information</p>
                            <div class="cont1">
                                <div class="productName">
                                    <p>* Product Name</p>
                                    <input type="text" name="productName" placeholder="<?php echo $targetProduct['prodName'] ?>">
                                </div>

                                <div class="currentQuantity">
                                    <p>* Available Quantity</p>
                                    <input type="text" name="prodRemainingQuantity" placeholder="<?php echo $targetProduct['prod_remaining'] ?>">
                                </div>

                                <div class="productQty">
                                    <p style="margin-left: -25px;">* Add Product </p>
                                    <div class="amount-container">
                                        <button class="plus-btn" type="button">+</button>
                                        <input type="text" name="prodQuantity" id="quantityInput" value="0">
                                        <button class="minus-btn" type="button">-</button>
                                    </div>
                                </div>

                            </div>
                            <div class="cont2">
                                <div class="left-details">
                                    <div class="productID">
                                        <p>* Product ID</p>
                                        <input type="text" name="prodID" value="<?php echo $targetProduct['prod_id'] ?>" readonly>
                                    </div>

                                    <div class="productPrice">
                                        <p>* Product Price</p>
                                        <input type="text" name="prodPrice" style="padding-left: 5px; border: 1px solid #B1B1B1;" value="<?php echo $targetProduct['prod_price'] ?>">
                                        <input type="text" name="prodOldPrice" style="padding-left: 5px; border: 1px solid #B1B1B1;" value="<?php echo $targetProduct['prod_price'] ?>" hidden="hidden">
                                    </div>
                                </div>
                                <div class="right-details">
                                    <p>* Product Description</p>
                                    <textarea name="prodDesc" id="" cols="35" rows="4" placeholder="<?php echo $targetProduct['prodDesc'] ?>"></textarea>
                                </div>
                            </div>
                            <div class="productInfoBtns">
                                <button class="update-btn">Update Product</button>
                                <button id="closeModalBtn" class="disable-btn" type="button">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="text" name="TotalQuantity" value="<?php echo $targetProduct['prodQuantity']?>" hidden="hidden" readonly>

    </form>

       <!-- Disable account -->
    <form method="post" action="../../Admin/functions/disableproduct.php">
        <input type="text" name="userid" value="<?php echo $userid=$usersData[0]['user_id']; ?>" hidden="hidden">
        <input type="text" name="pname" value="<?php echo $targetProduct['prodName'] ?>" hidden="hidden">
        <div class="modal fade" id="deleteItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <input type="text" name="prodID" value="<?php echo $targetProduct['prod_id'] ?>" readonly hidden="hidden">
                        <input type="text" name="prodStatus" value="<?php echo $targetProduct['prodStatus'] ?>" readonly hidden="hidden">
                        <h6>Are you sure you want to <?php echo ($targetProduct['prodStatus'] === 'Disabled') ? 'enable' : 'disable'; ?> this product?</h6>
                        <p style="line-height: 20px;">This will <?php echo ($targetProduct['prodStatus'] === 'Disabled') ? 'enable' : 'disable'; ?> this product. You cannot undo this action</p>

                        <div class="modalDisablebtn">
                            <button style="background-color: <?php echo ($targetProduct['prodStatus'] === 'Disabled') ? '#3DC53A' : '#9C1421'; ?>; border: none;  box-shadow: 0px 0px 10px rgba(0, 0, 0, 25%); color: white;"><?php echo ($targetProduct['prodStatus'] === 'Disabled') ? 'Enable' : 'Disable'; ?></button>
                            <button style="background-color: white; border: none; color: black;  box-shadow: 0px 0px 10px rgba(0, 0, 0, 25%);" data-bs-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Javascript -->
    <script src="/Admin/admin_js/admin.js"></script>
</body>
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('image-container');

        const file = input.files[0];
        const reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
            container.style.display = 'block';
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            container.style.display = 'none';
        }
    }
    document.getElementById('closeModalBtn').addEventListener('click', function() {
        document.getElementById('editModal').style.display = 'none';
        location.reload();
    });

</script>

<script>
    const quantityInput = document.getElementById('quantityInput');
    const plusBtn = document.querySelector('.plus-btn');
    const minusBtn = document.querySelector('.minus-btn');

    plusBtn.addEventListener('click', function() {
        let quantity = parseInt(quantityInput.value, 10);
        if (!isNaN(quantity)) {
            quantityInput.value = quantity + 1;
        }
    });

    minusBtn.addEventListener('click', function() {
        let quantity = parseInt(quantityInput.value, 10);
        if (!isNaN(quantity) && quantity > 1) {
            quantityInput.value = quantity - 1;
        }
    });

    quantityInput.addEventListener('input', function() {
        let quantity = parseInt(quantityInput.value, 10) || 1;
        quantityInput.value = quantity;
    });
</script>
</html>

