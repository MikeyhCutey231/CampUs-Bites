<?php

require_once("../../Admin/functions/dbConfig.php");
include '../../Admin/functions/Admin_Manager_logs.php';
$logger = new Admin_Manager_logs();

$database = new Connection();
$conn = $database->conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userid'];//admin/manager ni nga id

    $username = $_POST['username'];
    $employee_id = $_POST['employee_id'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $suffix = $_POST['suffix'];
    $gender = $_POST['gender'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $position = $_POST['position'];
/*    $basic_salary = $_POST['basic_salary'];*/

    /*if (!is_null($position)) {
        $positionUpdateQuery = "UPDATE user_roles SET ROLE_CODE = ? WHERE USER_ID = ?";
        $positionUpdateStmt = $conn->prepare($positionUpdateQuery);
        $positionUpdateStmt->bind_param("si", $position, $employee_id);

        if ($positionUpdateStmt->execute()) {
            $logger->logUpdateEmployee($userId, $employee_id, $username);
            header("location: ../admin_php/admin-viewEmployee.php?employee_id=" . $employee_id);
        }
        $positionUpdateStmt->close();
    }*/
/*
    if (!is_null($basic_salary)) {
        $salaryUpdateQuery = "UPDATE emp_job_info SET EMP_BASIC_SALARY = ? WHERE USER_ID = ?";
        $salaryUpdateStmt = $conn->prepare($salaryUpdateQuery);
        $salaryUpdateStmt->bind_param("di", $basic_salary, $employee_id);

        if ($salaryUpdateStmt->execute()) {
        }
        $salaryUpdateStmt->close();
    }*/

    if (!empty($_FILES['image']['name'][0])) {
        $fileCount = count($_FILES['image']['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $empPic = $_FILES['image']['name'][$i];
            $imageTmpName = $_FILES['image']['tmp_name'][$i];
            $targetPath = '../../Icons/' . $empPic;

            if (move_uploaded_file($imageTmpName, $targetPath)) {
                $query = "UPDATE users SET U_PICTURE = '$empPic' WHERE USER_ID = '$employee_id'";
                $result = $conn->query($query);

                if ($result) {
                    $rec_success = "Updated";
                    $logger->logUpdateEmployee($userId, $employee_id, $username);
                    header("location: ../../Admin/admin_php/admin-viewEmployee.php?employee_id=" . $employee_id);
                } else {
                    $rec_error = "Something went wrong: " . $conn->error;
                }
            } else {
                $rec_error = "Error moving the profile picture to the target directory.";
            }
        }
    } else {
        $sql = "UPDATE users SET ";
        $bind_types = "";
        $bind_params = array();

        if (!empty($lastName)) {
            $sql .= "U_LAST_NAME = ?, ";
            $bind_types .= "s";
            $bind_params[] = &$lastName;
        }
        if (!empty($firstName)) {
            $sql .= "U_FIRST_NAME = ?, ";
            $bind_types .= "s";
            $bind_params[] = &$firstName;
        }
        if (!empty($middleName)) {
            $sql .= "U_MIDDLE_NAME = ?, ";
            $bind_types .= "s";
            $bind_params[] = &$middleName;
        }
        if (!empty($suffix)) {
            $sql .= "U_SUFFIX = ?, ";
            $bind_types .= "s";
            $bind_params[] = &$suffix;
        }
        if (!empty($gender)) {
            $sql .= "U_GENDER = ?, ";
            $bind_types .= "s";
            $bind_params[] = &$gender;
        }
        if (!empty($phone_number)) {
            $sql .= "U_PHONE_NUMBER = ?, ";
            $bind_types .= "s";
            $bind_params[] = &$phone_number;
        }
        if (!empty($email)) {
            $sql .= "U_EMAIL = ?, ";
            $bind_types .= "s";
            $bind_params[] = &$email;
        }

        $sql = rtrim($sql, ", ");
        $sql .= " WHERE USER_ID = ?";
        $bind_types .= "i";
        $bind_params[] = &$employee_id;

        $stmt = $conn->prepare($sql);

        $bind_params = array_merge(array($bind_types), $bind_params);
        call_user_func_array(array($stmt, 'bind_param'), $bind_params);

        if ($stmt->execute()) {
            $logger->logUpdateEmployee($userId, $employee_id, $username);
            $rec_success = "Employee updated successfully.";
            header("location: ../../Admin/admin_php/admin-viewEmployee.php?employee_id=" . $employee_id);
        } else {
            $rec_error = "Error updating employee: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>
