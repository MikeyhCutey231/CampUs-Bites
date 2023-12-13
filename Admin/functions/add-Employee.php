<?php
include("../../Admin/functions/dbConfig.php");
include '../../Admin/functions/Admin_Manager_logs.php';
$logger = new Admin_Manager_logs();

$database = new Connection();
$conn = $database->conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userid'];//admin/manager ni nga id
    $username = $_POST['username'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $suffix = $_POST['suffix'];
    $gender = $_POST['gender'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $position = $_POST['position'];

    $checkDuplicateQuery = "SELECT USER_ID FROM users 
                            WHERE U_LAST_NAME = ? AND U_FIRST_NAME = ? AND U_MIDDLE_NAME = ? 
                            AND U_SUFFIX = ? AND U_GENDER = ? AND U_PHONE_NUMBER = ? AND U_EMAIL = ? AND U_USER_NAME = ?";

    $stmtCheckDuplicate = $conn->prepare($checkDuplicateQuery);
    $stmtCheckDuplicate->bind_param("ssssssss", $lastName, $firstName, $middleName, $suffix, $gender, $phone_number, $email, $username);
    $stmtCheckDuplicate->execute();
    $stmtCheckDuplicate->store_result();

    if ($stmtCheckDuplicate->num_rows > 0) {
        $rec_error = "An exact copy of the user already exists.";
    } else {
        // Insert into users table
        $hashedPassword = password_hash($phone_number, PASSWORD_DEFAULT);

        $insertUserQuery = "INSERT INTO users (U_USER_NAME,U_PASSWORD,U_LAST_NAME, U_FIRST_NAME, U_MIDDLE_NAME, U_SUFFIX, U_GENDER, U_PHONE_NUMBER, U_EMAIL) VALUES (?,?, ?, ?, ?, ?, ?,?, ?)";

        $stmt = $conn->prepare($insertUserQuery);
        $stmt->bind_param("sssssssss", $username,$hashedPassword,$lastName, $firstName, $middleName, $suffix, $gender, $phone_number, $email);

        if ($stmt->execute()) {
            $lastUserId = $conn->insert_id;

            $insertUserRoleQuery = "INSERT INTO user_roles (USER_ID, ROLE_CODE) VALUES (?, ?)";

            $stmtUserRole = $conn->prepare($insertUserRoleQuery);
            $stmtUserRole->bind_param("is", $lastUserId, $position);

            if ($stmtUserRole->execute()) {
                if (!empty($_FILES['image']['name'][0])) {
                    $fileCount = count($_FILES['image']['name']);

                    for ($i = 0; $i < $fileCount; $i++) {
                        $empPic = $_FILES['image']['name'][$i];
                        $imageTmpName = $_FILES['image']['tmp_name'][$i];
                        $targetPath = '../../Icons/' . $empPic;

                        if (move_uploaded_file($imageTmpName, $targetPath)) {
                            $updateUserQuery = "UPDATE users SET U_PICTURE = ? WHERE USER_ID = ?";
                            $stmtUpdateUser = $conn->prepare($updateUserQuery);
                            $stmtUpdateUser->bind_param("si", $empPic, $lastUserId);

                            if ($stmtUpdateUser->execute()) {
                                $logger->logAddEmployee($userId, $lastUserId, $username);
                                $rec_success = "Updated";
                                header("location:  ../../Admin/admin_php/admin-viewEmployee.php?employee_id=" . $lastUserId);
                            } else {
                                $rec_error = "Something went wrong: " . $conn->error;
                            }

                            $stmtUpdateUser->close();
                        } else {
                            $rec_error = "Error moving the profile picture to the target directory.";
                        }
                    }
                }else{
                    $logger->logAddEmployee($userId, $lastUserId, $username);
                    $rec_success = "Added";
                    header("location: ../../Admin/admin_php/admin-viewEmployee.php?employee_id=" . $lastUserId);
                }
            }
            else {
                $rec_error = "Error inserting into user_roles table: " . $conn->error;
            }

            $stmtUserRole->close();
        } else {
            $rec_error = "Error inserting into users table: " . $conn->error;
        }

        $stmt->close();
    }

    $stmtCheckDuplicate->close();
}
?>
