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

    <title>Campus Bites</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon/favicon-16x16.png">
    <link rel="manifest" href="../../favicon/site.webmanifest">
    <link rel="stylesheet" href="../../Admin/admin_css/admin-dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        // Logout after 15 minutes of inactivity (for testing purposes)
        var inactivityTime = 15 * 1000;
        var logoutTimer;

        function resetTimer() {
            clearTimeout(logoutTimer);
            logoutTimer = setTimeout(logout, inactivityTime);
        }

        function logout() {
            // Clear session or perform other logout actions

            // Use replaceState to replace the current URL with the logout.php URL
            history.replaceState(null, null, 'logout.php');

            // Redirect to the logout page
            window.location.href = 'logout.php';
        }

        $(document).ready(function () {
            // Attach events to reset the timer when there is user activity
            $(document).on('mousemove keypress', resetTimer);

            // Initial setup of the timer
            resetTimer();
        });
    </script>
</head>
<body>
<div class="wrapper">

    <?php include '../../Admin/admin_php/sidebar.php'; ?>

    <div class="right-container">
        <div class="head-content">
            <div class="Menu-name">
                <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                <p style="margin-bottom: 0;">Dashboard</p>
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
                    <div class="col-12 col-md-6 mt-3">
                        <div class="content-1 p-4 overview">
                            <h4 class="text-start" style="font-weight: bold">Overview</h4>
                            <h6 class="text-start" style="font-size: 14px; color: #626262">Status records of CampUs Bites</h6>
                            <div class="wrapper d-flex flex-wrap justify-content-between mt-4">
                                <div class="totalU p-4  red mb-2">
                                    <?php 
                                        $employeeData = new UserData();
                                        $data = $employeeData->overAllUsers();
                                        $employeeData->renderTotalUsers($data);
                                       ?>
                                </div>
                                <div class="totalP p-4  yellow mb-2">
                                    <div class="circle-container1">
                                        <img src="../../Icons/packagewhite.svg" alt="Icon">
                                    </div>
                                    <div class="text-container">
                                        <?php 
                                            $employeeData = new UserData();
                                            $data = $employeeData->overAllProduct();
                                            $employeeData->renderTotalProduct($data);
                                        ?>
                                    </div>
                                </div>
                                <div class="totalR p-4  green mb-2">
                                    <div class="circle-container2">
                                        <img src="../../Icons/bar-chartwhite.svg" alt="Icon">
                                    </div>
                                    <h4 class="text-start mt-2" style="font-weight: bold; color: #5f5f5f ">5.0</h4>
                                    <h6 class="text-start" style="font-size: 14px;">Total Ratings</h6>
                                    <h6 class="text-start mt-2" style="font-size: 9.5px; color: #82A2F3">+10 from yesterday</h6>
                                </div>
                                <div class="totalI p-4  violet mb-2">
                                    <div class="circle-container3">
                                        <img src="../../Icons/dollar-sign.svg" alt="Icon" width="15px">
                                    </div>
                                    <div class="text-container">
                                        <?php 
                                            $employeeData = new UserData();
                                            $data = $employeeData->overAllIncome();
                                            $employeeData->renderoverAllIncome($data);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-3">
                        <div class="content-2 p-4 userRating">
                            <h4 class="text-start" style="font-weight: bold">Users Rating</h4>
                            <canvas id="lineChart" height="100"></canvas>
                        </div>
                    </div>

                    <div class="col-12 col-md-5 mt-3">
                        <div class="content-3 p-4 userRating">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="text-start" style="font-weight: bold">Total Revenue</h4>
                                <div class="btn-group" role="group" aria-label="Time Range">
                                    <button type="button" class="btn btn-primary" id="monthlyButton">Monthly</button>
                                    <button type="button" class="btn btn-primary" id="weeklyButton">Weekly</button>
                                    <button type="button" class="btn btn-primary" id="yearlyButton">Yearly</button>
                                </div>
                            </div>
                            <canvas id="revenueChart" height="120"></canvas>
                        </div>
                    </div>

                    <div class="col-12 col-md-7 mt-3">
                        <div class="content-4 p-4 userRating" style="max-height: 275px; overflow-y: auto; font-size: 12px;">
                            <h4 class="text-start" style="font-weight: bold">Top Products Rating</h4>
                            <div>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col" style="width: 150px;">Name</th>
                                        <th scope="col">Popularity</th>
                                        <th scope="col" class="w-25">Sales</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                             $employeeData = new UserData();
                                             $data = $employeeData->productRating();
                                             $employeeData->renderProductRating($data);
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 mt-3">
                        <div class="content-4 p-4 userRating staffList">
                            <h4 class="text-start" style="font-weight: bold">Staff List</h4>
                            <div>
                                <table class="table table-light" style="overflow: auto;">
                                    <thead>
                                    <tr>
                                        <th scope="col">Picture</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Status</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                         <?php
                                             $employeeData = new UserData();
                                             $data = $employeeData->usersList();
                                             $employeeData->renderStaffList($data);
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 mt-3">
                        <div class="content-4 p-4 userRating deliveryPerson">
                            <h4 class="text-start" style="font-weight: bold">Delivery Person List</h4>
                            <div>
                                <table class="table table-light">
                                    <thead>
                                    <tr>
                                        <th scope="col">Picture</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Status</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                             $employeeData = new UserData();
                                             $data = $employeeData->deliveryList();
                                             $employeeData->renderDeliveryList($data);
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 mt-3">
                        <div class="content-4 p-4 userRating highestOrder">
                            <h4 class="text-start" style="font-weight: bold">User Highest Order</h4>
                            <div class="table-container">
                                <table class="table table-light">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Picture</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Item Ordered</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                             $employeeData = new UserData();
                                             $data = $employeeData->highestOrder();
                                             $employeeData->renderHighestOrder($data);
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>

            </div>

        </div>
    </div>
