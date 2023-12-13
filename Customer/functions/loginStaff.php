<?php 
    include("../../Admin/functions/dbConfig.php");
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../../vendor/autoload.php';


    class LoginStaff extends Connection{
        const REGISTRATION_SUCCESS = 'success';
        const REGISTRATION_NOTSAME = 'not same';
        const REGISTRATION_EMPTY_FIELDS = 'empty_fields';
        const EMAIL_EMPTY_FIELDS = 'Input proper email';
        const REGISTRATION_PASSWORD_LENGTH = 'Not enough length';
        const REGISTRATION_PASSWORD_CASE = 'Not same cases';
        const REGISTRATION_PASSWORD_SPECIAL_CHAR = 'No special character';

        
        private $staffID;
        public function userLogin($username, $staffID){
        
           $result =  mysqli_query($this->conn,"SELECT * FROM emp_personal_info WHERE EMP_USERNAME = '$username' OR EMP_PASSWORD = '$staffID';");
           $row = mysqli_fetch_assoc($result);
            
            if (empty(trim($username)) && empty(trim($staffID))) {
                return self::REGISTRATION_EMPTY_FIELDS;
            } else {
                    if($username == $row["EMP_USERNAME"] && $staffID == $row["EMP_PASSWORD"]){
                        $staffID = $row['EMPLOYEE_ID'];

                       $_SESSION['Staff_ID'] = $staffID ;
                       return self::REGISTRATION_SUCCESS;
                    }else{
                      return self::REGISTRATION_NOTSAME;
                  }
            }
        }


        public function forgotPass($email){
            $mail = new PHPMailer(true);

            try {
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'kwcpenas00251@usep.edu.ph';
                $mail->Password = 'TrashTaste69';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('kwcpenas00251@usep.edu.ph','CampusBites');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $verification_code = substr(number_format(time() * rand(), 0, '', ''),0,6);
                $mail->Subject = 'Forgot Password';
                $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>' ; 
                $mail->send();


                $result =  mysqli_query($this->conn,"SELECT * FROM emp_personal_info WHERE EMP_EMAIL = '$email'");
                $row = mysqli_fetch_assoc($result);

                $empID = $row['EMPLOYEE_ID'];

                $_SESSION['Staff_ID'] = $empID ;
                $_SESSION['code'] = $verification_code;
                    header("location: staff-securitycode.php"); 
                }
                     
                catch(Exception $e) {
                    return self::EMAIL_EMPTY_FIELDS;
                }
        }

        public function changeLoginForgot($newPassword, $confirmPassword, $empID){
            if (empty(trim($newPassword)) || empty(trim($confirmPassword))) {
                return self::REGISTRATION_EMPTY_FIELDS;
            } else {
                // Check if the password is longer than 8 characters
                if (strlen($newPassword) < 8) {
                    return self::REGISTRATION_PASSWORD_LENGTH;
                }
        
                // Check if the password contains both uppercase and lowercase letters
                if (!preg_match('/[A-Z]/', $newPassword) || !preg_match('/[a-z]/', $newPassword)) {
                    return self::REGISTRATION_PASSWORD_CASE;
                }
        
                // Check if the password contains at least one special character
                if (!preg_match('/[\W_]/', $newPassword)) {
                    return self::REGISTRATION_PASSWORD_SPECIAL_CHAR;
                }
        
                if ($newPassword == $confirmPassword) {
                    $result =  mysqli_query($this->conn, "UPDATE emp_personal_info SET EMP_PASSWORD = '$confirmPassword' WHERE EMPLOYEE_ID  = '$empID'");
                    return self::REGISTRATION_SUCCESS;
                } else {
                    return self::REGISTRATION_NOTSAME;
                }
            }
         }


         public function passwordErrorHandler($username, $password, $fname, $mname, $lname, $suffix, $phoneNumber, $gender, $usepEmail, $usepLandmark) {
            if (empty(trim($password))) {
                return self::REGISTRATION_EMPTY_FIELDS;
            } else {
                if (strlen($password) < 8) {
                    return self::REGISTRATION_PASSWORD_LENGTH;
                }
        
                if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password)) {
                    return self::REGISTRATION_PASSWORD_CASE;
                }
        
                if (!preg_match('/[\W_]/', $password)) {
                    return self::REGISTRATION_PASSWORD_SPECIAL_CHAR;
                }
        
                // Insert data into the database
                $stmt = $this->conn->prepare("INSERT INTO users (U_FIRST_NAME, U_MIDDLE_NAME, U_LAST_NAME, U_USER_NAME, U_PASSWORD, U_SUFFIX, U_PHONE_NUMBER, U_GENDER, U_EMAIL, U_CAMPUS_AREA, U_STATUS) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')");
                $stmt->bind_param('ssssssssss', $fname, $mname, $lname, $username, $password, $suffix, $phoneNumber, $gender, $usepEmail, $usepLandmark);

        
                if ($stmt->execute()) {
                    $lastInsertedId = mysqli_insert_id($this->conn);
        
                    $insertRoles = "INSERT INTO user_roles (ROLE_CODE, USER_ID) VALUES ('cstmr', '$lastInsertedId')";
                    mysqli_query($this->conn, $insertRoles);
        
                    return self::REGISTRATION_SUCCESS;
                } else {
                    return "Database error";
                }
            }
        }
        
         
    }
?>


