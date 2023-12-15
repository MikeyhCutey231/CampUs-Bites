<?php
include('cart.class.php');
class CartControl extends Cart
{

    public function removeItem($prod_id, $cart_id)
    {
        $this->delItem($prod_id, $cart_id);

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public function removeAll($customer_id)
    {
        $this->deleteAll($customer_id);

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        echo getcwd();
    }

    public function newQuantity($prod_id, $newQuantity)
    {
        $this->updateQuantity($prod_id, $newQuantity);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        echo getcwd();

        header('location: ../../Customer/customer_php/customer-cart.php');
    }
}
