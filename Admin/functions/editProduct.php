<?php

require_once("../../Admin/functions/dbConfig.php");
include '../../Admin/functions/Admin_Manager_logs.php';
$logger = new Admin_Manager_logs();

$database = new Connection();
$conn = $database->conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $pnameLogs = $_POST['pname'];

    $userId = $_POST["userid"];
    $prod_id = $_POST["prodID"];
    $new_prodDesc = $_POST["prodDesc"];
    $new_prodPrice = $_POST["prodPrice"];
    $new_prodRemainingQuantity = $_POST["prodRemainingQuantity"];
    $new_productName = $_POST["productName"];
    $new_prodQuantity = $_POST["prodQuantity"];
    $totalQuantity = $_POST["TotalQuantity"];
    $oldPrice = $_POST['prodOldPrice'];
    if (!empty($_FILES['productPic']['name'][0])) {
        $fileCount = count($_FILES['productPic']['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $prodPic = $_FILES['productPic']['name'][$i];
            $imageTmpName = $_FILES['productPic']['tmp_name'][$i];
            $targetPath = '../../Icons/' . $prodPic;

            if (move_uploaded_file($imageTmpName, $targetPath)) {
                $query = "UPDATE product SET PROD_PIC = '$prodPic' WHERE PROD_ID = '$prod_id'";
                $result = $conn->query($query);

                if ($result) {
                    $logger->logProductUpdate($userId, $pnameLogs);
                    $rec_success = "Updated";
                } else {
                    $rec_error = "Something went wrong: " . $conn->error;
                }
            } else {
                $rec_error = "Error moving the profile picture to the target directory.";
            }
        }
    } else {
        $sql = "UPDATE product SET ";
        $bind_types = "";
        $bind_params = array();

        if (!empty($new_productName)) {
            $sql .= "PROD_NAME = ?, ";
            $bind_types .= "s";
            $bind_params[] = &$new_productName;
        }
        if (!empty($new_prodPrice)) {
            $sql .= "PROD_SELLING_PRICE = ?, ";
            $bind_types .= "s";
            $bind_params[] = &$new_prodPrice;
        }
        if (!empty($new_prodDesc)) {
            $sql .= "PROD_DESC = ?, ";
            $bind_types .= "s";
            $bind_params[] = &$new_prodDesc;
        }
        if (!empty($new_prodRemainingQuantity)) {
            $sql .= "PROD_REMAINING_QUANTITY = ?, ";
            $bind_types .= "s";
            $bind_params[] = &$new_prodRemainingQuantity;
        }
        if (!empty($new_prodQuantity)) {
            $sql .= "PROD_TOTAL_QUANTITY = PROD_TOTAL_QUANTITY + ?, ";
            $bind_types .= "s";
            $bind_params[] = &$new_prodQuantity;
        }

        $sql = rtrim($sql, ", ");

        $sql .= " WHERE PROD_ID = ?";
        $bind_types .= "i";
        $bind_params[] = &$prod_id;

        $stmt = $conn->prepare($sql);

        $bind_params = array_merge(array($bind_types), $bind_params);
        call_user_func_array(array($stmt, 'bind_param'), $bind_params);

        if ($stmt->execute()) {
            $logger->logProductUpdate($userId, $pnameLogs);
            $rec_success = "Product updated successfully.";
        } else {
            $rec_error = "Error updating product: " . $stmt->error;
        }

        $stmt->close();
    }

    if (!empty($new_prodQuantity) && $new_prodQuantity !== '0') {
        $check_sql = "INSERT INTO prod_replenishment (PROD_ID, REPLENISH_QUANTITY, REPLENISH_DATE, REPLENISH_TIME, TOTAL_BEFORE_REPLENISH, PROD_PRICE, PROD_NEW_PRICE) VALUES (?, ?, NOW(), TIME_FORMAT(NOW(), '%r'), ?, ?,?)";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("iiiii", $prod_id, $new_prodQuantity, $totalQuantity, $oldPrice, $new_prodPrice);

        if ($check_stmt->execute()) {
            $rec_success = "New record inserted in another_table for the replenishment transaction.";
        } else {
            $rec_error = "Error inserting new record in another_table: " . $check_stmt->error;
        }

        $check_stmt->close();
    }

    echo '<script>window.history.back();</script>';}
?>
