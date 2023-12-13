<?php
session_start();
require 'config.php';
$database = new Database();
$conn = $database->conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST["username"];
    $new_fullname = $_POST["fullname"];
    $new_email = $_POST["email"];
    $new_phoneNumber = $_POST["phoneNum"];

    $sql = "UPDATE users SET ";
    $bind_types = "";
    $bind_params = array();

    if (!empty($new_username)) {
        $sql .= "U_USER_NAME = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$new_username;
    }

    $nameComponents = explode(' ', $new_fullname);
    $new_firstname = isset($nameComponents[0]) ? trim($nameComponents[0]) : '';
    $new_middlename = isset($nameComponents[1]) ? trim($nameComponents[1]) : '';
    $new_lastname = isset($nameComponents[2]) ? trim($nameComponents[2]) : '';

    if (!empty($new_firstname)) {
        $sql .= "U_FIRST_NAME = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$new_firstname;
    }

    if (!empty($new_middlename)) {
        $sql .= "U_MIDDLE_NAME = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$new_middlename;
    }

    if (!empty($new_lastname)) {
        $sql .= "U_LAST_NAME = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$new_lastname;
    }


    if (!empty($new_email)) {
        $sql .= "U_EMAIL = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$new_email;
    }

    if (!empty($new_phoneNumber)) {
        $sql .= "U_PHONE_NUMBER = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$new_phoneNumber;
    }

    $sql = rtrim($sql, ", ");

    $sql .= " WHERE USER_ID = ?";

    $bind_types .= "i";
    $bind_params[] = &$_SESSION['USER_ID'];

    $stmt = $conn->prepare($sql);

    $bind_params = array_merge(array($bind_types), $bind_params);
    call_user_func_array(array($stmt, 'bind_param'), $bind_params);

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
        header("Location: ../admin_php/admin-profile.php");
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}

?>
