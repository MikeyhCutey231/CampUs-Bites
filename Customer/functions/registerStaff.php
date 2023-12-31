<?php
include("../../Admin/functions/dbConfig.php");
// This file is generated by Composer
require_once '../../vendor/autoload.php';

$dbConnection = new Connection();
$conn = $dbConnection->conn;

$client = new QuickEmailVerification\Client('84d3ad00435ab4b916a0b90a2f9f1cb1783f8345122e97925fd48ca261a1');
$quickemailverification = $client->quickemailverification();

// Check if all necessary POST parameters are set
if (
    isset($_POST['fname'], $_POST['mname'], $_POST['lname'], $_POST['suffix'],
    $_POST['phoneNumber'], $_POST['gender'],
    $_POST['usepEmail'], $_POST['usepLandmark'])
) {
    // Collect POST data
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $suffix = $_POST['suffix'];
    $phoneNumber = $_POST['phoneNumber'];
    $gender = $_POST['gender'];
    $usepEmail = $_POST['usepEmail'];
    $usepLandmark = $_POST['usepLandmark'];

    // Check for empty values
    if (empty($fname) || empty($lname) || empty($suffix) || empty($phoneNumber) || empty($gender) || empty($usepEmail) || empty($usepLandmark)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Check if the email domain is '@usep.edu.ph'
    if (endsWith($usepEmail, '@usep.edu.ph')) {
        try {
            // Check if the email already exists in the database
            $checkEmailQuery = "SELECT U_EMAIL FROM users WHERE U_EMAIL = ?";
            $checkEmailStmt = $conn->prepare($checkEmailQuery);
            $checkEmailStmt->bind_param('s', $usepEmail);
            $checkEmailStmt->execute();
            $checkEmailResult = $checkEmailStmt->get_result();

            if ($checkEmailResult->num_rows > 0) {
                // Email already exists, return an error message
                echo json_encode(['status' => 'error', 'message' => 'This email already has an account.']);
            } else {
                // PRODUCTION MODE
                $response = $quickemailverification->verify($usepEmail);

                // Check if the email is valid
                if ($response->body['result'] === 'valid') {
                    // Store user information in session
                  
                    $_SESSION['user_info'] = [
                        'fname' => $fname,
                        'mname' => $mname,
                        'lname' => $lname,
                        'suffix' => $suffix,
                        'phoneNumber' => $phoneNumber,
                        'gender' => $gender,
                        'usepEmail' => $usepEmail,
                        'usepLandmark' => $usepLandmark,
                    ];

                    // Redirect to a new PHP file
                    echo json_encode(['status' => 'success', 'message' => 'Registration successful', 'redirect' => '../customer_php/customer_createAccount.php']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'This email does not exist in UseP Information']);
                }
            }
            
            $checkEmailStmt->close();
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email domain. Only "@usep.edu.ph" domain is allowed.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

// Function to check if a string ends with a specific suffix
function endsWith($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
}
?>
