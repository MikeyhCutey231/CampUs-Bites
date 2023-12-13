<?php
require_once("../../Admin/functions/dbConfig.php");
include '../../Admin/functions/Admin_Manager_logs.php';
$database_connection = new Connection();
$conn = $database_connection->conn;

if($conn->connect_error){
die("Connection failed: " . $conn->connect_error);
}
else {
    if (isset($_POST['submit'])) {
        $userId = $_POST['userid'];
        $prodName = $_POST['prodName'];
        $prodID = $_POST['prodId'];
        $prodQuantity = $_POST['prodQuantity'];
        $prodCapitalPrice = $_POST['prodCapitalPrice'];
        $prodSellingPrice = $_POST['prodSellingPrice'];
        $prodDesc = $_POST['prod_desc'];
        $prodCategory = $_POST['category'];
        $currentDate = date("Y-m-d");
        $currentTime = date("H:i:s");

        $check_query = "SELECT PROD_ID, PROD_NAME FROM product WHERE PROD_ID = '$prodID' AND PROD_NAME = '$prodName'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            $rec_error = "Product already exists. Please choose a different name.";
        }
        else {
            if (isset($_FILES['productPic']) && !empty($_FILES['productPic']['name'][0])) {
                $fileCount = count($_FILES['productPic']['name']);

                for ($i = 0; $i < $fileCount; $i++) {
                    $prodPic = $_FILES['productPic']['name'][$i];
                    $imageTmpName = $_FILES['productPic']['tmp_name'][$i];
                    $targetPath = '../../Icons/' . $prodPic;

                    if (move_uploaded_file($imageTmpName, $targetPath)) {
                        $query = "INSERT INTO product (PROD_ID, PROD_NAME, PROD_DESC, PROD_CAPITAL_PRICE, PROD_SELLING_PRICE, PROD_TOTAL_QUANTITY, PROD_REMAINING_QUANTITY, PROD_STATUS,CATEGORY_ID,PROD_SALES,PROD_DATE_ADD,PROD_TIME_ADDED, PROD_PIC)
                        VALUES('$prodID','$prodName','$prodDesc','$prodCapitalPrice','$prodSellingPrice','$prodQuantity','$prodQuantity','Available','$prodCategory','$prodCapitalPrice','$currentDate', '$currentTime', '$prodPic')";
                        $result = $conn->query($query);

                        if ($result) {
                            $logger = new Admin_Manager_logs();
                            $logger->logProductAddition($userId, $prodName);
                            $rec_success = "Added";
                            header('location: ../../Admin/admin_php/admin_itemInventory.php');
                        } else {
                            $rec_error = "Something went wrong: " . $conn->error;
                           
                        
                        }
                    }
                    else{
                        $rec_error = "Error moving the profile picture to the target directory. Upload Error: " . $_FILES['productPic']['error'][$i];
                        
                    }
                }
            }
            else{
             echo 'Cant move file';
            }
        }
    }
}
?>