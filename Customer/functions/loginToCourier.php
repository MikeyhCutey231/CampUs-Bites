<?php 
include ("../../Staff/functions/loginStaff.php");

$database = new Connection();
$conn = $database->conn;

if (isset($_POST['action']) && $_POST['action'] === 'activateCourier' && $_POST['profName']) {
    $enteredUsername = $_POST['profName']; // Add this line to get the profName from AJAX
    
    $sql = "SELECT users.USER_ID, users.U_PASSWORD, users.U_FIRST_NAME FROM users JOIN user_roles ON users.USER_ID = user_roles.USER_ID WHERE users.U_FIRST_NAME = ? AND user_roles.ROLE_CODE = 'cour'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $enteredUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        $userID = $row['USER_ID'];
        $staffName = $row['U_FIRST_NAME'];

        $_SESSION['USER_ID'] = $userID;
        $_SESSION['Courier_Name'] = $staffName;

        echo json_encode(['status' => 'success']);
        header('Content-Type: application/json');
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or role']);
    }

    // Always set the content type, even in case of error
   
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>
