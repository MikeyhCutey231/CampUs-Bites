<?php
require_once("../../Admin/functions/dbConfig.php");


class EmployeeData{
    public $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getEmployeeData() {
        $employeeData = array();

        $query = "SELECT USER_ID, U_FIRST_NAME, U_MIDDLE_NAME, U_LAST_NAME, U_PICTURE, role_name   
              FROM vwpayroll_list
              ORDER BY users.U_LAST_NAME ASC";

        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($employeeId, $firstName, $middleName, $lastName, $profPic, $rolename);

            while ($stmt->fetch()) {
                $employeeData[] = [
                    'EMPLOYEE_ID' => $employeeId,
                    'EMP_FIRST_NAME' => $firstName,
                    'EMP_MIDDLE_NAME' => $middleName,
                    'EMP_LAST_NAME' => $lastName,
                    'EMP_PROF_PIC' => $profPic,
                    'EMP_ROLE_NAME' => $rolename,

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
                    <img src="../admin_php/upload/<?php echo $row['EMP_PROF_PIC']?>" alt="" style="width: 90px; height: 90px; object-fit: cover;">
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
                    <form action="../admin_php/viewpayroll.php" method="POST">
                        <button type="button" class="payrollRecord">Payroll Record</button>
                    </form>
                </div>
            </div>
            <?php
        }
        ?> </div> <?php
    }



    public function searchPayrollEmployees($searchName) {
        $query = "SELECT USER_ID, U_FIRST_NAME, U_MIDDLE_NAME, U_LAST_NAME, U_PICTURE, role_name   
              FROM vwpayroll_list WHERE U_FIRST_NAME LIKE ? OR U_MIDDLE_NAME LIKE ? OR U_LAST_NAME LIKE ?";

        $stmt = $this->conn->prepare($query);

        $searchNameParam = "%" . $searchName . "%"; // Add % to create a LIKE query

        $stmt->bind_param("sss", $searchNameParam, $searchNameParam, $searchNameParam);

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


    public function filterEmployeesPosition($position) {
        $query = "SELECT USER_ID, U_FIRST_NAME, U_MIDDLE_NAME, U_LAST_NAME, U_PICTURE, role_name   
              FROM vwpayroll_list WHERE role_name LIKE ?";

        $stmt = $this->conn->prepare($query);

        $positionParam = "%" . $position . "%"; // Add % to create a LIKE query

        $stmt->bind_param("s", $positionParam);

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

    public function viewDTR($id){
        $query = "SELECT *,
        CASE
            WHEN EMP_DTR_TIME_IN = '00:00:00' AND EMP_DTR_TIME_OUT = '00:00:00' THEN 'ABSENT'
            ELSE DATE_FORMAT(EMP_DTR_TIME_IN, '%h:%i %p')
        END AS EMP_DTR_TIME_IN_FORMATTED,
        CASE
            WHEN EMP_DTR_TIME_IN = '00:00:00' AND EMP_DTR_TIME_OUT = '00:00:00' THEN 'ABSENT'
            ELSE DATE_FORMAT(EMP_DTR_TIME_OUT, '%h:%i %p')
        END AS EMP_DTR_TIME_OUT_FORMATTED
        FROM emp_dtr WHERE EMPLOYEE_ID = ?";


        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id);

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
}
?>