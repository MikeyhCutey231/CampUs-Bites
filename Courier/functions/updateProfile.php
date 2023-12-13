<?php
include("../functions/courierLogin.php");
$database = new Connection();
$conn = $database->conn;

$userID = $_SESSION['USER_ID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $suffix = $_POST['suffix'];
    $gender = $_POST['gender'];
    $phoneNumber = $_POST['phoneNumber'];
    $campusArea = $_POST['campusArea'];

    $imageFile = $_FILES['imageFile'];
    $uploadDirectory = 'C:/wamp64/www/CampUs Bites/Icons/';

    
    if ($imageFile['size'] > 0) {
        $targetFilePath = $uploadDirectory . basename($imageFile['name']);

        
        if (move_uploaded_file($imageFile['tmp_name'], $targetFilePath)) {

            $imageName = basename($targetFilePath);
            
            $updateProf = "UPDATE users SET U_FIRST_NAME = '$firstName', U_MIDDLE_NAME = '$middleName', U_LAST_NAME = '$lastName', U_SUFFIX = '$suffix', U_GENDER = '$gender', U_PHONE_NUMBER = '$phoneNumber', U_CAMPUS_AREA = '$campusArea', U_PICTURE = '$imageName'
            WHERE USER_ID = '$userID'";
            mysqli_query($conn, $updateProf);

            $successMessage = 'Profile updated successfully!';
        } else {
            $errorMessage = 'Error uploading image.';
        }
    } else {
        $updateProf = "UPDATE users SET U_FIRST_NAME = '$firstName', U_MIDDLE_NAME = '$middleName', U_LAST_NAME = '$lastName', U_SUFFIX = '$suffix', U_GENDER = '$gender', U_PHONE_NUMBER = '$phoneNumber', U_CAMPUS_AREA = '$campusArea'
        WHERE USER_ID = '$userID'";
        mysqli_query($conn, $updateProf);

        $successMessage = 'Profile updated successfully!';
    }
} else {
    
    $errorMessage = 'Invalid request.';
}

if (isset($successMessage)) {
    echo $successMessage;
} elseif (isset($errorMessage)) {
    echo $errorMessage;
} else {
    echo 'Invalid request.';
}

?>
