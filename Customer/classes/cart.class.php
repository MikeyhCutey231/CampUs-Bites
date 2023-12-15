<?php
include_once '../../Admin/functions/dbConfig.php';

class Cart extends Connection
{

    protected function getCartItems($customer_id)
    {
        $cartitems = array();
        $sql = "SELECT ol_cart.OL_CART_ID,  online_cart_item.OL_CART_ITEM_ID, ol_cart.CUSTOMER_ID, product.PROD_ID, product.PROD_NAME, 
        product.PROD_PIC, product.PROD_DESC, product.PROD_SELLING_PRICE, online_cart_item.OL_PROD_QUANTITY, online_cart_item.OL_SUBTOTAL, 
        ol_cart.OL_CART_STATUS
        from ol_cart 
        inner join online_cart_item on ol_cart.OL_CART_ID=online_cart_item.OL_CART_ID
        inner join users on ol_cart.CUSTOMER_ID = users.USER_ID
        inner join product on online_cart_item.PROD_ID = product.PROD_ID 
        where ol_cart_status ='alive' and customer_id = '$customer_id'";
        $result = $this->conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $cartitems[] = [
                    'CART_ID' => $row['OL_CART_ID'],
                    'PROD_ID' => $row['PROD_ID'],
                    'PROD_PIC' => $row['PROD_PIC'],
                    'PROD_NAME' => $row['PROD_NAME'],
                    'UNIT_PRICE' => $row['PROD_SELLING_PRICE'],
                    'QUANTITY' => $row['OL_PROD_QUANTITY'],
                    'SUBTOTAL' => $row['OL_SUBTOTAL'],
                ];
            }
        } else {
            die('Error executing the query: ' . $this->conn->error);
        }

        return $cartitems;
        header("Location: ../customer_php/customer-cart.php");
    }

    protected function getCartTotal($customer_id)
    {
        $totals = array();
        $sql = "SELECT ol_cart.OL_CART_ID, COUNT(online_cart_item.PROD_ID) as 'TOTAL_ITEMS',
        SUM(online_cart_item.OL_SUBTOTAL) as 'OL_CART_TOTAL' from online_cart_item
       inner join ol_cart on online_cart_item.OL_CART_ID= ol_cart.OL_CART_ID
       where ol_cart.OL_CART_STATUS = 'alive' and ol_cart.CUSTOMER_ID = $customer_id";

        $result = $this->conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $totals[] = [
                    'TOTAL_ITEMS' => $row['TOTAL_ITEMS'],
                    'CART_TOTAL' => $row['OL_CART_TOTAL'],
                    'CART_ID' => $row['OL_CART_ID']
                ];
            }
        } else {
            die('Error executing the query: ' . $this->conn->error);
        }

        return $totals;
    }

    protected function delItem($prod_id, $cart_id)
    {
        $sql = "DELETE from online_cart_item where online_cart_item.OL_CART_ID = $cart_id and online_cart_item.PROD_ID = $prod_id";
        $result = $this->conn->query($sql);

        if ($result) {
            header('location: ../customer_php/customer-cart.php');
        } else {
            die('Error executing the query: ' . $this->conn->error);
        }
    }

    protected function deleteAll($customer_id)
    {
        $sql = "DELETE online_cart_item from online_cart_item 
        inner join ol_cart on online_cart_item.OL_CART_ID = ol_cart.OL_CART_ID
        where ol_cart.OL_CART_STATUS = 'alive' and ol_cart.CUSTOMER_ID = '$customer_id'";
        $result = $this->conn->query($sql);
        if ($result) {
            header('location: ../customer_php/customer-cart.php');
        } else {
            die('Error executing the query: ' . $this->conn->error);
        }
    }

    protected function updateQuantity($prod_id, $newQuantity)
    {
        $sql = "UPDATE  online_cart_item 
        INNER JOIN ol_cart ON online_cart_item.OL_CART_ID = ol_cart.OL_CART_ID
        INNER JOIN PRODUCT ON online_cart_item.PROD_ID = product.PROD_ID
        SET online_cart_item.OL_PROD_QUANTITY = $newQuantity,
        online_cart_item.OL_SUBTOTAL = product.PROD_SELLING_PRICE*$newQuantity

        WHERE online_cart_item.PROD_ID = $prod_id AND ol_cart.OL_CART_STATUS = 'alive';";

        $result = $this->conn->query($sql);


        if ($result) {
            header('location: ../cartview.class.php');
        } else {
            die('Error executing the query: ' . $this->conn->error);
        }
    }


    function getTotalItem($customer_id)
    {
        $totals = 0;
        $sql = "SELECT COUNT(online_cart_item.PROD_ID) as 'TOTAL_ITEMS'
        from online_cart_item
            inner join ol_cart on online_cart_item.OL_CART_ID= ol_cart.OL_CART_ID
            where ol_cart.OL_CART_STATUS = 'alive' and ol_cart.CUSTOMER_ID  = $customer_id";

        $result = $this->conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $totals = $row['TOTAL_ITEMS'];
            }
        } else {
            die('Error executing the query: ' . $this->conn->error);
        }

        return $totals;
    }
}
