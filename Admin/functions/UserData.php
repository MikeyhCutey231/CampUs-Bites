<?php
require("../../Admin/functions/dbConfig.php");

class UserData {
    private $conn;

    public function __construct() {
        $database = new Connection();
        $this->conn = $database->conn;
    }
    public function getAdminAccounts() {
        $sql = "SELECT users.*, user_roles.*
            FROM users
            JOIN user_roles ON users.USER_ID = user_roles.USER_ID
            WHERE user_roles.ROLE_CODE = 'adm'";

        $adminAccounts = array();

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $roleCode = $row["ROLE_CODE"];
            $admin = array(
                'user_id' => $row["USER_ID"],
             /*   'username' => $row["U_USER_NAME"],
                'email' => $row["U_EMAIL"],
                'password' => $row["U_PASSWORD"],
                'phoneNum' => $row["U_PHONE_NUMBER"],
                'fullname' => $row["U_FIRST_NAME"] . ' ' . $row["U_MIDDLE_NAME"] . ' ' . $row["U_LAST_NAME"],
                'profilePic' => $row["U_PICTURE"],
                'userRole' => $row["ROLE_CODE"],
                'fname' => $row["U_FIRST_NAME"],
                'lname' => $row["U_LAST_NAME"],
                'mname' => $row["U_MIDDLE_NAME"],
                'gender' => $row["U_GENDER"],
                'campusArea' => $row["U_CAMPUS_AREA"],
                'suffix' => $row["U_SUFFIX"],
                'status' => $row["U_STATUS"],*/
                'role_code' => $roleCode,
            );

            $adminAccounts[] = $admin;
        }

        $stmt->close();

