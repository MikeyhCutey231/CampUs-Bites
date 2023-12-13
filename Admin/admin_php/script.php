<?php
include '../../config.php';
$database_connection = new Database();
$conn = $database_connection->conn;

$rec_error = '';
$rec_success = '';
$validate_err = '';
$validate_suc = '';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    if (isset($_POST['record'])) {
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];

        // Check if the username already exists
        $check_query = "SELECT U_USER_NAME FROM users WHERE U_USER_NAME = '$username'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            $rec_error = "Username already exists. Please choose a different username.";
        } else {
            if (!empty($_FILES['profilePic']['name'][0])) {
                $fileCount = count($_FILES['profilePic']['name']);

                for ($i = 0; $i < $fileCount; $i++) {
                    $profilePic = $_FILES['profilePic']['name'][$i];
                    $imageTmpName = $_FILES['profilePic']['tmp_name'][$i];
                    $targetPath = 'upload/' . $profilePic;

                    if (move_uploaded_file($imageTmpName, $targetPath)) {
                        $password = $_POST['password'];
                        $encrypt_pass = password_hash($password, PASSWORD_DEFAULT);

                        $query = "INSERT INTO users (U_USER_NAME, U_PASSWORD, U_PICTURE)
                VALUES('$username', '$encrypt_pass','$profilePic')";
                        $result = $conn->query($query);

                        if ($result) {
                            $rec_success = "Added";
                        } else {
                            $rec_error = "Something went wrong: " . $conn->error;
                        }
                    }
                    else {
                            $rec_error = "Error moving the profile picture to the target directory.";
                        }
                }
            } else {
                    $rec_error = "No file was selected.";
               }
    }
    }

    elseif (isset($_POST['login'])) {
        $username = $_POST['username'];
        $raw_pass = $_POST['password'];

        $query = "SELECT password FROM admin_info WHERE username = '$username'";
        $result = $conn->query($query);

        $hashed_pass = $result->fetch_assoc()['password'];

        if (!password_verify($raw_pass, $hashed_pass)) {
            $validate_err = "Wrong password";
        } else {
            header("Location: admin-dashboard.php");
        }
    }
}
?>
