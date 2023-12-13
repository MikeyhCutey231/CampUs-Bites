<?php

class MyClass {
        const REGISTRATION_EMPTY_FIELDS = 'empty_fields';
        const REGISTRATION_SUCCESS = 'success';
        const REGISTRATION_NOT_SAME = 'not_same';
    
        protected $conn;
    
        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
        }
    
        public function handleRegistration($userData)
        {
            // Extract user data
            $fname = $userData['fname'] ?? '';
            $lname = $userData['lname'] ?? '';
            $suffix = $userData['suffix'] ?? '';
            $phoneNumber = $userData['phoneNumber'] ?? '';
            $gender = $userData['gender'] ?? '';
            $usepEmail = $userData['usepEmail'] ?? '';
            $usepLandmark = $userData['usepLandmark'] ?? '';
    
            // Check if any required field is empty
            if (empty($fname) || empty($lname) || empty($suffix) || empty($phoneNumber) || empty($gender) || empty($usepEmail) || empty($usepLandmark)) {
                return ['status' => 'error', 'message' => 'All fields are required.'];
            }
    
            // Check if the email domain is '@usep.edu.ph'
            if ($this->isValidEmailDomain($usepEmail)) {
                try {
                    // Check if the email already exists in the database
                    if ($this->isEmailAlreadyExists($usepEmail)) {
                        return ['status' => 'error', 'message' => 'This email already has an account.'];
                    }
    
                    // Store user information in session
                    $_SESSION['user_info'] = $userData;
    
                    // Redirect to a new PHP file
                    return ['status' => 'success', 'message' => 'Registration successful', 'redirect' => '../customer_html/customer_createAccount.php'];
                } catch (Exception $e) {
                    return ['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()];
                }
            } else {
                return ['status' => 'error', 'message' => 'Invalid email domain. Only "@usep.edu.ph" domain is allowed.'];
            }
        }
    
        protected function isValidEmailDomain($email)
        {
            // Check if the email domain is '@usep.edu.ph'
            return endsWith($email, '@usep.edu.ph');
        }

        function endsWith($haystack, $needle)
        {
            $length = strlen($needle);
            if ($length == 0) {
                return true;
            }

            return (substr($haystack, -$length) === $needle);
        }


        protected function isEmailAlreadyExists($email)
        {
            // Check if the email already exists in the database
            $checkEmailQuery = "SELECT U_EMAIL FROM users WHERE U_EMAIL = ?";
            $checkEmailStmt = $this->conn->prepare($checkEmailQuery);
            $checkEmailStmt->bind_param('s', $email);
            $checkEmailStmt->execute();
            $checkEmailResult = $checkEmailStmt->get_result();
    
            return $checkEmailResult->num_rows > 0;
        }


        
    }
