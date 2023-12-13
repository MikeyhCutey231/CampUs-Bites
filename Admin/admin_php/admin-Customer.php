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
    <link rel="stylesheet" href="../../Admin/admin_css/admin-customer.css">
</head>

<body>
<div class="wrapper">

    <?php include 'sidebar.php'; ?>

    <div class="right-container">
        <div class="head-content">
            <div class="Menu-name">
                <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                <p style="margin-bottom: 0;">Customer</p>
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
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-11 mt-4">
                        <div class="content-1 p-2 d-flex">
                            <div class="searchbar justify-content-start d-flex col-8">
                                    <span class="input-group-text col-7">
                                        <img src="../../Icons/search.svg" alt="searchIcon" width="18px">
                                        <input type="text" name="fname" id="live_search" placeholder="Search name...">
                                    </span>
                            </div>
                            <div class="sortContainer d-flex align-items-center justify-content-end">
                                <h6 class="sort">Sort by:</h6>
                            </div>
                            <div class="filter-recent">
                                <select id="sortDropdown" class="form-select filterDropdown">
                                    <option value="1">Alphabetical A-Z</option>
                                    <option value="2">Alphabetical Z-A</option>
                                    <option value="3">Others</option>
                                </select>

                            </div>

                            <!-- <div class="addEmployeebtn align-items-center">
                                <button class="btnAdd" data-bs-toggle="modal" data-bs-target="#editInfo">
                                    <img src="/Icons/user-plus.svg">
                                    <h6 class="text-start">Add Employee</h6>
                                </button>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-12 col-md-11 mt-3" id="searchresult">
                        <div class="content-2 d-flex">
                            <div class="col-12 table-container">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col" style="color: #555; font-weight: normal;">Picture</th>
                                        <th scope="col" style="color: #555; font-weight: normal;">Last Name</th>
                                        <th scope="col" style="color: #555; font-weight: normal;">First Name</th>
                                        <th scope="col" style="color: #555; font-weight: normal;">Middle Name</th>
                                        <th scope="col" style="color: #555; font-weight: normal;">Customer ID</th>
                                        <th scope="col" style="color: #555; font-weight: normal;">Phone Number</th>
                                        <th scope="col" style="color: #555; font-weight: normal;">Status</th>
                                        <th scope="col" style="color: #555; font-weight: normal;"></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $customerDataInstance = new UserData();
                                    $customerData = $customerDataInstance->getCustomerData();

                                    if (!empty($customerData)) {
                                        foreach ($customerData as $customer) {
                                            echo '<tr>';
                                            echo '<td><img src="../../Icons/' . $customer['customer_profPic'] . '" class="staffPic rounded-circle" style="width: 42px; height: 40px;"></td>';

                                            // Separate first name, middle name, and last name
                                            $lastName = $customer['customer_lname'];
                                            $firstName = $customer['customer_fname'];
                                            $middleName = $customer['customer_mname'];

                                            echo '<td>' . $lastName . '</td>';
                                            echo '<td>' . $firstName . '</td>';
                                            echo '<td>' . $middleName . '</td>';
                                            echo '<td>' . $customer['customer_id'] . '</td>';
                                            echo '<td>' . $customer['customer_phoneNum'] . '</td>';
                                            echo '<td class="status';
                                            if ($customer['customer_status'] !== 'Active') {
                                                echo ' Dstatus';
                                            }
                                            echo '">' . $customer['customer_status'] . '</td>';
                                            echo '<td><a class="vwEmpProfile btn btn-secondary d-flex" href="admin-viewCustomer.php?customer_id=' . $customer['customer_id'] . '" role="button"><h6 class="text-center m-auto">View Profile</h6></a></td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-3">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </div>
</div>
</div>

<!-- Javascript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="../admin_js/admin.js"></script>
<script>
    let ascending = true;

    function toggleSort(header) {
        const icon = header.querySelector('.toggle-icon');

        if (ascending) {
            icon.innerHTML = '&#8593;&#8597;';
        } else {
            icon.innerHTML = '&#8597;&#8595;';
        }

        ascending = !ascending;
    }
</script>

<!-- Script for livesearch -->
<script type="text/javascript">
    $(document).ready(function() {
        $("#live_search").keyup(function() {
            var input = $(this).val();

            if (input !== "") {
                $.ajax({
                    url: "../../Admin/functions/livesearch_customer.php",
                    method: "POST",
                    data: {
                        input: input
                    },
                    success: function(data) {
                        $("#searchresult").html(data);
                        $("#searchresult").css("display", "block");
                    }
                });
            } else {
                // If the input is empty, display the complete table
                $.ajax({
                    url: "../../Admin/functions/livesearch_customer.php",
                    method: "POST",
                    data: {
                        input: ""
                    }, // Pass an empty input to your server-side code
                    success: function(data) {
                        $("#searchresult").html(data);
                        $("#searchresult").css("display", "block");
                    }
                });
            }
        });
    });
</script>

<!-- Sort JS -->
<script>
    $(document).ready(function() {
        // Handle sorting when the select dropdown changes
        $('#sortDropdown').on('change', function() {
            let selectedValue = $(this).val();
            let rows = $('#searchresult tbody tr').get();

            rows.sort(function(a, b) {
                let aData = $(a).children('td').eq(1).text(); // Last Name
                let bData = $(b).children('td').eq(1).text();

                if (selectedValue === "1") {
                    return aData.localeCompare(bData); // Alphabetical A-Z
                } else if (selectedValue === "2") {
                    return bData.localeCompare(aData); // Alphabetical Z-A
                } else {
                    // Handle other sorting logic (add more cases if needed)
                    return 0;
                }
            });

            $('#searchresult tbody').empty().append(rows);
        });
    });
</script>

</body>

</html>