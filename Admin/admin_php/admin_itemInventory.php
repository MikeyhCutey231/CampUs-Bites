<?php

require '../../Admin/functions/UserData.php';
require_once '../../Admin/functions/ProductData.php';

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
<!--    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>-->
    <title>CampUs Bites</title>
    <link rel="stylesheet" href="../../Admin/admin_css/iteminventory.css">
</head>
<body>
    <div class="wrapper">

        <?php include 'sidebar.php'; ?>

        <div class="right-container">
            <div class="head-content">
                <div class="Menu-name">
                    <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                    <p style="margin-bottom: 0;">Item Inventory</p>
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
              <div class="top-container">
                <div class="left-button">  
                    <img src="../../Icons/plus-circle.svg" alt="" width="18px">                      
                    <button class="add-item"  data-bs-target="#editModal" data-bs-toggle="modal">  
                        Add Item
                    </button>
                </div>
                <div class="right-button">
                    <a href="admin-InventoryReport.php">
                        <button class="view-records">
                            <img src="../../Icons/paperclip.svg" alt="">
                            View Records
                        </button>
                    </a>

                    <div class="filter-recent">
                        <select name="sortOption" id="sortOption" class="form-select filterDropdown">
                            <option value="1">Sort by Item ID</option>
                            <option value="2">Sort by Name</option>
                            <option value="3">Sort by Product Quantity Ascending</option>
                            <option value="4">Sort by Product Quantity Descending</option>
                        </select>
                    </div>


                </div>
              </div>
              <div class="bottom-container">
                  <?php
                  $productsPerPage = 12;
                  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

                  $offset = ($page - 1) * $productsPerPage;
                  $productDataInstance = new ProductData();
                  $productData = $productDataInstance->getProductDataWithPagination($productsPerPage, $offset);

                  // Display the products
                  if (!empty($productData)) {
                      foreach ($productData as $product) {
                          // Check if the keys exist before accessing them
                          $status = isset($product['prodStatus']) ? $product['prodStatus'] : 'Unknown';
                          $textColor = ($status === 'Disabled') ? '#FF0000' : '#29AD27';

                          $notificationCircle = (isset($product['prodQuantity']) && $product['prodQuantity'] <= 5) ? '<div class="notification-circle"><p>' . $product['prodQuantity'] . '</p></div>' : '';

                          // Add a class based on prod_remaining value
                          $cardClass = (isset($product['prod_remaining']) && $product['prod_remaining'] <= 5) ? 'low-stock' : '';

                          // Check if the keys exist before accessing them
                          echo '<div class="card-main">
                            <button class="card-1 ' . $cardClass . '">
                                <div class="Cardtop-content">
                                    <img src="../../Icons/' . (isset($product['prod_pic']) ? $product['prod_pic'] : '') . '" alt="" style="width: 80px; height: 60px; object-fit: cover; border-radius: 5px;" class="foodImage">
                                    <p class="foodName">' . (isset($product['prodName']) ? $product['prodName'] : '') . '</p>
                                    <p class="status" style="color: ' . $textColor . ';">' . $status . '</p>
                                </div>
                                <div class="Cardbottom-content">
                                    <div class="topBottomcard-content">
                                        <img src="../../Icons/clipboard.svg" alt="" width="18px">
                                        <p>Code: ' . (isset($product['prod_id']) ? $product['prod_id'] : '') . '</p>
                                    </div>
                                    <div class="bottomBottomcard-content">
                                        <img src="../../Icons/package.svg" alt="">
                                        <p>' . (isset($product['prod_remaining']) ? $product['prod_remaining'] : '') . ' stock available</p>
                                        ' . $notificationCircle . '
                                    </div>
                                </div>
                            </button>

                            <a href="admin-viewItem.php?itemID=' . (isset($product['prod_id']) ? $product['prod_id'] : '') . '" style="color: white;"><button class="restock">View Item</button></a>
                        </div>';
                      }
                  }

                  $totalProducts = $productDataInstance->getTotalProductsCount();
                  $totalPages = ceil($totalProducts / $productsPerPage);

                  // Move pagination block to the bottom of the screen
                  echo '<div class="paginationFooter fixed-bottom">';
                  echo '<nav aria-label="Page navigation example">';
                  echo '<ul class="pagination justify-content-center">';

                  if ($page > 1) {
                      echo '<li class="page-item">
        <a class="page-link" href="?page=' . ($page - 1) . '" tabindex="-1">Previous</a>
    </li>';
                  }

                  for ($i = 1; $i <= $totalPages; $i++) {
                      echo '<li class="page-item ' . ($i === $page ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                  }

                  if ($page < $totalPages) {
                      echo '<li class="page-item">
        <a class="page-link" href="?page=' . ($page + 1) . '">Next</a>
    </li>';
                  }

                  echo '</ul>';
                  echo '</nav>';
                  echo '</div>';
                  ?>
              </div>
            </div>
        </div>
    </div>



    <!--Add item Modal -->
    <form method="POST" action="../../Admin/functions/addProduct.php" enctype="multipart/form-data">
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="left-side">
                            <div class="image-container">
                                <img id="image-preview" src="../../Icons/upload-cloud.svg" alt="" style="width: 100%; height: 100%; overflow: hidden">
                                <p style=" margin-bottom: -5px;">Drag & drop photo</p>
                                <p>or <span style="color: #9C1421;">choose from your computer</span></p>
                            </div>
                            <input type="text" name="userid" hidden="hidden" value="<?php echo $userid=$usersData[0]['user_id'] ?>">
                            <input type="file"  name="productPic[]" id="uploadBtn" accept="image/*" onchange="previewImage(event)">
                            <label for="uploadBtn">Choose File</label>


                        </div>
                        <div class="right-side">
                            <p class="pdInformation">Product Information</p>

                            <div class="cont1">
                                <div class="productName">
                                    <p><span style="color: red;">*</span> Product Name</p>
                                    <input type="text" name="prodName">
                                </div>

                                <div class="productQty">
                                    <p style="margin-left: -25px;"><span style="color: red;">*</span> Product Quantity</p>
                                    <div class="amount-container">
                                        <button class="plus-btn" type="button">+</button>
                                        <input type="text" name="prodQuantity" id="quantityInput" value="1">
                                        <button class="minus-btn" type="button">-</button>
                                    </div>


                                </div>
                            </div>
                            <div class="cont2">
                                <div class="left-details">
                                    <div class="productID">
                                        <p><span style="color: red;">*</span> Category</p>
                                        <select name="category">
                                            <option value=1>Hot Meals</option>
                                            <option value=2>Drinks</option>
                                            <option value=3>Snacks</option>
                                            <option value=4>Sandwiches</option>
                                            <option value=5>Salads</option>
                                            <option value=6>Desserts</option>
                                            <option value=7>Sauces</option>
                                            <option value=8>Utensils</option>
                                            <option value=9>Packaging</option>
                                            <option value=10>School Supplies</option>
                                            <option value=11>Special</option>
                                            <option value=12>Local</option>
                                            <option value=13>Fresh Fruits</option>
                                            <option value=14>Others</option>
                                        </select>
                                    </div>


                                    <div class="productID">
                                        <p><span style="color: red;">*</span> Product ID</p>
                                        <input type="text" name="prodId" id="quantityInput" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>


                                    <div class="productCapitalPrice">
                                        <p><span style="color: red;">*</span> Capital Price</p>
                                        <input type="text" style="padding-left: 5px; border: 1px solid #B1B1B1;" name="prodCapitalPrice" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                    </div>

                                    <div class="productSellingPrice">
                                        <p><span style="color: red;">*</span> Selling Price</p>
                                        <input type="text" style="padding-left: 5px; border: 1px solid #B1B1B1;" name="prodSellingPrice" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                    </div>

                                </div>
                                <div class="right-details">
                                    <p><span style="color: red;">*</span> Product Description</p>
                                    <textarea name="prod_desc" id="" cols="35" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="productInfoBtns">
                                <button class="update-btn" name="submit" type="submit">Add Product</button>
                                <button class="disable-btn" data-bs-dismiss="modal" id="cancelButton" type="button">Cancel</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <!-- Javascript -->

    <script src="../../Admin/admin_js/admin.js"></script>
    <script>
        $(document).ready(function() {
            loadProducts();

            $("#sortOption").change(function() {
                loadProducts();
            });

            function loadProducts() {
                var selectedOption = $("#sortOption").val();

                $.ajax({
                    type: "POST",
                    url: "../../Admin/functions/itemFilter.php",
                    data: {
                        sortOption: selectedOption
                    },
                    success: function(response) {
                        $("#productContainer").html(response);
                    }
                });
            }
        });
    </script>
    <script>
        document.getElementById('cancelButton').addEventListener('click', function() {
            window.location.href = 'admin_itemInventory.php';
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
</body>
</html>