<?php
require '../../Admin/functions/UserData.php';
$database = new Connection();
$conn = $database->conn;

if (isset($_POST['name'])) {
    $new = new UserData();
    $searchName = $_POST['name'];
    $employeeData = $new->searchPayrollEmployees($searchName);

    if (!empty($employeeData)) {
        foreach ($employeeData as $row) {
            ?> <div class="card-1">
                    <div class="user-face">
                        <?php echo '<img src="../../Icons/' . $row['EMP_PROF_PIC'] . '" alt="" style="width: 90px; height: 90px; object-fit: cover; border-radius:200px;">'; ?>
                    </div>
                    <div class="user-info">
                        <p style="margin-bottom: -5px; font-weight: 900; font-size: 18px; margin-top: 8px;"><?php echo $row['EMP_FIRST_NAME'] . ' ' . $row['EMP_MIDDLE_NAME'] . ' ' . $row['EMP_LAST_NAME'] ?></p>
                        <p style="margin-bottom: 0px; color: #737373;"><?php echo $row['EMP_ROLE_NAME'] ?></p>
                        <p class="positionEmpID" style="display: none;"><?php echo $row['EMPLOYEE_ID'] ?></p>
                    </div>
                    <div class="card-button">
                        <button class="payrollRecord" data-payroll-id="<?php echo $row['EMP_PAYROLL_ID']; ?>">Payroll Record</button>
                    </div>
                <script>
                    $(document).ready(function() {
                        $(".payrollRecord").on("click", function() {
                            const payrollId = $(this).data("payroll-id");

                            window.location.href = "../../Admin/admin_php/viewpayroll.php?payrollId=" + payrollId;
                        });
                    });
                </script>
                </div>
        <?php
        }
         ?> </div> <?php
    } else {
        echo "No data";
    }
}
?>



