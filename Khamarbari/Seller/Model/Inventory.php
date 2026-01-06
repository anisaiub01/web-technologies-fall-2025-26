<?php
class Inventory {

    public static function init() {
        if (!isset($_SESSION['inventory'])) {
            $_SESSION['inventory'] = [];
        }
    }

    public static function add($product) {
        $_SESSION['inventory'][] = $product;
    }

    public static function all() {
        return $_SESSION['inventory'];
    }
}
