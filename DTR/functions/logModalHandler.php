<?php
require '../../config.php';

$dbConnection = new Database();
$conn = $dbConnection->conn;

if (isset($_POST["username"], $_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM emp_personal_info WHERE EMP_USERNAME = ? AND EMP_PASSWORD = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if (empty(trim($username)) || empty(trim($password))) {
        echo "Kindly, fill in all the inputs";
    } elseif ($result->num_rows == 0) {
        echo "Kindly input your proper credentials";
    } else {
        $row = $result->fetch_assoc();
        if ($row["EMP_USERNAME"] === $username && $row["EMP_PASSWORD"] === $password) {
            $_SESSION['username'] = $row['EMPLOYEE_ID'];
            echo "success";
        } else {
            echo "Kindly input your proper credentials";
        }
    }

    $stmt->close();

}
?>
