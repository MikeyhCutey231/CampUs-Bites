<?php
    include("../functions/transactionData.php");
    $database = new Connection();
    $conn = $database->conn;

    if (!isset($_SESSION['USER_ID'])) {
        header("Location: staff-login.php");
        exit();
    }

    $currentDate = date('Y-m-d');

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <title>Staff POS | Order List</title>
    <link rel="stylesheet" href="../../Staff/staff_css/inventory.css">
</head>
<body>
    <div class="wrapper">

        <div class="left-container" id="sidebar">
            <div class="sidebar-logo">
                <div class="main-logo"></div>
                <div class="sidelogo-text">
                    <div style="position: relative;">
                        <a href="">CampUs</a>
                        <p class="">Bites</p>
                    </div>
                    <div class="close-btn"><img src="/Icons/x-circle.svg" alt=""></div>
                </div>
            </div>

            <ul class="sidebar-nav">
                <li class="sidebar-items" style="margin-top: 20px;">
                    <a href="staff-menu.html" class="sidebar-link"  id="invHover">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 3H8C9.06087 3 10.0783 3.42143 10.8284 4.17157C11.5786 4.92172 12 5.93913 12 7V21C12 20.2044 11.6839 19.4413 11.1213 18.8787C10.5587 18.3161 9.79565 18 9 18H2V3Z" stroke="#5F5F5F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 3H16C14.9391 3 13.9217 3.42143 13.1716 4.17157C12.4214 4.92172 12 5.93913 12 7V21C12 20.2044 12.3161 19.4413 12.8787 18.8787C13.4413 18.3161 14.2044 18 15 18H22V3Z" stroke="#5F5F5F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>    
                    <p>Menu Item</p>
                    </a>
                </li>


                <li class="sidebar-items">
                    <a href="staff-orderList.html" class="sidebar-link" id="invHover">
                        <div class="circle"> <p class="cirlcecolor">0</p></div>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.88995 1.62988L2.44495 4.88988V16.2999C2.44495 16.7322 2.61668 17.1468 2.92236 17.4525C3.22805 17.7581 3.64264 17.9299 4.07495 17.9299H15.4849C15.9172 17.9299 16.3318 17.7581 16.6375 17.4525C16.9432 17.1468 17.1149 16.7322 17.1149 16.2999V4.88988L14.6699 1.62988H4.88995Z" stroke="#5F5F5F" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2.44495 4.88965H17.1149" stroke="#5F5F5F" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.04 8.15039C13.04 9.015 12.6966 9.84419 12.0852 10.4556C11.4738 11.0669 10.6446 11.4104 9.78002 11.4104C8.91541 11.4104 8.08622 11.0669 7.47485 10.4556C6.86348 9.84419 6.52002 9.015 6.52002 8.15039" stroke="#5F5F5F" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                    <p>Orders</p>
                    </a>
                </li>

                
                <li class="sidebar-items">
                    <a href="staff-transactionHistory.html" class="sidebar-link" id="invHover">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_488_1719)">
                            <path d="M17.1149 6.51953V17.1145H2.44495V6.51953" stroke="#5F5F5F" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18.7449 2.44531H0.814941V6.52031H18.7449V2.44531Z" stroke="#5F5F5F" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.15002 9.78027H11.41" stroke="#5F5F5F" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_488_1719">
                            <rect width="19.56" height="19.56" fill="white"/>
                            </clipPath>
                            </defs>
                            </svg>
                        
                    <p>Order History</p>
                    </a>
                </li>


                <li class="sidebar-items">
                    <a href="staff-inventory.html" class="sidebar-link" id="invHover">
                        <div class="circle"> <p id="notificationCount">0</p></div>
                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.0625 7.44176L5.9375 3.33301" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.625 12.6667V6.33334C16.6247 6.05568 16.5514 5.78298 16.4125 5.54259C16.2735 5.3022 16.0738 5.10258 15.8333 4.96375L10.2917 1.79709C10.051 1.65812 9.77793 1.58496 9.5 1.58496C9.22207 1.58496 8.94903 1.65812 8.70833 1.79709L3.16667 4.96375C2.92621 5.10258 2.72648 5.3022 2.58753 5.54259C2.44858 5.78298 2.37528 6.05568 2.375 6.33334V12.6667C2.37528 12.9443 2.44858 13.217 2.58753 13.4574C2.72648 13.6978 2.92621 13.8974 3.16667 14.0363L8.70833 17.2029C8.94903 17.3419 9.22207 17.415 9.5 17.415C9.77793 17.415 10.051 17.3419 10.2917 17.2029L15.8333 14.0363C16.0738 13.8974 16.2735 13.6978 16.4125 13.4574C16.5514 13.217 16.6247 12.9443 16.625 12.6667Z" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2.58875 5.50977L9.49999 9.50768L16.4112 5.50977" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.5 17.48V9.5" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                    <p>Item Inventory</p>
                    </a>
                </li>



                <li class="sidebar-items">
                    <a href="staff-salesReport.html" class="sidebar-link">
                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.25 15.8332V7.9165" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.5 15.8332V3.1665" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4.75 15.8335V11.0835" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                    <p>Sales Report</p>
                    </a>
                </li>


                <li class="sidebar-items">
                    <a href="../../Staff/staff_html/staff-logout.php" class="sidebar-link">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.33428 17.1132H4.07464C3.64239 17.1132 3.22784 16.9415 2.92219 16.6358C2.61654 16.3302 2.44482 15.9156 2.44482 15.4834V4.07464C2.44482 3.64239 2.61654 3.22784 2.92219 2.92219C3.22784 2.61654 3.64239 2.44482 4.07464 2.44482H7.33428" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.0386 13.8537L17.1131 9.77914L13.0386 5.70459" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.1129 9.77881H7.33398" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>                                                        
                    <p>Signout</p>
                    </a>
                </li>

                <div class="usep-footercard">
                    <img src="/Icons/useplogo.png" alt="" width="50px">
                    <p style="font-size: 11px; margin-bottom: -5px; font-weight: bold; margin-top: 5px;">University of Southeastern</p>
                    <p style="font-size: 11px; font-weight: bold;">Philippines (TU)</p>
    
                    <p style="font-size: 12px; margin-bottom: -5px; color: #0F947E;">University Property</p>
                    <p  style="font-size: 12px; color: #0F947E;">E-Canteen</p>
    
                    <p style="font-size: 12px; margin-bottom: -20px; color: #0F947E; font-weight: 900;">Learn More</p>
                    <p style="margin-bottom: 0px; color: #0F947E;">___________</p>
                </div>
            </ul>
        </div>

        <div class="right-container">
            <div class="head-content">
                <div class="Menu-name">
                    <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                    <p style="margin-bottom: 0;">Item Inventory</p>
                </div>
                <div class="head-searchbar">
                    <img src="/Icons/searchgreen.svg" alt="">
                    <input type="text" name="fname" placeholder="Search here...">
                </div>
                <div class="usep-texthead">
                    <img src="/Icons/useplogo.png" alt="" width="30px" height="30px">
                    <p style="margin-bottom: 0px; margin-top: 3px; margin-left: 10px; font-weight: 600;">UseP (Tagum Unit)</p>
                </div>
                <div class="user-profile">
                    <div class="admin-profile"></div>
                    <div class="admin-detail">
                        <p style="margin-bottom: 0px; font-weight: 700;">Michael</p>
                        <p style="margin-bottom: 0px; font-size: 7px; background-color: #0F947E; color: white; padding: 3px; border-radius: 3px;">Cashier</p>
                    </div>
                </div>
            </div>

            <div class="main-content">
                <div class="top-container">
                  <div class="right-button">
                    <div class="filter-recent">
                        <select name="" id="filterDropdown" class="form-select filterDropdown">
                            <option value="0">All Product</option>
                            <?php 
                                $displayCategory = "SELECT * FROM prod_category";
                                $categoryRun = mysqli_query($conn, $displayCategory);
                                while ($row = mysqli_fetch_array($categoryRun)) {
                                    ?>
                                        <option value="<?php echo $row['CATEGORY_ID'] ?>"><?php echo $row['CATEGORY_NAME'] ?></option>
                                    <?php
                                }
                            ?>
                        </select>                       
                    </div>

                        <a href="staff-inventoryReport.html">
                            <button class="view-records">
                                <img src="/Icons/paperclip.svg" alt="">
                                View Records
                            </button>  
                        </a>
                  </div>
                </div>
                <div class="bottom-container" id="getData">
                      <!-- <div class="ItemContainer">
                            <div class="card-main">
                                <button class="card-1">
                                    <div class="Cardtop-content">
                                        <img src="../../Icons/abobo.svg" alt="" class="foodImage">
                                        <p class="foodName">Adobong Manok</p>
                                        <p class="availabe">Available</p>
                                        <img src="../../Icons/InvetoryStatus.svg" alt="" class="statusDesign" width="100px">
                                    </div>
                                    <div class="Cardbottom-content">
                                        <div class="topBottomcard-content">
                                            <img src="../../Icons/clipboard.svg" alt="" width="18px">
                                            <p>Code #235172</p>
                                        </div>
                                        <div class="bottomBottomcard-content">
                                            <img src="../../Icons/package.svg" alt="">
                                            <p>10 stock available</p>
                                        </div>
                                    </div>
                                </button>
        
                                <a href="staff-viewItem.html"><button class="restock">View Item</button></a>
                            </div>
                        </div>

                    <div class="paginationFooter">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    </div> -->

                </div>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="/Staff/staff_js/staff.js"></script>
    <script>
    $(document).ready(function () {
        function updateData(page, selectedValue) {
            $.ajax({
                url: "../functions/InventoryPagination.php",
                method: "POST",
                data: {
                    page: page,
                    selectedValue: selectedValue // Add selected value to the data
                },
                success: function (data) {
                    $("#getData").html(data);
                },
            });
        }

        // Initial load with page 1 and no selected value
        updateData(1, '');

        // Dropdown change event
        $('#filterDropdown').on('change', function () {
            var selectedValue = $(this).val();
            updateData(1, selectedValue); // Reload with page 1 and the selected value
        });

        // Pagination click event
        $(document).on("click", ".page-item", function () {
            var page = $(this).attr("id");
            var selectedValue = $('#filterDropdown').val();
            updateData(page, selectedValue); // Reload with the clicked page and the selected value
        });
    });
</script>
</body>
</html>