</div>

<!-- Javascript -->
<script src="/Admin/admin_js/admin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var data = {
        labels: ["Jan", "Feb", "March", "April", "May"],
        datasets: [
            {
                label: "User Ratings",
                borderColor: "#9C1421",
                borderWidth: 2,
                data: [75000, 15000, 16000, 8000, 52000],
            },
        ],
    };

    var ctx = document.getElementById("lineChart").getContext("2d");
    var lineChart = new Chart(ctx, {
        type: "line",
        data: data,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 80000,
                },
            },
            plugins: {
                legend: {
                    display: false, // Hide the legend
                },
                title: {
                    display: true,
                    text: 'Total Users Review of CampUs Bites',
                    position: 'bottom', // Display the title at the bottom
                },
            },
            layout: {
                padding: {
                    top: 10,
                },
            },
        },
    });


    var monthlyData = [5000, 8000, 12000, 15000, 20000];
    var weeklyData = [1000, 2000, 3000, 4000, 5000];
    var yearlyData = [60000, 70000, 80000, 90000, 100000];

    var data = {
        labels: ["Jan", "Feb", "March", "April", "May"],
        datasets: [
            {
                label: "Total Revenue (1st Line)",
                borderColor: "#A2C53A",
                borderWidth: 2,
                data: monthlyData,
                hidden: false,
            },
            {
                label: "Total Revenue (2nd Line)",
                borderColor: "#F0C419",
                borderWidth: 2,
                data: weeklyData,
                hidden: false,
            },
        ],
    };

    var ctx = document.getElementById("revenueChart").getContext("2d");
    var lineChart = new Chart(ctx, {
        type: "line",
        data: data,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: 'Total Revenue',
                    position: 'bottom',
                },
            },
            layout: {
                padding: {
                    top: 10,
                },
            },
        },
    });

    // Button click handlers
    document.getElementById("monthlyButton").addEventListener("click", function () {
        updateChart(monthlyData);
    });

    document.getElementById("weeklyButton").addEventListener("click", function () {
        updateChart(weeklyData);
    });

    document.getElementById("yearlyButton").addEventListener("click", function () {
        updateChart(yearlyData);
    });

    function updateChart(selectedData) {
        // Update the chart's data and labels
        lineChart.data.datasets[0].data = selectedData;
        lineChart.update();
    }
</script>







</body>
</html>