<?php
include("../functions/reportsInfo.php");
$database = new Connection();
$conn = $database->conn;

if (isset($_POST['name'])) {
    $new = new reportsData($conn);
    $searchName = $_POST['name'];
    $employeeData = $new->searchMenuList($searchName);

    $totalShippingFee = 0;

    if (!empty($employeeData)) {
        foreach ($employeeData as $row) {
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