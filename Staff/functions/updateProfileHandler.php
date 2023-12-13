<?php
include("../../Admin/functions/dbConfig.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $targetDirectory = 'C:/wamp64/www/CampUs Bites/Icons/'; 
        $targetFileName = $targetDirectory . basename($_FILES['profileImage']['name']);


        $fileType = strtolower(pathinfo($targetFileName, PATHINFO_EXTENSION));
        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFileName)) {
                $fileName = basename($targetFileName);

                $dbConnection = new Connection();
                $conn = $dbConnection->conn;

                $staffID = $_SESSION['USER_ID'];
                $updateQuery = "UPDATE users SET U_PICTURE = ? WHERE USER_ID = ?";

                $stmt = $conn->prepare($updateQuery);
                if ($stmt) {
                    $stmt->bind_param('si', $fileName, $staffID);

                    if ($stmt->execute()) {
                        echo 'success';
                    } else {
                        echo 'Error updating profile image in the database: ' . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo 'Error preparing the SQL statement: ' . $conn->error;
                }

                $conn->close();
            } else {
                echo 'Error uploading the file.';
            }
        } else {
            echo 'Invalid file type. Please upload an image (jpg, jpeg, png, or gif).';
        }
    } else {
        echo 'Error in file upload.';
    }
} else {
    echo 'Invalid request method.';
}
?>
