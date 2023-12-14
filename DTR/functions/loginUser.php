<?php 
    require_once("../../Admin/functions/dbConfig.php");
    class LoginUser extends Connection{
        const REGISTRATION_SUCCESS = 'success';
        const REGISTRATION_NOTSAME = 'not same';
        const REGISTRATION_NOTSTAFF = 'not staff';
        const REGISTRATION_DEACTIVATED = 'deactivated';
        const REGISTRATION_LIMIT = 'limit';

        const REGISTRATION_EMPTY_FIELDS = 'empty_fields';
        private $userID;
        public function setConnection($conn) {
            $this->conn = $conn;
        }

        public function userLogin($username, $password) {
            $result = mysqli_query($this->conn, "SELECT * FROM users u 
              JOIN user_roles r ON u.USER_ID = r.USER_ID
              WHERE u.U_USER_NAME = '$username' AND (r.ROLE_CODE = 'emp_cshr' OR r.ROLE_CODE = 'emp_srvr' OR r.ROLE_CODE = 'adm_manager' OR r.ROLE_CODE = 'emp_cook' OR r.ROLE_CODE = 'emp_asst_cook');");
            $row = mysqli_fetch_assoc($result);

            if (empty(trim($username)) || empty(trim($password))) {
                return self::REGISTRATION_EMPTY_FIELDS;
            } else {
                if ($row) {
                    $hashedPassword = $row["U_PASSWORD"];
                    $userID = $row['USER_ID'];


                    $loginStatus = $this->getLoginStatus($userID);

                    if ($loginStatus === 'Logged Out') {
                        return self::REGISTRATION_LIMIT;
                    }

                    if (password_verify($password, $hashedPassword)) {
                        $this->userID = $userID;

                        if ($loginStatus == 'Logged In') {
                            $this->performTimeout($userID);
                        } else {
                            $this->performLogin($userID);
                        }


                        return self::REGISTRATION_SUCCESS;
                    } else {
                        return self::REGISTRATION_NOTSAME;
                    }
                } else {
                    return self::REGISTRATION_NOTSAME;
                }
            }
        }
        public function generatePayroll($userID,$payrollStartDate,$payrollEndDate) {
            require '../../Admin/functions/UserData.php';
            require_once '../../Admin/functions/payroll.php';

            $userDataInstance = new UserData();

            $dtr = new EmployeeData($this->conn);
            $value1 = $dtr->getDeductionAmount("SSS");
            $value3 = $dtr->getDeductionAmount("PhilHealth");
            $value2 = $dtr->getDeductionAmount("PagIBIG");
            $payrollDate = date('Y-m-d');


            $viewUserData = "SELECT * FROM vwpayroll_list WHERE USER_ID = '$userID'";
            $viewUserDataRun = mysqli_query($this->conn, $viewUserData);
 while ($row = mysqli_fetch_array($viewUserDataRun)) {
     $basicSalary = $userDataInstance->getBasicSalaryByRole($row['ROLE_NAME']);
     $daysWorked = $userDataInstance->getDaysWorkedforDTR($userID);
     $userOvertimeHours = $userDataInstance->getTotalOvertimeHours($userID);

     $subtotal = $basicSalary * $daysWorked;
     $overtimeRate = $basicSalary / 8;

      if (!empty($userOvertimeHours)) {
          list($hours, $minutes, $seconds) = explode(':', $userOvertimeHours);
          $totalOvertimeHours = $hours + ($minutes / 60) + ($seconds / 3600);
          $overtimeSubtotal = $overtimeRate * $totalOvertimeHours;
          $grossSalary = $subtotal + $overtimeSubtotal;
          $netSalary = $grossSalary - $totalDeduction;
          $totalDeduction = $value1 + $value2 + $value3;

          if ($netSalary < 500) {
              $netSalary = $grossSalary;
$totalDeduction = 0;
          } else {
              $netSalary = $grossSalary - $totalDeduction;
          }
      }
      /*else{

      }*/
 }
            $insertQuery = "INSERT INTO emp_payroll (EMPLOYEE_ID,PAYROLL_START_DATE,PAYROLL_END_DATE,TOTAL_DAYS_WORKED,EMP_GROSS_SALARY,EMP_TOTAL_OVERTIME_HOURS,DEDUCTIONS,EMP_NET_SALARY,PAYROLL_DATE) VALUES ('$userID','$payrollStartDate','$payrollEndDate','$daysWorked','$grossSalary','$totalOvertimeHours','$totalDeduction','$netSalary', '$payrollDate')";

            mysqli_query($this->conn, $insertQuery);

        }

        public function getPayrollRecords($userID)//para ni sa payroll history
        {
            $query = "SELECT * FROM emp_payroll WHERE EMPLOYEE_ID = ?";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            $payrollRecords = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            return $payrollRecords;
        }
        // Inside your LoginUser class
        public function getMostRecentPayrollId($employeeId) {
            $sql = "SELECT MAX(PAYROLL_ID) AS mostRecentPayrollId FROM payroll WHERE EMPLOYEE_ID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $employeeId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                return $row['mostRecentPayrollId'];
            } else {
                // No payroll record found for the employee
                return null;
            }
        }



        public function getPayrollIdRecords($payrollId)//para ni sa view sa individual payroll record
        {
            $query = "SELECT * FROM emp_payroll WHERE EMP_PAYROLL_ID = ?";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $payrollId);
            $stmt->execute();
            $result = $stmt->get_result();
            $payrollRecords = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            return $payrollRecords;
        }

        private function getLoginStatus($userID) {
            $stmt = $this->conn->prepare("SELECT EMP_LOGIN_STATUS FROM emp_dtr WHERE EMPLOYEE_ID = ? AND DATE(EMP_DTR_DATE) = CURDATE() ORDER BY EMP_DTR_DATE DESC LIMIT 1");
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if ($row) {
                return $row['EMP_LOGIN_STATUS'];
            } else {
                return 'Not Logged In';
            }
        }

        private function performTimeout($userID) {
            $loginDetails = $this->getLoginDetails($userID);

            if ($loginDetails && $loginDetails['EMP_LOGIN_STATUS'] === 'Logged In') {

                // Set login status and time
                $logoutStatus = 'Logged Out';

                // Update query with the custom timestamp
                $updateEmpDtrQuery = "UPDATE emp_dtr SET EMP_DTR_TIME_OUT = TIME_FORMAT(NOW(), '%h:%i:%s %p'), EMP_LOGIN_STATUS = ? WHERE EMPLOYEE_ID = ? AND EMP_LOGIN_STATUS = 'Logged In'";

                // Prepare and execute the query
                $stmtUpdate = $this->conn->prepare($updateEmpDtrQuery);
                $stmtUpdate->bind_param("si", $logoutStatus, $userID);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            }
        }



        private function getLoginDetails($userID) {
            // Get the details of the last login for the user
            $stmt = $this->conn->prepare("SELECT EMP_DTR_DATE, EMP_LOGIN_STATUS FROM emp_dtr WHERE EMPLOYEE_ID = ? ORDER BY EMP_DTR_DATE DESC LIMIT 1");
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            return $row;
        }

        private function performLogin($userID) {
            date_default_timezone_set('Asia/Manila');
            $currentDate = date("Y-m-d");
            $lastRecordedDate = $this->getLastRecordedDate($userID);

            // Generate absent records for dates between the last recorded date and the current date
            $this->generateAbsentRecords($userID, $lastRecordedDate, $currentDate);

            $loginStatus = 'Logged In';
            $insertEmpDtrQuery = "INSERT INTO emp_dtr (EMPLOYEE_ID, EMP_DTR_DATE, EMP_DTR_TIME_IN, EMP_DTR_STATUS, EMP_LOGIN_STATUS) VALUES (?, ?, TIME_FORMAT(NOW(), '%r'), 'Present', ?)";
            $stmtEmpDtr = $this->conn->prepare($insertEmpDtrQuery);
            $stmtEmpDtr->bind_param("iss", $userID, $currentDate, $loginStatus);
            $stmtEmpDtr->execute();
            $stmtEmpDtr->close();

            $currentDate = date('j');
            $lastDayOfMonth = date('t');

            if ($currentDate == 14) {
                $payrollStartDate = date('Y-m-01');
                $payrollEndDate = date('Y-m-14');
                $this->generatePayroll($userID,$payrollStartDate,$payrollEndDate);
            } elseif ($currentDate === $lastDayOfMonth) {
                $payrollStartDate = date('Y-m-15');
                $payrollEndDate = date('Y-m-t');
                $this->generatePayroll($userID,$payrollStartDate,$payrollEndDate);
            }
        }

        private function getLastRecordedDate($userID) {
            // Get the last recorded date for the user
            $stmt = $this->conn->prepare("SELECT MAX(EMP_DTR_DATE) AS lastRecordedDate FROM emp_dtr WHERE EMPLOYEE_ID = ?");
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            return isset($row['lastRecordedDate']) ? $row['lastRecordedDate'] : null;
        }

        private function generateAbsentRecords($userID, $startDate, $endDate) {
            $currentDate = $startDate;

            // Loop through dates between the last recorded date and the current date
            while (strtotime($currentDate) < strtotime($endDate)) {
                // Check if a record already exists for the current date
                if (!$this->recordExistsForDate($userID, $currentDate)) {
                    // If not, insert an 'Absent' record
                    $this->insertAbsentRecord($userID, $currentDate);
                }

                // Move to the next date
                $currentDate = date("Y-m-d", strtotime($currentDate . "+1 day"));
            }
        }

        private function recordExistsForDate($userID, $date) {
            // Check if a record already exists for the specified date
            $stmt = $this->conn->prepare("SELECT 1 FROM emp_dtr WHERE EMPLOYEE_ID = ? AND DATE(EMP_DTR_DATE) = ? LIMIT 1");
            $stmt->bind_param("is", $userID, $date);
            $stmt->execute();
            $stmt->store_result();
            $rowCount = $stmt->num_rows;
            $stmt->close();

            return $rowCount > 0;
        }

        private function insertAbsentRecord($userID, $date) {
            // Insert an 'Absent' record for the specified date
            $insertEmpDtrQuery = "INSERT INTO emp_dtr (EMPLOYEE_ID, EMP_DTR_DATE, EMP_DTR_STATUS) VALUES (?, ?, 'Absent')";
            $stmtEmpDtr = $this->conn->prepare($insertEmpDtrQuery);
            $stmtEmpDtr->bind_param("is", $userID, $date);
            $stmtEmpDtr->execute();
            $stmtEmpDtr->close();
        }


        public function getEmpDtrData($userID) {
            $empDtrData = array();

            $sql = "SELECT *,
            DATE_FORMAT(EMP_DTR_TIME_IN, '%h:%i %p') AS EMP_DTR_TIME_IN_FORMATTED,
            DATE_FORMAT(EMP_DTR_TIME_OUT, '%h:%i %p') AS EMP_DTR_TIME_OUT_FORMATTED FROM emp_dtr WHERE EMPLOYEE_ID = ?";
            $stmt = $this->conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $userID);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $empDtrData[] = $row;
                }

                $stmt->close();
            }

            return $empDtrData;
        }

        public function getUserID() {
            return $this->userID;
        }
    }
?>






