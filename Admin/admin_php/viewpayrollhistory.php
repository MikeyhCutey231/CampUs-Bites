<?php
require '../../Admin/functions/UserData.php';
require '../../DTR/functions/loginUser.php';

$database = new Connection();
$conn = $database->conn;


if (isset($_SESSION["positionEmpID"])) {
    $positionEmpID = $_SESSION["positionEmpID"];
} else {
    echo "No data available.";
}
?>
<?php

$loggedUser = $_SESSION['USER_ID'];
$userDataInstance = new UserData();
$usersData = $userDataInstance->getUserData($loggedUser);

if (!isset($_SESSION['USER_ID'])) {
    header("Location: admin-login.php");
    exit();
}

$employeeData = $userDataInstance->getEmployeeDataCashier();

$loginuser = new LoginUser();

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
    <link rel="stylesheet" href="../../Admin/admin_css/viewpayrollhistory.css">
</head>
<body>
    <div class="wrapper">

        <?php include 'sidebar.php'; ?>

        <div class="right-container">
            <div class="head-content">
                <div class="Menu-name">
                    <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                    <p style="margin-bottom: 0;">View Payroll History</p>
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
              <div class="left-content">
                  <?php
                  $viewUserData = "SELECT * FROM vwpayroll_list WHERE USER_ID = '$positionEmpID'";
                  $viewUserDataRun = mysqli_query($conn, $viewUserData);

                  while ($row = mysqli_fetch_array($viewUserDataRun)) {
                  ?>
                <div class="user-image">
                    <?php echo '<img src="../../Icons/' . $row['U_PICTURE'] . '" alt="" style="border-radius: 200px; object-fit: cover; width: 150px; height: 150px;">'; ?>
                    </div>

                <div class="user-info">
                    <p class="userName"><?php
                        echo $row['U_FIRST_NAME'] . ' ' . (!empty($row['U_MIDDLE_NAME']) ? $row['U_MIDDLE_NAME'][0] . '. ' : '') . $row['U_LAST_NAME'];
                        ?></p>
                    <p style="color: #737373; font-size: 12px;"><?php echo $row['ROLE_NAME']?></p>
                </div>
                <div class="user-buttons">
                    <button type="button" class="positionEmpID view" data-employee-id="<?php echo $positionEmpID; ?>">View Profile</button>
                    <button type="button" class="back" onclick="goBack()">Back</button>
                </div>
              </div>
                <?php
                }

                ?>
              <div class="right-content">
                <div class="table-top">
                    <div class="payrollhistory-text">
                        <p>PAYROLL HISTORY</p>
                    </div>
                    <div class="filter-recent">

                    </div>
                    <div class="payroll-searchbar">
                        <img src="../../Icons/search.svg" alt="" width="20px" style="margin-top: 12px;">
                        <input type="text" name="fname" placeholder="Search here...">
                    </div>
                </div>
                <div class="main-table">
                    <?php
                        $employeePayroll=$loginuser->getPayrollRecords($positionEmpID);
                    ?>
                    <table class="table table-hover">
                        <thead>
                          <tr>
                            <th scope="col" style="font-size: 12px;">PAYROLL PERIOD</th>
                            <th scope="col" style="font-size: 12px;">GROSS SALARY</th>
                            <th scope="col" style="font-size: 12px;">TOTAL DEDUCTION</th>
                            <th scope="col" style="font-size: 12px;">NET SALARY</th>
                            <th scope="col" style="font-size: 12px;"></th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($employeePayroll as $row) {
                            $payrollPeriod = date('M j, Y', strtotime($row['PAYROLL_START_DATE'])) . ' - ' . date('M j, Y', strtotime($row['PAYROLL_END_DATE']));
                            ?>
                          <tr>
                            <th scope="row" style="font-size: 12px;"><?php echo $payrollPeriod ?></th>
                            <td style="font-size: 12px;"><?php echo $row['EMP_GROSS_SALARY']?></td>
                            <td style="font-size: 12px;"><?php echo $row['DEDUCTIONS']?></td>
                            <td style="font-size: 12px;"><?php echo $row['EMP_NET_SALARY']?></td>
                              <td><button class="viewBtn" data-payroll-id="<?php echo $row['EMP_PAYROLL_ID']; ?>">VIEW</button></td>
                          </tr>
                          <?php
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
    <script src="../../Admin/admin_js/admin.js"></script>

    <script>
        $(document).ready(function() {
            $(".viewBtn").on("click", function() {
                const payrollId = $(this).data("payroll-id");

                window.location.href = "viewpayroll.php?payrollId=" + payrollId;
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            var positionEmpIDButtons = document.querySelectorAll('.positionEmpID');

            positionEmpIDButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var employeeID = this.getAttribute('data-employee-id');

                    window.location.href = 'admin-viewEmployee.php?employee_id=' + employeeID;
                });
            });
        });

        function goBack() {
          window.history.back();


      }
  </script>
</body>
</html>