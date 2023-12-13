<?php
    include("../functions/payroll.php");
    $database = new Connection();
    $conn = $database->conn;

    if (isset($_POST['request'])) {
        $new = new EmployeeData($conn);
        $position = $_POST['request'];
        $employeeData = $new->filterEmployeesPosition($position);
    
        if (!empty($employeeData)) {
            foreach ($employeeData as $row) {
                ?> <div class="card-1">
                <div class="user-face">
                    <img src="../../Icons/cutemikey.svg" alt="" style="width: 90px; height: 90px; object-fit: contain;">
                </div>
                <div class="user-info">
                    <p style="margin-bottom: -5px; font-weight: 900; font-size: 18px; margin-top: 8px;"><?php echo $row['EMP_FIRST_NAME'] . ' ' . $row['EMP_MIDDLE_NAME'] . ' ' . $row['EMP_LAST_NAME'] ?></p>
                    <p style="margin-bottom: 0px; color: #737373;"><?php echo $row['EMP_POSITION'] ?></p>
                    <p class="positionEmpID" style="display: none;"><?php echo $row['EMPLOYEE_ID'] ?></p>
                </div>
                <div class="card-button">
                    <button type="button" class="payrollRecord">Payroll Record</button>
                </div>
             </div>
            <?php
            }
            ?> </div> <?php
        } else {
            echo "No data";
        }
    }

?>