<?php 
    include("../functions/dbConfig.php");

    class LoginUser extends Connection{
        const REGISTRATION_SUCCESS = 'success';
        const REGISTRATION_NOTSAME = 'not same';
        const REGISTRATION_EMPTY_FIELDS = 'empty_fields';
        const EMAIL_EMPTY_FIELDS = 'Input proper email';
        const REGISTRATION_PASSWORD_LENGTH = 'Not enough length';
        const REGISTRATION_PASSWORD_CASE = 'Not same cases';
        const REGISTRATION_PASSWORD_SPECIAL_CHAR = 'No special character';

        
        private $adminID;
        public function userLogin($username, $adminPassword){
        
           $result =  mysqli_query($this->conn,"SELECT * FROM emp_personal_info WHERE EMP_USERNAME = '$username' OR EMP_PASSWORD = '$adminPassword';");
           $row = mysqli_fetch_assoc($result);
            
            if (empty(trim($username)) && empty(trim($adminPassword))) {
                return self::REGISTRATION_EMPTY_FIELDS;
            } else {
                    if($username == $row["EMP_USERNAME"] && $adminPassword == $row["EMP_PASSWORD"]){
                        $adminID = $row['EMPLOYEE_ID'];
                        
                       $_SESSION['EMPLOYEE_ID'] = $adminID ;
                       return self::REGISTRATION_SUCCESS;
                       
                    }else{
                      return self::REGISTRATION_NOTSAME;
                  }
            }
        }


        public function changeLoginForgot($newPassword, $confirmPassword){
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
                    $result =  mysqli_query($this->conn, "UPDATE admin_info SET Password = '$confirmPassword'");
                    return self::REGISTRATION_SUCCESS;
                } else {
                    return self::REGISTRATION_NOTSAME;
                }
            }
         }
    }
?>



If mo click Yes
ma input sa database ang time and date = 1 (Time-in) login

if(user => 1) og login count sa database

if duha na ka same user ID nag login within that day

if(user == has already login in ? false){
    store database date then time-in
}else if(user == has 1 login ? true){
    store database date then time-out
}else if(user == 2 login ? true){
    prompt error
}


Upate?

Date and Time 
login count = 2 - 1 (first login)
login count = 1

if(currentDate == databaseDate){
    if(user == already exist){
        checks the log count
            if(loginCount == 1){
                if(user == first login) store database time in and date then login count = 1
                else if 
                (user = second login) update database time out, time thne logout count = 0

            }else if(loginCount == 0){
                print you have reached the maximum login
            }
    }else{
        if(loginCount == 1){
                if(user == first login) store database time in and date then login count = 1
                else if 
                (user = second login) update database time out, time thne logout count = 0

            }else if(loginCount == 0){
                print you have reached the maximum login
        }
    }
}else if(currentDate != databaseDate){
    if(loginCount == 1){
                if(user == first login) store database time in and date then login count = 1
                else if 
                (user = second login) update database time out, time thne logout count = 0

            }else if(loginCount == 0){
                print you have reached the maximum login
        }
}






