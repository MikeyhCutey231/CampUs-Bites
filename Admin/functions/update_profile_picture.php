<?php
header('Access-Control-Allow-Origin: *');

require("../../Admin/functions/dbConfig.php");

$database = new Connection();
$conn = $database->conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminID = $_POST['adminID'];

    if (isset($_FILES['profilePic']['name']) && is_array($_FILES['profilePic']['name'])) {
        $fileCount = count($_FILES['profilePic']['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $prodPic = $_FILES['profilePic']['name'][$i];
            $imageTmpName = $_FILES['profilePic']['tmp_name'][$i];
            $targetPath = '../../Icons/' . $prodPic;

            if (move_uploaded_file($imageTmpName, $targetPath)) {
                $query = "UPDATE users SET U_PICTURE = ? WHERE USER_ID = ?";

                // Using prepared statements for security
                $stmt = $conn->prepare($query);
                $stmt->bind_param('si', $prodPic, $adminID);

                if ($stmt->execute()) {
                    $rec_success = "Updated";
                } else {
                    $rec_error = "Something went wrong: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $rec_error = "Error moving the profile picture to the target directory.";
            }
        }

        if (isset($rec_success)) {
            echo json_encode(['status' => 'success', 'message' => 'Profile picture updated successfully.']);
        } elseif (isset($rec_error)) {
            echo json_encode(['status' => 'error', 'message' => $rec_error]);
        }
    }
}
?>
