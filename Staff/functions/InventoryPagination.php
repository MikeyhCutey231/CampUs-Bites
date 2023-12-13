<?php
include("../../Admin/functions/dbConfig.php");

$dbConnection = new Connection();
$conn = $dbConnection->conn;

$limit = 12;
$page = 1;
$output = '';

if (isset($_POST['page'])) {
    $page = $_POST['page'];
}

$start_from = ($page - 1) * $limit;

// Get the selected value from the POST data
$selectedValue = isset($_POST['selectedValue']) ? $_POST['selectedValue'] : '';

// Modify the query to include a WHERE clause based on the selected value
$query = "SELECT * FROM product";

// Check if a filter value is provided and not equal to 0
if (!empty($selectedValue) && $selectedValue != 0) {
    $query .= " WHERE CATEGORY_ID = '$selectedValue'";
}

$query .= " LIMIT $start_from, $limit";

$result = mysqli_query($conn, $query);

$output .=' <div class="ItemContainer">';

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $output .= '
            <div class="card-main">
                <button class="card-1">
                    <div class="Cardtop-content">
                        <img src="../../Icons/abobo.svg" alt="" class="foodImage">
                        <p class="foodName">'. $row['PROD_NAME'] .'</p>
                        <p class="availabe">Available</p>
                        <img src="../../Icons/InvetoryStatus.svg" alt="" class="statusDesign" width="100px">
                    </div>
                    <div class="Cardbottom-content">
                        <div class="topBottomcard-content">
                            <img src="../../Icons/clipboard.svg" alt="" width="18px">
                            <p>Code #'. $row['PROD_ID'] .'</p>
                        </div>
                        <div class="bottomBottomcard-content">
                            <img src="../../Icons/package.svg" alt="">
                            <p>'. $row['PROD_TOTAL_QUANTITY'] .' stock available</p>
                        </div>
                    </div>
                </button>

                <button class="restock viewItem" value="'. $row['PROD_ID'] .'">View Item</button>
            </div>';
    }
} else {
    $output .='<tr><td>Data Not Found</td></tr>';
}

$output .= '</div>';

$query1 = "SELECT * FROM product";

// Check if a filter value is provided and not equal to 0 for total records calculation
if (!empty($selectedValue) && $selectedValue != 0) {
    $query1 .= " WHERE CATEGORY_ID = '$selectedValue'";
}

$total_records = mysqli_num_rows($conn->query($query1));
$total_pages = ceil($total_records / $limit);

$output .= '<ul class="pagination">';

if ($page > 1) {
    $previous = $page - 1;
    $output .= '<li class="page-item" id="1"><span class="page-link">First Page</span></li>';
    $output .= '<li class="page-item" id="' . $previous . '"><span class="page-link"><i class="fa-solid fa-caret-left"></i></span></li>';
}

for ($i = 1; $i <= $total_pages; $i++) {
    $active_class = "";

    if ($i == $page) {
        $active_class = "active";
    }

    $output .= '<li class="page-item ' . $active_class . ' " id="' . $i . '"><span class="page-link">' . $i . '</span></li>';
}

if ($page < $total_pages) {
    $page++;
    $output .= '<li class="page-item" id="' . $page . '"><span class="page-link"><i class="fa-solid fa-caret-right"></i></span></li>';
    $output .= '<li class="page-item" id="' . $total_pages . '"><span class="page-link">Last Page</span></li>';
}

$output .= '</ul>';

// Return the pagination HTML
echo $output;
?>



<script>
        $(document).ready(function (){
            $('.viewItem').click(function (){
                var productID = $(this).attr('value');

                alert(productID);
                    $.ajax({
                    type: 'POST',
                    url: '../functions/productIDHandler.php',
                    data: { productID: productID },
                    success: function (data) {
                        window.location.href="staff-viewItem.php";;
                    },
                    error: function (xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            });
        });
</script>

<script>
    function updateNotificationCount() {
        $.ajax({
            url: "../functions/check_zero_stock.php", // Create a new PHP file for checking zero stock products
            method: "GET",
            success: function (data) {
                $("#notificationCount").text(data);
            },
        });
    }

    // Call the function when the page loads
    $(document).ready(function () {
        updateNotificationCount();

        // Set up an interval to update the notification count (e.g., every 5 minutes)
        setInterval(updateNotificationCount, 300000); // 300000 milliseconds = 5 minutes
    });
</script>


