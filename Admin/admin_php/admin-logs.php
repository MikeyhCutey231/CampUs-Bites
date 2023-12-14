<?php
include '../../Admin/functions/UserData.php';
include '../../Admin/functions/Admin_Manager_logs.php';
$logger = new Admin_Manager_logs();

$loggedUser = $_SESSION['USER_ID'];
$userDataInstance = new UserData();
$usersData = $userDataInstance->getUserData($loggedUser);

if (!isset($_SESSION['USER_ID'])) {
    header("Location: admin-login.php");
    exit();
}
?>

<?php
require '../../Admin/functions/LiveSearchFunction.php';
$customerTableSorter = new TableSorter('#searchresult', '.filterDropdown');
$customerTableSorter->addSortingScript();

$searchHandler = new SearchHandler();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $searchHandler->performSearch($searchTerm);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <title>CampUs Bites</title>
    <style>
    .custom-pagination {
        color: #9C1421;
        margin-bottom: 0px;
        margin-top: 20px;
    }

    .custom-pagination .page-item.active .page-link {
        background-color: #9C1421;
        border-color: #9C1421;
        color: white;
    }
    .custom-pagination .page-item .page-link {
        color: blue;
    }

</style>

    <link rel="stylesheet" href="../../Admin/admin_css/admin-cashier.css">
