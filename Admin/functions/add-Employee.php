<?php
include("../../Admin/functions/dbConfig.php");
include '../../Admin/functions/Admin_Manager_logs.php';
$logger = new Admin_Manager_logs();

$database = new Connection();
$conn = $database->conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userid']; // admin/manager ni nga id
    $username = $_POST['username'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $suffix = $_POST['suffix'];
    $gender = $_POST['gender'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $position = $_POST['position'];

    $selectAllUser = "SELECT U_EMAIL FROM users WHERE U_EMAIL = '$email'";
    $allUserRun = mysqli_query($conn, $selectAllUser);

    $duplicateFound = false;

    while ($row = mysqli_fetch_array($allUserRun)) {
        $dbEmail = $row['U_EMAIL'];

        if ($email == $dbEmail) {
            $duplicateFound = true;
            break; // Exit the loop immediately if duplicate is found
        }else{
            $duplicateFound = false;
        }
    }

    if ($duplicateFound == false) {
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
                                echo '<script>window.history.back();</script>';
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
                    echo '<script>window.history.back();</script>';
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
        echo '<script>window.history.back();</script>';

    } else {
        echo '<script> window.history.back();</script>';
    }
}
?>
