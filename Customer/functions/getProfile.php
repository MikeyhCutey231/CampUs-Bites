<?php 
include_once '../../Admin/functions/dbConfig.php';


class GetProfile extends Connection {

    function getProfile($customer_id){
        $sql = "SELECT users.U_PICTURE FROM users WHERE users.USER_ID = ?;";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $profile = $result->fetch_assoc();  // Use fetch_assoc instead of fetch_all
        $stmt->close();
    
        return $profile;
    }
}

?>