
<div class="left-container" id="sidebar">
    <div class="sidebar-logo">
        <div class="main-logo"></div>
        <div class="sidelogo-text">
            <div style="position: relative;">
                <a href="">CampUs</a>
                <p class="">Bites</p>
            </div>
            <div style="position: absolute; right: 20px; cursor: pointer;" class="close-btn"><img src="/Icons/x-circle.svg" alt=""></div>
        </div>
    </div>

    <ul class="sidebar-nav">
        <li class="sidebar-items">
            <a href="admin-dashboard.php" class="sidebar-link">
                <svg width="19" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.451 10.2994C17.4413 12.0203 16.9215 13.6996 15.9573 15.125C14.9931 16.5505 13.6279 17.658 12.0343 18.3075C10.4407 18.9571 8.69028 19.1195 7.00438 18.7742C5.31847 18.4289 3.77281 17.5915 2.56286 16.3678C1.35291 15.144 0.533007 13.589 0.206836 11.8993C-0.119336 10.2096 0.0628736 8.4611 0.730422 6.87495C1.39797 5.2888 2.52088 3.93622 3.95714 2.98825C5.39341 2.04029 7.07852 1.53951 8.79939 1.54925L8.75014 10.2501L17.451 10.2994Z" fill="#5F5F5F"/>
                    <path d="M19.0745 8.65408C19.0803 7.52336 18.8633 6.40258 18.4359 5.35573C18.0085 4.30889 17.3791 3.35647 16.5836 2.55287C15.7881 1.74926 14.8422 1.1102 13.7997 0.672182C12.7573 0.23416 11.6388 0.0057526 10.5081 0L10.4643 8.61027L19.0745 8.65408Z" fill="#5F5F5F"/>
                </svg>
                <p>Dashboard</p>
            </a>
        </li>

        <li class="sidebar-items">
            <a href="" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#pages" aria-expanded="false" aria-controls="pages">
                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.4583 16.625V15.0417C13.4583 14.2018 13.1247 13.3964 12.5308 12.8025C11.937 12.2086 11.1315 11.875 10.2917 11.875H3.95832C3.11847 11.875 2.31302 12.2086 1.71915 12.8025C1.12529 13.3964 0.791656 14.2018 0.791656 15.0417V16.625" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7.12501 8.70833C8.87391 8.70833 10.2917 7.29057 10.2917 5.54167C10.2917 3.79276 8.87391 2.375 7.12501 2.375C5.37611 2.375 3.95834 3.79276 3.95834 5.54167C3.95834 7.29057 5.37611 8.70833 7.12501 8.70833Z" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18.2083 16.6251V15.0418C18.2078 14.3401 17.9743 13.6586 17.5444 13.104C17.1146 12.5495 16.5127 12.1534 15.8333 11.978" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12.6667 2.47803C13.3478 2.65243 13.9516 3.04858 14.3827 3.60402C14.8138 4.15946 15.0479 4.8426 15.0479 5.54574C15.0479 6.24887 14.8138 6.93201 14.3827 7.48745C13.9516 8.04289 13.3478 8.43904 12.6667 8.61344" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Employees
            </a>
            <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <?php
                require_once '../../Admin/functions/user.php';
                $userDataManager = new User();
                if ($userDataManager->isManager()) {
                    echo '<li class="sidebar-item" style="display: none;">
            <a href="admin-manager.php">Manager</a>
          </li>';
                } else {
                    echo '<li class="sidebar-item">
            <a href="admin-manager.php">Manager</a>
          </li>';
                }
                ?>

                <li class="sidebar-item">
                    <a href="admin-cashier.php">Cashier</a>
                </li>
                <li class="sidebar-item">
                    <a href="admin-server.php">Server</a>
                </li>

                <li class="sidebar-item">
                    <a href="admin-cook.php">Cook</a>
                </li>
                <li class="sidebar-item">
                    <a href="admin-asstcook.php">Assistant Cook</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-items">
            <a href="admin-courier.php" class="sidebar-link">
                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.4583 16.625V15.0417C13.4583 14.2018 13.1247 13.3964 12.5308 12.8025C11.937 12.2086 11.1315 11.875 10.2917 11.875H3.95832C3.11847 11.875 2.31302 12.2086 1.71915 12.8025C1.12529 13.3964 0.791656 14.2018 0.791656 15.0417V16.625" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7.12501 8.70833C8.87391 8.70833 10.2917 7.29057 10.2917 5.54167C10.2917 3.79276 8.87391 2.375 7.12501 2.375C5.37611 2.375 3.95834 3.79276 3.95834 5.54167C3.95834 7.29057 5.37611 8.70833 7.12501 8.70833Z" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18.2083 16.6251V15.0418C18.2078 14.3401 17.9743 13.6586 17.5444 13.104C17.1146 12.5495 16.5127 12.1534 15.8333 11.978" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12.6667 2.47803C13.3478 2.65243 13.9516 3.04858 14.3827 3.60402C14.8138 4.15946 15.0479 4.8426 15.0479 5.54574C15.0479 6.24887 14.8138 6.93201 14.3827 7.48745C13.9516 8.04289 13.3478 8.43904 12.6667 8.61344" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Courier
            </a>
        </li>

        <li class="sidebar-items">
            <a href="admin-Customer.php" class="sidebar-link">
                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.4583 16.625V15.0417C13.4583 14.2018 13.1247 13.3964 12.5308 12.8025C11.937 12.2086 11.1315 11.875 10.2917 11.875H3.95832C3.11847 11.875 2.31302 12.2086 1.71915 12.8025C1.12529 13.3964 0.791656 14.2018 0.791656 15.0417V16.625" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7.12501 8.70833C8.87391 8.70833 10.2917 7.29057 10.2917 5.54167C10.2917 3.79276 8.87391 2.375 7.12501 2.375C5.37611 2.375 3.95834 3.79276 3.95834 5.54167C3.95834 7.29057 5.37611 8.70833 7.12501 8.70833Z" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18.2083 16.6251V15.0418C18.2078 14.3401 17.9743 13.6586 17.5444 13.104C17.1146 12.5495 16.5127 12.1534 15.8333 11.978" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12.6667 2.47803C13.3478 2.65243 13.9516 3.04858 14.3827 3.60402C14.8138 4.15946 15.0479 4.8426 15.0479 5.54574C15.0479 6.24887 14.8138 6.93201 14.3827 7.48745C13.9516 8.04289 13.3478 8.43904 12.6667 8.61344" stroke="#696969" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Customers
            </a>
        </li>



        <li class="sidebar-items">
            <a href="admin_itemInventory.php" class="sidebar-link" id="invHover">
                <div class="circle"> <p id="notificationCircle">0</p></div>
                <svg width="20" height="20" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.0625 7.44176L5.9375 3.33301" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16.625 12.6667V6.33334C16.6247 6.05568 16.5514 5.78298 16.4125 5.54259C16.2735 5.3022 16.0738 5.10258 15.8333 4.96375L10.2917 1.79709C10.051 1.65812 9.77793 1.58496 9.5 1.58496C9.22207 1.58496 8.94903 1.65812 8.70833 1.79709L3.16667 4.96375C2.92621 5.10258 2.72648 5.3022 2.58753 5.54259C2.44858 5.78298 2.37528 6.05568 2.375 6.33334V12.6667C2.37528 12.9443 2.44858 13.217 2.58753 13.4574C2.72648 13.6978 2.92621 13.8974 3.16667 14.0363L8.70833 17.2029C8.94903 17.3419 9.22207 17.415 9.5 17.415C9.77793 17.415 10.051 17.3419 10.2917 17.2029L15.8333 14.0363C16.0738 13.8974 16.2735 13.6978 16.4125 13.4574C16.5514 13.217 16.6247 12.9443 16.625 12.6667Z" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2.58875 5.50977L9.49999 9.50768L16.4112 5.50977" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9.5 17.48V9.5" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p>Item Inventory</p>
            </a>
        </li>

        <li class="sidebar-items">
            <a href="admin-payroll.php" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#5f5f5f}</style><path d="M64 0C46.3 0 32 14.3 32 32V96c0 17.7 14.3 32 32 32h80v32H87c-31.6 0-58.5 23.1-63.3 54.4L1.1 364.1C.4 368.8 0 373.6 0 378.4V448c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V378.4c0-4.8-.4-9.6-1.1-14.4L488.2 214.4C483.5 183.1 456.6 160 425 160H208V128h80c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H64zM96 48H256c8.8 0 16 7.2 16 16s-7.2 16-16 16H96c-8.8 0-16-7.2-16-16s7.2-16 16-16zM64 432c0-8.8 7.2-16 16-16H432c8.8 0 16 7.2 16 16s-7.2 16-16 16H80c-8.8 0-16-7.2-16-16zm48-168a24 24 0 1 1 0-48 24 24 0 1 1 0 48zm120-24a24 24 0 1 1 -48 0 24 24 0 1 1 48 0zM160 344a24 24 0 1 1 0-48 24 24 0 1 1 0 48zM328 240a24 24 0 1 1 -48 0 24 24 0 1 1 48 0zM256 344a24 24 0 1 1 0-48 24 24 0 1 1 0 48zM424 240a24 24 0 1 1 -48 0 24 24 0 1 1 48 0zM352 344a24 24 0 1 1 0-48 24 24 0 1 1 0 48z"/></svg>
                <p>Payroll</p>
            </a>
        </li>

        <li class="sidebar-items">
            <a href="admin-reports.php" class="sidebar-link">
                <svg width="22" height="22" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.25 15.8332V7.9165" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9.5 15.8332V3.1665" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M4.75 15.8335V11.0835" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p>Reports</p>
            </a>
        </li>

        <li class="sidebar-items">
            <a href="admin-logs.php" class="sidebar-link">
                <svg width="22" height="22" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.25 15.8332V7.9165" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9.5 15.8332V3.1665" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M4.75 15.8335V11.0835" stroke="#5F5F5F" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p>Logs</p>
            </a>
        </li>

        <li class="sidebar-items">
            <a href="admin-logout.php" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.33428 17.1132H4.07464C3.64239 17.1132 3.22784 16.9415 2.92219 16.6358C2.61654 16.3302 2.44482 15.9156 2.44482 15.4834V4.07464C2.44482 3.64239 2.61654 3.22784 2.92219 2.92219C3.22784 2.61654 3.64239 2.44482 4.07464 2.44482H7.33428" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.0386 13.8537L17.1131 9.77914L13.0386 5.70459" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17.1129 9.77881H7.33398" stroke="#5F5F5F" stroke-width="1.62982" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p>Signout</p>
            </a>
        </li>

        <div class="usep-footercard">
            <img src="../../Icons/useplogo.png" alt="" width="50px">
            <p style="font-size: 11px; margin-bottom: -5px; font-weight: bold; margin-top: 5px;">University of Southeastern</p>
            <p style="font-size: 11px; font-weight: bold;">Philippines (TU)</p>

            <p style="font-size: 12px; margin-bottom: -5px; color: #9C1421;">University Property</p>
            <p  style="font-size: 12px; color: #9C1421;">E-Canteen</p>

            <p style="font-size: 12px; margin-bottom: -20px; color: #9C1421; font-weight: 900;">Learn More</p>
            <p style="margin-bottom: 0px; color: #9C1421;">___________</p>
        </div>
    </ul>
</div>

<script>function updateNotificationCount() {
    $.ajax({
        url: "../../Admin/functions/check_zero_stock.php",
        method: "GET",
        success: function (data) {
            $("#notificationCircle").text(data);

            if (parseInt(data) > 0) {
                $("#notificationCircle")
            }
        },
    });
}

$(document).ready(function () {
    updateNotificationCount();

    setInterval(updateNotificationCount, 300000); // 300000 milliseconds = 5 minutes
});
</script>