</head>
<body>
<div class="wrapper">

    <?php include 'sidebar.php'; ?>

    <div class="right-container">
        <div class="head-content">
            <div class="Menu-name">
                <button class="open-btn"><i class="fa-solid fa-bars" style="font-size: 20px;"></i></button>
                <p style="margin-bottom: 0;">Activity Logs</p>
            </div>
            <!-- Main Search Bar -->
            <div class="head-searchbar">
                <img src="../../Icons/search.svg" alt="" width="20px" style="margin-top: 12px;">
                <form method="post">
                    <input type="text" name="search" placeholder="Search here...">
                </form>
            </div>
            <div class="usep-texthead">
                <img src="../../Icons/useplogo.png" alt="" width="30px" height="30px">
                <p style="margin-bottom: 0px; margin-top: 3px; margin-left: 10px; font-weight: 600;">UseP (Tagum Unit)</p>
            </div>
            <a href="admin-profile.php" style="color: black;">
                <div class="user-profile">
                    <div class="admin-profile">
                        <?php
                        if (!empty($usersData)) {
                            $profilePic = $usersData[0]['profilePic'];
                            echo '<img src="../../Icons/' . $profilePic . '" style="height: 38px; width: 40px; border-radius: 5px; object-fit: cover;">';
                        }
                        ?>
                    </div>
                    <div class="admin-detail">
                        <?php
                        if (!empty($usersData)) {
                            $adminUsername = $usersData[0]['username'];
                            echo '<p style="margin-bottom: 0px; font-weight: 700;">' . $adminUsername . '</p>';

                            require_once '../../Admin/functions/user.php';
                            $userDataManager = new User();
                            if ($userDataManager->isManager()) {
                                echo '<p style="margin-bottom: 0px; font-size: 6px; background-color: #9C1421; color: white; padding: 3px; border-radius: 3px;">Manager</p>';
                            } else {
                                echo '<p style="margin-bottom: 0px; font-size: 6px; background-color: #9C1421; color: white; padding: 3px; border-radius: 3px;">Administrator</p>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </a>
        </div>
        <div class="main-content">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-11 mt-4">
                        
                    </div>
                    <div class="col-12 col-md-11 mt-3" id="searchresult">
    <div class="content-2 d-flex">
        <div class="col-12 table-container">
            <?php
            $itemsPerPage = 15;
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

            $allLogs = $logger->getAllLogs();
            $totalItems = count($allLogs);
            $totalPages = ceil($totalItems / $itemsPerPage);

            $start = ($currentPage - 1) * $itemsPerPage;
            $logsToShow = array_slice($allLogs, $start, $itemsPerPage);

            if (!empty($logsToShow)) {
            ?>
                <table class="table text-left">
                    <thead>
                        <tr>
                            <th scope="col" style="color: #555; font-weight: normal;">Description</th>
                            <th scope="col" style="color: #555; font-weight: normal;">Action</th>
                            <th scope="col" style="color: #555; font-weight: normal;">User ID</th>
                            <th scope="col" style="color: #555; font-weight: normal;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($logsToShow as $logs) {
                            echo '<tr>';
                            echo '<td>' . $logs['DESCRIPTION'] . '</td>';
                            echo '<td>' . $logs['ACTION'] . '</td>';
                            echo '<td>' . $logs['USER_ID'] . '</td>';
                            echo '<td>' . date('Y-m-d', strtotime($logs['DATE'])) . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>

            <?php
            } else {
                echo '<p>No records found.</p>';
            }
            ?>
        </div>
    </div>

      <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination custom-pagination">
                        <?php
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                        }
                        ?>
                    </ul>
                </nav>
</div>

                </div>


            </div>
        </div>
    </div>
</div>
</div>


<!-- Modal -->
<div class="modal fade" id="editInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form class="needs-validation was-validated" action="../../Admin/functions/add-Employee.php" id="employee-form" method="POST" enctype="multipart/form-data" validate>
                    <input type="text" name="userid" value="<?php echo $userid=$usersData[0]['user_id']; ?>" hidden="hidden">
                    <div class="editLeft-container">
                        <div class="editLeft-container">
                            <div class="image-container">
                                <img id="image-preview" src="../../Icons/drop.svg" alt="" style="width:100%;height:100%;overflow:hidden" >

                            </div>
                            <input type="file" value="" id="uploadBtn" class="uploadBtn" name="image[]" accept="image/*" onchange="previewImage(event)">
                            <label for="uploadBtn" style=" width: 205px;">Choose File</label>
                        </div>
                    </div>

                    <div class="editRight-container">
                        <div class="modalPersonalInfo">
                            <h4>Personal Information</h4>
                        </div>

                        <div class="modalFirstRowInfo">
                            <div class="modalLname">
                                <p><span style="color: red;">*</span> Last Name</p>
                                <input class="form-control" id="validation1" type="text" name="lastName"  required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please provide a valid last name.</div>
                            </div>

                            <div class="modalFname">
                                <p><span style="color: red;">*</span> First Name</p>
                                <input class="form-control" id="validation2" type="text" name="firstName" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please provide a valid first name.</div>
                            </div>

                            <div class="modalMname">
                                <p>Middle Name</p>
                                <input class=""  type="text" name="middleName" required>
                                <div class="valid-feedback">Looks good!</div>
                            </div>

                            <div class="modalSuffix">
                                <p> Suffix <span style="font-size: 10px;">(Optional)</span></p>
                                <select name="suffix">
                                    <option value=""></option>
                                    <option value="I">I</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                    <option value="J.D">J.D</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                </select>
                            </div>
                        </div>

                        <div class="modalSecondRowInfo">
                            <div class="modalGender">
                                <p><span style="color: red;">*</span> Gender</p>
                                <select id="gender" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>

                            <div class="modalPhonenumber">
                                <p><span style="color: red;">*</span> Phone Number</p>
                                <input class="form-control" id="numericInput" name="phone_number" type="text" required minlength="11" maxlength="11">
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please provide a valid phone number.</div>
                            </div>

                            <div class="modalEmail">
                                <p><span style="color: red;">*</span> Email</p>
                                <input class="form-control" type="email" name="email" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please provide a valid email address.</div>
                            </div>
                            <div class="modalUsername">
                                <p><span style="color: red;">*</span> Username</p>
                                <input type="username" name="username" class="form-control" required>
                                <div class="valid-feedback" style="margin-left: 20px;">Looks good!</div>
                                <div class="invalid-feedback">Provide a valid username.</div>
                            </div>
                        </div>

                        <div class="modalFifthRowInfo">
                            <h4>Job Information</h4>
                        </div>

                        <div class="modalSixRowInfo">
                            <div class="modalPosition">
                                <p><span style="color: red;">*</span> Position</p>
                                <select id="position" name="position" required>
                                    <?php
                                    require_once '../../Admin/functions/user.php';
                                    $userDataManager = new User();

                                    $managerOption = '<option value="adm_manager">Manager</option>';
                                    $cashierOption = '<option value="emp_cshr" selected>Cashier</option>';

                                    if ($userDataManager->isManager()) {
                                        echo $cashierOption;
                                    } else {
                                        echo $managerOption;
                                        echo $cashierOption;
                                    }
                                    ?>
                                    <option value="emp_cook">Cook</option>
                                    <option value="emp_asst_cook">Assistant Cook</option>
                                    <option value="cour">Courier</option>
                                    <option value="emp_srvr">Server</option>
                                </select>

                            </div>
                        </div>

                        <div class="modalEigth">
                            <input type="submit" name="add-emp" value="Add Employee" class="modalUpdate">
                            <button type="button" class="closeModalProfile" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Javascript -->
<script src="../admin_js/admin.js"></script>
<script>
    let ascending = true;

    function toggleSort(header) {
        const icon = header.querySelector('.toggle-icon');

        if (ascending) {
            icon.innerHTML = '&#8593;&#8597;';
        } else {
            icon.innerHTML = '&#8597;&#8595;';
        }

        ascending = !ascending;
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#live_search").keyup(function() {
            var input = $(this).val();

            if (input !== "") {
                $.ajax({
                    url: "../../Admin/functions/livesearch_cashier.php",
                    method: "POST",
                    data: {
                        input: input
                    },
                    success: function(data) {
                        $("#searchresult").html(data);
                        $("#searchresult").css("display", "block");
                    }
                });
            } else {
                // If the input is empty, display the complete table
                $.ajax({
                    url: "../../Admin/functions/livesearch_cashier.php",
                    method: "POST",
                    data: {
                        input: ""
                    }, // Pass an empty input to your server-side code
                    success: function(data) {
                        $("#searchresult").html(data);
                        $("#searchresult").css("display", "block");
                    }
                });
            }
        });
    });

