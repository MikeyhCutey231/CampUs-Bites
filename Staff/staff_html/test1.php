<?php
    session_start();

    if (isset($_SESSION['selected_items'])) {
        $selectedItems = $_SESSION['selected_items'];

        unset($_SESSION['selected_items']);
        // Display the selected items in your desired format
        foreach ($selectedItems as $item) {
            echo 'Product Name: ' . $item['name'] . '<br>';
            echo 'Quantity: ' . $item['quantity'] . '<br>';
            echo 'Subtotal: ₱' . $item['subtotal'] . '<br>';
            echo 'Item ID: ' . $item['itemId'] . '<br>';
            echo $_SESSION['Staff_ID'];
            echo '<hr>';
        }

        unset($_SESSION['selected_items']);
    } else {
        echo 'No selected items found.';
    }



?>