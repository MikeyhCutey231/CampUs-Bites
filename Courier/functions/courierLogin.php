<?php
require("../../Admin/functions/dbConfig.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
class loginCourier extends Connection{
    const REGISTRATION_SUCCESS = 'success';
    const REGISTRATION_NOTSAME = 'not same';
    const REGISTRATION_EMPTY_FIELDS = 'empty_fields';
    const EMAIL_EMPTY_FIELDS = 'Input proper email';
    const REGISTRATION_PASSWORD_LENGTH = 'Not enough length';
    const REGISTRATION_PASSWORD_CASE = 'Not same cases';
    const REGISTRATION_PASSWORD_SPECIAL_CHAR = 'No special character';
    const REGISTRATION_EMAIL_NOTSAME = 'Not same';
    private $adminID;

    public function userLogin($enteredUsername, $enteredPassword) {
        if (empty($enteredUsername) || empty($enteredPassword)) {
            return self::REGISTRATION_EMPTY_FIELDS;
        }
    
        $sql = "SELECT users.USER_ID, users.U_PASSWORD, users.U_FIRST_NAME FROM users JOIN user_roles ON users.USER_ID = user_roles.USER_ID WHERE users.U_USER_NAME = ? AND user_roles.ROLE_CODE = 'cour' AND
        users.U_STATUS = 'Active'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $enteredUsername);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['U_PASSWORD'];
    
            // Use password_verify to compare entered password with stored hashed password
            if (password_verify($enteredPassword, $storedPassword)) {
                $this->adminID = $row['USER_ID'];
                $staffName = $row['U_FIRST_NAME'];
    
                session_start();
                $_SESSION['USER_ID'] = $this->adminID;
                $_SESSION['Courier_Name'] = $staffName;
    
                return self::REGISTRATION_SUCCESS;
            } else {
                return self::REGISTRATION_NOTSAME;
            }
        } else {
            return self::REGISTRATION_NOTSAME;
        }
    }
    
    

    public function forgotPass($email){
        $compare = "SELECT users.U_EMAIL FROM users 
                        INNER JOIN user_roles ON users.USER_ID = user_roles.USER_ID
                        WHERE user_roles.ROLE_CODE = 'cour';";
        
            $compareRun = mysqli_query($this->conn, $compare);
        
            while($row = mysqli_fetch_assoc($compareRun)){
                if($email == $row['U_EMAIL']){
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
                            $_SESSION['email'] = $email;
                            $_SESSION['code'] = $verification_code;
                        }
        
                        $mail->Subject = 'Forgot Password';
                        $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $_SESSION['code'] . '</b></p>';
                        $mail->send();
        
                        return self::REGISTRATION_SUCCESS;
                    } catch (Exception $e) {
                        return self::REGISTRATION_NOTSAME;
                    }
                }
            }
            return self::REGISTRATION_EMAIL_NOTSAME;     
    }

    public function changeLoginForgot($newPassword, $confirmPassword) {
        $email = $_SESSION['email'];
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

                $stmt = $this->conn->prepare("UPDATE users SET U_PASSWORD = ? WHERE U_EMAIL = ?");
                $stmt->bind_param("ss", $hashedPassword, $email);

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


    public function userInfo() {
        $userID = $_SESSION['USER_ID'];
        $employeeData = array();
    
        $personalInfo = "SELECT U_FIRST_NAME, U_MIDDLE_NAME, U_LAST_NAME, U_SUFFIX, U_GENDER, U_PHONE_NUMBER, U_EMAIL, U_CAMPUS_AREA, U_PICTURE FROM users where USER_ID = '$userID'";
    
        $stmt = $this->conn->prepare($personalInfo);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($fname, $mname, $lname, $suffix, $gender, $phoneNumber, $email, $campusArea, $picturee);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $employeeData[] = [
                    'U_FIRST_NAME' => $fname,
                    'U_MIDDLE_NAME' => $mname,
                    'U_LAST_NAME' => $lname,
                    'U_SUFFIX' => $suffix,
                    'U_GENDER' => $gender,
                    'U_PHONE_NUMBER' => $phoneNumber,
                    'U_EMAIL' => $email,
                    'U_CAMPUS_AREA' => $campusArea,
                    'U_PICTURE' => $picturee,
                ];
            }
    
            $stmt->close();
        }
    
        return $employeeData;
    }
    
    public function renderUserInfo($employeeData) {
        $userID = $_SESSION['USER_ID'];
        foreach ($employeeData as $row) {
            ?>
            <div class="userSettings-container">
                    <img src="../../Icons/<?php echo $row['U_PICTURE'] ?>" alt="" class="userProfile">
                    <h5 class="settingUsername">Michael C. Labastida</h5>
                    <p class="userJob">Cashier</p>

                    <button class="edit-info" data-bs-toggle="modal" data-bs-target="#editInfo"><img src="/Icons/edit.svg" alt="" style="padding-right: 10px; margin-top: -3px;">Edit Information</button>
                    <button class="changepass" data-bs-toggle="modal" data-bs-target="#editpassword">Change Password</button>


                    <button class="disable-account"  data-bs-toggle="modal" data-bs-target="#disableAccount">Disable Account</button>
                </div>
                <div class="userInformation-container">
                    <div class="personalInformation-container">
                        <div class="pInformation-head">
                           <h4>Personal Information</h4>
                        </div>
                        <div class="pInformation-content">
                        <div class="cardDetails">
                            <div class="userFname">
                                <p style="margin-right: 40px;">First Name</p>
                                <p style="font-weight: bold;"><?php echo $row['U_FIRST_NAME'] ?></p>
                            </div>

                            <div class="userEmergencyNum">
                                <p style="margin-right: 40px;">Phone Number</p>
                                <p  style="font-weight: bold;"><?php echo $row['U_PHONE_NUMBER'] ?></p>
                            </div>
                        </div>

                        <div class="cardDetails">
                            <div class="userMname">
                                <p style="margin-right: 25px;">Middle Name</p>
                                <p style="font-weight: bold;"><?php echo $row['U_MIDDLE_NAME'] ?></p>
                            </div>

                            <div class="userAddress">
                                <p style="margin-right: 52px;">Campus Area</p>
                                <p  style="font-weight: bold;"><?php echo $row['U_CAMPUS_AREA'] ?></p>
                            </div>
                        </div>

                        <div class="cardDetails">
                            <div class="userLname">
                                <p style="margin-right: 43px;">Last Name</p>
                                <p style="font-weight: bold;"><?php echo $row['U_LAST_NAME'] ?></p>
                            </div>

                            <div class="userCity">
                                <p style="margin-right: 102px;">Email</p>
                                <p  style="font-weight: bold;"><?php echo $row['U_EMAIL'] ?></p>
                            </div>
                        </div>


                        <div class="cardDetails">
                            <div class="userSuffix">
                                <p style="margin-right: 78px;">Suffix</p>
                                <p style="font-weight: bold;"><?php echo $row['U_SUFFIX'] ?></p>
                        </div>

                        </div>

                        <div class="cardDetails">
                            <div class="userGender">
                                <p style="margin-right: 63px;">Gender</p>
                                <p style="font-weight: bold;"><?php echo $row['U_GENDER'] ?></p>
                            </div>
                        </div> 
                        </div>
                    </div>
                    <div class="workInformation-container">
                        <div class="workInformation-head">
                            <h4>Work Information</h4>
                        </div>

                        <div class="cardDetails" style="margin-top: 10px;">
                            <div class="userEmployeeId">
                                <p style="margin-right: 54px;">Employee Id</p>
                                <p style="font-weight: bold;">#<?php echo $userID ?></p>
                            </div>

                        </div>

                        <div class="cardDetails">
                                <div class="userPosition">
                                    <p style="margin-right: 85px;">Position</p>
                                    <p  style="font-weight: bold;">Courier</p>
                                </div>
                        </div>
                    </div>
                </div>
        <?php
        }
    }


    public function updateUserInfo() {
        $userID = $_SESSION['USER_ID'];
        $employeeData = array();
    
        $personalInfo = "SELECT U_FIRST_NAME, U_MIDDLE_NAME, U_LAST_NAME, U_SUFFIX, U_GENDER, U_PHONE_NUMBER, U_EMAIL, U_CAMPUS_AREA, U_PICTURE FROM users where USER_ID = '$userID'";
    
        $stmt = $this->conn->prepare($personalInfo);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($fname, $mname, $lname, $suffix, $gender, $phoneNumber, $email, $campusArea, $picturee);  // Ayaw hilabti ni pula rana pero woking ni
            
            while ($stmt->fetch()) {
                $employeeData[] = [
                    'U_FIRST_NAME' => $fname,
                    'U_MIDDLE_NAME' => $mname,
                    'U_LAST_NAME' => $lname,
                    'U_SUFFIX' => $suffix,
                    'U_GENDER' => $gender,
                    'U_PHONE_NUMBER' => $phoneNumber,
                    'U_EMAIL' => $email,
                    'U_CAMPUS_AREA' => $campusArea,
                    'U_PICTURE' => $picturee,
                ];
            }
    
            $stmt->close();
        }
    
        return $employeeData;
    }


    public function renderUpdateUserInfo($employeeData) {
        $userID = $_SESSION['USER_ID'];
        foreach ($employeeData as $row) {
            ?>
            <div class="editLeft-container">
                            <div class="image-container">
                                <img src="../../Icons/<?php echo $row['U_PICTURE'] ?>" alt="">
                            </div>
                            <input type="file" value="" id="uploadBtn">
                            <label for="uploadBtn">Choose File</label>
                        </div>
                        <div class="editRight-container">
                            <div class="modalPersonalInfo">
                                <h4>Personal Information</h4>
                            </div>
        
                            <div class="modalFirstRowInfo">
                                <div class="modalFname">
                                    <p><span style="color:red;">*</span> First Name</p>
                                    <input type="text" value="<?php echo $row['U_FIRST_NAME']; ?>">
                                </div>
        
                                <div class="modalMname">
                                    <p><span style="color:red;">*</span> Middle Name</p>
                                    <input type="text" value="<?php echo $row['U_MIDDLE_NAME']; ?>">
                                </div>

                                <div class="modalLname">
                                    <p><span style="color:red;">*</span> Last Name</p>
                                    <input type="text" value="<?php echo $row['U_LAST_NAME']; ?>">
                                </div>
        
                                <div class="modalSuffix">
                                    <p><span style="color:red;">*</span> Suffix</p>
                                    <input type="text" value="<?php echo $row['U_SUFFIX']; ?>">
                                </div>
                            </div>
        
                            <div class="modalSecondRowInfo">
                                <div class="modalGender">
                                    <p><span style="color:red;">*</span> Gender</p>
                                    <select id="cars" name="cars">
                                        <option value="Male" <?php echo ($row['U_GENDER'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($row['U_GENDER'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Others" <?php echo ($row['U_GENDER'] === 'Others') ? 'selected' : ''; ?>>Others</option>
                                    </select>
                                </div>
        
                                <div class="modalPhonenumber">
                                    <p><span style="color:red;">*</span> Phone Number</p>
                                    <input type="text" value="<?php echo $row['U_PHONE_NUMBER']; ?>">
                                </div>
        
                                <div class="modalEmail">
                                    <p><span style="color:red;">*</span> Email</p>
                                    <input type="text" disabled value="<?php echo $row['U_EMAIL']; ?>">
                                </div>
        
                                <div class="modalDateborn">
                                    <p><span style="color:red;">*</span> Campus Area</p>
                                    <input type="text" value="<?php echo $row['U_CAMPUS_AREA']; ?>">
                                </div>
                            </div>                  
        
                            <div class="modalSixRowInfo">
                                <input type="submit"  value="Update Profile" class="modalUpdate">
                                <button class="closeModalProfile" data-bs-dismiss="modal">Close</button>
                            </div>
                    </div>
        <?php
        }
    }



    public function switchAccount() {
        $userID = $_SESSION['USER_ID'];
        $employeeData = array();
    
        $personalInfo = "SELECT U_FIRST_NAME, U_MIDDLE_NAME, U_LAST_NAME FROM users where USER_ID = '$userID'";
    
        $stmt = $this->conn->prepare($personalInfo);
        if ($stmt) {
            $stmt->execute();
            $stmt->bind_result($fname, $mname, $lname);
            
            while ($stmt->fetch()) {
                $employeeData[] = [
                    'U_FIRST_NAME' => $fname,
                    'U_MIDDLE_NAME' => $mname,
                    'U_LAST_NAME' => $lname,
                ];
            }
    
            $stmt->close();
        }
    
        return $employeeData;
    }


    public function renderSwitchAccount($employeeData) {
        $userNames = $_SESSION['Courier_Name'];
        foreach ($employeeData as $row) {
            ?>
                <div class="otherAcc-container">
                    <div class="switchLeft">
                        <img src="../../Icons/cutemikey.svg" alt="">
                        <div class="switchProfile">
                            <p class="switchUseProfile" name="<?php echo $userNames ?>"><?php echo $row['U_FIRST_NAME']. ' ' . $row['U_MIDDLE_NAME'] . ' ' . $row['U_LAST_NAME']; ?></p>
                            <p>Customer Account</p>
                        </div>
                    </div> 
                    <div class="switchRight">
                        <form action="" method="post">     
                            <button type="submit" name="switchAccountNow">Switch Account</button>
                        </form>
                    </div>
                </div>
            <?php 
        }
    }








    public function courSwitch($enteredUsername) {
        $sql = "SELECT users.USER_ID, users.U_PASSWORD, users.U_FIRST_NAME FROM users JOIN user_roles ON users.USER_ID = user_roles.USER_ID WHERE users.U_FIRST_NAME = ? AND user_roles.ROLE_CODE = 'cstmr'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $enteredUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

                $this->adminID = $row['USER_ID'];
                $staffName = $row['U_FIRST_NAME'];

                $_SESSION['USER_ID'] = $this->adminID;
                $_SESSION['Staff_Name'] = $staffName;
                return self::REGISTRATION_SUCCESS;
        } else {
            
        }
    }
}

?>
