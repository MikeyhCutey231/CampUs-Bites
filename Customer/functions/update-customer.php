<?php
session_start();
require '../../Admin/functions/dbConfig.php';
$database = new Connection();
$conn = $database->conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newFname = $_POST["fname"];
    $newMname = $_POST["mname"];
    $newLname = $_POST["lname"];
    $newEmail = $_POST["email"];
    $newPhoneNum = $_POST["phoneNum"];
    $newSuffix = $_POST["suffix"];
    $newGender = $_POST["gender"];
    $newArea = $_POST["area"];

    $sql = "UPDATE users SET ";
    $bind_types = "";
    $bind_params = array();

    if (!empty($newFname)) {
        $sql .= "U_FIRST_NAME = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$newFname;
    }

    if (!empty($newMname)) {
        $sql .= "U_MIDDLE_NAME = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$newMname;
    }

    if (!empty($newLname)) {
        $sql .= "U_LAST_NAME = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$newLname;
    }


    if (!empty($newEmail)) {
        $sql .= "U_EMAIL = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$newEmail;
    }

    if (!empty($newPhoneNum)) {
        $sql .= "U_PHONE_NUMBER = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$newPhoneNum;
    }

    if (!empty($newSuffix)) {
        $sql .= "U_SUFFIX = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$newSuffix;
    }
    if (!empty($newGender)) {
        $sql .= "U_GENDER = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$newGender;
    }
    if (!empty($newArea)) {
        $sql .= "U_CAMPUS_AREA = ?, ";
        $bind_types .= "s";
        $bind_params[] = &$newArea;
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
        header("Location: ../customer_html/customer-profile.php");
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}

?>
