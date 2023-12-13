<?php
include("../../Admin/functions/dbConfig.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
class loginStaff extends Connection{
    const REGISTRATION_SUCCESS = 'success';
    const REGISTRATION_NOTSAME = 'not same';
    const REGISTRATION_EMPTY_FIELDS = 'empty_fields';
    const EMAIL_EMPTY_FIELDS = 'Input proper email';
    const REGISTRATION_PASSWORD_LENGTH = 'Not enough length';
    const REGISTRATION_PASSWORD_CASE = 'Not same cases';
    const REGISTRATION_PASSWORD_SPECIAL_CHAR = 'No special character';
    private $adminID;

    public function userLogin($enteredUsername, $enteredPassword) {
        if (empty($enteredUsername) || empty($enteredPassword)) {
            return self::REGISTRATION_EMPTY_FIELDS;
        }

        $sql = "SELECT users.USER_ID, users.U_PASSWORD, users.U_FIRST_NAME FROM users JOIN user_roles ON users.USER_ID = user_roles.USER_ID WHERE users.U_USER_NAME = ? AND user_roles.ROLE_CODE = 'emp_cshr'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $enteredUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['U_PASSWORD'];

            if ($enteredPassword == $storedPassword) {
                $this->adminID = $row['USER_ID'];
                $staffName = $row['U_FIRST_NAME'];

                session_start();
                $_SESSION['USER_ID'] = $this->adminID;
                $_SESSION['Staff_Name'] = $staffName;
                return self::REGISTRATION_SUCCESS;
            } else {
                return self::REGISTRATION_NOTSAME;
            }
        } else {
            return self::EMAIL_EMPTY_FIELDS;
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
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                    'ciphers' => 'TLSv1.2', // Set the appropriate version, e.g., 'TLSv1.2'
                ),
            );
            $mail->Port = 587;
            $mail->setFrom('kwcpenas00251@usep.edu.ph','CampusBites');
            $mail->addAddress($email);
            $mail->isHTML(true);

            // Check if a verification code is already set in the session
            if (!isset($_SESSION['code'])) {
                $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                session_start();
                $_SESSION['code'] = $verification_code;
            }

            $mail->Subject = 'Forgot Password';
            $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $_SESSION['code'] . '</b></p>';
            $mail->send();

            header("location: ../admin_php/admin-securitycode.php");
        } catch (Exception $e) {
            return self::EMAIL_EMPTY_FIELDS;
        }
    }

    public function changeLoginForgot($newPassword, $confirmPassword) {
        if (empty(trim($newPassword)) || empty(trim($confirmPassword))) {
            return self::REGISTRATION_EMPTY_FIELDS;
        } else {
            if (strlen($newPassword) < 8) {
                return self::REGISTRATION_PASSWORD_LENGTH;
            }

            if (!preg_match('/[A-Z]/', $newPassword) || !preg_match('/[a-z]/', $newPassword)) {
                return self::REGISTRATION_PASSWORD_CASE;
            }

            if (!preg_match('/[\W_]/', $newPassword)) {
                return self::REGISTRATION_PASSWORD_SPECIAL_CHAR;
            }

            if ($newPassword == $confirmPassword) {
                $hashedPassword = password_hash($confirmPassword, PASSWORD_DEFAULT);

                $stmt = $this->conn->prepare("UPDATE admin_info SET Password = ?");
                $stmt->bind_param("s", $hashedPassword);

                if ($stmt->execute()) {
                    return self::REGISTRATION_SUCCESS;
                } else {
                    return "Database error";
                }
            } else {
                return self::REGISTRATION_NOTSAME;
            }
        }
    }

    public function getAdminID() {
        return $this->adminID;
    }



    public function staffSwitch($enteredUsername) {
        $sql = "SELECT users.USER_ID, users.U_PASSWORD, users.U_FIRST_NAME FROM users JOIN user_roles ON users.USER_ID = user_roles.USER_ID WHERE users.U_FIRST_NAME = ? AND user_roles.ROLE_CODE = 'emp_cshr'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $enteredUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

                $this->adminID = $row['USER_ID'];
                $staffName = $row['U_FIRST_NAME'];

                session_start();
                $_SESSION['USER_ID'] = $this->adminID;
                $_SESSION['Staff_Name'] = $staffName;
                return self::REGISTRATION_SUCCESS;
        } else {
            
        }
    }
}

?>
