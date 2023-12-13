<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery library -->

<script>
    $(document).ready(function() {
        $('.vwEmpProfile').click(function(e) {
            e.preventDefault();
            var employeeID = $(this).closest('tr').find('td:eq(4)').text();

            window.location.href = 'admin-viewEmployee.php?employee_id=' + employeeID;
        });
    });
</script>

<?php

include("../../Admin/functions/dbConfig.php");


$database = new Connection();
$conn = $database->conn;

if (isset($_POST['input'])) {
    $input = $_POST['input'];
    $sql = "SELECT users.*, user_roles.*
FROM users
JOIN user_roles ON users.USER_ID = user_roles.USER_ID
WHERE (U_FIRST_NAME LIKE '{$input}%' OR U_MIDDLE_NAME LIKE '{$input}%' OR U_LAST_NAME LIKE '{$input}%')
  AND user_roles.ROLE_CODE = 'emp_cook';
";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) { ?>
        <div class="content-2 d-flex">
            <div class="col-12 table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" style="color: #555; font-weight: normal;">Picture</th>
                            <th scope="col" style="color: #555; font-weight: normal;">Last Name</th>
                            <th scope="col" style="color: #555; font-weight: normal;">First Name</th>
                            <th scope="col" style="color: #555; font-weight: normal;">Middle Name</th>
                            <th scope="col" style="color: #555; font-weight: normal;">Employee ID</th>
                            <th scope="col" style="color: #555; font-weight: normal;">Position</th>
                            <th scope="col" style="color: #555; font-weight: normal;">Phone Number</th>
                            <th scope="col" style="color: #555; font-weight: normal;">Status</th>
                            <th scope="col" style="color: #555; font-weight: normal;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            $firstName = $row["U_FIRST_NAME"];
                            $middleName = $row["U_MIDDLE_NAME"];
                            $lastName = $row["U_LAST_NAME"];
                            $statusColor = ($row["U_STATUS"] == "Active") ? "#3DC53A" : "#9C1421";

                            // Define a variable for the position text
                            $positionText = ($row["ROLE_CODE"] == 'emp_cook') ? "Cook" : $row["ROLE_CODE"];

                            echo "<tr>";
                            echo "<td><img style='border-radius: 50px;' src='" . '../../Icons/' . $row['U_PICTURE'] . "' width='42', height='42'></td>";                            echo "<td>" . $lastName . "</td>";
                            echo "<td>" . $firstName . "</td>";
                            echo "<td>" . $middleName . "</td>";
                            echo "<td>" . $row["USER_ID"] . "</td>";
                            echo "<td>" . $positionText . "</td>"; // Display the position text
                            echo "<td>" . $row["U_PHONE_NUMBER"] . "</td>";
                            echo '<td style="color: ' . $statusColor . ';">' . $row["U_STATUS"] . '</td>';
                            echo ' <td><a class="vwEmpProfile btn btn-secondary d-flex" href="#" role="button">
                                    <h6 class="text-center m-auto">View Profile</h6>
                                </a></td>';
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    } else {
        echo "<h6 class='text-danger text-center mt-3'>No data Found</h6>";
    }
}
?>

