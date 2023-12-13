<?php
include("../functions/DateTableHandler.php");
$database = new Connection();
$conn = $database->conn;

if (isset($_POST['name'])) {
    $new = new DateHandler($conn);
    $searchName = $_POST['name'];
    $employeeData = $new->searchMenuList($searchName);

    if (!empty($employeeData)) {
        foreach ($employeeData as $row) {
            ?><tr>
                <td><?php echo $row['dateCreate']; ?></td>
                <td><?php echo $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']; ?></td>
                <td>Order Number# <?php echo $row['cartID']; ?></td>
                <td>₱ <?php echo $row['Total']; ?></td>
                <td>
                    <button class="vwEmpProfile viewOrder" value="<?php echo $row['cartID']; ?>">
                        <h6 class="text-center m-auto">View Order Record</h6>
                    </button>
                </td>
        <?php
        }
         ?> </tr> <?php
    } else {
        echo '<p style="padding-left: 40px; padding-top: 20px;">No Data Available</p>';
    }
    
}
?>


<script>
            $(document).ready(function () {
            $('.viewOrder').click(function () {
                var olCartId = $(this).val();

                $.ajax({
                    type: 'POST',
                    url: '../functions/viewTransactionIdHandler.php',
                    data: {olCartId: olCartId},
                    success: function (response) {
                        window.location.href = "courier-viewTransaction.php";

                    },
                    error: function (xhr, status, error) {
                        console.error('Error: ' + error);
                    }
                });
            });
        });

     </script>
