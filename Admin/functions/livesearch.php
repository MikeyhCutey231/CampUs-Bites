<?php

require_once("../../Admin/functions/dbConfig.php");
$database = new Connection();
$conn = $database->conn;

if(isset($_POST['input'])){

    $input = $_POST['input'];
    $sql = "SELECT * FROM emp_personal_info WHERE EMP_FIRST_NAME LIKE '{$input}%' OR EMP_MIDDLE_NAME LIKE '{$input}%' OR EMP_LAST_NAME LIKE '{$input}%'"; 

    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){?>

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
                        $firstName = $row["EMP_FIRST_NAME"];
                        $middleName = $row["EMP_MIDDLE_NAME"];
                        $lastName = $row["EMP_LAST_NAME"];
                        $statusColor = ($row["EMP_ACC_STATUS"] == "Active") ? "#3DC53A" : "#9C1421";

                        echo "<tr>";
                        echo "<td><img src='" . 'upload/' . $row['EMP_PROF_PIC'] . "' width='42'></td>";
                        echo "<td>" . $lastName . "</td>";
                        echo "<td>" . $firstName . "</td>";
                        echo "<td>" . $middleName . "</td>";
                        echo "<td>" . $row["EMPLOYEE_ID"] . "</td>";
                        echo "<td>" . $row["EMP_JOB_ID"] . "</td>";
                        echo "<td>" . $row["EMP_PHONE_NUM"] . "</td>";
                        echo '<td style="color: ' . $statusColor . ';">' . $row["EMP_ACC_STATUS"] . '</td>';
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
    }else{
        echo "<h6 class='text-danger text-center mt-3'>No data Found</h6>";
    }
}
?>