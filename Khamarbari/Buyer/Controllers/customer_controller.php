<?php
session_start();
require_once '../Models/config.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; 
}

$lastSearch = isset($_COOKIE['last_search']) ? $_COOKIE['last_search'] : "";

$products_data = getAllProducts();
$productListHTML = "";

foreach ($products_data as $product) {
    $safeName = str_replace(' ', '_', $product['name']);
    $productListHTML .= "
    <div class='product-item' data-name='{$product['name']}'>
        <img src='../img/{$product['image_path']}' alt='Product' width='100'>
        <div class='product-info'>
            <h3>{$product['name']}</h3>
            <p>Price: {$product['price_per_kg']} BDT per KG</p>
            <input type='hidden' name='price_{$safeName}' value='{$product['price_per_kg']}'>
            <label>Qty (KG): </label>
            <input type='number' name='qty_{$safeName}' value='1' min='1' style='width:50px;'>
            <label class='cart-label'>
                <input type='checkbox' name='selected_products[]' value='{$product['name']}'> Add to cart
            </label>
        </div>
    </div>";
}
?>