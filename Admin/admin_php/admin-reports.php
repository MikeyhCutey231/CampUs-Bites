<?php

require '../../Admin/functions/UserData.php';
include_once('../../Admin/functions/ProductData.php');


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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script><!--PIE CHART-->

    <title>CampUs Bites</title>
    <link rel="stylesheet" href="../../Admin/admin_css/admin-reports.css">
</head>
<body>
    <div class="wrapper">

        <?php include 'sidebar.php'; ?>

        <div class="right-container">
            <div class="head-content">
                <div class="Menu-name">
                    <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                    <p style="margin-bottom: 0;">Sales Report</p>
                </div>
                <div class="head-searchbar">
                    <img src="../../Icons/search.svg" alt="" width="20px" style="margin-top: 12px;">
                    <input type="text" name="fname" placeholder="Search here...">
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

            <div class="container-fluid col-12 main-content justify-content-between">
                <div class="row justify-content-between">
                 <!--SEARCH BAR AND THE TABLE-->
                 <div class="col-lg-8 px-1 mb-3 justify-content-between">
                    <div class="row justify-content-between mb-2 p-0"> 
                        <div class="input-group">
                            <span class="input-group-text" style="background-color: white; box-shadow: 1px 2px 2px rgba(0, 0, 0, 0.1); " width="100%">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="#9C1421" class="bi bi-search search-icon" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </span>
                            <input type="text" class="form-control" id="search" placeholder="Search an item here..." oninput="searchTable()" style="box-shadow: 1px 2px 2px rgba(0, 0, 0, 0.1);">

                        </div>
                        
                    </div>

                        <div class="container-fluid report-table" style="width: 100%; background-color: white; ">
                            <div class="row justify-content-center">
                                <div class="col-12 border" style="width: 100%; height: 550px; border-radius: 5px;  box-shadow: 1px 2px 2px rgba(0, 0, 0, 0.1); overflow: auto; ">
                                    <table class="table" >
                                       
                                        <thead class="mb-2">
                                            <tr class="header">
                                              <th scope="col" style="color:#5f5f5f">Date</th>
                                              <th scope="col">Product Name</th>
                                              <th scope="col">Cost Per Unit</th>
                                              <th scope="col">Selling Price</th>
                                              <th scope="col">Gross Margin</th>
                                              <th scope="col">Product Sold No.</th>
                                              <th scope="col">Subtotal</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr>
                                                <?php
                                                $productDataInstance = new ProductData();
                                                $productData = $productDataInstance->getSalesReport();
                                                $categoryChartData = $productDataInstance->getCategoryChartData();
                                                $topSoldProducts = json_decode($productDataInstance->getTopSoldProducts(), true);

                                                if (!empty($productData)) {
                                                    foreach ($productData as $product) {
                                                        $formattedDate = date('d-m-Y', strtotime($product['transaction_date']));
                                                        echo '<tr class="searchable">
                                                        <td>' . $formattedDate . '</td>
                                                        <td>' . $product['prod_name'] . '</td>
                                                        <td>₱' . number_format($product['prod_capital_price'], 2) . '</td>
                                                        <td>₱' . number_format($product['prod_selling_price'], 2) . '</td>
                                                        <td>₱' . number_format($product['gross_margin'], 2) . '</td>
                                                        <td>' . $product['pos_prod_quantity'] . '</td>
                                                        <td>' . $product['subtotal'] . '</td>
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
                    <!--FILTERS, GRAPH, RECENT ORDERS-->
                    <div class=" col-lg-4 px-0" >
                        <div class="container-fluid g-4 ">
                            <div class="row mb-2 report-filter">
                                <div class="col-4 px-1">
                                    <div class="filter-recent">
                                        <select name="" id="" class="form-select filterDropdown">
                                            <option value="1">Filter Product Category</option>
                                            <option value="2">Weekly</option>
                                            <option value="3">Monthly</option>
                                        </select>                       
                                    </div>
                                </div>
                                <div class="col-4 px-1 printRecord">
                                        <button type="button" class="print-records" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-printer printRecords-icon" viewBox="0 0 16 16">
                                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                                            </svg>
                                           Print Records
                                        </button>
                                </div>
                            </div>

                            <div class="row px-1 py-1 mb-2 report-graph" >
                                <div class="col-12 p-3 border" style="background-color: white; border-radius: 5px; box-shadow: 1px 2px 2px rgba(0, 0, 0, 0.1); ">
                                    <h5 class="mb-3">Total Sales Chart</h5>
                                    <canvas class="mb-1" id="myPieChart"  height="180" style="width: 100%;"></canvas>
                                </div> 
                            </div>

                            <div class="row px-1">
                                <div class="col-12 px-0 py-2 border" style="background-color: white; border-radius: 5px; height:280px; box-shadow: 1px 2px 2px rgba(0, 0, 0, 0.1); overflow: auto;">
                                    <div class="col-12 p-3" style="height: 60px; font-weight: bold; border-bottom: 1.5px solid #898989 ;">
                                        <h5>Best Seller Items</h5>
                                    </div>

                                    <?php foreach ($topSoldProducts as $product): ?>
                                        <div class="container col-12 px-3 justify-content-between recent-orders" style="height: 50px;  border-bottom: 1px solid #898989;">
                                            <div class="row px-3" style="height: 40px">
                                                <div class="col-2 d-flex align-items-center justify-content-center">
                                                    <div class="d-flex align-items-center justify-content-center cartIcon-container">
                                                        <img src="../../Icons/<?php echo $product['product_pic'] ?>" alt="" class="cartIcon" style="width: 38px; border-radius: 25px; object-fit: cover; height: 35px;">
                                                    </div>
                                                </div>
                                                <div class="col-8 py-1 px-0">
                                                    <div class="col-12" style="font-size: 15px; font-weight: bold; margin-top: 5px;"><?= $product['product_name'] ?></div>
                                                </div>
                                                <div class="col-2 py-1">
                                                    <div class="col-12" style="font-size: 15px; font-weight: bold; margin-top: 5px;"><?= $product['product_count'] ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                
                        </div>
                    </div>   
        </div>
    </div>






    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="topModal">
                        <i id="closeModalBtn" class="fa-solid fa-xmark"></i>

                        <button type="button" class="modalPrintRec" id="printRecord">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-printer printRecords-icon" viewBox="0 0 16 16">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                            </svg>
                            Print Records
                        </button>

                    </div>
                    <div class="midModal">
                        <div class="left-Modal">

                            <div class="DateFilter">
                                <div>
                                    <label for="fromDate">From</label>
                                    <input type="date" id="fromDate">
                                </div>

                                <div style="margin-left: 10px;">
                                    <label for="toDate">To</label>
                                    <input type="date" id="toDate">
                                </div>
                            </div>


                            <!--<div class="TypeOfFilter">
                                <h6 style="margin-bottom: 0px;">Type of Filter</h6>
                                <div style="padding-left: 20px; margin-top: 5px;">
                                    <input type="checkbox" value="Daily" name="filterType" id="daily">
                                    <label for="daily">Daily</label>

                                    <input type="checkbox" value="Monthly" name="filterType" id="monthly" style="margin-left: 50px;">
                                    <label for="monthly">Monthly</label>
                                </div>
                                <div style="padding-left: 20px;">
                                    <input type="checkbox" value="Weekly" name="filterType" id="weekly">
                                    <label for="weekly">Weekly</label>

                                    <input type="checkbox" value="Yearly" name="filterType" id="yearly" style="margin-left: 32px;">
                                    <label for="yearly">Yearly</label>
                                </div>
                            </div>-->

                            <div class="CategoryContainer">
                                <label for="categoryFilter">Category to Filter</label>
                                <select class="form-select" id="categoryFilter">
                                    <option value="">Select Category</option>
                                    <!-- Add options dynamically using JavaScript -->
                                </select>
                            </div>

                            <div class="ProductContainer">
                                <label for="productFilter">Types of Product</label>
                                <select class="form-select" id="productFilter">
                                    <option value="">Select Product</option>
                                </select>
                            </div>

                        </div>
    
                        <div class="right-Modal">
                            <table class="table">
                                <thead class="modalTable">
                                    <tr>
                                        <th scope="col">Date</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Cost Per Unit</th>
                                        <th scope="col">Selling Price</th>
                                        <th scope="col">Gross Margin</th>
                                        <th scope="col">Product Sold No.</th>
                                        <th scope="col">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                <?php


                                if (!empty($productData)) {
                                    foreach ($productData as $product) {
                                        $formattedDate = date('d-m-Y', strtotime($product['transaction_date']));
                                        echo '<tr>
                                        <td>' . $formattedDate . '</td>
                                        <td>' . $product['prod_name'] . '</td>
                                        <td>₱' . number_format($product['prod_capital_price'], 2) . '</td>
                                        <td>₱' . number_format($product['prod_selling_price'], 2) . '</td>
                                        <td>₱' . number_format($product['gross_margin'], 2) . '</td>
                                        <td>' . $product['pos_prod_quantity'] . '</td>
                                        <td>₱' . $product['subtotal'] . '</td>
                                    </tr>';
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="lowerModal">
                        <button type="button" id="searchFilterBtn">Search Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Javascript -->
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

            <script>
                function searchTable() {
                    var input, filter, table, tr, td, i, txtValue;
                    input = document.getElementById("search");
                    filter = input.value.toUpperCase();
                    table = document.querySelector(".table");
                    tr = table.getElementsByTagName("tr");

                    for (i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[1];
                        if (td) {
                            txtValue = td.textContent || td.innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                            } else {
                                tr[i].style.display = "none";
                            }
                        }
                    }
                }
            </script>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const categoryFilter = document.getElementById("categoryFilter");
                    const productFilter = document.getElementById("productFilter");
                    const fromDateInput = document.getElementById("fromDate");
                    const toDateInput = document.getElementById("toDate");
                    const searchFilterBtn = document.getElementById("searchFilterBtn");
                    const tableBody = document.getElementById("tbody");
                    fetchCategories();

                    categoryFilter.addEventListener("change", function () {
                        fetchProductsByCategory(categoryFilter.value);
                    });

                    searchFilterBtn.addEventListener("click", function (event) {
                        event.preventDefault();
                        applyFilters();
                    });

                    function fetchCategories() {
                        fetch('../../Admin/functions/CategoryData.php')
                            .then(response => response.json())
                            .then(data => {
                                categoryFilter.innerHTML = '<option value="">Select Category</option>';

                                data.forEach(category => {
                                    var option = document.createElement('option');
                                    option.value = category.id;
                                    option.textContent = category.name;
                                    categoryFilter.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error fetching categories:', error));
                    }

                    function fetchProductsByCategory(categoryId) {
                        fetch('../../Admin/functions/getProductsByCategory.php?categoryId=' + categoryId)
                            .then(response => response.json())
                            .then(data => {
                                productFilter.innerHTML = '<option value="">Select Product</option>';

                                data.forEach(product => {
                                    var option = document.createElement('option');
                                    option.textContent = product.name;
                                    productFilter.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error fetching products:', error));
                    }

                    function applyFilters() {
                        const fromDate = fromDateInput.value;
                        const toDate = toDateInput.value;
                        const categoryValue = categoryFilter.value;
                        const productValue = productFilter.value;

                        fetchFilteredData(fromDate, toDate, categoryValue, productValue);
                    }

                    /*function sendSaleData() {
                        $.ajax({
                            type: "POST",
                            url: "pdf_gen.php",
                            data: {
                                fromDate: fromDateInput.value,
                                toDate: toDateInput.value,
                                categoryValue: categoryFilter.value,
                                productValue: productFilter.value
                            },
                            success: function (response) {
                                // Handle the response if needed
                                console.log('AJAX Success:', response);
                            },
                            error: function (error) {
                                console.error('Error sending sale data:', error);
                            }
                        });
                    }*/

                    function fetchFilteredData(fromDate, toDate, categoryValue, productValue) {
                        if (!tableBody) {
                            console.error('Table body element not found.');
                            return;
                        }

                        fetch(`../../Admin/functions/getFilterData.php?fromDate=${fromDate}&toDate=${toDate}&selectedCategory=${categoryValue}&selectedProduct=${productValue}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Data received:', data);
                                tableBody.innerHTML = '';

                                if (data && data.length > 0) {
                                    data.forEach(sale => {
                                        console.log('dasdkasd');
                                        console.log('Sale object:', sale);

                                        const dateParts = sale.TRANSACTION_DATE.split('-');
                                        const formattedDate = dateParts.length === 3
                                            ? new Date(`${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`).toLocaleDateString('en-GB')
                                            : 'N/A';

                                        console.log('Formatted date:', formattedDate);

                                        const rowHTML = `
                        <tr>
                            <td>${formattedDate}</td>
                            <td>${sale.PROD_NAME}</td>
                            <td>₱${Number(sale.PROD_CAPITAL_PRICE).toFixed(2)}</td>
                            <td>₱${Number(sale.PROD_SELLING_PRICE).toFixed(2)}</td>
                            <td>₱${Number(sale.GROSS_MARGIN).toFixed(2)}</td>
                            <td>${sale.POS_PROD_QUANTITY}</td>
                            <td>₱${Number(sale.SUBTOTAL).toFixed(2)}</td>
                        </tr>`;
                                        tableBody.innerHTML += rowHTML;
                                    });
                                } else {
                                    const errorRowHTML = `
                    <tr>
                        <td colspan="7">No data found</td>
                    </tr>`;
                                    tableBody.innerHTML += errorRowHTML;
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching filtered sales report data:', error);
                            });
                    }

                });
            </script>
            <script>
                document.getElementById("printRecord").addEventListener("click", function() {
                    var form = document.createElement("form");
                    form.method = "POST";
                    form.action = "pdf_gen.php";

                    // Add input fields for your data
                    var fromDateInput = document.createElement("input");
                    fromDateInput.type = "hidden";
                    fromDateInput.name = "fromDate";
                    fromDateInput.value = fromDate.value; // Set the actual value here
console.log(fromDate.value);
                    var toDateInput = document.createElement("input");
                    toDateInput.type = "hidden";
                    toDateInput.name = "toDate";
                    toDateInput.value = toDate.value; // Set the actual value here
console.log(toDate.value);
                    var categoryValueInput = document.createElement("input");
                    categoryValueInput.type = "hidden";
                    categoryValueInput.name = "categoryValue";
                    categoryValueInput.value = categoryFilter.value; // Set the actual value here

                    var productValueInput = document.createElement("input");
                    productValueInput.type = "hidden";
                    productValueInput.name = "productValue";
                    productValueInput.value = productFilter.value; // Set the actual value here

                    // Append input fields to the form
                    form.appendChild(fromDateInput);
                    form.appendChild(toDateInput);
                    form.appendChild(categoryValueInput);
                    form.appendChild(productValueInput);

                    // Append the form to the document and submit it
                    document.body.appendChild(form);
                    form.submit();

                    // Remove the form from the document
                    document.body.removeChild(form);
                });
            </script>
            <script>
                $(document).ready(function () {
                    $("#closeModalBtn").click(function () {
                        $("#exampleModal").modal("hide");
                    });
                });
            </script>


            <script>
                var categoryData = <?php echo $categoryChartData; ?>;

                var ctx = document.getElementById('myPieChart').getContext('2d');

                var data = {
                    labels: categoryData.map(item => item.category),
                    datasets: [{
                        data: categoryData.map(item => item.product_count),
                        backgroundColor: ['#9C1421', '#403D55', '#A2C53A', '#F0C419', '#0F947E']
                    }],
                    hoverOffset: 4
                };

                var options = {
                    maintainAspectRatio: false,
                    responsive: false,
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            fontColor: '#5f5f5f',
                            fontSize: 10,
                            boxWidth: 10,
                            padding: 10
                        }
                    },
                };

                var myPieChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: data,
                    options: options
                });
            </script>

     <script src="../../Admin/admin_js/admin.js"></script>
</body>
</html>