        return $adminAccounts;
    }


    public function getUserData($userId) {
        $sql = "SELECT users.*, user_roles.*
        FROM users
        JOIN user_roles ON users.USER_ID = user_roles.USER_ID
        WHERE users.USER_ID = ?";

        $userData = array();

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $roleCode = $row["ROLE_CODE"];
            $user = array(
                'user_id' => $row["USER_ID"],
                'username' => $row["U_USER_NAME"],
                'email' => $row["U_EMAIL"],
                'password' => $row["U_PASSWORD"],
                'phoneNum' => $row["U_PHONE_NUMBER"],
                'fullname' => $row["U_FIRST_NAME"] . ' ' . $row["U_MIDDLE_NAME"] . ' ' . $row["U_LAST_NAME"],
                'profilePic' => $row["U_PICTURE"],
                'userRole' => $row["ROLE_CODE"],
                'fname' => $row["U_FIRST_NAME"],
                'lname' => $row["U_LAST_NAME"],
                'mname' => $row["U_MIDDLE_NAME"],
                'gender' => $row["U_GENDER"],
                'campusArea' => $row["U_CAMPUS_AREA"],
                'suffix' => $row["U_SUFFIX"],
                'status' => $row["U_STATUS"],
                'role_code' => $roleCode,
            );

            $userData[] = $user;
        }

        $stmt->close();

        return $userData;
    }
    function getBasicSalaryByRole($roleName) {
        $sql = "SELECT EMP_BASIC_SALARY FROM emp_job_info WHERE EMP_POSITION = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $roleName);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $basicSalary);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);

        return $basicSalary;
    }

    public function getDaysWorked($userId, $startDate, $endDate) {
        $sql = "SELECT DISTINCT DATE(EMP_DTR_DATE) AS work_date 
            FROM emp_dtr 
            WHERE EMPLOYEE_ID = ? 
            AND EMP_DTR_STATUS = 'Present' 
            AND EMP_DTR_DATE BETWEEN ? AND ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $userId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        $numDaysWorked = $result->num_rows;

        return $numDaysWorked;
    }


    public function getTotalOvertimeHours($userId) {
        $sql = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(EMP_DTR_OVERTIME_HOURS))) AS total_overtime_hours FROM emp_dtr WHERE EMPLOYEE_ID = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        $totalOvertimeHours = $row['total_overtime_hours'];

        return $totalOvertimeHours;
    }




    public function getPositionByRoleCode($roleCode) {
    $sql = "SELECT ROLE_NAME FROM roles WHERE ROLE_CODE = ?";

    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $roleCode);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $position);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $position;
}
public function getEmployeeData($employee_id) {
    $sql = "SELECT users.*, user_roles.* 
        FROM users 
        JOIN user_roles ON users.USER_ID = user_roles.USER_ID 
        WHERE users.USER_ID = ?";

    $employeeData = array();

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $employee = array(
            'username' => $row["U_USER_NAME"],
            'employee_id' => $row["USER_ID"],
            'employee_fname' => $row["U_FIRST_NAME"],
            'employee_lname' => $row["U_LAST_NAME"],
            'employee_mname' => $row["U_MIDDLE_NAME"],
            'employee_suffix' => $row["U_SUFFIX"],
            'employee_gender' => $row["U_GENDER"],
            'employee_phoneNum' => $row["U_PHONE_NUMBER"],
            'employee_email' => $row["U_EMAIL"],
            'employee_acc_status' => $row["U_STATUS"],
            'employee_profPic' => $row["U_PICTURE"],
            'role_code' => $row["ROLE_CODE"],
        );

        $employeeData[] = $employee;
    }

    $stmt->close();

    return $employeeData;
}


    public function getEmployeeDataCourier() {
        $sql = "SELECT users.*, user_roles.ROLE_CODE 
            FROM users 
            JOIN user_roles ON users.USER_ID = user_roles.USER_ID 
            WHERE user_roles.ROLE_CODE = 'cour'";
        $employeeData = array();

        $retval = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($retval)) {
            $employee = array(
                'employee_id' => $row["USER_ID"],
                'employee_fname' => $row["U_FIRST_NAME"],
                'employee_lname' => $row["U_LAST_NAME"],
                'employee_mname' => $row["U_MIDDLE_NAME"],
                'employee_suffix' => $row["U_SUFFIX"],
                'employee_gender' => $row["U_GENDER"],
                'employee_phoneNum' => $row["U_PHONE_NUMBER"],
                'employee_email' => $row["U_EMAIL"],
                'employee_acc_status' => $row["U_STATUS"],
                'employee_profPic' => $row["U_PICTURE"],
                'role_code' => $row["ROLE_CODE"],
            );

            $employeeData[] = $employee;
        }

        return $employeeData;
    }
    public function getEmployeeDataCashier() {
        $sql = "SELECT users.*, user_roles.ROLE_CODE 
            FROM users 
            JOIN user_roles ON users.USER_ID = user_roles.USER_ID 
            WHERE user_roles.ROLE_CODE = 'emp_cshr'";

        $employeeData = array();
        $retval = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($retval)) {
            $employee = array(
                'employee_id' => $row["USER_ID"],
                'employee_fname' => $row["U_FIRST_NAME"],
                'employee_lname' => $row["U_LAST_NAME"],
                'employee_mname' => $row["U_MIDDLE_NAME"],
                'employee_suffix' => $row["U_SUFFIX"],
                'employee_gender' => $row["U_GENDER"],
                'employee_phoneNum' => $row["U_PHONE_NUMBER"],
                'employee_email' => $row["U_EMAIL"],
                'employee_acc_status' => $row["U_STATUS"],
                'employee_profPic' => $row["U_PICTURE"],
                'role_code' => $row["ROLE_CODE"],
            );

            $employeeData[] = $employee;
        }

        return $employeeData;
    }



    public function getEmployeeDataServer() {
        $sql = "SELECT users.*, user_roles.ROLE_CODE 
            FROM users 
            JOIN user_roles ON users.USER_ID = user_roles.USER_ID 
            WHERE user_roles.ROLE_CODE = 'emp_srvr'";
        $employeeData = array();

        $retval = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($retval)) {
            $employee = array(
                'employee_id' => $row["USER_ID"],
                'employee_fname' => $row["U_FIRST_NAME"],
                'employee_lname' => $row["U_LAST_NAME"],
                'employee_mname' => $row["U_MIDDLE_NAME"],
                'employee_suffix' => $row["U_SUFFIX"],
                'employee_gender' => $row["U_GENDER"],
                'employee_phoneNum' => $row["U_PHONE_NUMBER"],
                'employee_email' => $row["U_EMAIL"],
                'employee_acc_status' => $row["U_STATUS"],
                'employee_profPic' => $row["U_PICTURE"],
                'role_code' => $row["ROLE_CODE"],
            );

            $employeeData[] = $employee;
        }

        return $employeeData;
    }
    public function getEmployeeDataManager() {
        $sql = "SELECT users.*, user_roles.ROLE_CODE 
            FROM users 
            JOIN user_roles ON users.USER_ID = user_roles.USER_ID 
            WHERE user_roles.ROLE_CODE = 'adm_manager'";
        $employeeData = array();

        $retval = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($retval)) {
            $employee = array(
                'employee_id' => $row["USER_ID"],
                'employee_fname' => $row["U_FIRST_NAME"],
                'employee_lname' => $row["U_LAST_NAME"],
                'employee_mname' => $row["U_MIDDLE_NAME"],
                'employee_suffix' => $row["U_SUFFIX"],
                'employee_gender' => $row["U_GENDER"],
                'employee_phoneNum' => $row["U_PHONE_NUMBER"],
                'employee_email' => $row["U_EMAIL"],
                'employee_acc_status' => $row["U_STATUS"],
                'employee_profPic' => $row["U_PICTURE"],
                'role_code' => $row["ROLE_CODE"],
            );

            $employeeData[] = $employee;
        }

        return $employeeData;
    }
    public function getEmployeeDataCook() {
        $sql = "SELECT users.*, user_roles.ROLE_CODE 
            FROM users 
            JOIN user_roles ON users.USER_ID = user_roles.USER_ID 
            WHERE user_roles.ROLE_CODE = 'emp_cook'";
        $employeeData = array();

        $retval = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($retval)) {
            $employee = array(
                'employee_id' => $row["USER_ID"],
                'employee_fname' => $row["U_FIRST_NAME"],
                'employee_lname' => $row["U_LAST_NAME"],
                'employee_mname' => $row["U_MIDDLE_NAME"],
                'employee_suffix' => $row["U_SUFFIX"],
                'employee_gender' => $row["U_GENDER"],
                'employee_phoneNum' => $row["U_PHONE_NUMBER"],
                'employee_email' => $row["U_EMAIL"],
                'employee_acc_status' => $row["U_STATUS"],
                'employee_profPic' => $row["U_PICTURE"],
                'role_code' => $row["ROLE_CODE"],
            );

            $employeeData[] = $employee;
        }

        return $employeeData;
    }
    public function getEmployeeDataAsstCook() {
        $sql = "SELECT users.*, user_roles.ROLE_CODE 
            FROM users 
            JOIN user_roles ON users.USER_ID = user_roles.USER_ID 
            WHERE user_roles.ROLE_CODE = 'emp_asst_cook'";
        $employeeData = array();

        $retval = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($retval)) {
            $employee = array(
                'employee_id' => $row["USER_ID"],
                'employee_fname' => $row["U_FIRST_NAME"],
                'employee_lname' => $row["U_LAST_NAME"],
                'employee_mname' => $row["U_MIDDLE_NAME"],
                'employee_suffix' => $row["U_SUFFIX"],
                'employee_gender' => $row["U_GENDER"],
                'employee_phoneNum' => $row["U_PHONE_NUMBER"],
                'employee_email' => $row["U_EMAIL"],
                'employee_acc_status' => $row["U_STATUS"],
                'employee_profPic' => $row["U_PICTURE"],
                'role_code' => $row["ROLE_CODE"],
            );

            $employeeData[] = $employee;
        }

        return $employeeData;
    }

    public function getCustomerData() {
        $sql = "SELECT users.*, user_roles.ROLE_CODE 
            FROM users 
            JOIN user_roles ON users.USER_ID = user_roles.USER_ID 
            WHERE user_roles.ROLE_CODE = 'cstmr'";
        $customerData = array();

        $retval = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($retval)) {
            $customer = array(
                'customer_id' => $row["USER_ID"],
                'username' => $row["U_USER_NAME"],
                'customer_password' => $row["U_PASSWORD"],
                'customer_fname' => $row["U_FIRST_NAME"],
                'customer_mname' => $row["U_MIDDLE_NAME"],
                'customer_lname' => $row["U_LAST_NAME"],
                'customer_suffix' => $row["U_SUFFIX"],
                'customer_email' => $row["U_EMAIL"],
                'customer_gender' => $row["U_GENDER"],
                'customer_phoneNum' => $row["U_PHONE_NUMBER"],
                'customer_schoolLandmark' => $row["U_CAMPUS_AREA"],
                'customer_status' => $row["U_STATUS"],
                'customer_profPic' => $row["U_PICTURE"],
            );

            $customerData[] = $customer;
        }

        return $customerData;
    }










    public function getEmployeeDatas() {
        $employeeData = array();

        $query = "SELECT
            users.USER_ID,
            users.U_FIRST_NAME,
            users.U_MIDDLE_NAME,
            users.U_LAST_NAME,
            users.U_PICTURE,
            vwpayroll_list.role_name,
            emp_payroll.EMP_PAYROLL_ID AS most_recent_payroll_id
        FROM
            vwpayroll_list
        JOIN
            users ON vwpayroll_list.USER_ID = users.USER_ID
        JOIN
            emp_payroll ON vwpayroll_list.USER_ID = emp_payroll.EMPLOYEE_ID
                    AND emp_payroll.EMP_PAYROLL_ID = (
                        SELECT MAX(EMP_PAYROLL_ID)
                        FROM emp_payroll
                        WHERE emp_payroll.EMPLOYEE_ID = vwpayroll_list.USER_ID
                    )
        ORDER BY
            users.U_LAST_NAME ASC;
        ";

        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($employeeId, $firstName, $middleName, $lastName, $profPic, $rolename, $mostRecentPayrollId);

            while ($stmt->fetch()) {
                $employeeData[] = [
                    'EMPLOYEE_ID' => $employeeId,
                    'EMP_FIRST_NAME' => $firstName,
                    'EMP_MIDDLE_NAME' => $middleName,
                    'EMP_LAST_NAME' => $lastName,
                    'EMP_PROF_PIC' => $profPic,
                    'EMP_ROLE_NAME' => $rolename,
                    'EMP_PAYROLL_ID' => $mostRecentPayrollId,
                ];
            }

            $stmt->close();

        }

        return $employeeData;
    }




    public function renderEmployeeTable($employeeData) {
        foreach ($employeeData as $row) {
            ?> <div class="card-1">
                <div class="user-face">
                    <img src="../../Icons/<?php echo $row['EMP_PROF_PIC']?>" alt="" style="width: 90px; height: 90px; object-fit: cover;">
                </div>
                <div class="user-info">
                    <p style="margin-bottom: -5px; font-weight: 900; font-size: 18px; margin-top: 8px;">
                        <?php
                        echo $row['EMP_FIRST_NAME'] . ' ' . (!empty($row['EMP_MIDDLE_NAME']) ? $row['EMP_MIDDLE_NAME'][0] . '. ' : '') . $row['EMP_LAST_NAME'];
                        ?>
                    </p>

                    <p style="margin-bottom: 0px; color: #737373;"><?php echo $row['EMP_ROLE_NAME'] ?></p>
                    <p class="positionEmpID" style="display: none;"><?php echo $row['EMPLOYEE_ID'] ?></p>
                </div>
                <div class="card-button">
                    <?php
                    if(isset($_POST['payrollRecord'])) {
                        echo "clicked";
                        $_SESSION['EMP_FIRST_NAME'] = $row['EMP_FIRST_NAME'];
                    }
                    ?>
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
    }



    public function searchPayrollEmployees($searchName) {
        $query = "SELECT
        users.USER_ID,
        users.U_FIRST_NAME,
        users.U_MIDDLE_NAME,
        users.U_LAST_NAME,
        users.U_PICTURE,
        vwpayroll_list.role_name,
        emp_payroll.EMP_PAYROLL_ID AS most_recent_payroll_id
    FROM
        vwpayroll_list
    JOIN
        users ON vwpayroll_list.USER_ID = users.USER_ID
    JOIN
        emp_payroll ON vwpayroll_list.USER_ID = emp_payroll.EMPLOYEE_ID
            AND emp_payroll.EMP_PAYROLL_ID = (
                SELECT MAX(EMP_PAYROLL_ID)
                FROM emp_payroll
                WHERE emp_payroll.EMPLOYEE_ID = vwpayroll_list.USER_ID
            )
    WHERE
        users.U_FIRST_NAME LIKE ? OR users.U_MIDDLE_NAME LIKE ? OR users.U_LAST_NAME LIKE ?
    ORDER BY
        users.U_LAST_NAME ASC";

        $stmt = $this->conn->prepare($query);

        $searchNameParam = "%" . $searchName . "%";

        $stmt->bind_param("sss", $searchNameParam, $searchNameParam, $searchNameParam);

        if ($stmt->execute()) {
            $stmt->bind_result($employeeId, $firstName, $middleName, $lastName, $profPic, $rolename, $mostRecentPayrollId);            $employeeData = array();

            while ($stmt->fetch()) {
                $employeeData[] = [
                    'EMPLOYEE_ID' => $employeeId,
                    'EMP_FIRST_NAME' => $firstName,
                    'EMP_MIDDLE_NAME' => $middleName,
                    'EMP_LAST_NAME' => $lastName,
                    'EMP_PROF_PIC' => $profPic,
                    'EMP_ROLE_NAME' => $rolename,
                    'EMP_PAYROLL_ID' => $mostRecentPayrollId,
                ];
            }

            return $employeeData;
        } else {
            // Handle the error
            return null;
        }
    }



    public function filterEmployeesPosition($position) {
        $employeeData = array();

        $query = "SELECT
        users.USER_ID,
        users.U_FIRST_NAME,
        users.U_MIDDLE_NAME,
        users.U_LAST_NAME,
        users.U_PICTURE,
        vwpayroll_list.role_name,
        emp_payroll.EMP_PAYROLL_ID AS most_recent_payroll_id
    FROM
        vwpayroll_list
    JOIN
        users ON vwpayroll_list.USER_ID = users.USER_ID
    JOIN
        emp_payroll ON vwpayroll_list.USER_ID = emp_payroll.EMPLOYEE_ID
            AND emp_payroll.EMP_PAYROLL_ID = (
                SELECT MAX(EMP_PAYROLL_ID)
                FROM emp_payroll
                WHERE emp_payroll.EMPLOYEE_ID = vwpayroll_list.USER_ID
            )
    WHERE
        vwpayroll_list.role_name LIKE ?
    ORDER BY
        users.U_LAST_NAME ASC";

        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $positionParam = "%" . $position . "%";
            $stmt->bind_param("s", $positionParam);

            $stmt->execute();
            $stmt->bind_result($employeeId, $firstName, $middleName, $lastName, $profPic, $rolename, $mostRecentPayrollId);

            while ($stmt->fetch()) {
                $employeeData[] = [
                    'EMPLOYEE_ID' => $employeeId,
                    'EMP_FIRST_NAME' => $firstName,
                    'EMP_MIDDLE_NAME' => $middleName,
                    'EMP_LAST_NAME' => $lastName,
                    'EMP_PROF_PIC' => $profPic,
                    'EMP_ROLE_NAME' => $rolename,
                    'EMP_PAYROLL_ID' => $mostRecentPayrollId,
                ];
            }

            $stmt->close();
        }

        return $employeeData;
    }

    public function viewDTR($id, $dateFrom, $dateTo) {
        $query = "SELECT *,
        CASE
            WHEN EMP_DTR_TIME_IN = '00:00:00' AND EMP_DTR_TIME_OUT = '00:00:00' THEN 'ABSENT'
            ELSE DATE_FORMAT(EMP_DTR_TIME_IN, '%h:%i %p')
        END AS EMP_DTR_TIME_IN_FORMATTED,
        CASE
            WHEN EMP_DTR_TIME_IN = '00:00:00' AND EMP_DTR_TIME_OUT = '00:00:00' THEN 'ABSENT'
            ELSE DATE_FORMAT(EMP_DTR_TIME_OUT, '%h:%i %p')
        END AS EMP_DTR_TIME_OUT_FORMATTED
        FROM emp_dtr
        WHERE EMPLOYEE_ID = ? AND EMP_DTR_DATE BETWEEN ? AND ?";


        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iss", $id, $dateFrom, $dateTo);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $employeeData = array();

            while ($row = $result->fetch_assoc()) {
                $employeeData[] = $row;
            }

            return $employeeData;
        } else {
            // Handle the error
            return null;
        }
    }

    public function renderEmployeeDTR($employeeData) {
        foreach ($employeeData as $row) {
            ?>
            <tr>
                <td><?php echo $row['EMP_DTR_DATE'] ?></td>
                <td><?php echo $row['EMP_DTR_TIME_IN_FORMATTED'] ?></td>
                <td><?php echo $row['EMP_DTR_TIME_OUT_FORMATTED'] ?></td>
                <td><?php echo $row['EMP_DTR_OVERTIME_HOURS']?></td>
            </tr>
            <?php
        }

    }


    public function updateSalary($employeeePosition, $salary) {
        $updateValue = "UPDATE emp_job_info SET EMP_BASIC_SALARY = '$salary' WHERE EMP_POSITION = '$employeeePosition'";
        $updateValueRun = mysqli_query($this->conn, $updateValue);

        return $updateValueRun;
    }


    public function getDeductionAmount($deductionName) {
        $query = "SELECT DEDUC_AMOUNT FROM deduction WHERE DEDUC_NAME = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $deductionName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row["DEDUC_AMOUNT"];
    }

    public function updateDeductionAmount($deductionName, $amount) {
        $query = "UPDATE deduction SET DEDUC_AMOUNT = ? WHERE DEDUC_NAME = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $amount, $deductionName);
        return $stmt->execute();
    }



    public function overAllUsers() {
        $userData = array();

        $query = "SELECT COUNT(*) AS totalUser FROM users;";

        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($totalUser);

            while ($stmt->fetch()) {
                $userData[] = [
                    'totalUser' => $totalUser,
                ];
            }

            $stmt->close();
        }

        return $userData;
    }


    public function renderTotalUsers($userData) {
        foreach ($userData as $row) {
            ?> 
               <div class="circle-container">
                  <img src="../../Icons/userswhite.svg" alt="Icon">
                </div>
                      <h4 class="text-start mt-2" style="font-weight: bold; color: #5f5f5f "><?php echo $row['totalUser'] ?></h4>
                      <h6 class="text-start" style="font-size: 14px;">Total Users</h6>

                      <h6 class="text-start mt-2" style="font-size: 9.5px; color: #82A2F3">+3 from yesterday</h6>
            <?php
        }
    }


    public function overAllProduct() {
        $userData = array();

        $query = "SELECT COUNT(*) AS totalProduct FROM product;";

        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($totalUser);

            while ($stmt->fetch()) {
                $userData[] = [
                    'totalProduct' => $totalUser,
                ];
            }

            $stmt->close();
        }

        return $userData;
    }


    public function renderTotalProduct($userData) {
        foreach ($userData as $row) {
            ?> 
                      <h4 class="text-start mt-2" style="font-weight: bold; color: #5f5f5f "><?php echo $row['totalProduct'] ?></h4>
                      <h6 class="text-start" style="font-size: 14px;">Total Product</h6>

                      <h6 class="text-start mt-2" style="font-size: 9.5px; color: #82A2F3">+3 from yesterday</h6>
            <?php
        }
    }


    public function overAllIncome() {
        $userData = array();
    
        $query = "SELECT
            (SELECT SUM(pos_cart_item.POS_SUBTOTAL) FROM pos_cart_item) +
            (SELECT SUM(online_cart_item.OL_SUBTOTAL) FROM online_cart_item) AS Overall;";
    
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($Overall);
    
            while ($stmt->fetch()) {
                // Format the Overall amount
                $formattedOverall = $this->formatAmount($Overall);
    
                $userData[] = [
                    'Overall' => $formattedOverall,
                ];
            }
    
            $stmt->close();
        }
    
        return $userData;
    }
    
    public function renderoverAllIncome($userData) {
        foreach ($userData as $row) {
            ?> 
            <h4 class="text-start mt-2" style="font-weight: bold; color: #5f5f5f "><?php echo $row['Overall'] ?></h4>
            <h6 class="text-start" style="font-size: 14px;">Total Users</h6>
    
            <h6 class="text-start mt-2" style="font-size: 9.5px; color: #82A2F3">+3 from yesterday</h6>
            <?php
        }
    }
    

    private function formatAmount($amount) {
        if ($amount >= 1000000) {
            return number_format($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return number_format($amount / 1000, 1) . 'K';
        } else {
            return number_format($amount);
        }
    }


    public function productRating() {
        $userData = array();

        $query = "SELECT
        product.PROD_NAME,
        ROUND(AVG(online_order.ORDER_RATING), 1) AS ORDER_RATING,
        ROW_NUMBER() OVER (ORDER BY ROUND(AVG(online_order.ORDER_RATING), 1) DESC) AS ranking
        FROM
            online_order
        INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
        INNER JOIN online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
        INNER JOIN product ON online_cart_item.PROD_ID = product.PROD_ID
        WHERE online_order.ORDER_RATING IS NOT NULL
        GROUP BY product.PROD_ID
        ORDER BY ORDER_RATING DESC
        LIMIT 4;
        ";

        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($prodName, $orderRating, $ranking);

            while ($stmt->fetch()) {
                $userData[] = [
                    'PROD_NAME' => $prodName,
                    'ORDER_RATING' => $orderRating,
                    'ranking' => $ranking,
                ];
            }

            $stmt->close();
        }

        return $userData;
    }

    
    public function renderProductRating($userData) {
        foreach ($userData as $row) {
            $progressColor = '';
    
            switch ($row['ranking']) {
                case 1:
                    $progressColor = '#9C1421'; // Red for the highest rating
                    break;
                case 2:
                    $progressColor = '#F0C419'; // Yellow for the second-highest rating
                    break;
                case 3:
                    $progressColor = '#A2C53A'; // Blue for the third-highest rating
                    break;
                case 4:
                    $progressColor = '#403D55'; // Green for the fourth-highest rating
                    break;
                // Add more cases as needed
            }


            $progressBackground = '';
    
            switch ($row['ranking']) {
                case 1:
                    $progressBackground = '#FFE2E6'; // Red for the highest rating
                    break;
                case 2:
                    $progressBackground = '#FFF4DE'; // Yellow for the second-highest rating
                    break;
                case 3:
                    $progressBackground = '#DCFCE7'; // Blue for the third-highest rating
                    break;
                case 4:
                    $progressBackground = '#CEC8FC'; // Green for the fourth-highest rating
                    break;
                // Add more cases as needed
            }
    
            ?>
            <tr>
                <th scope="row"><?php echo $row['ranking'] ?></th>
                <td><?php echo $row['PROD_NAME'] ?></td>
                <td>
                    <div class="progress mt-2" role="progressbar" aria-label="Example 5px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 5px; background-color: <?php echo $progressBackground ?>">
                        <div class="progress-bar" style="width: <?php echo $row['ORDER_RATING'] . "%" ?>; background-color: <?php echo $progressColor ?>"></div>
                    </div>
                </td>
                <td><div class="percent1" style="border: 2px <?php echo $progressColor ?> solid"><?php echo $row['ORDER_RATING'] ?>%</div></td>
            </tr>
            <?php
        }
    }
    
    
    public function usersList() {
        $userData = array();

        $query = "SELECT
        users.U_FIRST_NAME,
        users.U_MIDDLE_NAME,
        users.U_LAST_NAME,
        users.U_PICTURE,
        users.U_STATUS,
        roles.ROLE_NAME
    FROM
        users
    INNER JOIN
        user_roles ON users.USER_ID = user_roles.USER_ID
    INNER JOIN
        roles ON user_roles.ROLE_CODE = roles.ROLE_CODE
    WHERE
        (user_roles.ROLE_CODE IN ('emp_cshr', 'emp_asst_cook', 'emp_srvr', 'emp_cook') OR users.U_STATUS = 'Active') AND
        users.U_STATUS != 'Deactivated'";

        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($fname, $mname, $lname, $Upicture, $Ustatus, $roleName);

            while ($stmt->fetch()) {
                $userData[] = [
                    'U_FIRST_NAME' => $fname,
                    'U_MIDDLE_NAME' => $mname,
                    'U_LAST_NAME' => $lname,
                    'U_PICTURE' => $Upicture,
                    'U_STATUS' => $Ustatus,
                    'ROLE_NAME' => $roleName,
                ];
            }

            $stmt->close();
        }

        return $userData;
    }



    public function renderStaffList($userData) {
        foreach ($userData as $row) {
            ?> 
             <tr style="background-color: red;">
                <th scope="row">
                    <img src="../../Icons/<?php echo $row['U_PICTURE'] ?>" class="staffPic">
                </th>
                    <td><?php echo $row['U_FIRST_NAME'] . " ". $row['U_MIDDLE_NAME']. " " . $row["U_LAST_NAME"] ?><h6 class="position"><?php echo $row['ROLE_NAME'] ?></h6></td>
                        <td>
                            <button class="active-button"><?php echo $row['U_STATUS'] ?></button>
                        </td>
             </tr>
            <?php
        }
    }


    public function deliveryList() {
        $userData = array();

        $query = "SELECT
            users.U_FIRST_NAME,
            users.U_MIDDLE_NAME,
            users.U_LAST_NAME,
            users.U_PICTURE,
            users.U_STATUS,
            roles.ROLE_NAME
        FROM
            users
        INNER JOIN
            user_roles ON users.USER_ID = user_roles.USER_ID
        INNER JOIN
            roles ON user_roles.ROLE_CODE = roles.ROLE_CODE
        WHERE
            user_roles.ROLE_CODE LIKE 'cour' AND
            users.U_STATUS = 'Active'";

        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($fname, $mname, $lname, $Upicture, $Ustatus, $roleName);

            while ($stmt->fetch()) {
                $userData[] = [
                    'U_FIRST_NAME' => $fname,
                    'U_MIDDLE_NAME' => $mname,
                    'U_LAST_NAME' => $lname,
                    'U_PICTURE' => $Upicture,
                    'U_STATUS' => $Ustatus,
                    'ROLE_NAME' => $roleName,
                ];
            }

            $stmt->close();
        }

        return $userData;
    }


    public function renderDeliveryList($userData) {
        foreach ($userData as $row) {
            ?> 
             <tr style="background-color: red;">
                <th scope="row">
                    <img src="../../Icons/<?php echo $row['U_PICTURE'] ?>" class="staffPic">
                </th>
                    <td><?php echo $row['U_FIRST_NAME'] . " ". $row['U_MIDDLE_NAME']. " " . $row["U_LAST_NAME"] ?><h6 class="position"><?php echo $row['ROLE_NAME'] ?></h6></td>
                        <td>
                            <button class="active-button"><?php echo $row['U_STATUS'] ?></button>
                        </td>
             </tr>
            <?php
        }
    }


    public function highestOrder() {
        $userData = array();

        $query = "SELECT
            users.U_FIRST_NAME,
            users.U_MIDDLE_NAME,
            users.U_LAST_NAME,
            users.U_PICTURE,
            roles.ROLE_NAME,
            COUNT(online_cart_item.PROD_ID) AS CountOfItems,
            ROW_NUMBER() OVER (ORDER BY COUNT(online_cart_item.PROD_ID) DESC) AS Ranking
        FROM
            users
        INNER JOIN 
            ol_cart ON users.USER_ID = ol_cart.CUSTOMER_ID
        INNER JOIN
            online_cart_item ON ol_cart.OL_CART_ID = online_cart_item.OL_CART_ID
        INNER JOIN
            user_roles ON users.USER_ID = user_roles.USER_ID
        INNER JOIN
            roles ON user_roles.ROLE_CODE = roles.ROLE_CODE
        WHERE
            roles.ROLE_NAME = 'Customer'
        GROUP BY
            users.U_FIRST_NAME,
            users.U_MIDDLE_NAME,
            users.U_LAST_NAME,
            users.U_PICTURE,
            roles.ROLE_NAME
        ORDER BY Ranking";


        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($fname, $mname, $lname, $Upicture, $roleName, $countItems, $ranking);

            while ($stmt->fetch()) {
                $userData[] = [
                    'U_FIRST_NAME' => $fname,
                    'U_MIDDLE_NAME' => $mname,
                    'U_LAST_NAME' => $lname,
                    'U_PICTURE' => $Upicture,
                    'ROLE_NAME' => $roleName,
                    'CountOfItems' => $countItems,
                    'ranking' => $ranking,
                ];
            }

            $stmt->close();
        }

        return $userData;
    }


    public function renderHighestOrder($userData) {
        foreach ($userData as $row) {
            ?> 
             <tr>
                 <th scope="row"><?php echo $row['ranking'] ?>
                    </th>
                        <td><img src="../../Icons/<?php echo $row['U_PICTURE'] ?>" class="staffPic"></td>
                         <td>Marvin<h6 class="position"><?php echo $row['ROLE_NAME'] ?></h6></td>
                            <td>
                                <div>
                                    <img src="../../Icons/package.svg" class="icon">
                                    <h6 class="number mt-2"><?php echo $row['CountOfItems'] ?></h6>
                                </div>
                        </td>
             </tr>
            <?php
        }
    }



    public function getDaysWorkedforDTR($userId) {
        $sql = "SELECT DISTINCT DATE(EMP_DTR_DATE) AS work_date 
            FROM emp_dtr 
            WHERE EMPLOYEE_ID = ? 
            AND EMP_DTR_STATUS = 'Present'";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $numDaysWorked = $result->num_rows;

        return $numDaysWorked;
    }



}
?>
