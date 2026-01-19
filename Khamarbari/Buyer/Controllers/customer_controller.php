<?php
session_start();
require_once '../Models/config.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 'anis1'; 
}

$lastSearch = isset($_COOKIE['last_search']) ? $_COOKIE['last_search'] : "";
$products_data = getAllProducts();
$productListHTML = "";

foreach ($products_data as $product) {
    $pId = $product['product_id'];
    $pName = $product['name'];
    $pPrice = $product['price'];
    $pImg = $product['image'];

    $productListHTML .= "
    <div class='product-item' data-name='{$pName}'>
        <img src='../img/{$pImg}' alt='Product' width='100'>
        <div class='product-info'>
            <h3>{$pName}</h3>
            <p>Price: {$pPrice} BDT</p>
            <label>Qty: </label>
            <input type='number' name='qty_{$pId}' value='1' min='1' style='width:50px;'>
            <label class='cart-label'>
                <input type='checkbox' name='selected_products[]' value='{$pId}'> Add to cart
            </label>
        </div>
    </div>";
}
?>