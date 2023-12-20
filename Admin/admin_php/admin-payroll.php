<?php
require '../../Admin/functions/UserData.php';
$database = new Connection();
$conn = $database->conn;


$deductionUpdater = new UserData();

if(isset($_POST["updateDeducbtn"])){

    if (isset($_POST["SSS"])) {
        $sss = $_POST["SSS"];
        $deductionUpdater->updateDeductionAmount("SSS", $sss);
    }

    if (isset($_POST["PagIBIG"])) {
        $pagibig = $_POST["PagIBIG"];
        $deductionUpdater->updateDeductionAmount("PagIBIG", $pagibig);
    }

    if (isset($_POST["PhilHealth"])) {
        $phil = $_POST["PhilHealth"];
        $deductionUpdater->updateDeductionAmount("PhilHealth", $phil);
    }

    header("location: ../../Admin/admin_php/admin-payroll.php");

}


if(isset( $_POST["updatePayment"])){
    $selectedOption = $_POST['employeePositions'];
    $employeeSalary = $_POST['employeeSalary'];

    $update = new UserData();
    $update->updateSalary($selectedOption, $employeeSalary);
}

$value1 = $deductionUpdater->getDeductionAmount("SSS");
$value2 = $deductionUpdater->getDeductionAmount("PagIBIG");
$value3 = $deductionUpdater->getDeductionAmount("PhilHealth");
?>

<?php

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
    <link rel="stylesheet" href="../../Admin//admin_css/admin-payroll.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        // Logout after 15 minutes of inactivity (for testing purposes)
        var inactivityTime = 5 * 60 * 1000;
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

    <?php include 'sidebar.php'; ?>

    <div class="right-container">
        <div class="head-content">
            <div class="Menu-name">
                <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                <p style="margin-bottom: 0;">Payroll</p>
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
            <div class="header-filter">
                <div class="header-searchbar">
                    <img src="../../Icons/search.svg" alt="" width="15px" style="margin-top: 9px;">
                    <input type="text" name="fname" id="live_search" placeholder="Search Employee Name...">
                </div>
                <div class="header-sort">
                    <label for="" style="font-size: 14px; color: #5f5f5f; margin-right: 5px;  font-weight: 600;">Sort by:</label>
                    <select name="position" id="position_fetchVal" style="padding: 5px; border: 1px solid #D0D0D0; border-radius: 5px; width: 10rem;">
                        <option value="" disabled="" selected="">Search All Filter</option>
                        <option value="Manager">Manager</option>
                        <option value="Cashier">Cashier</option>
                        <option value="Server">Server</option>
                        <option value="Cook">Cook</option>
                        <option value="Assistant Cook">Assistant Cook</option>
                    </select>
                </div>

                <div class="deduction-container">
                    <button type="button" class="deductionBtn" data-bs-toggle="modal" data-bs-target="#deducationModal">Deduction Setting</button>
                </div>

                <div class="payment-container">
                    <button type="button" class="paymentBtn" data-bs-toggle="modal" data-bs-target="#paymentModal">Payment Setting</button>
                </div>

            </div>
            <div class="main-contentwraper" id="searchResult">
                <?php
                $employeeData = new UserData();
                $data = $employeeData->getEmployeeDatas();
                $employeeData->renderEmployeeTable($data);
                ?>
            </div>
        </div>
    </div>
</div>


<!-- Deduction Modal -->
<div class="modal fade" id="deducationModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="" method="post">
            <div class="modal-content">
                <div class="modal-header" style="border: none;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Deduction Setting</h1>
                    <i class="fa-solid fa-xmark" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <div class="modal-body">
                    <div class="sss-container">
                        <p>Social Security system</p>
                        <div class="sss-imageInput">
                            <input type="text" value="1" style="display:none;" name="SSS-ID">
                            <input type="text" name="SSS" value="<?php echo $value1 ?>" placeholder="">
                            <img src="../../Icons/sssImage.svg" alt="">
                        </div>
                    </div>
                    <div class="pagIbig-container">
                        <p>Human Development Mutual Fund</p>
                        <div class="pagIbig-imageInput">
                            <input type="text" value="2" style="display:none;" name="Pagibig-ID">
                            <input type="text" name="PagIBIG" value="<?php echo $value2 ?>"  id="">
                            <img src="../../Icons/pagIbiglogo.svg" alt="" width="80px">
                        </div>
                    </div>
                    <div class="philHealth-container">
                        <p>The Philippine Health Insurance</p>
                        <div class="philHealth-imageInput">
                            <input type="text" value="3" style="display:none;" name="PhilHealth-ID">
                            <input type="text" name="PhilHealth" value="<?php echo $value3 ?>"  id="">
                            <img src="../../Icons/philHealthlogo.svg" alt="" width="12px">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="submit" name="updateDeducbtn">Update Deduction</button>
                </div>
            </div>
        </form>
    </div>
</div>





<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="" method="post">
            <div class="modal-content">
                <div class="modal-header" style="border: none;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Payment Setting</h1>
                    <i class="fa-solid fa-xmark" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <div class="modal-body">
                    <div class="Employee-container">
                        <p>Employee's Position</p>
                        <select name="employeePositions" id="">
                            <option value="Courier">Courier</option>
                            <option value="Crew">Crew</option>
                            <option value="Cashier">Cashier</option>
                            <option value="Server">Server</option>
                            <option value="Manager">Manager</option>
                        </select>
                    </div>
                    <div class="paymentInput-container">
                        <input type="text" name="employeeSalary" placeholder="Enter Employee Salary"  id="">
                    </div>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="submit" name="updatePayment">Update Payment</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Javascript -->
<script src="../../Admin/admin_js/admin.js"></script>
<script>
    const sssInput = document.querySelector('.sss-container input[name="SssInput"]');
    const pagIbigInput = document.querySelector('.pagIbig-container input[name="pagIbigInput"]');
    const philHealthInput = document.querySelector('.philHealth-container input[name="philHealthInput"]');

    // Function to allow only numeric input
    function allowOnlyNumbers(input) {
        input.addEventListener('input', (e) => {
            // Remove any non-numeric characters from the input
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }

    allowOnlyNumbers(sssInput);
    allowOnlyNumbers(pagIbigInput);
    allowOnlyNumbers(philHealthInput);
</script>

<!-- Live search ajax -->
<script>
    $(document).ready(function(){

        $('#live_search').keypress(function(){
            $.ajax({
                type:'POST',
                url: '../../Admin/functions/livesearchPayroll.php',
                data: {name: $("#live_search").val(),
                },

                success:function(data){
                    $("#searchResult").html(data);
                }

            });
        });

    });
</script>

<!-- Drop down ajax -->
<script>
    $(document).ready(function(){
        $("#position_fetchVal").on('change', function(){
            var value = $(this).val();

            $.ajax({
                url:'../../Admin/functions/liveDropdownFilterPayroll.php',
                type: 'POST',
                data: 'request=' + value,
                beforeSend:function(){
                    $("#searchResult").html("<span> Workingg.. </span>");
                },
                success:function(data){
                    $("#searchResult").html(data);
                }
            });
        });
    });
</script>


<script>
    $(document).ready(function(){
        $(document).on("click", ".payrollRecord", function(){
            var positionEmpID = $(this).closest('.card-1').find('.positionEmpID').text();
            $.ajax({
                type: "POST",
                url: "../../Admin/functions/empPayrollDataHandler.php", // Specify the URL of the other PHP file
                data: { positionEmpID: positionEmpID },
                success: function(data){
                    window.location.href="viewpayroll.php";
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
</body>
</html>