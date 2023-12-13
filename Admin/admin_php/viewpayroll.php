<?php
include("../admin_functions/payroll.php");

$database = new Database();
$conn = $database->conn;

session_start();
if (isset($_SESSION["positionEmpID"])) {
    $positionEmpID = $_SESSION["positionEmpID"];
} else {
    echo "No data available.";
}
?>
<?php
require '../admin_functions/UserData.php';

$loggedUser = $_SESSION['USER_ID'];
$userDataInstance = new UserData();
$usersData = $userDataInstance->getUserData($loggedUser);

if (!isset($_SESSION['USER_ID'])) {
    header("Location: admin-login.php");
    exit();
}

$employeeData = $userDataInstance->getEmployeeDataCashier();

$dtr = new EmployeeData($conn);
$value1 = $dtr->getDeductionAmount("SSS");
$value3 = $dtr->getDeductionAmount("PhilHealth");
$value2 = $dtr->getDeductionAmount("PagIBIG");
?>

<?php
require '../admin_functions/LiveSearchFunction.php';
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
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon/favicon-16x16.png">
    <link rel="manifest" href="../../favicon/site.webmanifest">
    <link rel="stylesheet" href="../admin_css/viewpayroll.css">
</head>
<body>
<div class="wrapper">

    <?php include 'sidebar.php'; ?>

    <div class="right-container">
        <div class="head-content">
            <div class="Menu-name">
                <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                <p style="margin-bottom: 0;">Payroll Record</p>
            </div>
            <!-- Main Search Bar -->
            <div class="head-searchbar">
                <img src="/Icons/search.svg" alt="" width="20px" style="margin-top: 12px;">
                <form method="post">
                    <input type="text" name="search" placeholder="Search here...">
                </form>
            </div>
            <div class="usep-texthead">
                <img src="/Icons/useplogo.png" alt="" width="30px" height="30px">
                <p style="margin-bottom: 0px; margin-top: 3px; margin-left: 10px; font-weight: 600;">UseP (Tagum Unit)</p>
            </div>
            <a href="admin-profile.php" style="color: black;">
                <div class="user-profile">
                    <div class="admin-profile">
                        <?php
                        if (!empty($usersData)) {
                            $profilePic = $usersData[0]['profilePic'];
                            echo '<img src="upload/' . $profilePic . '" style="height: 38px; width: 40px; border-radius: 5px; object-fit: cover;">';
                        }
                        ?>
                    </div>
                    <div class="admin-detail">
                        <?php
                        if (!empty($usersData)) {
                            $adminUsername = $usersData[0]['username'];
                            echo '<p style="margin-bottom: 0px; font-weight: 700;">' . $adminUsername . '</p>';

                            require_once '../admin_functions/user.php';
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

            $viewUserData = "SELECT * FROM vwpayroll_list WHERE USER_ID = '$positionEmpID'";
            $viewUserDataRun = mysqli_query($conn, $viewUserData);

            while ($row = mysqli_fetch_array($viewUserDataRun)) {
                ?>
                <div class="leftcontainer">
                    <div class="top-content">
                        <div class="left-user-side">
                            <div class="user-image">
                                <?php echo '<img src="../admin_php/upload/' . $row['U_PICTURE'] . '" alt="" style="border-radius: 200px; object-fit: cover; width: 150px; height: 150px;">'; ?>

                            </div>
                            <div class="user-name">

                                <p style="font-size: 15px; font-weight: 900; margin-bottom: -3px; margin-top: 10px;"><?php echo $row['U_FIRST_NAME'] . " ". $row['U_MIDDLE_NAME'] . " " . $row['U_LAST_NAME'] ?></p>
                                <p style="color: #737373; margin-bottom: 20px; font-size: 14px;"><?php echo $row['ROLE_NAME']?></p>
                            </div>
                            <div class="viewprofile" style="">
                                <button type="button" class="positionEmpID" data-employee-id="<?php echo $positionEmpID; ?>">View Profile</button>
                            </div>
                            <div class="viewPayroll">
                                <a href="viewpayrollhistory.php"><button type="button" style="background-color:#A2C53A;">View Payroll History</button></a>
                            </div>
                        </div>
                        <div class="right-user-side">
                            <div class="line"></div>
                            <div class="top-right-user">
                                <?php
                                $basicSalary = $userDataInstance->getBasicSalaryByRole($row['ROLE_NAME']);
                                $daysWorked = $userDataInstance->getDaysWorked($positionEmpID);

                                $currentDate = date('Y-m-d');

                                $endDate = $currentDate;

                                $startDate = date('Y-m-d', strtotime('-15 days', strtotime($endDate)));
                                $startDateFormatted = date('F j, Y', strtotime($startDate));
                                $endDateFormatted = date('F j, Y', strtotime($endDate));

                                echo '<div class="payroll-period">';
                                echo '<p style="font-weight: 900; margin-bottom: 0px; font-size: 18px;">PAYROLL PERIOD</p>';
                                echo "<p style='font-size: 12px; color: #9C1421; font-weight: 600;'>$startDateFormatted - $endDateFormatted</p>";
                                echo '</div>';



                                ?>
                                <div class="print-payroll">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#recieptModal">PRINT PAYROLL SLIP</button>
                                </div>
                            </div>
                            <div class="middle-right-user">
                                <div class="rate-container">
                                    <div class="rate-perday">
                                        <p>Rate per day:</p>
                                        <p>₱ <?php echo $basicSalary ?></p>
                                    </div>

                                    <div class="dayswork-perday">
                                        <p>Number of days work:</p>
                                        <p><?php echo $daysWorked ?></p>
                                    </div>

                                    <div class="Subtotal-perday">
                                        <p>Subtotal:</p>
                                        <p>₱ <?php $subtotal = $basicSalary * $daysWorked;
                                            echo number_format($subtotal, 2) ?></p>
                                    </div>


                                </div>
                                <div class="tax-container">
                                    <div class="rate-container">
                                        <div class="totallate">
                                            <p>SSS:</p>
                                            <p>₱<?php echo $value1 ?></p>
                                        </div>

                                        <div class="tax">
                                            <p>PhilHealth:</p>
                                            <p>₱<?php echo $value3 ?></p>
                                        </div>

                                        <div class="tax">
                                            <p>Pag-IBIG:</p>
                                            <p>₱<?php echo $value2 ?></p>
                                        </div>

                                        <div class="deduction">
                                            <p>Total Deduction:</p>
                                            <?php $totalDeduction = $value1 + $value2 + $value3 ?>
                                            <input type="text" name="deduction" value="₱<?php echo number_format($totalDeduction,2) ?>" readonly style="font-size: 15px; font-weight: bold; text-align: right; padding: 5px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="lower-right-user">
                                <div class="rate-container-overall">
                                    <div class="totalgross">
                                        <p>Overtime Rate Per Hour:</p>
                                        <?php $overtimeRate = $basicSalary / 8 ?>
                                        <p>₱ <?php echo number_format($overtimeRate, 2) ?></p>
                                    </div>
                                    <?php
                                    $userOvertimeHours = $userDataInstance->getTotalOvertimeHours($positionEmpID);

                                    if (!empty($userOvertimeHours)) {
                                        list($hours, $minutes, $seconds) = explode(':', $userOvertimeHours);
                                        $totalOvertimeHours = $hours + ($minutes / 60) + ($seconds / 3600);
                                        $overtimeSubtotal = $overtimeRate * $totalOvertimeHours;
                                        $grossSalary = $subtotal + $overtimeSubtotal;
                                        $netSalary = $grossSalary - $totalDeduction;
                                        if ($netSalary < 500) {
                                            $netSalary = $grossSalary;
                                            $deductionInfo = 'No deductions applied because you will receive less than the minimum wage';
                                        } else {
                                            $netSalary = $grossSalary - $totalDeduction;
                                        }
                                    } else {
                                        $netSalary = 'N/A';
                                        $grossSalary = 'N/A';
                                        $totalOvertimeHours = 'N/A';
                                        $overtimeSubtotal = 'N/A';
                                    }
                                    ?>


                                    <div class="totaldeduction">
                                        <p>Total hrs of overtime:</p>
                                        <p><?php echo ($userOvertimeHours !== 'N/A') ? '' . $userOvertimeHours : 'N/A'; ?></p>
                                    </div>

                                    <div class="Subtotal-perday">
                                        <p>Subtotal:</p>
                                        <p><?php echo ($overtimeSubtotal !== 'N/A') ? '₱' . number_format($overtimeSubtotal, 2) : 'N/A'; ?></p>
                                    </div>

                                    <div class="netsalary">
                                        <p>Gross Salary:</p>
                                        <input type="text" name="grossSalary" value="₱<?php echo ($grossSalary !== 'N/A') ? '' . number_format($grossSalary, 2) : 'N/A'; ?>" readonly>
                                    </div>
                                </div>

                                <div class="rate2-container-overall">
                                    <div class="totaldeduction">
                                        <p>Gross Salary:</p>
                                        <p>₱<?php echo ($grossSalary !== 'N/A') ? '' . number_format($grossSalary, 2) : 'N/A'; ?></p>
                                    </div>

                                    <div class="totaldeduction">
                                        <p>Deduction:</p>
                                        <p>₱<?php echo number_format($totalDeduction,2) ?></p>
                                    </div>
                                    <div class="netsalary1">
                                        <p>Net Salary:</p>
                                        <input type="text" value="₱<?php echo ($netSalary !== 'N/A') ? '' . number_format($netSalary, 2) : 'N/A'; ?>" readonly style="font-size: 15px; font-weight: bold; text-align: right; padding: 5px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            }

            ?>
            <div class="rightcontainer">
                <div class="dtr-title">
                    <p style="font-weight: 900; font-size: 22px; margin-bottom: -5px; padding-top: 10px; padding-left: 15px;">DTR</p>
                    <p style="color: #9C1421; padding-left: 15px;">Daily Time Record</p>
                </div>
                <div class="dtr-content">
                    <table class="table" style="font-size: 13px;">
                        <thead>
                        <tr>
                            <th scope="col" style="font-size: 10px; color: #333333;">DATE</th>
                            <th scope="col" style="font-size: 10px; color: #333333;">TIME IN</th>
                            <th scope="col" style="font-size: 10px; color: #333333;">TIME OUT</th>
                            <th scope="col" style="font-size: 10px; color: #333333;">OVERTIME</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $rowDtr = $dtr->viewDTR($positionEmpID);

                        if (!empty($rowDtr)) {
                            $dtr->renderEmployeeDTR($rowDtr);
                        } else {
                            echo "<p style='color: red;text-align: center'>No DTR data available for this employee.</p>";
                        }
                        ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>











<?php
$viewUserData = "SELECT * FROM vwpayroll_list WHERE USER_ID = '$positionEmpID'";
$viewUserDataRun = mysqli_query($conn, $viewUserData);

while ($row = mysqli_fetch_array($viewUserDataRun)) {
?>
<!-- Modal -->
<div class="modal fade" id="recieptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="payslip-container">
                    <div class="left-payinfo">
                        <div class="top-info">
                            <div class="logo">
                                <div class="logo-name">
                                    <p style="margin-bottom: 0px;">CampUs Bites</p>
                                </div>
                                <div class="logo-subinfo">
                                    <p style="font-weight: 900;">PAYSLIP - SEMI-MONTHLY PAYROLL</p>
                                    <p style="margin-right: 30px;">PERIOD: 11/2022 to 11/15/22</p>
                                </div>
                            </div>
                            <div class="employee">
                                <div class="employee-name">
                                    <p style="margin-bottom: 0px;">EMPLOYEE: <?php echo $row['U_FIRST_NAME'] . ' ' . (!empty($row['U_MIDDLE_NAME']) ? $row['U_MIDDLE_NAME'][0] . '. ' : '') . $row['U_LAST_NAME']; ?></p>
                                </div>
                                <div class="employee-status">
                                    <p  style="margin-bottom: 0px;">STATUS: </p>
                                    <p  style="margin-bottom: 0px;">REGULAR</p>
                                </div>
                            </div>
                            <div class="position">
                                <p >POSITION:  <?php echo $row['ROLE_NAME'] ?></p>
                            </div>
                        </div>
                        <div class="bottom-info">
                            <div class="overtime">
                                <div class="overtime-top-container" style="border-right: 1px dashed black;">
                                    <p style="padding-left: 20px; margin-bottom: 0px;">OVERTIME</p>
                                    <p style="margin-bottom: 0px;">MIN</p>
                                    <p style="padding-right: 20px; margin-bottom: 0px;">PAY</p>
                                </div>
                                <div class="overtime-low-container">
                                    <div class="overtime-text">
                                        <p>REGULAR</p>
                                    </div>
                                    <div class="min-text">
                                        <p>0</p>
                                    </div>
                                    <div class="pay-text">
                                        <p style="padding-right: 20px;">0.00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="adjustment">
                                <div class="adjustment-top">
                                    <p style="margin-bottom: 0px; padding-left: 10px;">ADJUSTMENTS</p>
                                    <p style="margin-bottom: 0px; padding-right: 10px;">AMOUNT</p>
                                </div>
                                <div class="adjustment-bottom">
                                    <div class="adjustment-text">
                                        <p>13 MONTH</p>
                                        <p>INCENTIVES</p>
                                        <p>PAID LEAVES</p>
                                        <p>HOLIDAY PAY</p>
                                        <p>OTHERS</p>
                                    </div>
                                    <div class="amount-text">
                                        <p>0.00</p>
                                        <p>0.00</p>
                                        <p>0.00</p>
                                        <p>0.00</p>
                                        <p>0.00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tax-deduction">
                                <div class="tax-deduction-top">
                                    <p style="margin-bottom: 0px; padding-left: 10px;">DEDUCTION</p>
                                    <p style="margin-bottom: 0px; padding-right: 10px;">AMOUNT</p>
                                </div>
                                <div class="tax-deduction-bottom">
                                    <div class="deduction-info">
                                        <p>W/H TAX</p>
                                        <p>SSS</p>
                                        <p>PHILHEALTH</p>
                                        <p>PAG-IBIG</p>
                                        <p>TARDINESS</p>
                                        <p>LOAN</p>
                                        <p>OTHERS</p>
                                    </div>
                                    <div class="deductamount-info">
                                        <p>0.00</p>
                                        <p>0.00</p>
                                        <p>0.00</p>
                                        <p>0.00</p>
                                        <p>0.00</p>
                                        <p>0.00</p>
                                        <p>0.00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right-payinfo">
                        <div class="basic-pay">
                            <p style="margin-bottom: 0px; margin-left: 10px;">BASIC PAY</p>
                            <p style="margin-bottom: 0px; margin-left: 130px;">0.00</p>
                        </div>
                        <div class="overall-payment">
                            <div class="top-payment">
                                <div class="payments">
                                    <p>OVERTIME:</p>
                                    <p>13 MONTH:</p>
                                    <p>ALLOWANNCE:</p>
                                </div>
                                <div class="payment-cost">
                                    <p>0.00</p>
                                    <p>0.00</p>
                                    <p>0.00</p>
                                </div>
                            </div>

                            <div class="mid-payment">
                                <div class="payments">
                                    <p>GROSS PAY:</p>
                                    <p>DEDUCTION:</p>
                                </div>
                                <div class="payment-cost">
                                    <p>0.00</p>
                                    <p>0.00</p>
                                </div>
                            </div>

                            <div class="low-payment">
                                <div class="netpay">
                                    <p>NET PAY:</p>
                                </div>
                                <div class="netpay-cost">
                                    <p>0.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Print Payroll Slip</button>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
<script src="/Admin/admin_js/admin.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var positionEmpIDButtons = document.querySelectorAll('.positionEmpID');

        positionEmpIDButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var employeeID = this.getAttribute('data-employee-id');

                window.location.href = 'admin-viewEmployee.php?employee_id=' + employeeID;
            });
        });
    });
</script>
</body>
</html>