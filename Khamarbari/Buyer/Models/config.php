<?php
require_once 'db_Connect.php';
-
function getAllProducts() {
    global $conn;
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function addToCart($userId, $productId, $quantity) {
    global $conn;
    $cartId = "CRT" . rand(1000, 9999);
    $sql = "INSERT INTO cart (cart_id, user_id, product_id, quantity) 
            VALUES ('$cartId', '$userId', '$productId', '$quantity')";
    return mysqli_query($conn, $sql);
}
?>