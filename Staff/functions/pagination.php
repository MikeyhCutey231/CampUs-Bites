<!-- Add jQuery library if not already added -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<?php
include("../../Admin/functions/dbConfig.php");

$dbConnection = new Connection();
$conn = $dbConnection->conn;

$filter = isset($_POST['filter']) ? $_POST['filter'] : 0;
if($filter == 0){
    $limit = 10;
    $page = 1;
    $output = '';

    if (isset($_POST['page'])) {
        $page = $_POST['page'];
    }

    $start_from = ($page - 1) * $limit;
    $search = isset($_POST['search']) ? $_POST['search'] : "";

    $conditions = array();

    if (!empty($search)) {
        $conditions[] = "CONCAT_WS(' ', users.U_FIRST_NAME, users.U_MIDDLE_NAME, users.U_LAST_NAME) LIKE ?";
        $conditions[] = "pos_cart.POS_CART_DATE_CREATED LIKE ?";
    }

    $where_clause = !empty($conditions) ? "WHERE " . implode(" OR ", $conditions) : "";

    $query = "SELECT pos_order.ONSITE_ORDER_ID, users.U_FIRST_NAME, users.U_MIDDLE_NAME, users.U_LAST_NAME, 
                pos_cart.POS_CART_ID, pos_cart.POS_CART_DATE_CREATED, pos_cart.POS_CART_TOTAL, pos_order.RECEIVED_AMOUNT,  pos_order.CHANGE_AMOUNT  
                FROM pos_order
                INNER JOIN users ON pos_order.EMPLOYEE_ID = users.USER_ID 
                INNER JOIN pos_cart ON pos_order.POS_CART_ID = pos_cart.POS_CART_ID $where_clause
                LIMIT ?, ?";

        $stmt = $conn->prepare($query);

        if (!empty($search)) {
            $search_param = "%$search%";
            $stmt->bind_param("sii", $search_param, $start_from, $limit);
        } else {
            $stmt->bind_param("ii", $start_from, $limit);
        }

        $stmt->execute();
    $result = $stmt->get_result();

    $output .=  '
        <table class="table">
        <thead>
            <tr>
                <th scope="col">Transaction Date</th>
                <th scope="col">Employee Name</th>
                <th scope="col">Order Number</th>
                <th scope="col">Total Amount</th>
                <th scope="col">Amount Received</th>
                <th scope="col">Amount Changed</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
    ';

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $output .= '
            <tbody>
                <tr>
                    <td>' . $row['POS_CART_DATE_CREATED'] . '</td>
                    <td>' . ucfirst($row['U_FIRST_NAME']) . " " . ucfirst($row['U_MIDDLE_NAME']) . " " . ucfirst($row['U_LAST_NAME']) . '</td>
                    <td>Order#' . ucfirst($row['POS_CART_ID']) . '</td>
                    <td>₱' . $row['POS_CART_TOTAL'] . '</td>
                    <td>₱' . $row['RECEIVED_AMOUNT'] . '</td>
                    <td>₱' . $row['CHANGE_AMOUNT'] . '</td>
                    <td><button type="button" class="viewOrderRec" data-filter="' . $filter . '" data-cartID="' . $row['POS_CART_ID'] . '"  value="' . $row['ONSITE_ORDER_ID'] . '">View Order Record</button></td>
                </tr>
            </tbody>';
        }
    } else {
        $output .='<tr><td>Data Not Found</td></tr>';
    }

    $output .= '</table>';

    $query1 = "SELECT * FROM pos_order";
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

    // Close and release resources
    $stmt->close();
    $result->close();
    $stmt = null;
    $result = null;

}else if($filter == 2){
    $limit = 10;
$page = 1;
$output = '';

if (isset($_POST['page'])) {
    $page = $_POST['page'];
}

$start_from = ($page - 1) * $limit;
$search = isset($_POST['search']) ? $_POST['search'] : "";

$conditions = array();

if (!empty($search)) {
    $conditions[] = "CONCAT_WS(' ', users.U_FIRST_NAME, users.U_MIDDLE_NAME, users.U_LAST_NAME) LIKE ? AND online_order.OL_ORDER_TYPE_ID = 2 AND (ORDER_STATUS_ID = 8 OR ORDER_STATUS_ID = 4)";
    $conditions[] = "DATE(online_order.DATE_CREATED) LIKE ? AND online_order.OL_ORDER_TYPE_ID = 2 AND (ORDER_STATUS_ID = 8 OR ORDER_STATUS_ID = 4)";
    $conditions[] = "online_order.ONLINE_ORDER_ID LIKE ? AND online_order.OL_ORDER_TYPE_ID = 2 AND (ORDER_STATUS_ID = 8 OR ORDER_STATUS_ID = 4)";
}

$where_clause = !empty($conditions) ? "WHERE " . implode(" OR ", $conditions) : "";

// $query = "SELECT
//             DATE(online_order.DATE_CREATED),
//             online_order.OL_CART_ID,
//             users.U_FIRST_NAME,
//             users.U_MIDDLE_NAME,
//             users.U_LAST_NAME,
//             online_order.SHIPPING_FEE,
//             online_cart_item.OL_SUBTOTAL
//             FROM
//                 online_order
//             INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
//             INNER JOIN users ON online_order.EMPLOYEE_ID = users.USER_ID
//             INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
//             $where_clause AND online_order.OL_ORDER_TYPE_ID = 2
//              LIMIT ?, ? ";

    $query = "SELECT
            DATE(online_order.DATE_CREATED),
            online_order.ONLINE_ORDER_ID,
            online_order.OL_CART_ID,
            users.U_FIRST_NAME,
            users.U_MIDDLE_NAME,
            users.U_LAST_NAME,
            online_order.SHIPPING_FEE,
            SUM(online_cart_item.OL_SUBTOTAL),
            online_order.RECEIVED_AMOUNT,
            online_order.CHANGE_AMOUNT
            FROM
                online_order
            INNER JOIN ol_cart ON online_order.OL_CART_ID = ol_cart.OL_CART_ID
            INNER JOIN users ON online_order.EMPLOYEE_ID = users.USER_ID
            INNER JOIN online_cart_item ON online_order.OL_CART_ID = online_cart_item.OL_CART_ID
            $where_clause AND online_order.OL_ORDER_TYPE_ID = 2 AND (ORDER_STATUS_ID = 8 OR ORDER_STATUS_ID = 4)
                        GROUP BY
            DATE(online_order.DATE_CREATED),
            online_order.ONLINE_ORDER_ID,
            online_order.OL_CART_ID,
            users.U_FIRST_NAME,
            users.U_MIDDLE_NAME,
            users.U_LAST_NAME,
            online_order.SHIPPING_FEE
             LIMIT ?, ? ";
             
             

$stmt = $conn->prepare($query);

if (!empty($search)) {
    $search_param = "%$search%";
    $onlineOrderId = "%$search%";
    $date_param = "%$search%";
    $stmt->bind_param("sssii", $search_param, $onlineOrderId, $date_param, $start_from, $limit);
} else {
    $stmt->bind_param("ii", $start_from, $limit);
}

$stmt->execute();

$stmt->bind_result(
    $date_created,
    $onlineOrderId,
    $ol_cart_id,
    $u_first_name,
    $u_middle_name,
    $u_last_name,
    $shipping_fee,
    $ol_subtotal,
    $receivedAmount,
    $changeAmount
);

$output .=  '
    <table class="table">
    <thead>
        <tr>
            <th scope="col">Transaction Date</th>
            <th scope="col">Employee Name</th>
            <th scope="col">Order Number</th>
            <th scope="col">Total Amount</th>
            <th scope="col">Amount Received</th>
            <th scope="col">Amount Changed</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
';

while ($stmt->fetch()) {
    $output .= '
        <tbody>
            <tr>
                <td>' . $date_created . '</td>
                <td>' . ucfirst($u_first_name) . " " . ucfirst($u_middle_name) . " " . ucfirst($u_last_name) . '</td>
                <td>Order#' . ucfirst($onlineOrderId) . '</td>
                <td>₱' . $ol_subtotal . '</td>
                <td>₱' . $receivedAmount . '</td>
                <td>₱' . $changeAmount . '</td>
                <td><button type="button" class="viewOrderRec" data-filter="' . $filter . '" value="' . $ol_cart_id . '">View Order Record</button></td>
            </tr>
        </tbody>';
}

$output .= '</table>';

$query1 = "SELECT * FROM online_order";
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

// Close and release resources
$stmt->close();
$stmt = null;
}

?>


    <script>
        $(document).ready(function () {
            $('.viewOrderRec').click(function () {
                var onsiteOrderID = $(this).attr('value');
                var filterValue = $(this).data('filter');

                $.ajax({
                    type: 'POST',
                    url: '../functions/cartIDHandler.php',
                    data: { onsiteOrderID: onsiteOrderID , filterValue:filterValue},
                    success: function (data) {
                        window.location.href = "viewOrderHistory.php";
                    },
                    error: function (xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            });
        });
    </script>
