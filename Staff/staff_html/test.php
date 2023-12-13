<?php
    include("../functions/menuListfunctions.php");
    $database = new Connection();
    $conn = $database->conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $items = json_decode($_POST['items'], true);

    if (!isset($_SESSION['selected_items'])) {
        $_SESSION['selected_items'] = [];
    }

    foreach ($items as $item) {
        // ... (your code to process and save item details to the session)
        $_SESSION['selected_items'][] = $item;
    }

    $dataHandler = new MenuList($conn);
    $dataHandler->pasteData();
    echo 'success';
} else {
    echo 'Error: Invalid data or not logged in.';
}
?>