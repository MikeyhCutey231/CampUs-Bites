<?php
require '../../Admin/functions/UserData.php';
include_once('../../Admin/functions/ProductData.php');

$productDataInstance = new ProductData();

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
    <link rel="stylesheet" href="../../Admin/admin_css/admin-inventoryReport.css">
</head>
<body>
    <div class="wrapper">

        <?php include 'sidebar.php'; ?>

        <div class="right-container">
            <div class="head-content">
                <div class="Menu-name">
                    <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                    <p style="margin-bottom: 0;">Inventory Report</p>
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
                <div class="content">
                    <div class="button-top">
                        <div class="filter-recent">
                            <select name="" id="" class="form-select filterDropdown">
                                <option value="1">Filter</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>                       
                     
                    </div>

                        <a href="pdf_inventoryReport.php">
                            <div class="print">
                                <i class="fa-solid fa-print" style="padding-right:10px"></i> Print Report
                            </div>
                        </a>


                </div>

                    <div class="main-table" >

                        <table class="table table-hover">
                          
                            <thead>
                              <tr>
                                <th scope="col" style="font-size: 12px;padding-left: 25px;">Product Number</th>
                                <th scope="col" style="font-size: 12px;padding-left: 25px;">Product Name</th>
                                <th scope="col" style="font-size: 12px;padding-left: 25px;">Price</th>
                                <th scope="col" style="font-size: 12px;padding-left: 25px;">Stock</th>
                                <th scope="col" style="font-size: 12px;padding-left: 25px;">Product Sold</th>
                                <th scope="col" style="font-size: 12px;padding-left: 25px;">Total Sales</th>
                              </tr>
                            </thead>
                            <tbody>

                            <?php
                            $productData = $productDataInstance->getProductData();
                            if (!empty($productData)) {
                                foreach ($productData as $product) {
                                    echo '<tr>
                                        <td>' . $product['prod_id'] . '</td>
                                        <td>' . $product['prodName'] . '</td>
                                        <td>₱' . number_format($product['prod_price'], 2) . '</td>
                                        <td>' . $product['prodQuantity'] . '</td>
                                        <td>' . $product['prod_sold'] . '</td>
                                        <td>₱' . number_format($product['prod_sold'] * $product['prod_price'], 2) . '</td>
                                    </tr>';
                                }
                            }
                            ?>
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="/Admin/admin_js/admin.js"></script>
</body>
</html>