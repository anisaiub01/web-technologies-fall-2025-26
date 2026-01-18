<?php
class Inventory {

    public static function init() {
        if (!isset($_SESSION['inventory'])) {
            $_SESSION['inventory'] = [];
        }
    }

    public static function add($product)
{
    foreach ($_SESSION['inventory'] as $index => $item) {
        if (
            $item['name'] === $product['name'] &&
            $item['price'] === $product['price']
        ) {
            $_SESSION['inventory'][$index]['qty'] += $product['qty'];
            return;
        }
    }

    $_SESSION['inventory'][] = $product;
}




    public static function all() {
        return $_SESSION['inventory'];
    }
}
