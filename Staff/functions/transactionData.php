<?php 
    include("../../Admin/functions/dbConfig.php");

    class MenuList{
        public $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }


        public function searchPayrollEmployees($searchName) {
            $query = "SELECT pos_order.ONSITE_ORDER_ID, emp_personal_info.EMP_FIRST_NAME, emp_personal_info.EMP_MIDDLE_NAME, emp_personal_info.EMP_LAST_NAME, pos_cart.POS_CART_ID, pos_cart.POS_CART_DATE_CREATED, pos_cart.POS_CART_TOTAL, pos_order.RECEIVED_AMOUNT, pos_order.CHANGE_AMOUNT 
            FROM pos_order
            INNER JOIN emp_personal_info ON pos_order.EMPLOYEE_ID = emp_personal_info.EMPLOYEE_ID
            INNER JOIN pos_cart ON pos_order.POS_CART_ID = pos_cart.POS_CART_ID 
            WHERE emp_personal_info.EMP_FIRST_NAME LIKE ? OR emp_personal_info.EMP_MIDDLE_NAME LIKE ? OR emp_personal_info.EMP_LAST_NAME LIKE ?";
    
            $stmt = $this->conn->prepare($query);
    
            $searchNameParam = "%" . $searchName . "%"; // Add % to create a LIKE query
    
            $stmt->bind_param("sss", $searchNameParam, $searchNameParam, $searchNameParam);
    
            if ($stmt->execute()) {
            $result = $stmt->get_result();
            $employeeData = array();
            
            while ($row = $result->fetch_assoc()) {
                $employeeData[] = $row;
            }
            
            return $employeeData;
            } else {
            // Handle the error
            return null;
            }
        }
       
    }
?>


