<?php
require '../../Admin/functions/UserData.php';
include '../../DTR/functions/loginUser.php';

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

$dtr = new UserData();
$value1 = $dtr->getDeductionAmount("SSS");
$value3 = $dtr->getDeductionAmount("PhilHealth");
$value2 = $dtr->getDeductionAmount("PagIBIG");

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
    <script>
        window.onload = function() {
            // Check if there is no hash in the URL
            if (!window.location.hash) {
                // Add "#loaded" to the URL
                window.location = window.location + '#loaded';

                // Reload the page
                window.location.reload();
            }
        }

    </script>

    <title>CampUs Bites</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon/favicon-16x16.png">
    <link rel="manifest" href="../../favicon/site.webmanifest">
    <link rel="stylesheet" href="../../Admin/admin_css/viewpayroll.css">
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

            $viewUserData = "SELECT * FROM vwpayroll_list WHERE USER_ID = '$positionEmpID'";
            $viewUserDataRun = mysqli_query($conn, $viewUserData);

            while ($row = mysqli_fetch_array($viewUserDataRun)) {
                $empPic = $row['U_PICTURE'];
                $empFname = $row['U_FIRST_NAME'];
                $empLname = $row['U_LAST_NAME'];
                $empMname = $row['U_MIDDLE_NAME'];
                $empRoleName = $row['ROLE_NAME'];

                $fullName = $empFname . ' ' . $empMname . ' ' . $empLname;
                ?>
                <div class="leftcontainer">
                    <div class="top-content">
                        <div class="left-user-side">
                            <div class="user-image">
                                <?php echo '<img src="../../Icons/' . $empPic . '" alt="" style="border-radius: 200px; object-fit: cover; width: 150px; height: 150px;">'; ?>

                            </div>
                            <div class="user-name">

                                <p style="font-size: 15px; font-weight: 900; margin-bottom: -3px; margin-top: 10px;"><?php echo $empFname . " ". $empMname . " " . $empLname ?></p>
                                <p style="color: #737373; margin-bottom: 20px; font-size: 14px;"><?php echo $empRoleName?></p>
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
                                // Ensure that the payrollId parameter is set in the URL
                                if (isset($_GET['payrollId'])) {
                                    // Retrieve the payroll ID from the URL
                                    $payrollId = $_GET['payrollId'];

/*                                    echo "Payroll ID: " . $payrollId;*/
                                } else {

                                }
                                ?>

                                <?php
                                //pagkuha sa payroll records na gi generate
                                $employeePayroll=$loginuser->getPayrollIdRecords($payrollId);

                                foreach ($employeePayroll as $empPayroll) {
                                    $startDate = $empPayroll['PAYROLL_START_DATE'];
                                    $endDate = $empPayroll['PAYROLL_END_DATE'];
                                }

                                $basicSalary = $userDataInstance->getBasicSalaryByRole($row['ROLE_NAME']);
                                $daysWorked = $userDataInstance->getDaysWorked($positionEmpID, $startDate,$endDate);

                                $payrollPeriod = date('M j, Y', strtotime($startDate)) . ' - ' . date('M j, Y', strtotime($endDate));

                                echo '<div class="payroll-period">';
                                echo '<p style="font-weight: 900; margin-bottom: 0px; font-size: 18px;">PAYROLL PERIOD</p>';
                                echo "<p style='font-size: 12px; color: #9C1421; font-weight: 600;'>$payrollPeriod</p>";
                                echo '</div>';



                                ?>
                                <div class="print-payroll">
                                    <button type="button" id="printPayroll" data-bs-toggle="modal" data-bs-target="#recieptModal">PRINT PAYROLL SLIP</button>
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
                                            $totalDeduction = 'No deductions applied';
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
                                        <p>
                                            <?php if (is_numeric($totalDeduction)): ?>
                                                ₱<?php echo number_format($totalDeduction, 2) ?>
                                            <?php else: ?>
                                                <?php echo $totalDeduction ?>
                                            <?php endif; ?>
                                        </p>                                    </div>
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
                        $rowDtr = $dtr->viewDTR($positionEmpID, $startDate, $endDate);

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
<?php
}
?>
<script src="../../Admin/admin_js/admin.js"></script>
<script>
    document.getElementById("printPayroll").addEventListener("click", function() {
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "payroll_pdf.php";

        const employeeIdInput = document.createElement("input");
        employeeIdInput.type = "hidden";
        employeeIdInput.name = "employeeId";
        employeeIdInput.value = "<?php echo htmlspecialchars($positionEmpID, ENT_QUOTES, 'UTF-8'); ?>";

        const payrollPeriod = document.createElement("input");
        payrollPeriod.type = "hidden";
        payrollPeriod.name = "payrollPeriod";
        payrollPeriod.value = "<?php echo htmlspecialchars($payrollPeriod, ENT_QUOTES, 'UTF-8'); ?>";

        const fullName = document.createElement("input");
        fullName.type = "hidden";
        fullName.name = "fullName";
        fullName.value = "<?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>";

        const RPH = document.createElement("input");
        RPH.type = "hidden";
        RPH.name = "RPH";
        RPH.value = "<?php echo htmlspecialchars($basicSalary, ENT_QUOTES, 'UTF-8'); ?>";

        const NDW = document.createElement("input");
        NDW.type = "hidden";
        NDW.name = "NDW";
        NDW.value = "<?php echo htmlspecialchars($daysWorked, ENT_QUOTES, 'UTF-8'); ?>";

        const subtotal = document.createElement("input");
        subtotal.type = "hidden";
        subtotal.name = "subtotal";
        subtotal.value = "<?php echo htmlspecialchars($subtotal, ENT_QUOTES, 'UTF-8'); ?>";

        const overtimeRate = document.createElement("input");
        overtimeRate.type = "hidden";
        overtimeRate.name = "overtimeRate";
        overtimeRate.value = "<?php echo htmlspecialchars($overtimeRate, ENT_QUOTES, 'UTF-8'); ?>";

        const totalOT = document.createElement("input");
        totalOT.type = "hidden";
        totalOT.name = "totalOT";
        totalOT.value = "<?php echo htmlspecialchars($totalOvertimeHours, ENT_QUOTES, 'UTF-8'); ?>";

        const subtotalOT = document.createElement("input");
        subtotalOT.type = "hidden";
        subtotalOT.name = "subtotalOT";
        subtotalOT.value = "<?php echo htmlspecialchars($overtimeSubtotal, ENT_QUOTES, 'UTF-8'); ?>";

        const grossSalary = document.createElement("input");
        grossSalary.type = "hidden";
        grossSalary.name = "grossSalary";
        grossSalary.value = "<?php echo htmlspecialchars($grossSalary, ENT_QUOTES, 'UTF-8'); ?>";

        const SSS = document.createElement("input");
        SSS.type = "hidden";
        SSS.name = "SSS";
        SSS.value = "<?php echo htmlspecialchars($value1, ENT_QUOTES, 'UTF-8'); ?>";

        const PagIbig = document.createElement("input");
        PagIbig.type = "hidden";
        PagIbig.name = "PagIbig";
        PagIbig.value = "<?php echo htmlspecialchars($value3, ENT_QUOTES, 'UTF-8'); ?>";

        const Philhealth = document.createElement("input");
        Philhealth.type = "hidden";
        Philhealth.name = "Philhealth";
        Philhealth.value = "<?php echo htmlspecialchars($value2, ENT_QUOTES, 'UTF-8'); ?>";

        const totalDeduc = document.createElement("input");
        totalDeduc.type = "hidden";
        totalDeduc.name = "totalDeduc";
        totalDeduc.value = "<?php echo htmlspecialchars($totalDeduction, ENT_QUOTES, 'UTF-8'); ?>";

        const netSalary = document.createElement("input");
        netSalary.type = "hidden";
        netSalary.name = "netSalary";
        netSalary.value = "<?php echo htmlspecialchars($netSalary, ENT_QUOTES, 'UTF-8'); ?>";

        const adminUsername = document.createElement("input");
        adminUsername.type = "hidden";
        adminUsername.name = "adminUsername";
        adminUsername.value = "<?php echo htmlspecialchars($adminUsername, ENT_QUOTES, 'UTF-8'); ?>";

        const position = document.createElement("input");
        position.type = "hidden";
        position.name = "position";
        position.value = "<?php echo htmlspecialchars($empRoleName, ENT_QUOTES, 'UTF-8'); ?>";

        // Append input fields to the form
        form.appendChild(employeeIdInput);
        form.appendChild(payrollPeriod);
        form.appendChild(position);
        form.appendChild(fullName);
        form.appendChild(RPH);
        form.appendChild(NDW);
        form.appendChild(subtotal);
        form.appendChild(overtimeRate);
        form.appendChild(totalOT);
        form.appendChild(subtotalOT);
        form.appendChild(grossSalary);
        form.appendChild(SSS);
        form.appendChild(PagIbig);
        form.appendChild(Philhealth);
        form.appendChild(totalDeduc);
        form.appendChild(netSalary);
        form.appendChild(adminUsername);

        // Append the form to the document and submit it
        document.body.appendChild(form);
        form.submit();

        // Remove the form from the document
        document.body.removeChild(form);
    });
</script>
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