</script>
<!-- SORT JS -->
<script>
    $(document).ready(function() {
        // Handle sorting when the select dropdown changes
        $('#sortDropdown').on('change', function() {
            let selectedValue = $(this).val();
            let rows = $('#searchresult tbody tr').get();

            rows.sort(function(a, b) {
                let aData = $(a).children('td').eq(1).text(); // Last Name
                let bData = $(b).children('td').eq(1).text();

                if (selectedValue === "1") {
                    return aData.localeCompare(bData); // Alphabetical A-Z
                } else if (selectedValue === "2") {
                    return bData.localeCompare(aData); // Alphabetical Z-A
                } else {
                    // Handle other sorting logic (add more cases if needed)
                    return 0;
                }
            });

            $('#searchresult tbody').empty().append(rows);
        });
    });
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('image-container');

        const file = input.files[0];
        const reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
            container.style.display = 'block';
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            container.style.display = 'none';
        }
    }

    var numericInput = document.getElementById("numericInput");

    numericInput.addEventListener("input", function(event) {

        var inputValue = event.target.value.replace(/\D/g, "");

        event.target.value = inputValue;
    });


    var numericInput = document.getElementById("numericInput1");

    numericInput.addEventListener("input", function(event) {

        var inputValue = event.target.value.replace(/\D/g, "");

        event.target.value = inputValue;
    });


    var numericInput = document.getElementById("numericInput2");

    numericInput.addEventListener("input", function(event) {

        var inputValue = event.target.value.replace(/\D/g, "");

        event.target.value = inputValue;
    });
</script>
<script>
    (function () {
        'use strict'

        var forms = document.querySelectorAll('.needs-validation');

        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });
    })();
</script>

</body>
</html>