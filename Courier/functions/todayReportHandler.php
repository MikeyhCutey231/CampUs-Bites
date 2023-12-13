<?php
    include("reportsInfo.php");
    $database = new Connection();
    $conn = $database->conn;

    if (isset($_POST['currentdate'])) {
        $new = new reportsData($conn);
        $currentdate = $_POST['currentdate'];
        $DateCurrent = $new->todayDate($currentdate);

        $totalShippingFee = 0;

        if (!empty($DateCurrent)) {
            foreach ($DateCurrent as $row) {
                ?> 
                <tr>
                    <td><?php echo $row['orderDate'] ?></td>
                    <td># <?php echo $row['orderCartID'] ?></td>
                    <td>₱ <?php echo $row['Total'] ?></td>
                    <td>₱ <?php echo $row['SHIPPING_FEE'] ?></td>
                </tr>
    
                <?php
                // Add each shipping fee to the total
                $totalShippingFee += $row['SHIPPING_FEE'];
            }
        } else {
            ?>
            <p style="padding-left: 40px; padding-top: 20px;">No Data Available</p>
            <?php
        }

        // Include the total shipping fee directly in the HTML response
        echo '<tr><td colspan="4" class="total-shipping-fee" style="display:none;">Total Shipping Fee: ₱ ' . $totalShippingFee . '</td></tr>';
    }
?>
